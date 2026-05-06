<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Cita;
use App\Models\Paciente;
use Carbon\Carbon;

/**
 * Servicio para interactuar con la API de Groq (LLM).
 * Soporta:
 *   - Redacción de recordatorios (llama-3.1-8b-instant)
 *   - Chat interactivo del bot con Tool Calling (llama-3.3-70b-versatile)
 */
class GroqService
{
    private string $apiKey;
    private string $baseUrl = 'https://api.groq.com/openai/v1/chat/completions';

    // Modelos según la tarea
    const MODEL_RECORDATORIOS = 'llama-3.1-8b-instant';
    const MODEL_CHATBOT       = 'llama-3.3-70b-versatile';

    // System prompt del coordinador de citas (recordatorios)
    const SYSTEM_RECORDATORIOS = 'Eres el coordinador administrativo de la clínica odontológica. Redacta mensajes de recordatorios de citas médicas para enviarlos por WhatsApp. REGLAS ESTRICTAS: 1. Tono profesional, respetuoso y cálido. 2. PROHIBIDO utilizar emojis. 3. Dirígete al paciente por su nombre.';

    // System prompt del asistente virtual (chatbot)
    const SYSTEM_CHATBOT = 'Eres el asistente virtual de OdontologiaBD. Brindas servicio al cliente, ayudas a agendar citas y resuelves dudas. DIRECTRICES: Tono cálido, empático y estrictamente profesional. ESTRICTAMENTE PROHIBIDO: No uses emojis. Se conciso y directo (formato WhatsApp). Llama al paciente por su nombre. Si el paciente hace preguntas médicas, indícale cortésmente que debe realizarlas al doctor en la cita. Si dice "Sí" o "No" a un recordatorio, usa la herramienta para confirmar o cancelar la cita.';

    public function __construct()
    {
        $this->apiKey = config('services.groq.key', '');
    }

    // =========================================================================
    // RECORDATORIOS
    // =========================================================================

    /**
     * Redacta un recordatorio de 24 horas.
     */
    public function redactarRecordatorio24h(
        string $nombrePaciente,
        string $nombreDoctor,
        string $fechaCita,
        string $horaCita
    ): ?string {
        $userPrompt = "Redacta un recordatorio para mañana. Paciente: {$nombrePaciente}, Doctor: {$nombreDoctor}, Fecha: {$fechaCita}, Hora: {$horaCita}. Solicita que confirme asistencia respondiendo 'Sí' o 'No', y recuérdale llegar 10 minutos antes.";

        return $this->completarChat(self::MODEL_RECORDATORIOS, self::SYSTEM_RECORDATORIOS, [
            ['role' => 'user', 'content' => $userPrompt],
        ]);
    }

    /**
     * Redacta un recordatorio de 1 hora.
     */
    public function redactarRecordatorio1h(
        string $nombrePaciente,
        string $nombreDoctor,
        string $horaCita
    ): ?string {
        $userPrompt = "Redacta un recordatorio de última hora. Paciente: {$nombrePaciente}, Doctor: {$nombreDoctor}, Hora: {$horaCita}. Infórmale que su turno está próximo a comenzar en una hora y lo estamos esperando.";

        return $this->completarChat(self::MODEL_RECORDATORIOS, self::SYSTEM_RECORDATORIOS, [
            ['role' => 'user', 'content' => $userPrompt],
        ]);
    }

    // =========================================================================
    // CHATBOT CON TOOL CALLING
    // =========================================================================

    /**
     * Procesa un mensaje entrante del chatbot, con historial y tool calling.
     *
     * @param  string  $nombrePaciente  Nombre del paciente o 'Paciente' si no está registrado
     * @param  array   $historial       Array de mensajes previos [{role, content}, ...]
     * @param  string  $nuevoMensaje    Texto recibido desde WhatsApp
     * @return array   ['texto' => string|null, 'tool_calls' => array|null]
     */
    public function procesarMensajeChatbot(
        string $nombrePaciente,
        array  $historial,
        string $nuevoMensaje
    ): array {
        $systemPrompt = self::SYSTEM_CHATBOT . " El nombre del paciente actual es: {$nombrePaciente}.";

        $mensajes = array_merge(
            [['role' => 'system', 'content' => $systemPrompt]],
            $historial,
            [['role' => 'user', 'content' => $nuevoMensaje]]
        );

        try {
            $response = Http::timeout(30)
                ->withToken($this->apiKey)
                ->post($this->baseUrl, [
                    'model'    => self::MODEL_CHATBOT,
                    'messages' => $mensajes,
                    'tools'    => $this->definirHerramientas(),
                    'tool_choice' => 'auto',
                ]);

            if (!$response->successful()) {
                Log::error('[Groq Chatbot] Error HTTP: ' . $response->status() . ' - ' . $response->body());
                return ['texto' => null, 'tool_calls' => null];
            }

            $data    = $response->json();
            $message = $data['choices'][0]['message'] ?? null;

            if (!$message) {
                return ['texto' => null, 'tool_calls' => null];
            }

            // Si Groq decidió llamar a una herramienta
            if (!empty($message['tool_calls'])) {
                return [
                    'texto'      => null,
                    'tool_calls' => $message['tool_calls'],
                ];
            }

            return [
                'texto'      => $message['content'] ?? null,
                'tool_calls' => null,
            ];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('[Groq Chatbot] Error de conexión: ' . $e->getMessage());
            return ['texto' => null, 'tool_calls' => null];
        } catch (\Exception $e) {
            Log::error('[Groq Chatbot] Error inesperado: ' . $e->getMessage());
            return ['texto' => null, 'tool_calls' => null];
        }
    }

    // =========================================================================
    // TOOL CALLING - DEFINICIÓN DE HERRAMIENTAS
    // =========================================================================

    /**
     * Define las herramientas disponibles para el modelo de Groq.
     */
    private function definirHerramientas(): array
    {
        return [
            [
                'type' => 'function',
                'function' => [
                    'name'        => 'consultar_disponibilidad',
                    'description' => 'Consulta los espacios de horario libres de un doctor en una fecha específica.',
                    'parameters'  => [
                        'type'       => 'object',
                        'properties' => [
                            'fecha'     => ['type' => 'string', 'description' => 'Fecha en formato YYYY-MM-DD'],
                            'doctor_id' => ['type' => 'integer', 'description' => 'ID del doctor'],
                        ],
                        'required' => ['fecha', 'doctor_id'],
                    ],
                ],
            ],
            [
                'type' => 'function',
                'function' => [
                    'name'        => 'agendar_cita',
                    'description' => 'Agenda una nueva cita para el paciente.',
                    'parameters'  => [
                        'type'       => 'object',
                        'properties' => [
                            'paciente_id' => ['type' => 'string',  'description' => 'Cédula del paciente'],
                            'doctor_id'   => ['type' => 'integer', 'description' => 'ID del doctor'],
                            'fecha'       => ['type' => 'string',  'description' => 'Fecha en formato YYYY-MM-DD'],
                            'hora'        => ['type' => 'string',  'description' => 'Hora en formato HH:MM'],
                            'motivo'      => ['type' => 'string',  'description' => 'Motivo de la cita'],
                        ],
                        'required' => ['paciente_id', 'doctor_id', 'fecha', 'hora', 'motivo'],
                    ],
                ],
            ],
            [
                'type' => 'function',
                'function' => [
                    'name'        => 'consultar_citas',
                    'description' => 'Retorna las próximas citas agendadas de un paciente según su número de teléfono.',
                    'parameters'  => [
                        'type'       => 'object',
                        'properties' => [
                            'telefono' => ['type' => 'string', 'description' => 'Número de teléfono del paciente'],
                        ],
                        'required' => ['telefono'],
                    ],
                ],
            ],
            [
                'type' => 'function',
                'function' => [
                    'name'        => 'procesar_confirmacion',
                    'description' => 'Actualiza el estado de una cita cuando el paciente confirma ("Sí") o cancela ("No").',
                    'parameters'  => [
                        'type'       => 'object',
                        'properties' => [
                            'cita_id'      => ['type' => 'integer', 'description' => 'ID de la cita'],
                            'respuesta_bool' => ['type' => 'boolean', 'description' => 'true = confirmar, false = cancelar'],
                        ],
                        'required' => ['cita_id', 'respuesta_bool'],
                    ],
                ],
            ],
        ];
    }

    // =========================================================================
    // HELPER PRIVADO
    // =========================================================================

    /**
     * Realiza una llamada de completación de chat simple (sin tools).
     */
    private function completarChat(string $modelo, string $systemPrompt, array $mensajes): ?string
    {
        try {
            $response = Http::timeout(30)
                ->withToken($this->apiKey)
                ->post($this->baseUrl, [
                    'model'    => $modelo,
                    'messages' => array_merge(
                        [['role' => 'system', 'content' => $systemPrompt]],
                        $mensajes
                    ),
                ]);

            if (!$response->successful()) {
                Log::error('[Groq] Error HTTP: ' . $response->status() . ' - ' . $response->body());
                return null;
            }

            return $response->json('choices.0.message.content');

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('[Groq] Error de conexión con la API: ' . $e->getMessage());
            return null;
        } catch (\Exception $e) {
            Log::error('[Groq] Error inesperado: ' . $e->getMessage());
            return null;
        }
    }
}
