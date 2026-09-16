<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\MaletinController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\SupervisionController;
use App\Http\Controllers\Api\VehicleController;
use Illuminate\Support\Facades\Route;

// =============================================
// Rutas publicas (sin autenticacion)
// =============================================

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/catalogo', [CatalogController::class, 'vehiculos']);
Route::get('/catalogo/{id}', [CatalogController::class, 'vehiculo']);
Route::get('/maletin', [CatalogController::class, 'maletin']);

// =============================================
// Rutas protegidas (requieren token)
// =============================================

Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::put('/profile/password', [AuthController::class, 'changePassword']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'stats']);

    // Supervisión (Jefe de Sucursal / Administrador)
    Route::get('/supervision', [SupervisionController::class, 'index']);

    // Vehiculos
    Route::apiResource('vehiculos', VehicleController::class)
        ->parameters(['vehiculos' => 'vehicle'])
        ->names('api.vehiculos');

    // Clientes
    Route::apiResource('clientes', ClientController::class)
        ->parameters(['clientes' => 'client'])
        ->names('api.clientes');

    // Ventas
    Route::apiResource('ventas', SaleController::class)
        ->except(['update'])
        ->parameters(['ventas' => 'sale'])
        ->names('api.ventas');
    Route::get('/comisiones', [SaleController::class, 'comisiones'])->name('api.comisiones');

    // Maletin
    Route::put('/maletin', [MaletinController::class, 'update']);
    Route::get('/maletin/detalle', [MaletinController::class, 'show']);
});
