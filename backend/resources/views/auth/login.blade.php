@extends('layouts.app')

@section('title', 'Iniciar sesión')

@section('content')

@php
    $tipo = $tipo ?? 'invitado';

    $nombreRol = match($tipo) {
        'tecnico' => 'Ejecutivo de Venta',
        'admin' => 'Administrador General',
        'jefe' => 'Jefe de Sucursal',
        default => 'Invitado',
    };
@endphp

<section class="login-pegaso">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="login-caja">
        <div class="card login-card border-0">
            <div class="card-body p-4 p-md-5">

                <div class="text-center mb-4">
                    @include('partials.logo', ['size' => 'lg'])
                </div>

                <h3 class="text-center fw-bold mb-1">Iniciar sesión</h3>
                <p class="text-center text-muted mb-4">
                    Acceso como <strong>{{ $nombreRol }}</strong>
                </p>

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <strong>Revise los datos ingresados.</strong>
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" autocomplete="off">
                    @csrf

                    <input type="hidden" name="role_esperado" value="{{ $tipo }}">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Correo electrónico o usuario</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="text"
                                   name="login"
                                   class="form-control"
                                   value="{{ old('login', old('username')) }}"
                                   placeholder="correo@pegaso.com o tu usuario"
                                   required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password"
                                   name="password"
                                   class="form-control"
                                   placeholder="Tu contraseña"
                                   required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Recordarme</label>
                        </div>

                        <a href="{{ route('password.request') }}" class="small text-decoration-none">¿Olvidaste tu contraseña?</a>
                    </div>

                    <button type="submit" class="btn btn-dorado w-100 py-2 fw-bold">
                        Entrar como {{ $nombreRol }}
                    </button>
                </form>

                <hr>

                <div class="text-center">
                    <a href="{{ route('registro') }}" class="text-decoration-none">Registrar nueva cuenta</a>
                    <span class="mx-2 text-muted">·</span>
                    <a href="{{ route('inicio') }}" class="text-muted text-decoration-none">Volver al inicio</a>
                </div>

                <div class="mt-4 small text-muted bg-light rounded-3 p-3">
                    <div class="mb-1"><i class="bi bi-info-circle me-1"></i><strong>Credenciales de ejemplo:</strong></div>
                    <div class="mb-1">• Invitado: <code>invitado / invitado123</code></div>
                    <div class="mb-1">• Ejecutivo: <code>tecnico1 / tecnico123</code></div>
                    <div class="mb-1">• Jefe de Sucursal: <code>admin / admin123</code> o <code>jefe1 / jefe123</code></div>
                </div>

            </div>
        </div>
    </div>
</section>

<style>
    .login-pegaso {
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 16px;
        background: linear-gradient(135deg, #170a30 0%, #2b1552 45%, #4b0082 100%);
        overflow: hidden;
    }

    .orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(70px);
        opacity: .55;
    }

    .orb-1 { width: 420px; height: 420px; background: #7c3aed; top: -120px; right: -80px; }
    .orb-2 { width: 360px; height: 360px; background: #d4af37; bottom: -140px; left: -100px; opacity: .22; }
    .orb-3 { width: 220px; height: 220px; background: #a855f7; top: 40%; left: -60px; }

    .login-caja { position: relative; z-index: 2; width: 100%; max-width: 440px; }

    .login-card {
        border-radius: 22px;
        background: rgba(255, 255, 255, .98);
        box-shadow: 0 24px 60px rgba(0, 0, 0, .45);
    }

    .btn-dorado {
        background: linear-gradient(90deg, #d4af37, #f0d87a);
        border: none;
        color: #2b1552;
        font-weight: 800;
        border-radius: 10px;
    }

    .btn-dorado:hover {
        filter: brightness(1.06);
        color: #2b1552;
    }

    .input-group-text {
        background: #f1eef7;
        border: none;
        color: #4b0082;
    }

    .form-control {
        border: 1px solid #e3dff0;
        border-radius: 0 10px 10px 0;
        padding: 11px 14px;
    }

    .input-group .input-group-text { border-radius: 10px 0 0 10px; }
</style>

@endsection