<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Cita;
use App\Services\GroqService;
use App\Services\EvolutionApiService;
use Carbon\Carbon;

/**
 * Comando Artisan para el sistema proactivo de recordatorios de citas.
 *
 * Ejecución manual:   php artisan recordatorios:enviar
 * Ejecución automática: Configurado en routes/console.php o Kernel (cada hora)
 *
 * Lógica:
 *  - 24h: Busca citas de mañana con recordatorio_24h_enviado = 0
 *  - 1h:  Busca citas en la próxima hora con recordatorio_1h_enviado = 0
 *  - Genera el texto con Groq y envía por WhatsApp vía Evolution API
 *  - Solo marca el flag como enviado si la API respondió 200/201
 */
class EnviarRecordatoriosCommand extends Command
{
    protected $signature   = 'recordatorios:enviar';
    protected $description = 'Envía recordatorios de citas por WhatsApp (24h y 1h antes)';

    public function __construct(
        private readonly GroqService         $groq,
        private readonly EvolutionApiService  $evolution
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Iniciando envío de recordatorios...');
        Log::info('[Recordatorios] Inicio del proceso de recordatorios.');

        $this->procesarRecordatorios24h();
        $this->procesarRecordatorios1h();

        $this->info('Proceso de recordatorios finalizado.');
        Log::info('[Recordatorios] Proceso finalizado.');

        return Command::SUCCESS;
    }

    // =========================================================================
    // RECORDATORIOS 24 HORAS
    // =========================================================================

    private function procesarRecordatorios24h(): void
    {
        $this->info('--- Procesando recordatorios de 24 horas ---');

        $manana = Carbon::tomorrow();

        // Citas programadas para mañana (cualquier hora) donde no se ha enviado el recordatorio
        $citas = Cita::whereDate('fec_cit', $manana->toDateString())
            ->where('recordatorio_24h_enviado', false)
            ->with(['paciente', 'doctor'])
            ->get();

        $this->info("Citas encontradas para mañana: {$citas->count()}");

        foreach ($citas as $cita) {
            $this->procesarRecordatorio($cita, '24h');
        }
    }

    // =========================================================================
    // RECORDATORIOS 1 HORA
    // =========================================================================

    private function procesarRecordatorios1h(): void
    {
        $this->info('--- Procesando recordatorios de 1 hora ---');

        $ahora       = Carbon::now();
        $en60Minutos = $ahora->copy()->addMinutes(60);
        $en70Minutos = $ahora->copy()->addMinutes(70); // Margen de 10 min para no perder la ventana

        // Citas que comienzan en los próximos 60-70 minutos y no han recibido el recordatorio
        $citas = Cita::whereBetween('fec_cit', [$en60Minutos, $en70Minutos])
            ->where('recordatorio_1h_enviado', false)
            ->with(['paciente', 'doctor'])
            ->get();

        $this->info("Citas en la próxima hora: {$citas->count()}");

        foreach ($citas as $cita) {
            $this->procesarRecordatorio($cita, '1h');
        }
    }

    // =========================================================================
    // LÓGICA CENTRAL DE ENVÍO
    // =========================================================================

    /**
     * Genera el texto con Groq y lo envía por WhatsApp.
     *
     * @param  Cita    $cita  Instancia de la cita (con relaciones cargadas)
     * @param  string  $tipo  '24h' | '1h'
     */
    private function procesarRecordatorio(Cita $cita, string $tipo): void
    {
        // --- Validar que el paciente tenga teléfono ---
        $paciente = $cita->paciente;
        $doctor   = $cita->doctor;

        if (!$paciente || empty($paciente->tel_pac)) {
            $this->warn("  [Cita #{$cita->id_cit}] Paciente sin teléfono. Omitiendo.");
            Log::warning("[Recordatorios] Cita #{$cita->id_cit} omitida: paciente sin teléfono.");
            return;
        }

        $nombrePaciente = "{$paciente->nom_pac} {$paciente->ape_pac}";
        $nombreDoctor   = $doctor ? "Dr. {$doctor->nom_doc} {$doctor->ape_doc}" : 'su doctor';
        $fechaCita      = Carbon::parse($cita->fec_cit)->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY');
        $horaCita       = Carbon::parse($cita->fec_cit)->format('H:i');

        $this->line("  Procesando Cita #{$cita->id_cit} | Paciente: {$nombrePaciente} | Tel: {$paciente->tel_pac}");

        // --- Generar texto con Groq ---
        try {
            if ($tipo === '24h') {
                $texto = $this->groq->redactarRecordatorio24h($nombrePaciente, $nombreDoctor, $fechaCita, $horaCita);
            } else {
                $texto = $this->groq->redactarRecordatorio1h($nombrePaciente, $nombreDoctor, $horaCita);
            }
        } catch (\Exception $e) {
            $this->error("  [Cita #{$cita->id_cit}] Error al generar texto con Groq: " . $e->getMessage());
            Log::error("[Recordatorios] Error Groq en cita #{$cita->id_cit}: " . $e->getMessage());
            return;
        }

        if (!$texto) {
            $this->error("  [Cita #{$cita->id_cit}] Groq devolvió un texto vacío. Omitiendo.");
            return;
        }

        // --- Enviar por WhatsApp ---
        try {
            $enviado = $this->evolution->enviarMensaje($paciente->tel_pac, $texto);
        } catch (\Exception $e) {
            $this->error("  [Cita #{$cita->id_cit}] Error al enviar mensaje: " . $e->getMessage());
            Log::error("[Recordatorios] Error Evolution API en cita #{$cita->id_cit}: " . $e->getMessage());
            return;
        }

        // --- Actualizar flag SOLO si el envío fue exitoso ---
        if ($enviado) {
            try {
                if ($tipo === '24h') {
                    $cita->recordatorio_24h_enviado = true;
                } else {
                    $cita->recordatorio_1h_enviado = true;
                }
                $cita->save();

                $this->info("  [Cita #{$cita->id_cit}] Recordatorio {$tipo} enviado y registrado correctamente.");
                Log::info("[Recordatorios] Cita #{$cita->id_cit}: recordatorio {$tipo} enviado a {$paciente->tel_pac}.");

            } catch (\Exception $e) {
                $this->error("  [Cita #{$cita->id_cit}] Mensaje enviado pero error al actualizar el flag: " . $e->getMessage());
                Log::error("[Recordatorios] Error al actualizar flag de cita #{$cita->id_cit}: " . $e->getMessage());
            }
        } else {
            $this->warn("  [Cita #{$cita->id_cit}] El mensaje no pudo enviarse. Flag no actualizado.");
        }
    }
}
