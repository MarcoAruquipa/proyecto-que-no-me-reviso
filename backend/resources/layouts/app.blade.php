<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Maletín Digital - Movilidades</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            margin: 0;
        }

        header {
            background: #111827;
            color: white;
            padding: 15px 40px;
        }

        nav a, nav button {
            color: white;
            margin-right: 15px;
            text-decoration: none;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 15px;
        }

        .container {
            width: 90%;
            margin: 25px auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 0 10px #ddd;
        }

        .btn {
            display: inline-block;
            padding: 9px 14px;
            background: #2563eb;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            margin: 3px;
        }

        .btn-red {
            background: #dc2626;
        }

        .btn-green {
            background: #16a34a;
        }

        .btn-dark {
            background: #111827;
        }

        input, select, textarea {
            width: 100%;
            padding: 9px;
            margin: 6px 0 13px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 9px;
            text-align: left;
        }

        th {
            background: #e5e7eb;
        }

        .success {
            background: #dcfce7;
            padding: 10px;
            border-radius: 8px;
            color: #166534;
            margin-bottom: 12px;
        }

        .error {
            background: #fee2e2;
            padding: 10px;
            border-radius: 8px;
            color: #991b1b;
            margin-bottom: 12px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 15px;
        }

        .card {
            background: #f9fafb;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 12px;
        }

        .card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-radius: 10px;
        }
    </style>
</head>
<body>

<header>
    <nav>
        <a href="{{ route('inicio') }}">Inicio</a>
        <a href="{{ route('catalogo') }}">Catálogo</a>

        @auth
            @if(auth()->user()->role == 'tecnico')
                <a href="{{ route('tecnico.dashboard') }}">Técnico 1</a>
            @endif

            @if(auth()->user()->role == 'admin')
                <a href="{{ route('admin.dashboard') }}">Administrador</a>
            @endif

            @if(auth()->user()->role == 'tecnico' || auth()->user()->role == 'admin')
                <a href="{{ route('vehiculos.index') }}">Movilidades</a>
                <a href="{{ route('clientes.index') }}">Clientes</a>
                <a href="{{ route('ventas.index') }}">Ventas</a>
            @endif

            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit">Cerrar sesión</button>
            </form>
        @else
            <a href="{{ route('login') }}">Sesión</a>
            <a href="{{ route('registro') }}">Registro</a>
        @endauth
    </nav>
</header>

<div class="container">
    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @yield('content')
</div>

</body>
</html>