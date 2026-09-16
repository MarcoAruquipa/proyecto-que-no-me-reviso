<?php

use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\SistemaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SistemaController::class, 'inicio'])->name('inicio');
Route::get('/catalogo', [SistemaController::class, 'catalogo'])->name('catalogo');

Route::get('/sesion/{tipo?}', [SistemaController::class, 'loginForm'])
    ->whereIn('tipo', ['invitado', 'tecnico', 'admin', 'jefe'])
    ->name('login');

Route::post('/sesion', [SistemaController::class, 'login'])->name('login.post');

Route::get('/registro', [SistemaController::class, 'registroForm'])->name('registro');
Route::post('/registro', [SistemaController::class, 'registro'])->name('registro.post');

Route::match(['get', 'post'], '/salir', [SistemaController::class, 'logout'])->name('logout');

Route::get('/maletin-digital', [SistemaController::class, 'principal'])->name('maletin.principal');

Route::get('/tecnico1', [SistemaController::class, 'tecnicoDashboard'])->name('tecnico.dashboard');

Route::get('/vehiculos', [SistemaController::class, 'vehiculosIndex'])->name('vehiculos.index');
Route::get('/vehiculos/crear', [SistemaController::class, 'vehiculosCrear'])->name('vehiculos.create');
Route::post('/vehiculos', [SistemaController::class, 'vehiculosGuardar'])->name('vehiculos.store');
Route::get('/vehiculos/{vehicle}/editar', [SistemaController::class, 'vehiculosEditar'])->name('vehiculos.edit');
Route::put('/vehiculos/{vehicle}', [SistemaController::class, 'vehiculosActualizar'])->name('vehiculos.update');
Route::delete('/vehiculos/{vehicle}', [SistemaController::class, 'vehiculosEliminar'])->name('vehiculos.destroy');

Route::get('/clientes', [SistemaController::class, 'clientesIndex'])->name('clientes.index');
Route::get('/clientes/crear', [SistemaController::class, 'clientesCrear'])->name('clientes.create');
Route::post('/clientes', [SistemaController::class, 'clientesGuardar'])->name('clientes.store');
Route::get('/clientes/{client}/editar', [SistemaController::class, 'clientesEditar'])->name('clientes.edit');
Route::put('/clientes/{client}', [SistemaController::class, 'clientesActualizar'])->name('clientes.update');
Route::delete('/clientes/{client}', [SistemaController::class, 'clientesEliminar'])->name('clientes.destroy');

Route::get('/ventas', [SistemaController::class, 'ventasIndex'])->name('ventas.index');
Route::get('/ventas/crear', [SistemaController::class, 'ventasCrear'])->name('ventas.create');
Route::post('/ventas', [SistemaController::class, 'ventasGuardar'])->name('ventas.store');
Route::delete('/ventas/{sale}', [SistemaController::class, 'ventasEliminar'])->name('ventas.destroy');

// Panel principal (dashboard unificado)
Route::get('/panel', [SistemaController::class, 'dashboard'])->name('panel.dashboard');

// Supervision de ejecutivos (Jefe de Sucursal)
Route::get('/panel/supervision', [SistemaController::class, 'supervision'])->name('panel.supervision');

// Contratos
Route::get('/contratos', [SistemaController::class, 'contratos'])->name('panel.contratos');
Route::post('/contratos', [SistemaController::class, 'contratosGuardar'])->name('contratos.store');
Route::put('/contratos/{contrato}', [SistemaController::class, 'contratosEstado'])->name('contratos.update');

// Documentos
Route::post('/documentos', [SistemaController::class, 'documentosGuardar'])->name('documentos.store');
Route::delete('/documentos/{documento}', [SistemaController::class, 'documentosEliminar'])->name('documentos.destroy');

Route::get('/documentos', [SistemaController::class, 'documentos'])->name('documentos.index');
Route::get('/comisiones', [SistemaController::class, 'comisiones'])->name('comisiones.index');

Route::get('/administrador', [SistemaController::class, 'adminDashboard'])->name('admin.dashboard');

Route::get('/mi-perfil', [SistemaController::class, 'perfil'])->name('perfil');
Route::put('/mi-perfil', [SistemaController::class, 'actualizarPerfil'])->name('perfil.actualizar');
Route::put('/mi-perfil/contrasena', [SistemaController::class, 'cambiarContrasena'])->name('perfil.contrasena');

// Recuperación de contraseña
Route::get('/olvide-contrasena', [PasswordResetController::class, 'solicitudForm'])->name('password.request');
Route::post('/olvide-contrasena', [PasswordResetController::class, 'enviarEnlace'])->name('password.email');

Route::get('/restablecer-contrasena/{token}', [PasswordResetController::class, 'formularioReset'])->name('password.reset');
Route::post('/restablecer-contrasena', [PasswordResetController::class, 'restablecer'])->name('password.update');

// Documentacion API
Route::get('/api-docs', function () {
    return view('docs.api');
})->name('api.docs');