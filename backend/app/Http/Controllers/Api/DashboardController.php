<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Sale;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        $disponibles = Schema::hasColumn('vehicles', 'estado')
            ? Vehicle::where('estado', 'Disponible')->count()
            : Vehicle::count();

        return response()->json([
            'vehiculos' => Vehicle::count(),
            'clientes' => Client::count(),
            'ventas' => Sale::count(),
            'disponibles' => $disponibles,
            'monto_total_ventas' => round(Sale::sum('precio_venta'), 2),
            'vehiculos_recientes' => Vehicle::latest()->take(5)->get(),
            'ventas_recientes' => Sale::with(['vehicle', 'client', 'user'])->latest()->take(5)->get(),
        ]);
    }
}
