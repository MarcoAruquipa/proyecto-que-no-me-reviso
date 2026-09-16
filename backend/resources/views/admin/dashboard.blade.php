@extends('layouts.app')

@section('title', 'Panel Jefe de Sucursal')

@section('content')

<div class="container py-5">

    <div class="card shadow border-0 mb-4">
        <div class="card-body">
            <h1 class="fw-bold text-primary mb-2">
                Panel de Control - Jefe de Sucursal
            </h1>

            <p class="text-muted mb-0">
                Resumen general del maletín digital de venta de movilidades.
            </p>
        </div>
    </div>

    <div class="row g-4 mb-4">

        <div class="col-md-3">
            <div class="card shadow border-0 text-center">
                <div class="card-body">
                    <h5 class="text-muted">Usuarios</h5>
                    <h1 class="fw-bold text-primary">{{ $usuarios ?? 0 }}</h1>
                    <p class="mb-0">Usuarios registrados</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow border-0 text-center">
                <div class="card-body">
                    <h5 class="text-muted">Movilidades</h5>
                    <h1 class="fw-bold text-success">{{ $vehiculos ?? 0 }}</h1>
                    <p class="mb-0">Autos registrados</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow border-0 text-center">
                <div class="card-body">
                    <h5 class="text-muted">Clientes</h5>
                    <h1 class="fw-bold text-warning">{{ $clientes ?? 0 }}</h1>
                    <p class="mb-0">Clientes prospectos</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow border-0 text-center">
                <div class="card-body">
                    <h5 class="text-muted">Ventas</h5>
                    <h1 class="fw-bold text-danger">{{ $ventas ?? 0 }}</h1>
                    <p class="mb-0">Ventas realizadas</p>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-4 mb-4">

        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-header bg-dark text-white">
                    Monto total de ventas
                </div>

                <div class="card-body text-center">
                    <h1 class="fw-bold text-success">
                        {{ number_format($montoVentas ?? 0, 2) }} Bs
                    </h1>

                    <p class="text-muted mb-0">
                        Total acumulado por ventas registradas.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    Accesos rápidos
                </div>

                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('vehiculos.index') }}" class="btn btn-success">
                            Gestionar movilidades
                        </a>

                        <a href="{{ route('clientes.index') }}" class="btn btn-warning">
                            Gestionar clientes
                        </a>

                        <a href="{{ route('ventas.index') }}" class="btn btn-info">
                            Ver ventas
                        </a>

                        <a href="{{ route('comisiones.index') }}" class="btn btn-outline-dark">
                            Ver comisiones
                        </a>

                        <a href="{{ route('maletin.principal') }}" class="btn btn-secondary">
                            Volver a mi Maletín
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="card shadow border-0">
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0">Ventas recientes</h4>
        </div>

        <div class="card-body">

            @if(isset($ventasRecientes) && $ventasRecientes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Cliente</th>
                                <th>Movilidad</th>
                                <th>Ejecutivo</th>
                                <th>Fecha</th>
                                <th>Precio venta</th>
                                <th>Comisión</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($ventasRecientes as $venta)
                                <tr>
                                    <td>
                                        {{ $venta->client->nombre ?? 'Cliente no registrado' }}
                                    </td>

                                    <td>
                                        @if($venta->vehicle)
                                            {{ $venta->vehicle->marca ?? 'Sin marca' }}
                                            {{ $venta->vehicle->modelo ?? '' }}
                                        @else
                                            Movilidad no registrada
                                        @endif
                                    </td>

                                    <td>
                                        {{ $venta->user->name ?? 'Sin usuario' }}
                                    </td>

                                    <td>
                                        {{ $venta->fecha ?? 'Sin fecha' }}
                                    </td>

                                    <td class="fw-bold text-primary">
                                        {{ number_format($venta->precio_venta ?? 0, 2) }} Bs
                                    </td>

                                    <td class="fw-bold text-success">
                                        {{ number_format(($venta->precio_venta ?? 0) * 0.05, 2) }} Bs
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info mb-0">
                    Todavía no existen ventas recientes registradas.
                </div>
            @endif

        </div>
    </div>

</div>

@endsection