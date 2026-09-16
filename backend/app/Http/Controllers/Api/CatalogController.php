<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Maletin;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function vehiculos(Request $request): JsonResponse
    {
        $query = Vehicle::where('estado', '!=', 'Vendido');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('marca', 'like', "%{$search}%")
                  ->orWhere('modelo', 'like', "%{$search}%");
            });
        }

        if ($request->has('precio_min')) {
            $query->where('precio', '>=', $request->precio_min);
        }

        if ($request->has('precio_max')) {
            $query->where('precio', '<=', $request->precio_max);
        }

        $vehiculos = $query->latest()->paginate($request->get('per_page', 15));

        return response()->json($vehiculos);
    }

    public function vehiculo($id): JsonResponse
    {
        $vehicle = Vehicle::where('estado', '!=', 'Vendido')->findOrFail($id);

        return response()->json([
            'vehicle' => $vehicle,
        ]);
    }

    public function maletin(): JsonResponse
    {
        $maletin = Maletin::first();

        return response()->json([
            'maletin' => $maletin,
        ]);
    }
}
