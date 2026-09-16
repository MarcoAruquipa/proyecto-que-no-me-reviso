@extends('layouts.app')

@section('title', 'Mi Maletín Digital - Marco Antonio')

@section('content')

<div class="container py-5">

    {{-- PORTADA PERSONAL --}}
    <div class="card shadow-lg border-0 mb-4 portada-maletin">
        <div class="card-body p-4 p-md-5">

            <div class="row align-items-center">

                <div class="col-md-3 text-center mb-4 mb-md-0">

                    <img src="{{ asset('img/foto-marco.jpg') }}"
                         class="foto-perfil"
                         onerror="this.style.display='none'; document.getElementById('avatarIniciales').style.display='flex';">

                    <div id="avatarIniciales" class="avatar-iniciales">
                        MA
                    </div>

                </div>

                <div class="col-md-9">

                    <span class="badge bg-warning text-dark mb-3 px-3 py-2">
                        Maletín Personal Profesional
                    </span>

                    <h1 class="fw-bold text-primary mb-3">
                        Mi Maletín Digital Profesional
                    </h1>

                    <h3 class="fw-bold mb-3">
                        Marco Antonio Aruquipa Loza
                    </h3>

                    <p class="fs-5 mb-4">
                        Soy estudiante y profesional en formación en el área de programación,
                        desarrollo de sistemas web y administración de bases de datos.
                        Este maletín digital presenta mis estudios, habilidades, conocimientos
                        y el sistema web desarrollado en Laravel.
                    </p>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <p class="mb-1">
                                <strong>Nombre:</strong> Marco Antonio Aruquipa Loza
                            </p>

                            <p class="mb-1">
                                <strong>Instituto:</strong> Nombre de tu instituto
                            </p>

                            <p class="mb-1">
                                <strong>Carrera:</strong> Sistemas Informáticos
                            </p>

                            <p class="mb-1">
                                <strong>Área:</strong> Programación y desarrollo web
                            </p>
                        </div>

                        <div class="col-md-6">
                            <p class="mb-1">
                                <strong>Proyecto:</strong> Maletín Digital Web
                            </p>

                            <p class="mb-1">
                                <strong>Framework:</strong> Laravel
                            </p>

                            <p class="mb-1">
                                <strong>Base de datos:</strong> MySQL
                            </p>

                            <p class="mb-1">
                                <strong>Gestión:</strong> 2026
                            </p>
                        </div>

                    </div>

                    <div class="alert alert-primary mt-4 mb-0">
                        Este maletín digital fue desarrollado para demostrar conocimientos en
                        diseño Bootstrap, formularios, inicio de sesión, visualización de datos,
                        actualización de datos y administración de información mediante Laravel.
                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- DATOS DE ACCESO --}}
    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-dark text-white">
            Datos de acceso al sistema
        </div>

        <div class="card-body">

            @auth
                <div class="alert alert-success">
                    Sesión activa como:
                    <strong>{{ Auth::user()->name }}</strong>

                    @if(Auth::user()->role == 'admin')
                        - Jefe de Sucursal
                    @elseif(Auth::user()->role == 'tecnico')
                        - Ejecutivo de Venta
                    @else
                        - Invitado
                    @endif
                </div>
            @else
                <div class="alert alert-info">
                    Puedes ver este maletín sin iniciar sesión. Para registrar, actualizar o administrar datos debes iniciar sesión.
                </div>
            @endauth

            <div class="row g-4">

                <div class="col-md-3">
                    <div class="card text-center border-0 shadow-sm card-contador">
                        <div class="card-body">
                            <h2 class="fw-bold text-primary">{{ $stats['vehiculos'] ?? 0 }}</h2>
                            <p class="mb-0">Movilidades</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-center border-0 shadow-sm card-contador">
                        <div class="card-body">
                            <h2 class="fw-bold text-success">{{ $stats['clientes'] ?? 0 }}</h2>
                            <p class="mb-0">Clientes</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-center border-0 shadow-sm card-contador">
                        <div class="card-body">
                            <h2 class="fw-bold text-warning">{{ $stats['ventas'] ?? 0 }}</h2>
                            <p class="mb-0">Ventas</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-center border-0 shadow-sm card-contador">
                        <div class="card-body">
                            <h2 class="fw-bold text-danger">{{ $stats['disponibles'] ?? 0 }}</h2>
                            <p class="mb-0">Disponibles</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    {{-- OPCIONES PRINCIPALES --}}
    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-dark text-white">
            Opciones principales del Maletín
        </div>

        <div class="card-body">
            <div class="d-flex flex-wrap gap-3">

                <a href="{{ route('catalogo') }}" class="btn btn-primary">
                    Ver catálogo
                </a>

                @auth
                    @if(Route::has('perfil'))
                        <a href="{{ route('perfil') }}" class="btn btn-outline-primary">
                            Mostrar / Actualizar mis datos
                        </a>
                    @endif
                @else
                    <a href="{{ route('login', ['tipo' => 'admin']) }}" class="btn btn-warning">
                        Iniciar sesión para administrar
                    </a>
                @endauth

                @auth
                    @if(Auth::user()->role == 'tecnico' || Auth::user()->role == 'admin')

                        @if(Route::has('vehiculos.index'))
                            <a href="{{ route('vehiculos.index') }}" class="btn btn-success">
                                Registrar / Actualizar Movilidades
                            </a>
                        @endif

                        @if(Route::has('clientes.index'))
                            <a href="{{ route('clientes.index') }}" class="btn btn-warning">
                                Registrar / Actualizar Clientes
                            </a>
                        @endif

                        @if(Route::has('ventas.index'))
                            <a href="{{ route('ventas.index') }}" class="btn btn-info">
                                Ver Ventas
                            </a>
                        @endif

                        @if(Route::has('documentos.index'))
                            <a href="{{ route('documentos.index') }}" class="btn btn-secondary">
                                Subir Documentos
                            </a>
                        @endif

                        @if(Route::has('comisiones.index'))
                            <a href="{{ route('comisiones.index') }}" class="btn btn-outline-dark">
                                Mis Comisiones
                            </a>
                        @endif

                    @endif

                    @if(Auth::user()->role == 'admin')

                        @if(Route::has('admin.dashboard'))
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-dark">
                                Panel Jefe de Sucursal
                            </a>
                        @endif

                        @if(Route::has('personal.create'))
                            <a href="{{ route('personal.create') }}" class="btn btn-outline-success">
                                Agregar Personal
                            </a>
                        @endif

                    @endif
                @endauth

            </div>
        </div>
    </div>

    {{-- ESTUDIOS Y FORMACIÓN --}}
    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-primary text-white">
            Mis estudios y formación profesional
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-6">
                    <div class="info-box">
                        <h4 class="fw-bold text-primary">Formación académica</h4>

                        <p>
                            Actualmente me formo en el área de <strong>Sistemas Informáticos</strong>,
                            con orientación al desarrollo de aplicaciones web, administración de bases
                            de datos y creación de sistemas digitales.
                        </p>

                        <ul class="mb-0">
                            <li>Estudios en programación web.</li>
                            <li>Desarrollo de sistemas con Laravel.</li>
                            <li>Diseño de interfaces con Bootstrap.</li>
                            <li>Gestión de información mediante MySQL.</li>
                            <li>Creación de formularios, reportes y paneles administrativos.</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-box">
                        <h4 class="fw-bold text-primary">Perfil profesional</h4>

                        <p>
                            Me considero una persona responsable, dedicada y con interés en el
                            desarrollo de soluciones tecnológicas. Tengo conocimientos en la creación
                            de sistemas web dinámicos, manejo de datos y diseño de páginas funcionales.
                        </p>

                        <ul class="mb-0">
                            <li>Responsabilidad en el desarrollo de proyectos.</li>
                            <li>Capacidad para resolver problemas técnicos.</li>
                            <li>Organización de información en sistemas digitales.</li>
                            <li>Uso de herramientas de programación.</li>
                            <li>Interés en mejorar continuamente mis conocimientos.</li>
                        </ul>
                    </div>
                </div>

            </div>

        </div>
    </div>

    {{-- HABILIDADES --}}
    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-dark text-white">
            Mis habilidades técnicas
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">
                    <div class="skill-card">
                        <div class="skill-icon">💻</div>
                        <h5 class="fw-bold">Programación Web</h5>
                        <p>
                            Desarrollo de páginas y sistemas web utilizando HTML, CSS, PHP y Laravel.
                        </p>

                        <div class="progress">
                            <div class="progress-bar" style="width: 85%">
                                85%
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="skill-card">
                        <div class="skill-icon">🎨</div>
                        <h5 class="fw-bold">Diseño Bootstrap</h5>
                        <p>
                            Creación de interfaces modernas con tarjetas, botones, tablas y formularios.
                        </p>

                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: 80%">
                                80%
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="skill-card">
                        <div class="skill-icon">🗄️</div>
                        <h5 class="fw-bold">Base de Datos</h5>
                        <p>
                            Manejo de MySQL para registrar, mostrar, actualizar y administrar datos.
                        </p>

                        <div class="progress">
                            <div class="progress-bar bg-warning text-dark" style="width: 75%">
                                75%
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row g-4 mt-2">

                <div class="col-md-4">
                    <div class="skill-card">
                        <div class="skill-icon">🔐</div>
                        <h5 class="fw-bold">Inicio de sesión</h5>
                        <p>
                            Implementación de acceso por roles: invitado, ejecutivo de venta y jefe de sucursal.
                        </p>

                        <div class="progress">
                            <div class="progress-bar bg-info text-dark" style="width: 78%">
                                78%
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="skill-card">
                        <div class="skill-icon">📋</div>
                        <h5 class="fw-bold">CRUD</h5>
                        <p>
                            Creación de módulos para registrar, mostrar, actualizar y eliminar información.
                        </p>

                        <div class="progress">
                            <div class="progress-bar bg-danger" style="width: 82%">
                                82%
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="skill-card">
                        <div class="skill-icon">📊</div>
                        <h5 class="fw-bold">Panel Administrativo</h5>
                        <p>
                            Organización de clientes, ventas, productos, comisiones y usuarios del sistema.
                        </p>

                        <div class="progress">
                            <div class="progress-bar bg-dark" style="width: 80%">
                                80%
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    {{-- HERRAMIENTAS --}}
    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-primary text-white">
            Herramientas que manejo
        </div>

        <div class="card-body">

            <div class="row g-3 text-center">

                <div class="col-md-2 col-6">
                    <div class="herramienta-box">
                        <h3>🐘</h3>
                        <strong>PHP</strong>
                    </div>
                </div>

                <div class="col-md-2 col-6">
                    <div class="herramienta-box">
                        <h3>🌐</h3>
                        <strong>HTML</strong>
                    </div>
                </div>

                <div class="col-md-2 col-6">
                    <div class="herramienta-box">
                        <h3>🎨</h3>
                        <strong>CSS</strong>
                    </div>
                </div>

                <div class="col-md-2 col-6">
                    <div class="herramienta-box">
                        <h3>⚙️</h3>
                        <strong>Laravel</strong>
                    </div>
                </div>

                <div class="col-md-2 col-6">
                    <div class="herramienta-box">
                        <h3>🅱️</h3>
                        <strong>Bootstrap</strong>
                    </div>
                </div>

                <div class="col-md-2 col-6">
                    <div class="herramienta-box">
                        <h3>🗃️</h3>
                        <strong>MySQL</strong>
                    </div>
                </div>

            </div>

        </div>
    </div>

    {{-- FUNCIONES DEL SISTEMA --}}
    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-dark text-white">
            Funciones que cumple mi maletín
        </div>

        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-4">
                    <div class="funcion-box">
                        <h5 class="fw-bold">Diseño Bootstrap</h5>
                        <p class="mb-0">
                            El sistema utiliza diseño moderno con tarjetas, botones, tablas y formularios.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="funcion-box">
                        <h5 class="fw-bold">Formulario de registro</h5>
                        <p class="mb-0">
                            Permite registrar usuarios en el sistema con distintos roles de acceso.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="funcion-box">
                        <h5 class="fw-bold">Inicio de sesión</h5>
                        <p class="mb-0">
                            Permite ingresar como invitado, ejecutivo de venta o jefe de sucursal.
                        </p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="funcion-box">
                        <h5 class="fw-bold">Mostrar datos</h5>
                        <p class="mb-0">
                            Permite visualizar información registrada en tablas y paneles del sistema.
                        </p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="funcion-box">
                        <h5 class="fw-bold">Actualizar datos</h5>
                        <p class="mb-0">
                            Permite modificar información de usuarios, clientes y movilidades registradas.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

<style>
    .portada-maletin {
        border-radius: 25px;
        background: linear-gradient(135deg, #ffffff, #edf5ff);
        overflow: hidden;
    }

    .foto-perfil {
        width: 180px;
        height: 180px;
        object-fit: cover;
        border-radius: 50%;
        border: 7px solid #0d6efd;
        box-shadow: 0 8px 25px rgba(0,0,0,.25);
    }

    .avatar-iniciales {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        border: 7px solid #0d6efd;
        background: linear-gradient(135deg, #0d6efd, #001f3f);
        color: white;
        font-size: 58px;
        font-weight: bold;
        display: none;
        align-items: center;
        justify-content: center;
        margin: auto;
        box-shadow: 0 8px 25px rgba(0,0,0,.25);
    }

    .card-contador {
        border-radius: 18px;
        transition: 0.3s;
    }

    .card-contador:hover {
        transform: translateY(-5px);
    }

    .info-box {
        background: #f8f9fa;
        padding: 25px;
        border-radius: 18px;
        height: 100%;
        border-left: 5px solid #0d6efd;
        box-shadow: 0 4px 12px rgba(0,0,0,.08);
    }

    .skill-card {
        background: white;
        padding: 25px;
        border-radius: 18px;
        height: 100%;
        box-shadow: 0 4px 12px rgba(0,0,0,.10);
        border-top: 5px solid #0d6efd;
    }

    .skill-icon {
        width: 65px;
        height: 65px;
        background: #eaf2ff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        margin-bottom: 15px;
    }

    .herramienta-box {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 16px;
        box-shadow: 0 4px 10px rgba(0,0,0,.08);
        height: 100%;
    }

    .funcion-box {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 16px;
        height: 100%;
        border-left: 5px solid #0d6efd;
        box-shadow: 0 4px 10px rgba(0,0,0,.08);
    }

    .progress {
        height: 22px;
        border-radius: 20px;
        overflow: hidden;
    }

    .progress-bar {
        font-weight: bold;
    }
</style>

@endsection