@extends('layouts.app')

@section('content')
<h2>Registro de Ventas</h2>

<a class="btn" href="{{ route('ventas.create') }}">Registrar nueva venta</a>

<table>
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Cliente</th>
            <th>Movilidad</th>
            <th>Precio venta</th>
            <th>Registrado por</th>
            <th>Observación</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
        @forelse($ventas as $v)
            <tr>
                <td>{{ $v->fecha }}</td>
                <td>{{ $v->client->nombre ?? '' }}</td>
                <td>{{ $v->vehicle->marca ?? '' }} {{ $v->vehicle->modelo ?? '' }}</td>
                <td>Bs {{ number_format($v->precio_venta, 2) }}</td>
                <td>{{ $v->user->name ?? 'Sin usuario' }}</td>
                <td>{{ $v->observacion }}</td>
                <td>
                    <form action="{{ route('ventas.destroy', $v) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-red" onclick="return confirm('¿Eliminar venta?')">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7">No hay ventas registradas.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection