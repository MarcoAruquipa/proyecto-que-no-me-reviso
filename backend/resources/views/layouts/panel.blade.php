<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Panel Pegaso Motors')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --pegaso-oscuro: #22103f;
            --pegaso-primario: #4b0082;
            --pegaso-secundario: #5b21b6;
            --pegaso-dorado: #d4af37;
            --pegaso-fondo: #f4f6f9;
        }

        body {
            font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
            background: var(--pegaso-fondo);
        }

        .panel-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .panel-sidebar {
            width: 260px;
            flex-shrink: 0;
            background: linear-gradient(180deg, #2b1552, #1a0b33);
            color: #fff;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }

        .panel-sidebar .marca {
            padding: 20px 18px;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
        }

        .panel-sidebar .nav-link {
            color: rgba(255, 255, 255, .82);
            font-weight: 500;
            border-radius: 10px;
            padding: 10px 14px;
            margin: 2px 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14.5px;
        }

        .panel-sidebar .nav-link:hover {
            color: #fff;
            background: rgba(212, 175, 55, .16);
        }

        .panel-sidebar .nav-link.active {
            background: linear-gradient(90deg, var(--pegaso-primario), var(--pegaso-secundario));
            color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .3);
        }

        .panel-sidebar .nav-section {
            color: rgba(255, 255, 255, .45);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.6px;
            margin: 18px 18px 6px;
        }

        .panel-sidebar .rol-badge {
            margin: 14px 18px;
            background: rgba(255, 255, 255, .07);
            border-radius: 12px;
            padding: 12px 14px;
        }

        .rol-badge .rol {
            color: var(--pegaso-dorado);
            font-weight: 700;
            font-size: 13px;
        }

        .panel-main {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .panel-topbar {
            background: #fff;
            border-bottom: 1px solid #e6e9f0;
            padding: 14px 26px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
        }

        .panel-topbar .titulo {
            font-weight: 800;
            color: var(--pegaso-oscuro);
            margin: 0;
            font-size: 19px;
        }

        .panel-content {
            padding: 26px;
            flex: 1;
        }

        .btn-pegaso {
            background: linear-gradient(90deg, var(--pegaso-primario), var(--pegaso-secundario));
            border: none;
            color: #fff;
            font-weight: 600;
        }

        .btn-pegaso:hover {
            filter: brightness(1.12);
            color: #fff;
        }

        .btn-dorado {
            background: var(--pegaso-dorado);
            border: none;
            color: #22103f;
            font-weight: 700;
        }

        .btn-dorado:hover {
            filter: brightness(1.08);
            color: #22103f;
        }

        .card {
            border-radius: 16px;
            border: none;
            box-shadow: 0 4px 18px rgba(34, 16, 63, .06);
        }

        .card-header {
            font-weight: 700;
            background: #fff;
            border-bottom: 1px solid #eef0f5;
            color: var(--pegaso-oscuro);
        }

        .pagina-titulo {
            color: var(--pegaso-oscuro);
            font-weight: 800;
        }

        .badge {
            font-weight: 600;
        }

        footer.panel-footer {
            background: #fff;
            border-top: 1px solid #e6e9f0;
            color: #7a8292;
            padding: 12px 26px;
            font-size: 13px;
        }
    </style>
</head>
<body>

@php
    $usuario = Auth::user();
    $rolNombre = match($usuario->role) {
        'admin' => 'Administrador General',
        'jefe' => 'Jefe de Sucursal',
        'tecnico' => 'Ejecutivo de Venta',
        default => 'Invitado',
    };
    $esJefe = in_array($usuario->role, ['admin', 'jefe']);
@endphp

<div class="panel-wrapper">

    <aside class="panel-sidebar">

        <div class="marca">
            @include('partials.logo', ['size' => 'sm'])
        </div>

        <nav class="nav flex-column">
            <div class="nav-section">Menú principal</div>

            <a href="{{ route('panel.dashboard') }}" class="nav-link {{ request()->routeIs('panel.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Panel principal
            </a>

            <a href="{{ route('vehiculos.index') }}" class="nav-link {{ request()->routeIs('vehiculos.*') ? 'active' : '' }}">
                <i class="bi bi-car-front"></i> Movilidades
            </a>

            <a href="{{ route('clientes.index') }}" class="nav-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Clientes
            </a>

            <a href="{{ route('ventas.index') }}" class="nav-link {{ request()->routeIs('ventas.*') ? 'active' : '' }}">
                <i class="bi bi-cash-coin"></i> Ventas
            </a>

            <a href="{{ route('comisiones.index') }}" class="nav-link {{ request()->routeIs('comisiones.index') ? 'active' : '' }}">
                <i class="bi bi-percent"></i> Comisiones
            </a>

            <div class="nav-section">Equipo</div>

            <a href="{{ route('documentos.index') }}" class="nav-link {{ request()->routeIs('documentos.*') ? 'active' : '' }}">
                <i class="bi bi-folder2-open"></i> Documentos
            </a>

            <a href="{{ route('panel.contratos') }}" class="nav-link {{ request()->routeIs('panel.contratos') || request()->routeIs('contratos.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-check"></i> Contratos
            </a>

            @if($esJefe)
                <a href="{{ route('panel.supervision') }}" class="nav-link {{ request()->routeIs('panel.supervision') ? 'active' : '' }}">
                    <i class="bi bi-clipboard-data"></i> Supervisión
                </a>
            @endif

            <div class="nav-section">Cuenta</div>

            <a href="{{ route('perfil') }}" class="nav-link {{ request()->routeIs('perfil') ? 'active' : '' }}">
                <i class="bi bi-person-circle"></i> Mi perfil
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link" style="border: 0; background: none; width: calc(100% - 24px); cursor: pointer;">
                    <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                </button>
            </form>
        </nav>

        <div class="rol-badge">
            <div class="small opacity-75">Rol asignado</div>
            <div class="rol">{{ $rolNombre }}</div>
            <div class="small opacity-75">{{ $usuario->name }}</div>
        </div>

    </aside>

    <main class="panel-main">

        <header class="panel-topbar">
            <h1 class="titulo">@yield('title', 'Panel Pegaso Motors')</h1>

            <div class="d-flex align-items-center gap-2">
                <span class="badge text-bg-dark">
                    <i class="bi bi-calendar3"></i> {{ now()->format('d/m/Y') }}
                </span>
            </div>
        </header>

        @if(session('success'))
            <div class="container-fluid mt-3">
                <div class="alert alert-success mb-0">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="container-fluid mt-3">
                <div class="alert alert-danger mb-0">{{ session('error') }}</div>
            </div>
        @endif

        <div class="panel-content">
            @yield('content')
        </div>

        <footer class="panel-footer">
            © 2026 Pegaso Motors · Maletín Digital de Venta de Movilidades · Marco Antonio Aruquipa Loza
        </footer>

    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>