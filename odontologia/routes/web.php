<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\SeguroController;
use App\Http\Controllers\TratamientoController;

// Rutas accesibles para invitados
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('index');
    })->name('index');

    Route::get('/iniciop', function () {
        return view('InicioSPaciente');
    })->name('iniciop');

    Route::get('/registrop', function () {
        return view('RegistroPaciente');
    })->name('registrop');

    Route::get('/sesion', function () {
        return view('sesion');
    })->name('login');

    Route::get('/inicio', function () {
        return view('inicio');
    })->name('registro');

});

// Rutas de procesamiento para pacientes
Route::get('/registro-paciente', function () {
    return redirect()->route('registrop');
});
Route::post('/registro-paciente', [AuthController::class, 'registrarPaciente'])->name('paciente.registrar');
Route::get('/login-paciente', function () {
    return redirect()->route('iniciop');
});
Route::post('/login-paciente', [AuthController::class, 'loginPaciente'])->name('paciente.login');

// Rutas de procesamiento para usuarios sistema
Route::post('/registro-usuario', [AuthController::class, 'registrar'])->name('usuario.registrar');
Route::post('/login-usuario', [AuthController::class, 'login'])->name('usuario.login');




// Rutas protegidas
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('usuario.logout');
});

Route::middleware(['auth', 'sysuser'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Rutas protegidas para Pacientes
Route::middleware(['auth', 'paciente'])->group(function () {
    Route::get('/dashboard-paciente', [CitaController::class, 'dashboard'])->name('paciente.dashboard');
    Route::post('/guardar-cita', [CitaController::class, 'store'])->name('citas.guardar');
});



Route::middleware(['auth', 'sysuser'])->group(function () {
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

    // Rutas de Doctores
    Route::get('/mantenimientos/doctores', [DoctorController::class, 'index'])
        ->name('mantenimientos.doctores.index');
    Route::post('/mantenimientos/doctores', [DoctorController::class, 'store'])
        ->name('mantenimientos.doctores.store');
    Route::put('/mantenimientos/doctores/{id}', [DoctorController::class, 'update'])
        ->name('mantenimientos.doctores.update');
    Route::delete('/mantenimientos/doctores/{id}', [DoctorController::class, 'destroy'])
        ->name('mantenimientos.doctores.destroy');

    // Rutas de Empleados
    Route::get('/mantenimientos/empleados', [EmpleadoController::class, 'index'])
        ->name('mantenimientos.empleados.index');
    Route::post('/mantenimientos/empleados', [EmpleadoController::class, 'store'])
        ->name('mantenimientos.empleados.store');
    Route::put('/mantenimientos/empleados/{id}', [EmpleadoController::class, 'update'])
        ->name('mantenimientos.empleados.update');
    Route::delete('/mantenimientos/empleados/{id}', [EmpleadoController::class, 'destroy'])
        ->name('mantenimientos.empleados.destroy');

    // Rutas de Materiales
    Route::get('/mantenimientos/materiales', [MaterialController::class, 'index'])
        ->name('mantenimientos.materiales.index');
    Route::post('/mantenimientos/materiales', [MaterialController::class, 'store'])
        ->name('mantenimientos.materiales.store');
    Route::put('/mantenimientos/materiales/{id}', [MaterialController::class, 'update'])
        ->name('mantenimientos.materiales.update');
    Route::delete('/mantenimientos/materiales/{id}', [MaterialController::class, 'destroy'])
        ->name('mantenimientos.materiales.destroy');

    // Rutas de Proveedores
    Route::get('/mantenimientos/proveedores', [ProveedorController::class, 'index'])
        ->name('mantenimientos.proveedores.index');
    Route::post('/mantenimientos/proveedores', [ProveedorController::class, 'store'])
        ->name('mantenimientos.proveedores.store');
    Route::put('/mantenimientos/proveedores/{id}', [ProveedorController::class, 'update'])
        ->name('mantenimientos.proveedores.update');
    Route::delete('/mantenimientos/proveedores/{id}', [ProveedorController::class, 'destroy'])
        ->name('mantenimientos.proveedores.destroy');

    // Rutas de Seguros
    Route::get('/mantenimientos/seguros', [SeguroController::class, 'index'])
        ->name('mantenimientos.seguros.index');
    Route::post('/mantenimientos/seguros', [SeguroController::class, 'store'])
        ->name('mantenimientos.seguros.store');
    Route::put('/mantenimientos/seguros/{id}', [SeguroController::class, 'update'])
        ->name('mantenimientos.seguros.update');
    Route::delete('/mantenimientos/seguros/{id}', [SeguroController::class, 'destroy'])
        ->name('mantenimientos.seguros.destroy');

    // Rutas de Tratamientos
    Route::get('/mantenimientos/tratamientos', [TratamientoController::class, 'index'])
        ->name('mantenimientos.tratamientos.index');
    Route::post('/mantenimientos/tratamientos', [TratamientoController::class, 'store'])
        ->name('mantenimientos.tratamientos.store');
    Route::put('/mantenimientos/tratamientos/{id}', [TratamientoController::class, 'update'])
        ->name('mantenimientos.tratamientos.update');
    Route::delete('/mantenimientos/tratamientos/{id}', [TratamientoController::class, 'destroy'])
        ->name('mantenimientos.tratamientos.destroy');
});

// Citas (General or cleanup)
Route::post('/citas', [CitaController::class, 'store'])->name('citas.store');
