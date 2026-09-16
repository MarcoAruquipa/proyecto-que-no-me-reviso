@extends('layouts.app')

@section('title', 'Maletín Digital - Pegaso Motors')

@section('content')

<section class="hero-principal">
    <div class="hero-overlay"></div>

    <div class="container position-relative">
        <div class="row align-items-center min-vh-75">
            <div class="col-lg-7 text-white">
                <div class="logo-box mb-4">
                    <div class="logo-icon">🪽</div>
                    <div>
                        <h1 class="fw-bold mb-0">Pegaso Motors</h1>
                        <p class="mb-0">Maletín Digital de Venta de Movilidades</p>
                    </div>
                </div>

                <h2 class="display-5 fw-bold mb-3">
                    Sistema de gestión para venta de movilidades
                </h2>

                <p class="lead mb-4">
                    Administra clientes, prospectos, catálogo de vehículos, ventas,
                    documentos, comisiones y reportes desde un solo panel.
                </p>

 <div class="d-flex flex-wrap gap-3">
    <a href="{{ route('catalogo') }}" class="btn btn-light btn-lg px-4">
        Ver catálogo
    </a>

    <a href="{{ route('maletin.principal') }}" class="btn btn-warning btn-lg px-4">
        Ir a mi Maletín
    </a>

    <a href="{{ route('login', ['tipo' => 'invitado']) }}" class="btn btn-outline-light btn-lg px-4">
        Entrar como invitado
    </a>
</div>
            </div>

            <div class="col-lg-5 mt-5 mt-lg-0">
                <div class="card login-card shadow-lg border-0">
                    <div class="card-body p-4">
                        <h3 class="text-center fw-bold mb-4 text-primary">
                            Accesos del sistema
                        </h3>

                        <div class="d-grid gap-3">
                            <a href="{{ route('login', ['tipo' => 'invitado']) }}" class="btn btn-outline-primary btn-lg">
    Iniciar sesión como Invitado
</a>

<a href="{{ route('login', ['tipo' => 'tecnico']) }}" class="btn btn-primary btn-lg">
    Iniciar sesión como Ejecutivo de Venta
</a>

<a href="{{ route('login', ['tipo' => 'admin']) }}" class="btn btn-dark btn-lg">
    Iniciar sesión como Administrador
</a>

<a href="{{ route('login', ['tipo' => 'jefe']) }}" class="btn btn-outline-dark btn-lg">
    Iniciar sesión como Jefe de Sucursal
</a>
                        </div>

                        <hr>

                        <div class="small text-muted">
                            <p class="mb-1"><strong>Usuario invitado:</strong> invitado / invitado123</p>
                            <p class="mb-1"><strong>Ejecutivo de Venta:</strong> tecnico1 / tecnico123</p>
                            <p class="mb-1"><strong>Jefe de Sucursal:</strong> jefe1 / jefe123</p>
                            <p class="mb-0"><strong>Administrador:</strong> admin / admin123</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center fw-bold mb-4">Funciones del Maletín Digital</h2>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h4 class="fw-bold">Catálogo de autos</h4>
                        <p>Permite visualizar las movilidades registradas con marca, modelo, precio y estado.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h4 class="fw-bold">CRUD Técnico I</h4>
                        <p>El técnico puede registrar, mostrar, actualizar y eliminar movilidades del sistema.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h4 class="fw-bold">Panel administrador</h4>
                        <p>El administrador puede revisar usuarios, clientes, ventas y reportes generales.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection