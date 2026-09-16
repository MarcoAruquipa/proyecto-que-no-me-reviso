@extends('layouts.panel')

@section('title', 'Clientes')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="pagina-titulo mb-1">Clientes</h1>
            <p class="text-muted mb-0">
                Registro, visualización, actualización y eliminación de clientes prospectos.
            </p>
        </div>

        <a href="{{ route('clientes.create') }}" class="btn btn-pegaso">
            <i class="bi bi-person-plus me-1"></i> Registrar cliente
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
            <h4 class="mb-0">Lista de clientes registrados</h4>
        </div>

        <div class="card-body">

            @if($clientes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Nombre</th>
                                <th>CI</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                                <th>Dirección</th>
                                <th>Vehículo de interés</th>
                                <th>Estado</th>
                                <th width="180">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($clientes as $cliente)
                                <tr>
                                    <td class="fw-bold">
                                        {{ $cliente->nombre ?? 'Sin nombre' }}
                                    </td>

                                    <td>
                                        {{ $cliente->ci ?? 'No registrado' }}
                                    </td>

                                    <td>
                                        {{ $cliente->telefono ?? 'No registrado' }}
                                    </td>

                                    <td>
                                        {{ $cliente->email ?? 'No registrado' }}
                                    </td>

                                    <td>
                                        {{ $cliente->direccion ?? 'No registrada' }}
                                    </td>

                                    <td>
                                        {{ $cliente->vehiculo_interes ?? 'No definido' }}
                                    </td>

                                    <td>
                                        @if(($cliente->estado ?? '') == 'Crédito aprobado')
                                            <span class="badge bg-success">Crédito aprobado</span>
                                        @elseif(($cliente->estado ?? '') == 'Documentos pendientes')
                                            <span class="badge bg-warning text-dark">Documentos pendientes</span>
                                        @elseif(($cliente->estado ?? '') == 'Venta cerrada')
                                            <span class="badge bg-primary">Venta cerrada</span>
                                        @else
                                            <span class="badge bg-info text-dark">
                                                {{ $cliente->estado ?? 'En negociación' }}
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-sm btn-warning">
                                            Editar
                                        </a>

                                        <form action="{{ route('clientes.destroy', $cliente) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('¿Seguro que desea eliminar este cliente?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-danger">
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
                    Todavía no existen clientes registrados.
                </div>
            @endif

        </div>
    </div>

@endsection