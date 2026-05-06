<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Sistema Proactivo de Recordatorios de Citas (WhatsApp)
|--------------------------------------------------------------------------
| Se ejecuta automáticamente al inicio de cada hora.
| Para activarlo en producción, agrega al cron del servidor:
|   * * * * * cd /ruta/del/proyecto && php artisan schedule:run >> /dev/null 2>&1
|
| En desarrollo, ejecuta manualmente:
|   php artisan recordatorios:enviar
*/
Schedule::command('recordatorios:enviar')
    ->hourly()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/recordatorios.log'));

