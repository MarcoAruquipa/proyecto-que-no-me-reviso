<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|min:3|max:100',
            'username' => 'required|alpha_dash|min:4|max:20|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'telefono' => 'nullable|max:30',
            'sucursal' => 'nullable|max:100',
            'cargo' => 'nullable|max:100',
        ]);

        $user = User::create([
            'name' => trim($request->name),
            'username' => trim($request->username),
            'email' => trim($request->email),
            'password' => Hash::make($request->password),
            'role' => 'invitado',
            'telefono' => $request->telefono,
            'sucursal' => $request->sucursal,
            'cargo' => $request->cargo,
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Registro exitoso.',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'login' => 'nullable',
            'username' => 'nullable',
            'password' => 'required',
        ]);

        $identificador = strtolower(trim((string) $request->input('login', $request->input('username'))));

        $campo = filter_var($identificador, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $user = User::where($campo, $identificador)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión correcto.',
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente.',
        ]);
    }

    public function profile(Request $request): JsonResponse
    {
        $user = $request->user()->load(['maletin', 'vehicles', 'sales']);

        return response()->json([
            'user' => $user,
        ]);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $usuario = $request->user();

        $request->validate([
            'name' => 'sometimes|required|min:3|max:100',
            'email' => ['sometimes', 'required', 'email', 'max:255', 'unique:users,email,' . $usuario->id],
            'telefono' => 'nullable|max:30',
            'sucursal' => 'nullable|max:100',
            'cargo' => 'nullable|max:100',
            'supervisor' => 'nullable|max:100',
        ]);

        $datos = array_filter([
            'name' => $request->has('name') ? trim($request->name) : null,
            'email' => $request->has('email') ? trim($request->email) : null,
            'telefono' => $request->has('telefono') ? trim($request->telefono) : null,
            'sucursal' => $request->has('sucursal') ? trim($request->sucursal) : null,
            'cargo' => $request->has('cargo') ? trim($request->cargo) : null,
            'supervisor' => $request->has('supervisor') ? trim($request->supervisor) : null,
        ], fn ($v) => $v !== null);

        $usuario->forceFill($datos)->save();

        return response()->json([
            'message' => 'Perfil actualizado correctamente.',
            'user' => $usuario->fresh(),
        ]);
    }

    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $usuario = $request->user();

        if (! Hash::check($request->current_password, $usuario->password)) {
            return response()->json([
                'message' => 'La contraseña actual es incorrecta.',
            ], 422);
        }

        $usuario->forceFill([
            'password' => Hash::make($request->password),
        ])->save();

        return response()->json([
            'message' => 'Contraseña cambiada correctamente.',
        ]);
    }
}
