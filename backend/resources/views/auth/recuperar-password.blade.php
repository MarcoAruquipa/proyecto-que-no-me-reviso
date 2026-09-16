<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña - Pegaso Motors</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow border-0 p-4">
                    <h4 class="text-center mb-3 fw-bold">Recuperar Contraseña</h4>
                    <p class="text-muted text-center small">Ingresa tu correo electrónico y te enviaremos las instrucciones.</p>
                    
                    @if (session('status'))
                        <div class="alert alert-success small">{{ session('status') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger small">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Correo electrónico</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control" required placeholder="correo@ejemplo.com">
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold">Enviar enlace</button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="{{ url('/') }}" class="small text-decoration-none">Volver al inicio</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>