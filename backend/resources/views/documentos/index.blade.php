@extends('layouts.panel')

@section('title', 'Documentos')

@section('content')

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold"><i class="bi bi-upload me-2"></i>Subir documento</h5>
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

        <form action="{{ route('documentos.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
            @csrf

            <div class="col-md-4">
                <label class="form-label fw-bold">Tipo de documento</label>
                <select name="tipo" class="form-select" required>
                    <option value="">Seleccione el tipo</option>
                    <option value="Cédula de Identidad">Cédula de Identidad</option>
                    <option value="Cédula Reverso">Cédula Reverso</option>
                    <option value="Boleta de Pago">Boleta de Pago</option>
                    <option value="Factura">Factura</option>
                    <option value="Contrato">Contrato</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">Cliente asociado</label>
                <select name="client_id" class="form-select">
                    <option value="">Sin cliente</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}" {{ old('client_id') == $cliente->id ? 'selected' : '' }}>
                            {{ $cliente->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">Archivo (PDF, JPG, PNG)</label>
                <input type="file" name="archivo" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
            </div>

            <div class="col-md-8">
                <label class="form-label fw-bold">Título / descripción</label>
                <input type="text" name="titulo" class="form-control" value="{{ old('titulo') }}" placeholder="Ej.: Cédula frente - Ana Fernández">
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-pegaso"><i class="bi bi-cloud-arrow-up me-1"></i> Subir documento</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold"><i class="bi bi-folder2-open me-2"></i>Documentos del equipo ({{ $documentos->count() }})</h5>
    </div>

    <div class="card-body">
        @if($documentos->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tipo</th>
                            <th>Título</th>
                            <th>Cliente</th>
                            <th>Subido por</th>
                            <th>Fecha</th>
                            <th>Archivo</th>
                            <th class="text-end">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($documentos as $documento)
                            <tr>
                                <td><span class="badge text-bg-primary">{{ $documento->tipo }}</span></td>
                                <td>{{ $documento->titulo ?: 'Sin título' }}</td>
                                <td>{{ $documento->client->nombre ?? '—' }}</td>
                                <td>{{ $documento->user->name ?? '—' }}</td>
                                <td>{{ $documento->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($documento->archivo)
                                        <a href="{{ asset('storage/' . $documento->archivo) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye me-1"></i>Ver
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if(in_array(Auth::user()->role, ['admin', 'jefe']) || $documento->user_id === Auth::id())
                                        <form action="{{ route('documentos.destroy', $documento) }}" method="POST"
                                              onsubmit="return confirm('¿Eliminar este documento?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info mb-0">Todavía no existen documentos cargados. Usa el formulario superior para subir el primer documento.</div>
        @endif
    </div>
</div>

@endsection