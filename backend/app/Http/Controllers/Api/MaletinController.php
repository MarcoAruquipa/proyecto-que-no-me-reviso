<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Maletin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MaletinController extends Controller
{
    public function show(): JsonResponse
    {
        $maletin = Maletin::first();

        if (! $maletin) {
            return response()->json([
                'message' => 'No hay maletin registrado.',
                'maletin' => null,
            ]);
        }

        return response()->json([
            'maletin' => $maletin,
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'titulo' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'telefono' => 'nullable|string|max:30',
            'direccion' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:5120',
        ]);

        $maletin = Maletin::first();

        if (! $maletin) {
            $maletin = Maletin::create([
                'user_id' => $request->user()->id,
                'titulo' => $request->titulo,
                'descripcion' => $request->descripcion,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
            ]);
        } else {
            $datos = $request->only('titulo', 'descripcion', 'telefono', 'direccion');

            if ($request->hasFile('logo')) {
                $datos['logo'] = $request->file('logo')->store('maletin', 'public');
            }

            if ($request->hasFile('banner')) {
                $datos['banner'] = $request->file('banner')->store('maletin', 'public');
            }

            $maletin->update(array_filter($datos, fn ($v) => $v !== null));
        }

        return response()->json([
            'message' => 'Maletin actualizado correctamente.',
            'maletin' => $maletin,
        ]);
    }
}
