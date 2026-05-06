<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla para guardar el historial de conversaciones del chatbot de WhatsApp.
     */
    public function up(): void
    {
        Schema::create('whatsapp_conversaciones', function (Blueprint $table) {
            $table->increments('id');
            // Número de teléfono del remitente (clave para buscar historial)
            $table->string('telefono', 30)->index();
            // 'user' = mensaje del paciente, 'assistant' = respuesta del bot
            $table->string('rol', 20);
            $table->text('contenido');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_conversaciones');
    }
};
