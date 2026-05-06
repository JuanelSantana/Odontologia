<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Servicio para enviar mensajes mediante la Evolution API local.
 * Basado en la lógica probada y funcional de test_whatsapp.py:
 *   - URL:     http://localhost:8080/message/sendText/{instance}
 *   - Headers: apikey + Content-Type: application/json
 *   - Payload: { "number": "...", "text": "..." }
 */
class EvolutionApiService
{
    private string $baseUrl;
    private string $apiKey;
    private string $instance;

    public function __construct()
    {
        $this->baseUrl  = config('services.evolution_api.url', 'http://localhost:8080');
        $this->apiKey   = config('services.evolution_api.key', '');
        $this->instance = config('services.evolution_api.instance', 'clinica_cepin');
    }

    /**
     * Envía un mensaje de texto plano a través de WhatsApp.
     *
     * @param  string  $numero  Número destino con código de país (ej: "18092684228")
     * @param  string  $texto   Texto del mensaje
     * @return bool             true si Evolution API respondió 200/201
     */
    public function enviarMensaje(string $numero, string $texto): bool
    {
        $url = "{$this->baseUrl}/message/sendText/{$this->instance}";

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'apikey'       => $this->apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($url, [
                    'number' => $numero,
                    'text'   => $texto,
                ]);

            if (in_array($response->status(), [200, 201])) {
                Log::info("[EvolutionAPI] Mensaje enviado a {$numero}.");
                return true;
            }

            Log::warning("[EvolutionAPI] Respuesta inesperada para {$numero}: " . $response->status() . ' - ' . $response->body());
            return false;

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error("[EvolutionAPI] Error de conexión: No se pudo conectar a {$url}. Asegúrate de que Docker esté corriendo en el puerto 8080.");
            return false;
        } catch (\Exception $e) {
            Log::error("[EvolutionAPI] Error inesperado al enviar mensaje a {$numero}: " . $e->getMessage());
            return false;
        }
    }
}
