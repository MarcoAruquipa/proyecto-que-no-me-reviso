@extends('layouts.app')

@section('content')
<h2>Panel Técnico 1</h2>

<p>
    Usuario técnico 1:
    <b>{{ auth()->user()->name }}</b>
</p>

<div class="cards">
    <div class="card">
        <h3>Movilidades</h3>
        <p>Total registrado: {{ $vehiculos }}</p>
        <a class="btn" href="{{ route('vehiculos.index') }}">Ir al CRUD</a>
    </div>

    <div class="card">
        <h3>Clientes</h3>
        <p>Total registrado: {{ $clientes }}</p>
        <a class="btn" href="{{ route('clientes.index') }}">Ir al CRUD</a>
    </div>

    <div class="card">
        <h3>Ventas</h3>
        <p>Total registrado: {{ $ventas }}</p>
        <a class="btn" href="{{ route('ventas.index') }}">Registrar venta</a>
    </div>

    <div class="card">
        <h3>Maletín Digital</h3>
        <p>Modificar datos, imágenes, descripción, teléfono y dirección.</p>
        <a class="btn btn-green" href="{{ route('tecnico.maletin.edit') }}">Modificar maletín</a>
    </div>
</div>
@endsection
