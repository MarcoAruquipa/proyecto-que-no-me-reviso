@extends('layouts.app')

@section('content')
<h2>Formulario de modificación de datos del maletín digital</h2>

<form action="{{ route('tecnico.maletin.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <label>Título del maletín</label>
    <input type="text" name="titulo" value="{{ $maletin->titulo }}">

    <label>Descripción</label>
    <textarea name="descripcion">{{ $maletin->descripcion }}</textarea>

    <label>Teléfono o WhatsApp</label>
    <input type="text" name="telefono" value="{{ $maletin->telefono }}">

    <label>Dirección</label>
    <input type="text" name="direccion" value="{{ $maletin->direccion }}">

    <label>Logo del maletín</label>
    <input type="file" name="logo">

    @if($maletin->logo)
        <p>Logo actual:</p>
        <img src="{{ asset('storage/' . $maletin->logo) }}" width="120">
    @endif

    <label>Banner del maletín</label>
    <input type="file" name="banner">

    @if($maletin->banner)
        <p>Banner actual:</p>
        <img src="{{ asset('storage/' . $maletin->banner) }}" width="250">
    @endif

    <button class="btn" type="submit">Guardar cambios</button>
</form>
@endsection