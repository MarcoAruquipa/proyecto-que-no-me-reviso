@extends('layouts.panel')

@section('title', 'Mi perfil')

@section('content')

@php
    $usuario = Auth::user();
    $rolNombre = match($usuario->role) {
        'admin' => 'Administrador General',
        'jefe' => 'Jefe de Sucursal',
        'tecnico' => 'Ejecutivo de Venta',
        default => 'Invitado',
    };
@endphp

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center p-4">
                <div class="mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center text-white"
                     style="width: 96px; height: 96px; background: linear-gradient(135deg, #4b0082, #6d28d9); font-size: 38px; font-weight: 800;">
                    {{ strtoupper(substr($usuario->name ?: 'U', 0, 1)) }}
                </div>

                <h4 class="fw-bold mb-1">{{ $usuario->name }}</h4>
                <p class="text-muted mb-1">{{ $usuario->email }}</p>
                <p class="mb-0">
                    <span class="badge text-bg-primary">{{ $rolNombre }}</span>
                    <span class="badge text-bg-dark">{{ $usuario->username }}</span>
                </p>

                <hr>

                <div class="text-start small">
                    <div class="mb-1"><strong>Teléfono:</strong> {{ $usuario->telefono ?? 'No registrado' }}</div>
                    <div class="mb-1"><strong>Sucursal:</strong> {{ $usuario->sucursal ?? 'No registrada' }}</div>
                    <div class="mb-1"><strong>Cargo:</strong> {{ $usuario->cargo ?? 'No registrado' }}</div>
                    <div class="mb-0"><strong>Supervisor:</strong> {{ $usuario->supervisor ?? 'No registrado' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Actualizar mis datos</h5>
            </div>

            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <strong>Corrige los siguientes errores:</strong>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('perfil.actualizar') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre completo</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $usuario->name) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Usuario</label>
                            <input type="text" name="username" class="form-control" value="{{ old('username', $usuario->username) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Correo electrónico</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $usuario->email) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $usuario->telefono ?? '') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Sucursal</label>
                            <input type="text" name="sucursal" class="form-control" value="{{ old('sucursal', $usuario->sucursal ?? '') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Cargo</label>
                            <input type="text" name="cargo" class="form-control" value="{{ old('cargo', $usuario->cargo ?? '') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Supervisor</label>
                            <input type="text" name="supervisor" class="form-control" value="{{ old('supervisor', $usuario->supervisor ?? '') }}">
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-pegaso"><i class="bi bi-check-lg me-1"></i> Actualizar mis datos</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-shield-lock me-2"></i>Cambiar contraseña</h5>
            </div>

            <div class="card-body">
                <p class="text-muted small mb-4">
                    Por seguridad, debes confirmar tu contraseña actual para poder establecer una nueva.
                </p>

                <form action="{{ route('perfil.contrasena') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Contraseña actual</label>
                            <input type="password" name="current_password"
                                   class="form-control @error('current_password') is-invalid @enderror"
                                   required>
                            @error('current_password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nueva contraseña</label>
                            <input type="password" name="password" class="form-control" placeholder="Mínimo 8 caracteres" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Confirmar nueva contraseña</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Repite la nueva contraseña" required>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-dorado"><i class="bi bi-key me-1"></i> Cambiar mi contraseña</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection