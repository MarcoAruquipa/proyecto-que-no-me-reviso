@extends('layouts.panel')

@section('title', 'Registrar venta')

@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Registrar nueva venta</h3>
                </div>

                <div class="card-body p-4">

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

                    <form action="{{ route('ventas.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold">Cliente</label>
                            <select name="client_id" class="form-select" required>
                                <option value="">Seleccione un cliente</option>

                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">
                                        {{ $cliente->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Movilidad</label>
                            <select name="vehicle_id" class="form-select" required>
                                <option value="">Seleccione una movilidad</option>

                                @foreach($vehiculos as $vehiculo)
                                    <option value="{{ $vehiculo->id }}">
                                        {{ $vehiculo->marca ?? 'Sin marca' }}
                                        {{ $vehiculo->modelo ?? '' }}
                                        - {{ number_format($vehiculo->precio ?? 0, 2) }} Bs
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Fecha de venta</label>
                            <input type="date"
                                   name="fecha"
                                   class="form-control"
                                   value="{{ date('Y-m-d') }}"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Precio de venta</label>
                            <input type="number"
                                   step="0.01"
                                   name="precio_venta"
                                   class="form-control"
                                   placeholder="Ejemplo: 85000"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Observación</label>
                            <textarea name="observacion"
                                      class="form-control"
                                      rows="4"
                                      placeholder="Ingrese una observación de la venta"></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-pegaso">
                                Guardar venta
                            </button>

                            <a href="{{ route('ventas.index') }}" class="btn btn-secondary">
                                Cancelar
                            </a>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>

@endsection