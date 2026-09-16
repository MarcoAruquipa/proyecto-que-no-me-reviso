<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Maletín Digital - Movilidades')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f6f9;
        }

        .navbar {
            background: #071426;
        }

        .navbar-brand,
        .nav-link {
            color: white !important;
            font-weight: 600;
        }

        .nav-link:hover {
            color: #d4af37 !important;
        }

        .hero-principal {
            position: relative;
            min-height: 82vh;
            background:
                linear-gradient(rgba(3, 19, 39, 0.72), rgba(3, 19, 39, 0.72)),
                url('https://images.unsplash.com/photo-1563720223185-11003d516935?auto=format&fit=crop&w=1600&q=80');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
        }

        .min-vh-75 {
            min-height: 75vh;
        }

        .login-card {
            border-radius: 18px;
        }

        .logo-box {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, #d4af37, #ffffff);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 35px;
            color: #071426;
            box-shadow: 0 5px 20px rgba(0,0,0,.35);
        }

        .btn-primary {
            background: #073763;
            border-color: #073763;
        }

        .btn-primary:hover {
            background: #052744;
            border-color: #052744;
        }

        .btn-warning {
            background: #d4af37;
            border-color: #d4af37;
            color: #071426;
            font-weight: bold;
        }

        .card {
            border-radius: 16px;
        }

        footer {
            background: #071426;
            color: white;
            padding: 15px;
            text-align: center;
            margin-top: 0;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark shadow">
    <div class="container">
        <a class="navbar-brand" href="{{ route('inicio') }}">
            Pegaso Motors
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuPrincipal">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a href="{{ route('inicio') }}" class="nav-link">Inicio</a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('catalogo') }}" class="nav-link">Catálogo</a>
                </li>

                @guest
                    <li class="nav-item">
                        <a href="{{ route('login', ['tipo' => 'invitado']) }}" class="nav-link">Invitado</a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('login', ['tipo' => 'tecnico']) }}" class="nav-link">Ejecutivo de Venta</a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('login', ['tipo' => 'admin']) }}" class="nav-link">Jefe de Sucursal</a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('registro') }}" class="nav-link">Registro</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="{{ route('panel.dashboard') }}" class="nav-link">Panel principal</a>
                    </li>

                    @if(in_array(Auth::user()->role, ['tecnico', 'admin', 'jefe']))
                        <li class="nav-item">
                            <a href="{{ route('vehiculos.index') }}" class="nav-link">Movilidades</a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('clientes.index') }}" class="nav-link">Clientes</a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('ventas.index') }}" class="nav-link">Ventas</a>
                        </li>
                    @endif

                    @if(in_array(Auth::user()->role, ['admin', 'jefe']))
                        <li class="nav-item">
                            <a href="{{ route('panel.supervision') }}" class="nav-link">Supervisión</a>
                        </li>
                    @endif

                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-outline-light ms-2">
                                Cerrar sesión
                            </button>
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

@if(session('success'))
    <div class="container mt-3">
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    </div>
@endif

@if(session('error'))
    <div class="container mt-3">
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    </div>
@endif

@yield('content')

<footer>
    © 2026 Pegaso Motors - Maletín Digital de Venta de Movilidades <br>

    Proyecto desarrollado por:
    <a href="{{ route('maletin.principal') }}" style="color: white; font-weight: bold; text-decoration: underline;">
        MARCO ANTONIO ARUQUIPA LOZA
    </a>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>