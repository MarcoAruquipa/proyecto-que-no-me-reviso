@extends('layouts.panel')

@section('title', $cliente->exists ? 'Actualizar cliente' : 'Registrar cliente')

@section('content')

<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">
                        {{ $cliente->exists ? 'Actualizar datos del cliente' : 'Registrar nuevo cliente prospecto' }}
                    </h3>
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

                    <form action="{{ $cliente->exists ? route('clientes.update', $cliente) : route('clientes.store') }}" method="POST">
                        @csrf

                        @if($cliente->exists)
                            @method('PUT')
                        @endif

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nombre completo</label>
                                <input type="text"
                                       name="nombre"
                                       class="form-control"
                                       value="{{ old('nombre', $cliente->nombre) }}"
                                       placeholder="Ejemplo: Ana Fernández Ramírez"
                                       required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">CI</label>
                                <input type="text"
                                       name="ci"
                                       class="form-control"
                                       value="{{ old('ci', $cliente->ci) }}"
                                       placeholder="Ejemplo: 12345678">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Teléfono</label>
                                <input type="text"
                                       name="telefono"
                                       class="form-control"
                                       value="{{ old('telefono', $cliente->telefono) }}"
                                       placeholder="Ejemplo: (+591) 78945123">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Correo electrónico</label>
                                <input type="email"
                                       name="email"
                                       class="form-control"
                                       value="{{ old('email', $cliente->email) }}"
                                       placeholder="cliente@gmail.com">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold">Dirección</label>
                                <input type="text"
                                       name="direccion"
                                       class="form-control"
                                       value="{{ old('direccion', $cliente->direccion) }}"
                                       placeholder="Ingrese la dirección del cliente">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Vehículo de interés</label>
                                <select name="vehiculo_interes" class="form-select">
                                    <option value="">Seleccione una opción</option>
                                    <option value="Sedán" {{ old('vehiculo_interes', $cliente->vehiculo_interes) == 'Sedán' ? 'selected' : '' }}>Sedán</option>
                                    <option value="SUV" {{ old('vehiculo_interes', $cliente->vehiculo_interes) == 'SUV' ? 'selected' : '' }}>SUV</option>
                                    <option value="Camioneta" {{ old('vehiculo_interes', $cliente->vehiculo_interes) == 'Camioneta' ? 'selected' : '' }}>Camioneta</option>
                                    <option value="Vagoneta" {{ old('vehiculo_interes', $cliente->vehiculo_interes) == 'Vagoneta' ? 'selected' : '' }}>Vagoneta</option>
                                    <option value="Deportivo" {{ old('vehiculo_interes', $cliente->vehiculo_interes) == 'Deportivo' ? 'selected' : '' }}>Deportivo</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Método de pago preferido</label>
                                <select name="metodo_pago" class="form-select">
                                    <option value="">Seleccione una opción</option>
                                    <option value="Contado" {{ old('metodo_pago', $cliente->metodo_pago) == 'Contado' ? 'selected' : '' }}>Contado</option>
                                    <option value="Crédito" {{ old('metodo_pago', $cliente->metodo_pago) == 'Crédito' ? 'selected' : '' }}>Crédito</option>
                                    <option value="Financiamiento" {{ old('metodo_pago', $cliente->metodo_pago) == 'Financiamiento' ? 'selected' : '' }}>Financiamiento</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Fuente</label>
                                <select name="fuente" class="form-select">
                                    <option value="">Seleccione una opción</option>
                                    <option value="Referido" {{ old('fuente', $cliente->fuente) == 'Referido' ? 'selected' : '' }}>Referido</option>
                                    <option value="Redes sociales" {{ old('fuente', $cliente->fuente) == 'Redes sociales' ? 'selected' : '' }}>Redes sociales</option>
                                    <option value="WhatsApp" {{ old('fuente', $cliente->fuente) == 'WhatsApp' ? 'selected' : '' }}>WhatsApp</option>
                                    <option value="Visita directa" {{ old('fuente', $cliente->fuente) == 'Visita directa' ? 'selected' : '' }}>Visita directa</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Estado del cliente</label>
                                <select name="estado" class="form-select">
                                    <option value="En negociación" {{ old('estado', $cliente->estado) == 'En negociación' ? 'selected' : '' }}>En negociación</option>
                                    <option value="Crédito aprobado" {{ old('estado', $cliente->estado) == 'Crédito aprobado' ? 'selected' : '' }}>Crédito aprobado</option>
                                    <option value="Documentos pendientes" {{ old('estado', $cliente->estado) == 'Documentos pendientes' ? 'selected' : '' }}>Documentos pendientes</option>
                                    <option value="Venta cerrada" {{ old('estado', $cliente->estado) == 'Venta cerrada' ? 'selected' : '' }}>Venta cerrada</option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold">Notas</label>
                                <textarea name="notas"
                                          class="form-control"
                                          rows="4"
                                          placeholder="Ejemplo: Cliente busca sedán mediano con financiamiento. Prefiere contacto por la mañana.">{{ old('notas', $cliente->notas) }}</textarea>
                            </div>

                        </div>

                        <div class="mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-pegaso px-4">
                                {{ $cliente->exists ? 'Actualizar datos' : 'Guardar cliente' }}
                            </button>

                            <a href="{{ route('clientes.index') }}" class="btn btn-secondary px-4">
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