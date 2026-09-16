<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Client::query();

        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('ci', 'like', "%{$search}%")
                  ->orWhere('telefono', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $clientes = $query->latest()->paginate($request->get('per_page', 15));

        return response()->json($clientes);
    }

    public function show(Client $client): JsonResponse
    {
        $client->load('sales');

        return response()->json([
            'client' => $client,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nombre' => 'required|min:3|max:100',
            'ci' => 'nullable|max:20',
            'telefono' => 'nullable|max:30',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|max:255',
            'vehiculo_interes' => 'nullable|max:255',
            'metodo_pago' => 'nullable|max:100',
            'fuente' => 'nullable|max:100',
            'estado' => 'nullable|max:100',
            'notas' => 'nullable|string',
        ]);

        $client = Client::create($request->only(
            'nombre', 'ci', 'telefono', 'email', 'direccion',
            'vehiculo_interes', 'metodo_pago', 'fuente', 'estado', 'notas'
        ));

        return response()->json([
            'message' => 'Cliente registrado correctamente.',
            'client' => $client,
        ], 201);
    }

    public function update(Request $request, Client $client): JsonResponse
    {
        $request->validate([
            'nombre' => 'sometimes|required|min:3|max:100',
            'ci' => 'nullable|max:20',
            'telefono' => 'nullable|max:30',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|max:255',
            'vehiculo_interes' => 'nullable|max:255',
            'metodo_pago' => 'nullable|max:100',
            'fuente' => 'nullable|max:100',
            'estado' => 'nullable|max:100',
            'notas' => 'nullable|string',
        ]);

        $client->update($request->only(
            'nombre', 'ci', 'telefono', 'email', 'direccion',
            'vehiculo_interes', 'metodo_pago', 'fuente', 'estado', 'notas'
        ));

        return response()->json([
            'message' => 'Cliente actualizado correctamente.',
            'client' => $client->fresh(),
        ]);
    }

    public function destroy(Client $client): JsonResponse
    {
        $client->delete();

        return response()->json([
            'message' => 'Cliente eliminado correctamente.',
        ]);
    }
}
