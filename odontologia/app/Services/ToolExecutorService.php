<?php

namespace App\Services;

use App\Models\Cita;
use App\Models\Paciente;
use App\Models\Doctor;
use App\Models\EstadoCita;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Ejecuta las herramientas (Tool Calls) que Groq solicita al chatbot.
 * Cada método corresponde a una herramienta definida en GroqService::definirHerramientas().
 */
class ToolExecutorService
{
    /**
     * Despacha y ejecuta el tool_call que devolvió Groq.
     *
     * @param  array  $toolCall  Elemento del array tool_calls de Groq
     * @return string            Resultado en texto para enviar de vuelta al modelo
     */
    public function ejecutar(array $toolCall): string
    {
        $nombre = $toolCall['function']['name'] ?? '';
        $args   = json_decode($toolCall['function']['arguments'] ?? '{}', true);

        Log::info("[ToolExecutor] Ejecutando herramienta: {$nombre}", $args);

        return match($nombre) {
            'consultar_disponibilidad' => $this->consultarDisponibilidad($args['fecha'], (int)$args['doctor_id']),
            'agendar_cita'             => $this->agendarCita($args['paciente_id'], (int)$args['doctor_id'], $args['fecha'], $args['hora'], $args['motivo']),
            'consultar_citas'          => $this->consultarCitas($args['telefono']),
            'procesar_confirmacion'    => $this->procesarConfirmacion((int)$args['cita_id'], (bool)$args['respuesta_bool']),
            default                    => "Herramienta '{$nombre}' no reconocida.",
        };
    }

    // =========================================================================
    // HERRAMIENTA 1: Consultar Disponibilidad
    // =========================================================================

    /**
     * Busca los espacios de tiempo disponibles de un doctor en una fecha dada.
     */
    private function consultarDisponibilidad(string $fecha, int $doctorId): string
    {
        try {
            $doctor = Doctor::find($doctorId);
            if (!$doctor) {
                return "No se encontró un doctor con ID {$doctorId}.";
            }

            // Horas laborales de la clínica (8am - 5pm, citas de 1 hora)
            $horasLaborales = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00'];

            // Citas ya agendadas para ese doctor y fecha
            $citasOcupadas = Cita::where('id_doc', $doctorId)
                ->whereDate('fec_cit', $fecha)
                ->whereNotIn('id_eci', $this->getEstadosCancelados())
                ->pluck('fec_cit')
                ->map(fn($f) => Carbon::parse($f)->format('H:i'))
                ->toArray();

            $horasLibres = array_filter($horasLaborales, fn($h) => !in_array($h, $citasOcupadas));

            if (empty($horasLibres)) {
                return "El Dr. {$doctor->nom_doc} {$doctor->ape_doc} no tiene horarios disponibles el {$fecha}.";
            }

            $lista = implode(', ', array_values($horasLibres));
            return "Horarios disponibles con el Dr. {$doctor->nom_doc} {$doctor->ape_doc} el {$fecha}: {$lista}.";

        } catch (\Exception $e) {
            Log::error('[ToolExecutor::consultarDisponibilidad] ' . $e->getMessage());
            return 'Ocurrió un error al consultar la disponibilidad.';
        }
    }

    // =========================================================================
    // HERRAMIENTA 2: Agendar Cita
    // =========================================================================

    /**
     * Inserta una nueva cita en la base de datos.
     */
    private function agendarCita(string $pacienteId, int $doctorId, string $fecha, string $hora, string $motivo): string
    {
        try {
            $paciente = Paciente::find($pacienteId);
            if (!$paciente) {
                return "No se encontró un paciente con cédula {$pacienteId}.";
            }

            $doctor = Doctor::find($doctorId);
            if (!$doctor) {
                return "No se encontró un doctor con ID {$doctorId}.";
            }

            // Estado "Pendiente" (id_eci = 1 por convención)
            $estadoPendiente = EstadoCita::where('nom_eci', 'like', '%Pendiente%')->first();
            $idEci = $estadoPendiente?->id_eci ?? 1;

            $fechaHora = Carbon::createFromFormat('Y-m-d H:i', "{$fecha} {$hora}");

            $cita = Cita::create([
                'ced_pac' => $pacienteId,
                'id_doc'  => $doctorId,
                'id_eci'  => $idEci,
                'fec_cit' => $fechaHora,
                'mtv_cit' => $motivo,
            ]);

            return "Cita agendada exitosamente (ID: {$cita->id_cit}) para el {$fecha} a las {$hora} con el Dr. {$doctor->nom_doc} {$doctor->ape_doc}.";

        } catch (\Exception $e) {
            Log::error('[ToolExecutor::agendarCita] ' . $e->getMessage());
            return 'Ocurrió un error al agendar la cita. Por favor intente de nuevo.';
        }
    }

    // =========================================================================
    // HERRAMIENTA 3: Consultar Citas del Paciente
    // =========================================================================

    /**
     * Retorna las próximas citas de un paciente buscado por teléfono.
     */
    private function consultarCitas(string $telefono): string
    {
        try {
            // Normaliza el teléfono eliminando código de país (1809... → 809...)
            $telefonoLimpio = preg_replace('/^1/', '', preg_replace('/\D/', '', $telefono));

            $paciente = Paciente::where('tel_pac', 'like', "%{$telefonoLimpio}%")->first();
            if (!$paciente) {
                return "No se encontró ningún paciente registrado con el número {$telefono}.";
            }

            $citas = Cita::where('ced_pac', $paciente->ced_pac)
                ->where('fec_cit', '>=', Carbon::now())
                ->whereNotIn('id_eci', $this->getEstadosCancelados())
                ->with(['doctor', 'estado'])
                ->orderBy('fec_cit')
                ->take(5)
                ->get();

            if ($citas->isEmpty()) {
                return "No tienes citas próximas agendadas, {$paciente->nom_pac}.";
            }

            $resultado = "Tus próximas citas, {$paciente->nom_pac}:\n";
            foreach ($citas as $cita) {
                $fecha = Carbon::parse($cita->fec_cit)->format('d/m/Y');
                $hora  = Carbon::parse($cita->fec_cit)->format('H:i');
                $doc   = $cita->doctor ? "Dr. {$cita->doctor->nom_doc} {$cita->doctor->ape_doc}" : 'Doctor no asignado';
                $estado = $cita->estado?->nom_eci ?? 'Pendiente';
                $resultado .= "- {$fecha} a las {$hora} con {$doc} ({$estado})\n";
            }

            return trim($resultado);

        } catch (\Exception $e) {
            Log::error('[ToolExecutor::consultarCitas] ' . $e->getMessage());
            return 'Ocurrió un error al consultar tus citas.';
        }
    }

    // =========================================================================
    // HERRAMIENTA 4: Procesar Confirmación
    // =========================================================================

    /**
     * Confirma o cancela una cita según la respuesta del paciente.
     */
    private function procesarConfirmacion(int $citaId, bool $respuestaBool): string
    {
        try {
            $cita = Cita::find($citaId);
            if (!$cita) {
                return "No se encontró la cita con ID {$citaId}.";
            }

            if ($respuestaBool) {
                // Buscar estado "Confirmada"
                $estadoConfirmada = EstadoCita::where('nom_eci', 'like', '%Confirm%')->first();
                $cita->id_eci = $estadoConfirmada?->id_eci ?? $cita->id_eci;
                $cita->save();
                return "Tu cita del " . Carbon::parse($cita->fec_cit)->format('d/m/Y \a \l\a\s H:i') . " ha sido confirmada. Te esperamos.";
            } else {
                // Buscar estado "Cancelada"
                $estadoCancelada = EstadoCita::where('nom_eci', 'like', '%Cancel%')->first();
                $cita->id_eci = $estadoCancelada?->id_eci ?? $cita->id_eci;
                $cita->save();
                return "Tu cita ha sido cancelada. Si deseas reagendar, con gusto te ayudamos.";
            }

        } catch (\Exception $e) {
            Log::error('[ToolExecutor::procesarConfirmacion] ' . $e->getMessage());
            return 'Ocurrió un error al procesar tu confirmación.';
        }
    }

    // =========================================================================
    // HELPERS
    // =========================================================================

    /**
     * Retorna los IDs de estados de cita que implican cancelación.
     */
    private function getEstadosCancelados(): array
    {
        return EstadoCita::where('nom_eci', 'like', '%Cancel%')
            ->pluck('id_eci')
            ->toArray();
    }
}
