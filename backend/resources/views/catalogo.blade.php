@extends('layouts.app')

@section('title', 'Catálogo de Movilidades')

@section('content')

<div class="container py-5">

    <div class="text-center mb-5">
        <h1 class="fw-bold text-primary">Catálogo de movilidades</h1>
        <p class="text-muted">
            El invitado puede visualizar todos los productos registrados.
        </p>
    </div>

    @if($vehiculos->count() > 0)
        <div class="row g-4">
            @foreach($vehiculos as $vehiculo)
                <div class="col-md-4">
                    <div class="card card-auto h-100">

                        @if($vehiculo->imagen)
                            <img src="{{ asset('storage/' . $vehiculo->imagen) }}" class="img-auto">
                        @else
                            <div class="sin-imagen">
                                Sin imagen
                            </div>
                        @endif

                        <div class="card-body">
                            <h4 class="fw-bold">
                                {{ $vehiculo->marca ?? 'Marca no registrada' }}
                                {{ $vehiculo->modelo ?? '' }}
                            </h4>

                            <p class="mb-1">
                                <strong>Año:</strong> {{ $vehiculo->anio ?? 'No definido' }}
                            </p>

                            <p class="mb-1">
                                <strong>Color:</strong> {{ $vehiculo->color ?? 'No definido' }}
                            </p>

                            <p class="mb-1">
                                <strong>Estado:</strong>
                                <span class="badge bg-success">
                                    {{ $vehiculo->estado ?? 'Disponible' }}
                                </span>
                            </p>

                            <h5 class="mt-3 text-primary fw-bold">
                                {{ number_format($vehiculo->precio ?? 0, 2) }} Bs
                            </h5>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info">
            Todavía no hay movilidades registradas.
        </div>
    @endif

</div>

<style>
    .card-auto {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border: none;
    }

    .img-auto {
        width: 100%;
        height: 220px;
        object-fit: cover;
    }

    .sin-imagen {
        height: 220px;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        font-weight: bold;
    }
</style>

@endsection