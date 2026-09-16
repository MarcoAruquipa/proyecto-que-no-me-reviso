@extends('layouts.panel')

@section('title', 'Supervisión de Ejecutivos')

@section('content')

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="text-muted small text-uppercase fw-bold">Clientes</div>
                <div class="fs-2 fw-bold" style="color:#4b0082;">{{ $totalClientes }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="text-muted small text-uppercase fw-bold">Ventas</div>
                <div class="fs-2 fw-bold" style="color:#0f766e;">{{ $totalVentas }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="text-muted small text-uppercase fw-bold">Ingresos</div>
                <div class="fs-2 fw-bold" style="color:#b45309;">{{ number_format($totalIngresos, 0, ',', '.') }} Bs</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="text-muted small text-uppercase fw-bold">Comisiones (5%)</div>
                <div class="fs-2 fw-bold" style="color:#7c2d12;">{{ number_format($totalComisiones, 2, ',', '.') }} Bs</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small text-uppercase fw-bold">Documentos del equipo</div>
                    <div class="fs-2 fw-bold">{{ $totalDocumentos }}</div>
                </div>
                <i class="bi bi-folder2-open fs-1 text-primary"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small text-uppercase fw-bold">Contratos totales</div>
                    <div class="fs-2 fw-bold">{{ $totalContratos }}</div>
                    <div class="small text-muted">{{ $totalFirmados }} firmados</div>
                </div>
                <i class="bi bi-file-earmark-check fs-1 text-success"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small text-uppercase fw-bold">Avance de firmas</div>
                    <div class="fs-2 fw-bold">{{ $totalContratos > 0 ? round(($totalFirmados / $totalContratos) * 100) : 0 }}%</div>
                </div>
                <i class="bi bi-check2-circle fs-1" style="color:#4b0082;"></i>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold"><i class="bi bi-person-badge me-2"></i>Desempeño por ejecutivo</h5>
    </div>

    <div class="card-body">
        @if($filas->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Ejecutivo</th>
                            <th class="text-center">Ventas</th>
                            <th class="text-end">Monto</th>
                            <th class="text-end">Comisión</th>
                            <th class="text-center">Clientes atendidos</th>
                            <th class="text-center">% de clientes</th>
                            <th class="text-center">Documentos</th>
                            <th class="text-center">Contratos</th>
                            <th class="text-center">Firmados</th>
                            <th>Avance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($filas as $fila)
                            <tr>
                                <td class="fw-bold">
                                    {{ $fila['usuario']->name }}
                                    <div class="small text-muted">{{ $fila['usuario']->username }}</div>
                                </td>

                                <td class="text-center">{{ $fila['ventas'] }}</td>
                                <td class="text-end fw-bold">{{ number_format($fila['monto'], 2, ',', '.') }} Bs</td>
                                <td class="text-end" style="color:#0f766e; font-weight: 600;">{{ number_format($fila['comision'], 2, ',', '.') }} Bs</td>
                                <td class="text-center">{{ $fila['clientes_atendidos'] }}</td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center gap-2 justify-content-center">
                                        <span>{{ $fila['pct_clientes'] }}%</span>
                                        <div class="progress" style="width:60px; height:6px;">
                                            <div class="progress-bar" style="width:{{ min(100, $fila['pct_clientes']) }}%; background:#4b0082;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">{{ $fila['documentos'] }}</td>
                                <td class="text-center">{{ $fila['contratos'] }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $fila['firmados'] > 0 ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $fila['firmados'] }}</span>
                                </td>
                                <td>
                                    @if($fila['avance'] >= 80)
                                        <span class="badge text-bg-success">{{ $fila['avance'] }}%</span>
                                    @elseif($fila['avance'] >= 40)
                                        <span class="badge text-bg-warning">{{ $fila['avance'] }}%</span>
                                    @else
                                        <span class="badge text-bg-danger">{{ $fila['avance'] }}%</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info mb-0">No hay ejecutivos de venta registrados todavía.</div>
        @endif
    </div>
</div>

@endsection