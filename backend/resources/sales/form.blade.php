@extends('layouts.app')

@section('content')
<h2>Registrar venta</h2>

<form action="{{ route('ventas.store') }}" method="POST">
    @csrf

    <label>Movilidad</label>
    <select name="vehicle_id">
        <option value="">Seleccione una movilidad</option>
        @foreach($vehiculos as $v)
            <option value="{{ $v->id }}">
                {{ $v->marca }} {{ $v->modelo }} - Bs {{ $v->precio }}
            </option>
        @endforeach
    </select>

    <label>Cliente</label>
    <select name="client_id">
        <option value="">Seleccione un cliente</option>
        @foreach($clientes as $c)
            <option value="{{ $c->id }}">
                {{ $c->nombre }} - {{ $c->telefono }}
            </option>
        @endforeach
    </select>

    <label>Fecha</label>
    <input type="date" name="fecha">

    <label>Precio de venta</label>
    <input type="number" step="0.01" name="precio_venta">

    <label>Observación</label>
    <textarea name="observacion"></textarea>

    <button class="btn" type="submit">Guardar venta</button>
</form>
@endsection