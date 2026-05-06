<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Models\Paciente;
use App\Models\WhatsappConversacion;
use App\Services\GroqService;
use App\Services\EvolutionApiService;
use App\Services\ToolExecutorService;

/**
 * Controlador para el Webhook de Evolution API.
 *
 * Flujo:
 *  1. Evolution API recibe un mensaje de WhatsApp y lo reenvía aquí vía POST.
 *  2. Extraemos el número y el texto del payload.
 *  3. Buscamos al paciente en la BD para personalizar la respuesta.
 *  4. Cargamos el historial de conversación (máx. 10 mensajes).
 *  5. Enviamos todo a Groq (llama-3.3-70b-versatile) con tool calling.
 *  6. Si Groq llama a una herramienta, la ejecutamos y enviamos el resultado.
 *  7. Respondemos al paciente por WhatsApp.
 *  8. Guardamos el intercambio en la BD.
 */
class WhatsappWebhookController extends Controller
{
    public function __construct(
        private readonly GroqService        $groq,
        private readonly EvolutionApiService $evolution,
        private readonly ToolExecutorService $toolExecutor
    ) {}

    /**
     * POST /api/webhook/whatsapp
     * Punto de entrada principal del webhook.
     */
    public function handle(Request $request): Response
    {
        // Responder inmediatamente 200 a Evolution API para evitar reintentos
        Log::info('[Webhook] Payload recibido: ' . $request->getContent());

        try {
            // --- 1. Extraer datos del payload de Evolution API ---
            $data = $request->all();

            $numero = $this->extraerNumero($data);
            $texto  = $this->extraerTexto($data);

            if (!$numero || !$texto) {
                Log::warning('[Webhook] Payload sin número o texto válidos. Ignorando.');
                return response('OK', 200);
            }

            // Ignorar mensajes de grupos o mensajes propios del bot
            if ($this->esMensajeDeGrupo($data) || $this->esMensajePropio($data)) {
                return response('OK', 200);
            }

            Log::info("[Webhook] Mensaje de {$numero}: {$texto}");

            // --- 2. Buscar paciente por teléfono ---
            $telefonoLimpio = $this->normalizarTelefono($numero);
            $paciente = Paciente::where('tel_pac', 'like', "%{$telefonoLimpio}%")->first();
            $nombrePaciente = $paciente ? "{$paciente->nom_pac} {$paciente->ape_pac}" : 'Paciente';

            // --- 3. Cargar historial de conversación (máx. 10 mensajes) ---
            $historial = WhatsappConversacion::where('telefono', $numero)
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get()
                ->reverse()
                ->map(fn($m) => ['role' => $m->rol, 'content' => $m->contenido])
                ->values()
                ->toArray();

            // --- 4. Guardar mensaje del usuario en el historial ---
            WhatsappConversacion::create([
                'telefono'  => $numero,
                'rol'       => 'user',
                'contenido' => $texto,
            ]);

            // --- 5. Llamar a Groq con tool calling ---
            $resultado = $this->groq->procesarMensajeChatbot($nombrePaciente, $historial, $texto);

            $respuestaFinal = null;

            // --- 6. Ejecutar herramienta si Groq lo pidió ---
            if (!empty($resultado['tool_calls'])) {
                $toolCall       = $resultado['tool_calls'][0]; // Procesamos la primera herramienta
                $toolResultado  = $this->toolExecutor->ejecutar($toolCall);

                // Enviar el resultado de la herramienta de vuelta a Groq para que genere la respuesta final
                $historialConTool = array_merge($historial, [
                    ['role' => 'user',      'content' => $texto],
                    ['role' => 'assistant', 'content' => null, 'tool_calls' => $resultado['tool_calls']],
                    [
                        'role'         => 'tool',
                        'tool_call_id' => $toolCall['id'],
                        'content'      => $toolResultado,
                    ],
                ]);

                $respuestaFinal = $this->groq->procesarMensajeChatbot(
                    $nombrePaciente,
                    $historialConTool,
                    ''
                )['texto'];

                // Si falla la segunda llamada, usar el resultado directo de la herramienta
                $respuestaFinal = $respuestaFinal ?? $toolResultado;

            } else {
                $respuestaFinal = $resultado['texto'];
            }

            // --- 7. Mensaje de fallback si Groq no respondió ---
            if (!$respuestaFinal) {
                $respuestaFinal = 'En este momento no podemos procesar tu solicitud. Por favor, llámanos directamente a la clínica.';
            }

            // --- 8. Enviar respuesta por WhatsApp ---
            $enviado = $this->evolution->enviarMensaje($numero, $respuestaFinal);

            if ($enviado) {
                // Guardar respuesta del bot en el historial
                WhatsappConversacion::create([
                    'telefono'  => $numero,
                    'rol'       => 'assistant',
                    'contenido' => $respuestaFinal,
                ]);
            }

        } catch (\Exception $e) {
            Log::error('[Webhook] Error crítico: ' . $e->getMessage() . ' | Traza: ' . $e->getTraceAsString());
        }

        // Siempre retornar 200 para que Evolution API no reintente
        return response('OK', 200);
    }

    // =========================================================================
    // HELPERS PRIVADOS
    // =========================================================================

    /**
     * Extrae el número de teléfono del payload de Evolution API.
     * La estructura varía según la versión; soportamos los formatos más comunes.
     */
    private function extraerNumero(array $data): ?string
    {
        // Formato v2 de Evolution API
        return $data['data']['key']['remoteJid']
            ?? $data['data']['message']['extendedTextMessage']['contextInfo']['participant']
            ?? $data['sender']
            ?? null;
    }

    /**
     * Extrae el texto del mensaje.
     */
    private function extraerTexto(array $data): ?string
    {
        return $data['data']['message']['conversation']
            ?? $data['data']['message']['extendedTextMessage']['text']
            ?? $data['data']['message']['imageMessage']['caption']
            ?? null;
    }

    /**
     * Determina si el mensaje proviene de un grupo de WhatsApp.
     */
    private function esMensajeDeGrupo(array $data): bool
    {
        $jid = $data['data']['key']['remoteJid'] ?? '';
        return str_contains($jid, '@g.us');
    }

    /**
     * Determina si el mensaje fue enviado por el propio bot (para no crear bucles).
     */
    private function esMensajePropio(array $data): bool
    {
        return (bool) ($data['data']['key']['fromMe'] ?? false);
    }

    /**
     * Normaliza el número de teléfono para buscar en la BD.
     * Elimina código de país "1" al inicio (ej: 18092684228 → 8092684228).
     */
    private function normalizarTelefono(string $numero): string
    {
        // Quitar sufijo @s.whatsapp.net o @c.us si viene
        $numero = preg_replace('/@.*$/', '', $numero);
        // Quitar código de país "1" al inicio (RD, EEUU)
        $numero = preg_replace('/^1(\d{10})$/', '$1', preg_replace('/\D/', '', $numero));
        return $numero;
    }
}
