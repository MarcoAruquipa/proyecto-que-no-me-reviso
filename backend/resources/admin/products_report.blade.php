@extends('layouts.app')

@section('content')
<h2>Reporte de todos los productos</h2>

<a class="btn btn-green" href="{{ route('admin.reporte.excel') }}">Descargar Excel</a>
<a class="btn btn-dark" href="{{ route('admin.reporte.pdf') }}" target="_blank">Ver PDF imprimible</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Año</th>
            <th>Placa</th>
            <th>Color</th>
            <th>Precio</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @forelse($vehiculos as $v)
            <tr>
                <td>{{ $v->id }}</td>
                <td>{{ $v->marca }}</td>
                <td>{{ $v->modelo }}</td>
                <td>{{ $v->anio }}</td>
                <td>{{ $v->placa }}</td>
                <td>{{ $v->color }}</td>
                <td>Bs {{ number_format($v->precio, 2) }}</td>
                <td>{{ $v->estado }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8">No hay productos registrados.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection