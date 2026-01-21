<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

// Rutas de procesamiento
Route::post('/registro-usuario', [AuthController::class, 'registrar'])->name('usuario.registrar');
Route::post('/login-usuario', [AuthController::class, 'login'])->name('usuario.login');
