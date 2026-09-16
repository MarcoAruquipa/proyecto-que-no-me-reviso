@extends('layouts.panel')

@section('title', 'Ventas')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="pagina-titulo mb-1">Ventas registradas</h1>
            <p class="text-muted mb-0">
                Panel de control de ventas para el Jefe de Sucursal y Ejecutivo de Venta.
            </p>
        </div>

        <a href="{{ route('ventas.create') }}" class="btn btn-pegaso">
            <i class="bi bi-plus-lg me-1"></i> Registrar venta
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow border-0">
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0">Lista de ventas realizadas</h4>
        </div>

        <div class="card-body">

            @if($ventas->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Movilidad</th>
                                <th>Ejecutivo</th>
                                <th>Fecha</th>
                                <th>Precio de venta</th>
                                <th>Comisión 5%</th>
                                <th>Observación</th>
                                <th width="120">Acción</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($ventas as $venta)
                                <tr>
                                    <td>{{ $venta->id }}</td>

                                    <td>
                                        {{ $venta->client->nombre ?? 'Cliente no registrado' }}
                                    </td>

                                    <td>
                                        @if($venta->vehicle)
                                            {{ $venta->vehicle->marca ?? '' }}
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

                                    <td>
                                        {{ $venta->observacion ?? 'Sin observación' }}
                                    </td>

                                    <td>
                                        <form action="{{ route('ventas.destroy', $venta) }}"
                                              method="POST"
                                              onsubmit="return confirm('¿Seguro que desea eliminar esta venta?')">
                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-sm btn-danger">
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            @else
                <div class="alert alert-info mb-0">
                    Todavía no existen ventas registradas.
                </div>
            @endif

        </div>
    </div>

@endsection