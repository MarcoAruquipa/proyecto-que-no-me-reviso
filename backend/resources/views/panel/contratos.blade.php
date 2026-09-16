@extends('layouts.panel')

@section('title', 'Contratos')

@section('content')

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold"><i class="bi bi-file-earmark-plus me-2"></i>Registrar nuevo contrato</h5>
    </div>

    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Corrige los siguientes errores:</strong>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('contratos.store') }}" method="POST" class="row g-3">
            @csrf

            <div class="col-md-4">
                <label class="form-label fw-bold">Cliente</label>
                <select name="client_id" class="form-select" required>
                    <option value="">Seleccione un cliente</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}" {{ old('client_id') == $cliente->id ? 'selected' : '' }}>
                            {{ $cliente->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">Movilidad</label>
                <select name="vehicle_id" class="form-select">
                    <option value="">Sin movilidad asignada</option>
                    @foreach($vehiculos as $vehiculo)
                        <option value="{{ $vehiculo->id }}" {{ old('vehicle_id') == $vehiculo->id ? 'selected' : '' }}>
                            {{ $vehiculo->marca }} {{ $vehiculo->modelo }} ({{ $vehiculo->anio }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">Fecha de firma</label>
                <input type="date" name="fecha_firma" class="form-control" value="{{ old('fecha_firma') }}">
            </div>

            <div class="col-md-12">
                <label class="form-label fw-bold">Notas</label>
                <textarea name="notas" class="form-control" rows="2" placeholder="Detalles del contrato">{{ old('notas') }}</textarea>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-pegaso"><i class="bi bi-check-lg me-1"></i> Guardar contrato</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold"><i class="bi bi-list-check me-2"></i>Contratos registrados</h5>
    </div>

    <div class="card-body">
        @if($contratos->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Cliente</th>
                            <th>Movilidad</th>
                            <th>Ejecutivo</th>
                            <th>Fecha firma</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contratos as $contrato)
                            <tr>
                                <td class="fw-bold">{{ $contrato->client->nombre ?? 'Sin cliente' }}</td>
                                <td>
                                    @if($contrato->vehicle)
                                        {{ $contrato->vehicle->marca }} {{ $contrato->vehicle->modelo }}
                                    @else
                                        <span class="text-muted">Sin asignar</span>
                                    @endif
                                </td>
                                <td>{{ $contrato->user->name ?? '—' }}</td>
                                <td>{{ $contrato->fecha_firma ? \Carbon\Carbon::parse($contrato->fecha_firma)->format('d/m/Y') : 'Pendiente' }}</td>
                                <td>
                                    @if($contrato->estado === 'Firmado')
                                        <span class="badge text-bg-success">Firmado</span>
                                    @elseif($contrato->estado === 'En revisión')
                                        <span class="badge text-bg-warning">En revisión</span>
                                    @else
                                        <span class="badge text-bg-secondary">Pendiente</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if(in_array(Auth::user()->role, ['admin', 'jefe']))
                                        <form action="{{ route('contratos.update', $contrato) }}" method="POST" class="d-inline-flex gap-1">
                                            @csrf
                                            @method('PUT')

                                            <select name="estado" class="form-select form-select-sm" style="width:140px;">
                                                <option value="Pendiente" {{ $contrato->estado === 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                                <option value="En revisión" {{ $contrato->estado === 'En revisión' ? 'selected' : '' }}>En revisión</option>
                                                <option value="Firmado" {{ $contrato->estado === 'Firmado' ? 'selected' : '' }}>Firmado</option>
                                            </select>

                                            <input type="hidden" name="notas" value="{{ $contrato->notas }}">

                                            <button class="btn btn-sm btn-dorado">Actualizar</button>
                                        </form>
                                    @else
                                        <span class="text-muted small">Esperando supervisión</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info mb-0">Todavía no existen contratos registrados.</div>
        @endif
    </div>
</div>

@endsection