<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\PacienteController;


// Rutas accesibles para invitados
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('sesion');
    })->name('login');

    Route::get('/sesion', function () {
        return view('sesion');
    });

    Route::get('/inicio', function () {
        return view('inicio');
    })->name('registro');
});

// Rutas protegidas
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('usuario.logout');
});

Route::middleware('auth')->group(function () {
    Route::get('/mantenimientos', function () {
        return view('mantenimientos.mantenimientos');
    })->name('mantenimientos');

    Route::get('/mantenimientos/especialidades', [EspecialidadController::class, 'index'])
        ->name('mantenimientos.especialidades.index');

    Route::post('/mantenimientos/especialidades', [EspecialidadController::class, 'store'])
        ->name('mantenimientos.especialidades.store');

    Route::put('/mantenimientos/especialidades/{id}', [EspecialidadController::class, 'update'])
        ->name('mantenimientos.especialidades.update');

    Route::delete('/mantenimientos/especialidades/{id}', [EspecialidadController::class, 'destroy'])
        ->name('mantenimientos.especialidades.destroy');

    // Rutas de Pacientes
    Route::get('/mantenimientos/pacientes', [PacienteController::class, 'index'])
        ->name('mantenimientos.pacientes.index');

    Route::post('/mantenimientos/pacientes', [PacienteController::class, 'store'])
        ->name('mantenimientos.pacientes.store');

    Route::put('/mantenimientos/pacientes/{id}', [PacienteController::class, 'update'])
        ->name('mantenimientos.pacientes.update');

    Route::delete('/mantenimientos/pacientes/{id}', [PacienteController::class, 'destroy'])
        ->name('mantenimientos.pacientes.destroy');
});

// Rutas de procesamiento
Route::post('/registro-usuario', [AuthController::class, 'registrar'])->name('usuario.registrar');
Route::post('/login-usuario', [AuthController::class, 'login'])->name('usuario.login');
