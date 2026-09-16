@extends('layouts.app')

@section('content')
<h2>Panel Administrador</h2>

<p>El administrador puede sacar reportes de registros, usuarios, clientes, ventas y productos.</p>

<div class="cards">
    <div class="card">
        <h3>Usuarios</h3>
        <p>{{ $usuarios }}</p>
    </div>

    <div class="card">
        <h3>Movilidades</h3>
        <p>{{ $vehiculos }}</p>
    </div>

    <div class="card">
        <h3>Clientes</h3>
        <p>{{ $clientes }}</p>
    </div>

    <div class="card">
        <h3>Ventas</h3>
        <p>{{ $ventas }}</p>
    </div>
</div>

<br>

<a class="btn" href="{{ route('admin.reporte.productos') }}">
    Reporte de todos los productos
</a>
@endsection