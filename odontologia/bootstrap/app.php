<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'sysuser' => \App\Http\Middleware\CheckSysUser::class,
            'paciente' => \App\Http\Middleware\CheckPaciente::class,
            'doctor' => \App\Http\Middleware\CheckDoctor::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'logout',
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
