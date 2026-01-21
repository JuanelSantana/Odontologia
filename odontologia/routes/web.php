<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('sesion');
});

Route::get('/inicio', function () {
    return view('inicio');
});

Route::get('/conexion', function () {
    return view('conexion');
});

Route::get('/sesion', function () {
    return view('sesion');
})->name('login');

Route::post('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

use App\Http\Controllers\AuthController;

Route::post('/registro-usuario', [AuthController::class, 'registrar'])->name('usuario.registrar');

Route::post('/login-usuario', [AuthController::class, 'login'])->name('usuario.login');
