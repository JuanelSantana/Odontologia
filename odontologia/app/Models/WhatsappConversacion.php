<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para almacenar el historial de conversaciones del chatbot de WhatsApp.
 */
class WhatsappConversacion extends Model
{
    protected $table = 'whatsapp_conversaciones';
    public $timestamps = true;

    protected $fillable = [
        'telefono',
        'rol',       // 'user' | 'assistant'
        'contenido',
    ];
}
