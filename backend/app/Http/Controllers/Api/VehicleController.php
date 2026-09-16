<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Vehicle::with('user');

        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('marca', 'like', "%{$search}%")
                  ->orWhere('modelo', 'like', "%{$search}%")
                  ->orWhere('placa', 'like', "%{$search}%");
            });
        }

        $vehiculos = $query->latest()->paginate($request->get('per_page', 15));

        return response()->json($vehiculos);
    }

    public function show(Vehicle $vehicle): JsonResponse
    {
        $vehicle->load(['user', 'sales.client']);

        return response()->json([
            'vehicle' => $vehicle,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'marca' => 'required|string|max:100',
            'modelo' => 'required|string|max:100',
            'anio' => 'required|integer|min:1950|max:2100',
            'precio' => 'required|numeric|min:0',
            'placa' => 'nullable|string|max:20',
            'color' => 'nullable|string|max:50',
            'estado' => 'required|in:Disponible,Reservado,Vendido',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|max:5120',
        ]);

        $datos = $request->only('marca', 'modelo', 'anio', 'placa', 'color', 'precio', 'estado', 'descripcion');
        $datos['user_id'] = $request->user()->id;

        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('vehiculos', 'public');
        }

        $vehicle = Vehicle::create($datos);

        return response()->json([
            'message' => 'Movilidad registrada correctamente.',
            'vehicle' => $vehicle,
        ], 201);
    }

    public function update(Request $request, Vehicle $vehicle): JsonResponse
    {
        $request->validate([
            'marca' => 'sometimes|required|string|max:100',
            'modelo' => 'sometimes|required|string|max:100',
            'anio' => 'sometimes|required|integer|min:1950|max:2100',
            'precio' => 'sometimes|required|numeric|min:0',
            'placa' => 'nullable|string|max:20',
            'color' => 'nullable|string|max:50',
            'estado' => 'sometimes|required|in:Disponible,Reservado,Vendido',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|max:5120',
        ]);

        $datos = $request->only('marca', 'modelo', 'anio', 'placa', 'color', 'precio', 'estado', 'descripcion');

        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('vehiculos', 'public');
        }

        $vehicle->update(array_filter($datos, fn ($v) => $v !== null));

        return response()->json([
            'message' => 'Movilidad actualizada correctamente.',
            'vehicle' => $vehicle->fresh(),
        ]);
    }

    public function destroy(Vehicle $vehicle): JsonResponse
    {
        $vehicle->delete();

        return response()->json([
            'message' => 'Movilidad eliminada correctamente.',
        ]);
    }
}
