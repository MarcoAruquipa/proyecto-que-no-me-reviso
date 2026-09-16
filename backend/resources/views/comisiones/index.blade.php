@extends('layouts.panel')

@section('title', 'Comisiones')

@section('content')

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="text-muted small text-uppercase fw-bold" style="color:#0f766e;">Comisión total (5%)</div>
                <div class="fs-2 fw-bold" style="color:#0f766e;">{{ number_format($total ?? 0, 2, ',', '.') }} Bs</div>
                <div class="small text-muted">Comisión calculada sobre el precio de venta.</div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="text-muted small text-uppercase fw-bold">Ventas consideradas</div>
                <div class="fs-2 fw-bold" style="color:#4b0082;">{{ $ventas->count() }}</div>
                <div class="small text-muted">
                    @if((Auth::user()->role ?? '') === 'tecnico')
                        Solo muestro tus ventas registradas.
                    @else
                        Ventas de todo el equipo de ejecutivos.
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if((Auth::user()->role ?? '') !== 'tecnico' && $porUsuario->count() > 0)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-people me-2"></i>Comisiones por ejecutivo</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Ejecutivo</th>
                            <th class="text-center">Ventas</th>
                            <th class="text-end">Monto vendido</th>
                            <th class="text-end">Comisión (5%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($porUsuario as $fila)
                            <tr>
                                <td class="fw-bold">{{ $fila['usuario']->name ?? 'Sin usuario' }}</td>
                                <td class="text-center">{{ $fila['ventas'] }}</td>
                                <td class="text-end">{{ number_format($fila['monto'], 2, ',', '.') }} Bs</td>
                                <td class="text-end fw-bold" style="color:#0f766e;">{{ number_format($fila['comision'], 2, ',', '.') }} Bs</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold"><i class="bi bi-list-check me-2"></i>Detalle de comisiones</h5>
    </div>

    <div class="card-body">
        @if($ventas->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Cliente</th>
                            <th>Movilidad</th>
                            <th>Fecha</th>
                            <th class="text-end">Precio de venta</th>
                            <th class="text-end">Comisión 5%</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ventas as $venta)
                            <tr>
                                <td>{{ $venta->client->nombre ?? 'Cliente no registrado' }}</td>
                                <td>
                                    @if($venta->vehicle)
                                        {{ $venta->vehicle->marca ?? 'Sin marca' }} {{ $venta->vehicle->modelo ?? '' }}
                                    @else
                                        <span class="text-muted">Movilidad no registrada</span>
                                    @endif
                                </td>
                                <td>{{ $venta->fecha ? \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') : 'Sin fecha' }}</td>
                                <td class="text-end fw-bold">{{ number_format($venta->precio_venta ?? 0, 2, ',', '.') }} Bs</td>
                                <td class="text-end fw-bold" style="color:#0f766e;">{{ number_format(($venta->precio_venta ?? 0) * 0.05, 2, ',', '.') }} Bs</td>
                                <td><span class="badge text-bg-success">Pagado</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info mb-0">
                Todavía no existen ventas registradas para calcular comisiones.
            </div>
        @endif
    </div>
</div>

@endsection