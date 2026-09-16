@extends('layouts.panel')

@section('title', 'Movilidades')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="pagina-titulo mb-1">Movilidades</h1>

            <p class="text-muted mb-0">
                Registrar, mostrar, actualizar y eliminar movilidades.
            </p>
        </div>

        <a href="{{ route('vehiculos.create') }}" class="btn btn-pegaso">
            <i class="bi bi-plus-lg me-1"></i> Nueva movilidad
        </a>
    </div>

    <div class="card shadow border-0">
        <div class="card-header bg-dark text-white">
            Lista de movilidades registradas
        </div>

        <div class="card-body">

            @if($vehiculos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Imagen</th>
                                <th>Marca</th>
                                <th>Modelo</th>
                                <th>Año</th>
                                <th>Placa</th>
                                <th>Color</th>
                                <th>Precio</th>
                                <th>Estado</th>
                                <th width="180">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($vehiculos as $vehiculo)
                                <tr>
                                    <td>{{ $vehiculo->id }}</td>

                                    <td>
                                        @if($vehiculo->imagen)
                                            <img src="{{ asset('storage/' . $vehiculo->imagen) }}"
                                                 width="80"
                                                 height="55"
                                                 style="object-fit: cover; border-radius: 8px;"
                                                 onerror="this.style.display='none';">
                                        @else
                                            <span class="text-muted">Sin imagen</span>
                                        @endif
                                    </td>

                                    <td>{{ $vehiculo->marca }}</td>
                                    <td>{{ $vehiculo->modelo }}</td>
                                    <td>{{ $vehiculo->anio }}</td>
                                    <td>{{ $vehiculo->placa ?? 'Sin placa' }}</td>
                                    <td>{{ $vehiculo->color ?? 'No definido' }}</td>

                                    <td>
                                        {{ number_format($vehiculo->precio, 2) }} Bs
                                    </td>

                                    <td>
                                        @if($vehiculo->estado == 'Disponible')
                                            <span class="badge bg-success">Disponible</span>
                                        @elseif($vehiculo->estado == 'Reservado')
                                            <span class="badge bg-warning text-dark">Reservado</span>
                                        @else
                                            <span class="badge bg-danger">Vendido</span>
                                        @endif
                                    </td>

                                    <td>
                                        <a href="{{ route('vehiculos.edit', $vehiculo) }}"
                                           class="btn btn-sm btn-warning">
                                            Actualizar
                                        </a>

                                        <form action="{{ route('vehiculos.destroy', $vehiculo) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('¿Seguro que desea eliminar esta movilidad?')">
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
                    Todavía no existen movilidades registradas.
                    Presione <strong>Nueva movilidad</strong> para registrar una.
                </div>
            @endif

        </div>
    </div>

@endsection