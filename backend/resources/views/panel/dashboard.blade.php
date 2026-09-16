@extends('layouts.panel')

@section('title', 'Panel principal')

@section('content')

<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle p-3 text-white" style="background: linear-gradient(135deg,#4b0082,#6d28d9);">
                    <i class="bi bi-people fs-3"></i>
                </div>
                <div>
                    <div class="text-muted small text-uppercase fw-bold">Clientes</div>
                    <div class="fs-3 fw-bold" style="color:#4b0082;">{{ $clientes }}</div>
                    <div class="small text-muted">{{ $prospectos }} en negociación</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle p-3 text-white" style="background: linear-gradient(135deg,#0f766e,#14b8a6);">
                    <i class="bi bi-car-front fs-3"></i>
                </div>
                <div>
                    <div class="text-muted small text-uppercase fw-bold">Vehículos</div>
                    <div class="fs-3 fw-bold" style="color:#0f766e;">{{ $vehiculos }}</div>
                    <div class="small text-muted">{{ $disponibles }} disponibles</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle p-3 text-white" style="background: linear-gradient(135deg,#b45309,#f59e0b);">
                    <i class="bi bi-cart-check fs-3"></i>
                </div>
                <div>
                    <div class="text-muted small text-uppercase fw-bold">Ventas</div>
                    <div class="fs-3 fw-bold" style="color:#b45309;">{{ $ventas }}</div>
                    <div class="small text-muted">ventas cerradas</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle p-3 text-white" style="background: linear-gradient(135deg,#7c2d12,#c2410c);">
                    <i class="bi bi-cash-stack fs-3"></i>
                </div>
                <div>
                    <div class="text-muted small text-uppercase fw-bold">Ingresos</div>
                    <div class="fs-3 fw-bold" style="color:#7c2d12;">{{ number_format($ingresos, 0, ',', '.') }} Bs</div>
                    <div class="small text-muted">total acumulado</div>
                </div>
            </div>
        </div>
    </div>
</div>

@if((Auth::user()->role ?? '') === 'tecnico')
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small text-uppercase fw-bold">Mis ventas</div>
                        <div class="fs-2 fw-bold" style="color:#4b0082;">{{ $misVentas }}</div>
                    </div>
                    <i class="bi bi-trophy fs-1 text-warning"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small text-uppercase fw-bold">Mis comisiones (5%)</div>
                        <div class="fs-2 fw-bold" style="color:#0f766e;">{{ number_format($misComisiones, 2, ',', '.') }} Bs</div>
                    </div>
                    <i class="bi bi-percent fs-1 text-success"></i>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2"></i>Ventas recientes</h5>
            <a href="{{ route('ventas.index') }}" class="btn btn-sm btn-pegaso">Ver todas</a>
        </div>
    </div>

    <div class="card-body">
        @if($ventasRecientes->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Cliente</th>
                            <th>Movilidad</th>
                            <th>Ejecutivo</th>
                            <th>Fecha</th>
                            <th class="text-end">Precio</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ventasRecientes as $venta)
                            <tr>
                                <td>{{ $venta->client->nombre ?? 'Cliente no registrado' }}</td>
                                <td>
                                    @if($venta->vehicle)
                                        {{ $venta->vehicle->marca }} {{ $venta->vehicle->modelo }}
                                    @else
                                        <span class="text-muted">Movilidad no registrada</span>
                                    @endif
                                </td>
                                <td>{{ $venta->user->name ?? '—' }}</td>
                                <td>{{ $venta->fecha ? \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') : '—' }}</td>
                                <td class="text-end fw-bold">{{ number_format($venta->precio_venta ?? 0, 2, ',', '.') }} Bs</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info mb-0">Todavía no existen ventas registradas.</div>
        @endif
    </div>
</div>

@if(in_array(Auth::user()->role, ['admin', 'jefe']))
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold"><i class="bi bi-clipboard-data me-2"></i>Supervisión de ejecutivos</h5>
    </div>

    <div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-md-3"><div class="border rounded-3 p-3 text-center"><div class="small text-muted">Ejecutivos</div><div class="fs-4 fw-bold">{{ $ejecutivos }}</div></div></div>
            <div class="col-md-3"><div class="border rounded-3 p-3 text-center"><div class="small text-muted">Ventas</div><div class="fs-4 fw-bold">{{ $ventas }}</div></div></div>
            <div class="col-md-3"><div class="border rounded-3 p-3 text-center"><div class="small text-muted">Ingresos</div><div class="fs-4 fw-bold">{{ number_format($ingresos, 0, ',', '.') }} Bs</div></div></div>
            <div class="col-md-3"><div class="border rounded-3 p-3 text-center"><div class="small text-muted">Comisiones 5%</div><div class="fs-4 fw-bold">{{ number_format($ingresos * 0.05, 2, ',', '.') }} Bs</div></div></div>
        </div>

        <a href="{{ route('panel.supervision') }}" class="btn btn-dorado">
            <i class="bi bi-person-badge me-1"></i> Ver detalle por ejecutivo
        </a>
    </div>
</div>
@endif

@endsection