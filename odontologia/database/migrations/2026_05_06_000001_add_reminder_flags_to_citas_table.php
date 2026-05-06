<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Añade las columnas de control de recordatorios a la tabla Citas.
     */
    public function up(): void
    {
        Schema::table('Citas', function (Blueprint $table) {
            // Flag para recordatorio de 24 horas antes de la cita
            $table->boolean('recordatorio_24h_enviado')->default(false)->after('cmt_cit');
            // Flag para recordatorio de 1 hora antes de la cita
            $table->boolean('recordatorio_1h_enviado')->default(false)->after('recordatorio_24h_enviado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Citas', function (Blueprint $table) {
            $table->dropColumn(['recordatorio_24h_enviado', 'recordatorio_1h_enviado']);
        });
    }
};
