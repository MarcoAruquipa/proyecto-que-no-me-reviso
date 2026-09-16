<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Sale::with(['vehicle', 'client', 'user']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('vehicle', function ($q) use ($search) {
                $q->where('marca', 'like', "%{$search}%")
                  ->orWhere('modelo', 'like', "%{$search}%");
            })->orWhereHas('client', function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%");
            });
        }

        $ventas = $query->latest()->paginate($request->get('per_page', 15));

        return response()->json($ventas);
    }

    public function show(Sale $sale): JsonResponse
    {
        $sale->load(['vehicle', 'client', 'user']);

        return response()->json([
            'sale' => $sale,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'client_id' => 'required|exists:clients,id',
            'fecha' => 'required|date',
            'precio_venta' => 'required|numeric|min:0',
            'observacion' => 'nullable|string',
        ]);

        $vehicle = Vehicle::findOrFail($request->vehicle_id);

        if ($vehicle->estado === 'Vendido') {
            return response()->json([
                'message' => 'Este vehículo ya fue vendido.',
            ], 422);
        }

        $sale = Sale::create([
            'vehicle_id' => $request->vehicle_id,
            'client_id' => $request->client_id,
            'user_id' => $request->user()->id,
            'fecha' => $request->fecha,
            'precio_venta' => $request->precio_venta,
            'observacion' => $request->observacion,
        ]);

        $vehicle->update(['estado' => 'Vendido']);

        $sale->load(['vehicle', 'client', 'user']);

        return response()->json([
            'message' => 'Venta registrada correctamente.',
            'sale' => $sale,
        ], 201);
    }

    public function destroy(Sale $sale): JsonResponse
    {
        if ($sale->vehicle) {
            $sale->vehicle->update(['estado' => 'Disponible']);
        }

        $sale->delete();

        return response()->json([
            'message' => 'Venta eliminada correctamente.',
        ]);
    }

    public function comisiones(): JsonResponse
    {
        $ventas = Sale::with(['vehicle', 'client'])->latest()->get();
        $total = $ventas->sum(fn ($venta) => $venta->precio_venta * 0.05);

        return response()->json([
            'ventas' => $ventas,
            'total_comisiones' => round($total, 2),
        ]);
    }
}
