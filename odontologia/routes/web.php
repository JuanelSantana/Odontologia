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
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\HistorialController;
use App\Http\Controllers\AdminCitaController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\PagoController;
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

    /*Route::get('/inicio', function () {
        return view('inicio');
    })->name('registro');*/

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




// Ruta de Cerrar Sesión (soporta GET y POST, libre de expiración de sesión)
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('usuario.logout');

Route::middleware(['auth', 'sysuser'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
});

// Rutas de Doctor
Route::middleware(['auth', 'doctor'])->group(function () {
    Route::get('/doctor/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('doctor.dashboard');

    // Solo lectura para Citas
    Route::get('/doctor/citas', [\App\Http\Controllers\DoctorCitaController::class, 'index'])->name('doctor.citas.index');

    // CRUD para Consultas
    Route::resource('/doctor/consultas', \App\Http\Controllers\DoctorConsultaController::class)->names('doctor.consultas');

    // CRUD para Tratamientos adaptado para Doctores
    Route::resource('/doctor/tratamientos', \App\Http\Controllers\DoctorTratamientoController::class)->names('doctor.tratamientos');
});

// Rutas protegidas para Pacientes
Route::middleware(['auth', 'paciente'])->group(function () {
    Route::get('/dashboard-paciente', [CitaController::class, 'dashboard'])->name('paciente.dashboard');
    Route::post('/guardar-cita', [CitaController::class, 'store'])->name('citas.guardar');
    Route::get('/citas/disponibilidad', [CitaController::class, 'getAvailability'])->name('citas.disponibilidad');
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

    // Rutas de Inventario
    Route::get('/mantenimientos/inventario', [InventarioController::class, 'index'])
        ->name('mantenimientos.inventario.index');
    Route::post('/mantenimientos/inventario', [InventarioController::class, 'store'])
        ->name('mantenimientos.inventario.store');
    Route::put('/mantenimientos/inventario/{id}', [InventarioController::class, 'update'])
        ->name('mantenimientos.inventario.update');
    Route::delete('/mantenimientos/inventario/{id}', [InventarioController::class, 'destroy'])
        ->name('mantenimientos.inventario.destroy');

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

    // Rutas de Usuarios
    Route::get('/mantenimientos/usuarios', [\App\Http\Controllers\UserController::class, 'index'])
        ->name('mantenimientos.usuarios.index');
    Route::post('/mantenimientos/usuarios', [\App\Http\Controllers\UserController::class, 'store'])
        ->name('mantenimientos.usuarios.store');
    Route::put('/mantenimientos/usuarios/{id}', [\App\Http\Controllers\UserController::class, 'update'])
        ->name('mantenimientos.usuarios.update');
    Route::delete('/mantenimientos/usuarios/{id}', [\App\Http\Controllers\UserController::class, 'destroy'])
        ->name('mantenimientos.usuarios.destroy');

    // =========================== PROCESOS ===========================
    // Rutas de Historial Clínico
    Route::get('/procesos/historial', [HistorialController::class, 'index'])
        ->name('procesos.historial.index');
    Route::post('/procesos/historial', [HistorialController::class, 'store'])
        ->name('procesos.historial.store');
    Route::put('/procesos/historial/{id}', [HistorialController::class, 'update'])
        ->name('procesos.historial.update');
    Route::delete('/procesos/historial/{id}', [HistorialController::class, 'destroy'])
        ->name('procesos.historial.destroy');

    // Rutas de Citas (Administración)
    Route::get('/procesos/citas', [AdminCitaController::class, 'index'])
        ->name('procesos.citas.index');
    Route::post('/procesos/citas', [AdminCitaController::class, 'store'])
        ->name('procesos.citas.store');
    Route::put('/procesos/citas/{id}', [AdminCitaController::class, 'update'])
        ->name('procesos.citas.update');
    Route::delete('/procesos/citas/{id}', [AdminCitaController::class, 'destroy'])
        ->name('procesos.citas.destroy');

    // Rutas de Pagos
    Route::get('/procesos/pagos', [PagoController::class, 'index'])
        ->name('procesos.pagos.index');
    Route::post('/procesos/pagos', [PagoController::class, 'store'])
        ->name('procesos.pagos.store');
    Route::put('/procesos/pagos/{id}', [PagoController::class, 'update'])
        ->name('procesos.pagos.update');
    Route::delete('/procesos/pagos/{id}', [PagoController::class, 'destroy'])
        ->name('procesos.pagos.destroy');

    // Rutas de Facturación
    Route::get('/procesos/facturas', [FacturaController::class, 'index'])
        ->name('procesos.facturas.index');
    Route::post('/procesos/facturas', [FacturaController::class, 'store'])
        ->name('procesos.facturas.store');
    Route::get('/procesos/facturas/{id}', [FacturaController::class, 'show'])
        ->name('procesos.facturas.show');
    Route::delete('/procesos/facturas/{id}', [FacturaController::class, 'destroy'])
        ->name('procesos.facturas.destroy');
});


/*
|--------------------------------------------------------------------------
| Webhook de WhatsApp (Evolution API -> Sistema Reactivo)
|--------------------------------------------------------------------------
| Esta ruta NO requiere autenticacion (la invoca Evolution API externamente).
| IMPORTANTE: Agrega 'api/webhook/whatsapp' al array $except de:
|   app/Http/Middleware/VerifyCsrfToken.php
*/
use App\Http\Controllers\WhatsappWebhookController;

Route::post('/api/webhook/whatsapp', [WhatsappWebhookController::class, 'handle'])
    ->name('webhook.whatsapp');
