@extends('layouts.app')

@section('title', 'Registro de usuario')

@section('content')

<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="mb-0">Formulario de registro</h3>
                </div>

                <div class="card-body p-4">

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <strong>Corrige estos errores:</strong>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('registro.post') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Nombre completo</label>
                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   value="{{ old('name') }}"
                                   placeholder="Nombre completo"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Usuario</label>
                            <input type="text"
                                   name="username"
                                   class="form-control"
                                   value="{{ old('username') }}"
                                   placeholder="Ejemplo: marco1"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Correo electrónico</label>
                            <input type="email"
                                   name="email"
                                   class="form-control"
                                   value="{{ old('email') }}"
                                   placeholder="correo@gmail.com"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password"
                                   name="password"
                                   class="form-control"
                                   placeholder="Mínimo 8 caracteres"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tipo de usuario</label>
                            <select name="role" class="form-select" required>
                                <option value="invitado" {{ old('role') == 'invitado' ? 'selected' : '' }}>
                                    Invitado
                                </option>

                                <option value="tecnico" {{ old('role') == 'tecnico' ? 'selected' : '' }}>
                                    Ejecutivo de Venta
                                </option>
                            </select>

                            <div class="form-text">
                                Los accesos de Jefe de Sucursal y Administrador son asignados internamente.
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            Registrarme
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="{{ route('login', ['tipo' => 'invitado']) }}">
                            Ya tengo cuenta, iniciar sesión
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>

</div>

@endsection