@extends('layouts.panel')

@section('title', $vehiculo->exists ? 'Actualizar movilidad' : 'Registrar movilidad')

@section('content')

    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                {{ $vehiculo->exists ? 'Actualizar datos de movilidad' : 'Registrar nueva movilidad' }}
            </h4>
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

            <form
                action="{{ $vehiculo->exists ? route('vehiculos.update', $vehiculo) : route('vehiculos.store') }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf

                @if($vehiculo->exists)
                    @method('PUT')
                @endif

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Marca</label>
                        <input type="text"
                               name="marca"
                               class="form-control"
                               value="{{ old('marca', $vehiculo->marca) }}"
                               placeholder="Ejemplo: Toyota"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Modelo</label>
                        <input type="text"
                               name="modelo"
                               class="form-control"
                               value="{{ old('modelo', $vehiculo->modelo) }}"
                               placeholder="Ejemplo: Corolla"
                               required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Año</label>
                        <input type="number"
                               name="anio"
                               class="form-control"
                               value="{{ old('anio', $vehiculo->anio) }}"
                               placeholder="2024"
                               required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Placa</label>
                        <input type="text"
                               name="placa"
                               class="form-control"
                               value="{{ old('placa', $vehiculo->placa) }}"
                               placeholder="Ejemplo: 1234ABC">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Color</label>
                        <input type="text"
                               name="color"
                               class="form-control"
                               value="{{ old('color', $vehiculo->color) }}"
                               placeholder="Ejemplo: Blanco">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Precio en Bs</label>
                        <input type="number"
                               step="0.01"
                               name="precio"
                               class="form-control"
                               value="{{ old('precio', $vehiculo->precio) }}"
                               placeholder="Ejemplo: 85000"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select" required>
                            <option value="Disponible" {{ old('estado', $vehiculo->estado) == 'Disponible' ? 'selected' : '' }}>
                                Disponible
                            </option>

                            <option value="Reservado" {{ old('estado', $vehiculo->estado) == 'Reservado' ? 'selected' : '' }}>
                                Reservado
                            </option>

                            <option value="Vendido" {{ old('estado', $vehiculo->estado) == 'Vendido' ? 'selected' : '' }}>
                                Vendido
                            </option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion"
                                  class="form-control"
                                  rows="4"
                                  placeholder="Ingrese características de la movilidad">{{ old('descripcion', $vehiculo->descripcion) }}</textarea>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Imagen de la movilidad</label>
                        <input type="file" name="imagen" class="form-control">

                        @if($vehiculo->imagen)
                            <div class="mt-3 d-flex align-items-center gap-3">
                                <div>
                                    <p class="mb-1 fw-semibold">Imagen actual:</p>
                                    <img src="{{ asset('storage/' . $vehiculo->imagen) }}"
                                         width="220"
                                         height="140"
                                         style="object-fit: cover; border-radius: 12px; border: 2px solid #4b0082;"
                                         onerror="this.style.display='none';">
                                </div>

                                <label class="mb-0">
                                    <input type="checkbox" name="eliminar_imagen" value="1" class="form-check-input me-1">
                                    Quitar imagen actual al guardar
                                </label>
                            </div>
                        @else
                            <div class="mt-3 form-text">
                                Aún no hay imagen cargada para esta movilidad.
                            </div>
                        @endif
                    </div>

                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-pegaso">
                        {{ $vehiculo->exists ? 'Actualizar datos' : 'Guardar movilidad' }}
                    </button>

                    <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary">
                        Cancelar
                    </a>
                </div>

            </form>

        </div>
    </div>

@endsection