<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function solicitudForm()
    {
        return view('auth.recuperar-password');
    }

    public function enviarEnlace(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Debes escribir tu correo electrónico.',
            'email.email' => 'El formato del correo no es válido.',
        ]);

        $estado = Password::sendResetLink(
            $request->only('email')
        );

        if ($estado === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Hemos enviado a tu correo un enlace para restablecer tu contraseña. Revisa también la carpeta de spam.');
        }

        if ($estado === Password::RESET_THROTTLED) {
            return back()->withErrors(['email' => 'Debes esperar un minuto antes de solicitar otro enlace.']);
        }

        return back()->withErrors(['email' => 'No encontramos una cuenta registrada con ese correo.']);
    }

    public function formularioReset(Request $request, string $token)
    {
        return view('auth.restablecer-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function restablecer(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ], [
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        $estado = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $usuario, string $password) {
                $usuario->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($estado === Password::PASSWORD_RESET) {
            return redirect()->route('login')
                ->with('success', 'Tu contraseña fue restablecida correctamente. Ahora inicia sesión.');
        }

        return back()->withErrors(['email' => 'El enlace es inválido o ya expiró. Solicita uno nuevo.']);
    }
}
