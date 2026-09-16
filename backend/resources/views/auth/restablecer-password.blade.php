<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña - Pegaso Motors</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow border-0 p-4">
                    <h4 class="text-center mb-3 fw-bold">Restablecer Contraseña</h4>
                    <p class="text-muted text-center small">Escribe tu nueva contraseña para continuar.</p>

                    @if ($errors->any())
                        <div class="alert alert-danger small">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3">
                            <label class="form-label">Correo electrónico</label>
                            <input type="email" name="email" value="{{ old('email', $email) }}"
                                   class="form-control @error('email') is-invalid @enderror" required readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nueva contraseña</label>
                            <input type="password" name="password" class="form-control"
                                   placeholder="Mínimo 8 caracteres" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirmar nueva contraseña</label>
                            <input type="password" name="password_confirmation" class="form-control"
                                   placeholder="Repite la contraseña" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold">Guardar contraseña</button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}" class="small text-decoration-none">Volver a iniciar sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
