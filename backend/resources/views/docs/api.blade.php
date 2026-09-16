@extends('layouts.app')

@section('title', 'Documentacion API REST - Maletin Digital')

@section('content')
<style>
    .api-badge {
        font-size: 0.7rem;
        padding: 3px 10px;
        border-radius: 6px;
        font-weight: 700;
        letter-spacing: 0.5px;
    }
    .badge-get { background: #28a745; color: #fff; }
    .badge-post { background: #007bff; color: #fff; }
    .badge-put { background: #fd7e14; color: #fff; }
    .badge-delete { background: #dc3545; color: #fff; }
    .badge-public { background: #6f42c1; color: #fff; }

    .endpoint-card {
        border-left: 4px solid #073763;
        transition: transform 0.15s;
    }
    .endpoint-card:hover {
        transform: translateX(4px);
    }

    .method-section {
        scroll-margin-top: 80px;
    }

    pre.code-block {
        background: #1e1e2e;
        color: #cdd6f4;
        border-radius: 10px;
        padding: 16px 20px;
        font-size: 0.82rem;
        overflow-x: auto;
    }
    pre.code-block .key { color: #89b4fa; }
    pre.code-block .string { color: #a6e3a1; }
    pre.code-block .comment { color: #6c7086; }

    .nav-pills .nav-link.active {
        background: #073763;
    }
    .nav-pills .nav-link {
        color: #073763;
        font-weight: 600;
    }

    .table-api th {
        background: #071426;
        color: white;
    }

    .status-code {
        font-weight: 700;
        font-size: 0.85rem;
    }
    .status-200 { color: #28a745; }
    .status-201 { color: #17a2b8; }
    .status-422 { color: #fd7e14; }
    .status-401 { color: #dc3545; }
    .status-404 { color: #6c757d; }
</style>

<div class="container py-5">

    {{-- Header --}}
    <div class="text-center mb-5">
        <div class="d-inline-block p-3 rounded-circle mb-3" style="background: linear-gradient(135deg, #073763, #d4af37); width:80px; height:80px; line-height:50px;">
            <span style="font-size:36px; color:#fff;">&#128268;</span>
        </div>
        <h1 class="fw-bold" style="color:#071426;">Documentacion API REST</h1>
        <p class="text-muted fs-5">Maletin Digital de Venta de Movilidades - Pegaso Motors</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap mt-3">
            <span class="badge bg-dark fs-6">Base URL: <code>{{ url('/api') }}</code></span>
            <span class="badge bg-success fs-6">Version 1.0</span>
            <span class="badge bg-info fs-6">Laravel 12 + Sanctum</span>
        </div>
    </div>

    <div class="row">

        {{-- Sidebar de navegacion --}}
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm sticky-top" style="top:80px;">
                <div class="card-body p-3">
                    <h6 class="fw-bold mb-3" style="color:#071426;">Secciones</h6>
                    <nav class="nav flex-column nav-pills" id="api-nav">
                        <a class="nav-link text-start py-2" href="#autenticacion">Autenticacion</a>
                        <a class="nav-link text-start py-2" href="#catalogo-publico">Catalogo Publico</a>
                        <a class="nav-link text-start py-2" href="#perfil">Perfil de Usuario</a>
                        <a class="nav-link text-start py-2" href="#vehiculos">Vehiculos</a>
                        <a class="nav-link text-start py-2" href="#clientes">Clientes</a>
                        <a class="nav-link text-start py-2" href="#ventas">Ventas</a>
                        <a class="nav-link text-start py-2" href="#maletin">Maletin Digital</a>
                        <a class="nav-link text-start py-2" href="#dashboard">Dashboard</a>
                        <a class="nav-link text-start py-2" href="#errores">Manejo de Errores</a>
                    </nav>
                </div>
            </div>
        </div>

        {{-- Contenido principal --}}
        <div class="col-lg-9">

            {{-- Introduccion --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-3" style="color:#071426;">&#128214; Introduccion</h4>
                    <p>Esta API REST permite consumir todos los servicios del sistema de <strong>Maletin Digital</strong> desde una aplicacion movil. La API utiliza autenticacion basada en tokens (Laravel Sanctum).</p>

                    <h6 class="fw-bold mt-4 mb-2">Autenticacion</h6>
                    <p>Todas las rutas protegidas requieren un token en el header:</p>
                    <pre class="code-block">Authorization: Bearer &lt;tu_token_aqui&gt;</pre>

                    <h6 class="fw-bold mt-4 mb-2">Formato de respuesta</h6>
                    <p>Todas las respuestas estan en formato JSON. Las respuestas exitosas incluyen un campo <code>message</code> con la descripcion de la operacion.</p>

                    <h6 class="fw-bold mt-4 mb-2">Ejemplo de peticion con cURL</h6>
                    <pre class="code-block"><span class="comment">// Login</span>
curl -X POST {{ url('/api/login') }} \
  -H "Content-Type: application/json" \
  -d '{"username": "mi_usuario", "password": "mi_contrasena"}'

<span class="comment">// Peticion autenticada</span>
curl -X GET {{ url('/api/vehiculos') }} \
  -H "Authorization: Bearer TU_TOKEN" \
  -H "Content-Type: application/json"</pre>
                </div>
            </div>

            {{-- ==================== AUTENTICACION ==================== --}}
            <div class="method-section mb-4" id="autenticacion">
                <h3 class="fw-bold mb-3" style="color:#071426;">&#128272; Autenticacion</h3>

                {{-- Register --}}
                <div class="card endpoint-card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="api-badge badge-post">POST</span>
                            <code class="fs-6">/api/register</code>
                            <span class="api-badge badge-public">PUBLICO</span>
                        </div>
                        <p class="text-muted mb-3">Registrar un nuevo usuario en el sistema.</p>
                        <h6 class="fw-bold">Body (JSON):</h6>
                        <pre class="code-block">{
    <span class="key">"name"</span>: <span class="string">"Juan Perez"</span>,
    <span class="key">"username"</span>: <span class="string">"juanperez"</span>,
    <span class="key">"email"</span>: <span class="string">"juan@email.com"</span>,
    <span class="key">"password"</span>: <span class="string">"secret123"</span>,
    <span class="key">"password_confirmation"</span>: <span class="string">"secret123"</span>,
    <span class="key">"telefono"</span>: <span class="string">"70123456"</span>,
    <span class="key">"sucursal"</span>: <span class="string">"La Paz"</span>,
    <span class="key">"cargo"</span>: <span class="string">"Ejecutivo"</span>
}</pre>
                        <h6 class="fw-bold mt-3">Respuesta <span class="status-code status-201">201</span>:</h6>
                        <pre class="code-block">{
    <span class="key">"message"</span>: <span class="string">"Registro exitoso."</span>,
    <span class="key">"user"</span>: { <span class="comment">...</span> },
    <span class="key">"token"</span>: <span class="string">"1|abc123..."</span>
}</pre>
                    </div>
                </div>

                {{-- Login --}}
                <div class="card endpoint-card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="api-badge badge-post">POST</span>
                            <code class="fs-6">/api/login</code>
                            <span class="api-badge badge-public">PUBLICO</span>
                        </div>
                        <p class="text-muted mb-3">Iniciar sesion y obtener token de acceso.</p>
                        <h6 class="fw-bold">Body (JSON):</h6>
                        <pre class="code-block">{
    <span class="key">"username"</span>: <span class="string">"juanperez"</span>,
    <span class="key">"password"</span>: <span class="string">"secret123"</span>
}</pre>
                        <h6 class="fw-bold mt-3">Respuesta <span class="status-code status-200">200</span>:</h6>
                        <pre class="code-block">{
    <span class="key">"message"</span>: <span class="string">"Inicio de sesion correcto."</span>,
    <span class="key">"user"</span>: { <span class="comment">...</span> },
    <span class="key">"token"</span>: <span class="string">"2|xyz789..."</span>
}</pre>
                    </div>
                </div>

                {{-- Logout --}}
                <div class="card endpoint-card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="api-badge badge-post">POST</span>
                            <code class="fs-6">/api/logout</code>
                        </div>
                        <p class="text-muted mb-3">Cerrar sesion y eliminar el token actual.</p>
                        <h6 class="fw-bold">Headers:</h6>
                        <pre class="code-block">Authorization: Bearer &lt;token&gt;</pre>
                        <h6 class="fw-bold mt-3">Respuesta <span class="status-code status-200">200</span>:</h6>
                        <pre class="code-block">{ <span class="key">"message"</span>: <span class="string">"Sesion cerrada correctamente."</span> }</pre>
                    </div>
                </div>
            </div>

            {{-- ==================== CATALOGO PUBLICO ==================== --}}
            <div class="method-section mb-4" id="catalogo-publico">
                <h3 class="fw-bold mb-3" style="color:#071426;">&#128663; Catalogo Publico</h3>

                <div class="card endpoint-card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="api-badge badge-get">GET</span>
                            <code class="fs-6">/api/catalogo</code>
                            <span class="api-badge badge-public">PUBLICO</span>
                        </div>
                        <p class="text-muted mb-3">Listar vehiculos disponibles. No requiere autenticacion.</p>
                        <h6 class="fw-bold">Parametros query opcionales:</h6>
                        <table class="table table-sm table-bordered">
                            <thead class="table-dark"><tr><th>Parametro</th><th>Tipo</th><th>Descripcion</th></tr></thead>
                            <tbody>
                                <tr><td><code>search</code></td><td>string</td><td>Buscar por marca, modelo o placa</td></tr>
                                <tr><td><code>precio_min</code></td><td>number</td><td>Precio minimo</td></tr>
                                <tr><td><code>precio_max</code></td><td>number</td><td>Precio maximo</td></tr>
                                <tr><td><code>per_page</code></td><td>integer</td><td>Resultados por pagina (default: 15)</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card endpoint-card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="api-badge badge-get">GET</span>
                            <code class="fs-6">/api/catalogo/{id}</code>
                            <span class="api-badge badge-public">PUBLICO</span>
                        </div>
                        <p class="text-muted mb-3">Obtener un vehiculo especifico del catalogo.</p>
                    </div>
                </div>

                <div class="card endpoint-card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="api-badge badge-get">GET</span>
                            <code class="fs-6">/api/maletin</code>
                            <span class="api-badge badge-public">PUBLICO</span>
                        </div>
                        <p class="text-muted mb-3">Obtener informacion del maletin digital (datos del negocio).</p>
                    </div>
                </div>
            </div>

            {{-- ==================== PERFIL ==================== --}}
            <div class="method-section mb-4" id="perfil">
                <h3 class="fw-bold mb-3" style="color:#071426;">&#128100; Perfil de Usuario</h3>

                <div class="card endpoint-card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="api-badge badge-get">GET</span>
                            <code class="fs-6">/api/profile</code>
                        </div>
                        <p class="text-muted mb-3">Obtener datos del usuario autenticado con sus relaciones.</p>
                    </div>
                </div>

                <div class="card endpoint-card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="api-badge badge-put">PUT</span>
                            <code class="fs-6">/api/profile</code>
                        </div>
                        <p class="text-muted mb-3">Actualizar datos del perfil.</p>
                        <h6 class="fw-bold">Body (JSON) - todos opcionales:</h6>
                        <pre class="code-block">{
    <span class="key">"name"</span>: <span class="string">"Juan Perez"</span>,
    <span class="key">"email"</span>: <span class="string">"nuevo@email.com"</span>,
    <span class="key">"telefono"</span>: <span class="string">"70123456"</span>,
    <span class="key">"sucursal"</span>: <span class="string">"Cochabamba"</span>,
    <span class="key">"cargo"</span>: <span class="string">"Gerente"</span>,
    <span class="key">"supervisor"</span>: <span class="string">"Carlos Lopez"</span>
}</pre>
                    </div>
                </div>

                <div class="card endpoint-card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="api-badge badge-put">PUT</span>
                            <code class="fs-6">/api/profile/password</code>
                        </div>
                        <p class="text-muted mb-3">Cambiar la contrasena del usuario.</p>
                        <h6 class="fw-bold">Body (JSON):</h6>
                        <pre class="code-block">{
    <span class="key">"current_password"</span>: <span class="string">"contrasena_actual"</span>,
    <span class="key">"password"</span>: <span class="string">"nueva_contrasena"</span>,
    <span class="key">"password_confirmation"</span>: <span class="string">"nueva_contrasena"</span>
}</pre>
                    </div>
                </div>
            </div>

            {{-- ==================== VEHICULOS ==================== --}}
            <div class="method-section mb-4" id="vehiculos">
                <h3 class="fw-bold mb-3" style="color:#071426;">&#128663; Vehiculos (Movilidades)</h3>

                <div class="card endpoint-card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="api-badge badge-get">GET</span>
                            <code class="fs-6">/api/vehiculos</code>
                        </div>
                        <p class="text-muted mb-3">Listar todos los vehiculos (paginados).</p>
                        <h6 class="fw-bold">Parametros query opcionales:</h6>
                        <table class="table table-sm table-bordered">
                            <thead class="table-dark"><tr><th>Parametro</th><th>Tipo</th><th>Descripcion</th></tr></thead>
                            <tbody>
                                <tr><td><code>search</code></td><td>string</td><td>Buscar por marca, modelo o placa</td></tr>
                                <tr><td><code>estado</code></td><td>string</td><td>Filtrar por estado (Disponible, Reservado, Vendido)</td></tr>
                                <tr><td><code>per_page</code></td><td>integer</td><td>Resultados por pagina</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card endpoint-card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="api-badge badge-get">GET</span>
                            <code class="fs-6">/api/vehiculos/{id}</code>
                        </div>
                        <p class="text-muted mb-3">Obtener un vehiculo con sus relaciones (ventas, cliente).</p>
                    </div>
                </div>

                <div class="card endpoint-card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="api-badge badge-post">POST</span>
                            <code class="fs-6">/api/vehiculos</code>
                        </div>
                        <p class="text-muted mb-3">Crear un nuevo vehiculo. Soporte para imagen (multipart/form-data).</p>
                        <h6 class="fw-bold">Body (JSON o FormData):</h6>
                        <pre class="code-block">{
    <span class="key">"marca"</span>: <span class="string">"Toyota"</span>,
    <span class="key">"modelo"</span>: <span class="string">"Corolla"</span>,
    <span class="key">"anio"</span>: 2024,
    <span class="key">"placa"</span>: <span class="string">"1234ABC"</span>,
    <span class="key">"color"</span>: <span class="string">"Blanco"</span>,
    <span class="key">"precio"</span>: 25000,
    <span class="key">"estado"</span>: <span class="string">"Disponible"</span>,
    <span class="key">"descripcion"</span>: <span class="string">"Sedan automatico"</span>,
    <span class="key">"imagen"</span>: <span class="comment">(archivo - opcional)</span>
}</pre>
                    </div>
                </div>

                <div class="card endpoint-card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="api-badge badge-put">PUT</span>
                            <code class="fs-6">/api/vehiculos/{id}</code>
                        </div>
                        <p class="text-muted mb-3">Actualizar un vehiculo existente.</p>
                    </div>
                </div>

                <div class="card endpoint-card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="api-badge badge-delete">DELETE</span>
                            <code class="fs-6">/api/vehiculos/{id}</code>
                        </div>
                        <p class="text-muted mb-3">Eliminar un vehiculo.</p>
                    </div>
                </div>
            </div>

            {{-- ==================== CLIENTES ==================== --}}
            <div class="method-section mb-4" id="clientes">
                <h3 class="fw-bold mb-3" style="color:#071426;">&#128101; Clientes</h3>

                <div class="card endpoint-card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="api-badge badge-get">GET</span>
                            <code class="fs-6">/api/clientes</code>
                        </div>
                        <p class="text-muted mb-3">Listar todos los clientes (paginados).</p>
                        <h6 class="fw-bold">Parametros query opcionales:</h6>
                        <table class="table table-sm table-bordered">
                            <thead class="table-dark"><tr><th>Parametro</th><th>Tipo</th><th>Descripcion</th></tr></thead>
                            <tbody>
                                <tr><td><code>search</code></td><td>string</td><td>Buscar por nombre, CI, telefono o email</td></tr>
                                <tr><td><code>estado</code></td><td>string</td><td>Filtrar por estado</td></tr>
                                <tr><td><code>per_page</code></td><td>integer</td><td>Resultados por pagina</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card endpoint-card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="api-badge badge-get">GET</span>
                            <code class="fs-6">/api/clientes/{id}</code>
                        </div>
                        <p class="text-muted mb-3">Obtener un cliente con sus ventas.</p>
                    </div>
                </div>

                <div class="card endpoint-card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="api-badge badge-post">POST</span>
                            <code class="fs-6">/api/clientes</code>
                        </div>
                        <p class="text-muted mb-3">Registrar un nuevo cliente.</p>
                        <h6 class="fw-bold">Body (JSON):</h6>
                        <pre class="code-block">{
    <span class="key">"nombre"</span>: <span class="string">"Maria Garcia"</span>,
    <span class="key">"ci"</span>: <span class="string">"1234567"</span>,
    <span class="key">"telefono"</span>: <span class="string">"70987654"</span>,
    <span class="key">"email"</span>: <span class="string">"maria@email.com"</span>,
    <span class="key">"direccion"</span>: <span class="string">"Av. principal 123"</span>,
    <span class="key">"vehiculo_interes"</span>: <span class="string">"Toyota Corolla"</span>,
    <span class="key">"metodo_pago"</span>: <span class="string">"Credito"</span>,
    <span class="key">"fuente"</span>: <span class="string">"Instagram"</span>,
    <span class="key">"estado"</span>: <span class="string">"En negociacion"</span>,
    <span class="key">"notas"</span>: <span class="string">"Interesado en modelo 2024"</span>
}</pre>
                    </div>
                </div>

                <div class="card endpoint-card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="api-badge badge-put">PUT</span>
                            <code class="fs-6">/api/clientes/{id}</code>
                        </div>
                        <p class="text-muted mb-3">Actualizar un cliente existente.</p>
                    </div>
                </div>

                <div class="card endpoint-card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="api-badge badge-delete">DELETE</span>
                            <code class="fs-6">/api/clientes/{id}</code>
                        </div>
                        <p class="text-muted mb-3">Eliminar un cliente.</p>
                    </div>
                </div>
            </div>

            {{-- ==================== VENTAS ==================== --}}
            <div class="method-section mb-4" id="ventas">
                <h3 class="fw-bold mb-3" style="color:#071426;">&#128176; Ventas</h3>

                <div class="card endpoint-card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="api-badge badge-get">GET</span>
                            <code class="fs-6">/api/ventas</code>
                        </div>
                        <p class="text-muted mb-3">Listar todas las ventas con vehiculo, cliente y usuario.</p>
                    </div>
                </div>

                <div class="card endpoint-card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="api-badge badge-get">GET</span>
                            <code class="fs-6">/api/ventas/{id}</code>
                        </div>
                        <p class="text-muted mb-3">Obtener una venta especifica con todas sus relaciones.</p>
                    </div>
                </div>

                <div class="card endpoint-card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="api-badge badge-post">POST</span>
                            <code class="fs-6">/api/ventas</code>
                        </div>
                        <p class="text-muted mb-3">Registrar una venta. El vehiculo cambiara automaticamente su estado a "Vendido".</p>
                        <h6 class="fw-bold">Body (JSON):</h6>
                        <pre class="code-block">{
    <span class="key">"vehicle_id"</span>: 1,
    <span class="key">"client_id"</span>: 1,
    <span class="key">"fecha"</span>: <span class="string">"2026-09-04"</span>,
    <span class="key">"precio_venta"</span>: 24000,
    <span class="key">"observacion"</span>: <span class="string">"Venta con descuento especial"</span>
}</pre>
                    </div>
                </div>

                <div class="card endpoint-card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="api-badge badge-delete">DELETE</span>
                            <code class="fs-6">/api/ventas/{id}</code>
                        </div>
                        <p class="text-muted mb-3">Eliminar una venta. El vehiculo volvera a estado "Disponible".</p>
                    </div>
                </div>

                <div class="card endpoint-card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="api-badge badge-get">GET</span>
                            <code class="fs-6">/api/comisiones</code>
                        </div>
                        <p class="text-muted mb-3">Obtener el calculo de comisiones (5% sobre cada venta).</p>
                    </div>
                </div>
            </div>

            {{-- ==================== MALETIN ==================== --}}
            <div class="method-section mb-4" id="maletin">
                <h3 class="fw-bold mb-3" style="color:#071426;">&#128188; Maletin Digital</h3>

                <div class="card endpoint-card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="api-badge badge-get">GET</span>
                            <code class="fs-6">/api/maletin/detalle</code>
                        </div>
                        <p class="text-muted mb-3">Obtener la informacion completa del maletin digital.</p>
                    </div>
                </div>

                <div class="card endpoint-card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="api-badge badge-put">PUT</span>
                            <code class="fs-6">/api/maletin</code>
                        </div>
                        <p class="text-muted mb-3">Actualizar o crear el maletin digital. Soporte para archivos (logo, banner).</p>
                        <h6 class="fw-bold">Body (JSON o FormData):</h6>
                        <pre class="code-block">{
    <span class="key">"titulo"</span>: <span class="string">"Pegaso Motors"</span>,
    <span class="key">"descripcion"</span>: <span class="string">"Concesionario oficial de vehiculos"</span>,
    <span class="key">"telefono"</span>: <span class="string">"2-2345678"</span>,
    <span class="key">"direccion"</span>: <span class="string">"Av. Arce 1234, La Paz"</span>,
    <span class="key">"logo"</span>: <span class="comment">(archivo imagen - opcional)</span>,
    <span class="key">"banner"</span>: <span class="comment">(archivo imagen - opcional)</span>
}</pre>
                    </div>
                </div>
            </div>

            {{-- ==================== DASHBOARD ==================== --}}
            <div class="method-section mb-4" id="dashboard">
                <h3 class="fw-bold mb-3" style="color:#071426;">&#128200; Dashboard</h3>

                <div class="card endpoint-card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="api-badge badge-get">GET</span>
                            <code class="fs-6">/api/dashboard</code>
                        </div>
                        <p class="text-muted mb-3">Obtener estadisticas generales: total vehiculos, clientes, ventas, monto total, y registros recientes.</p>
                        <h6 class="fw-bold">Respuesta ejemplo:</h6>
                        <pre class="code-block">{
    <span class="key">"vehiculos"</span>: 45,
    <span class="key">"clientes"</span>: 120,
    <span class="key">"ventas"</span>: 30,
    <span class="key">"disponibles"</span>: 25,
    <span class="key">"monto_total_ventas"</span>: 750000.00,
    <span class="key">"vehiculos_recientes"</span>: [ ... ],
    <span class="key">"ventas_recientes"</span>: [ ... ]
}</pre>
                    </div>
                </div>
            </div>

            {{-- ==================== ERRORES ==================== --}}
            <div class="method-section mb-4" id="errores">
                <h3 class="fw-bold mb-3" style="color:#071426;">&#9888;&#65039; Manejo de Errores</h3>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>HTTP</th>
                                    <th>Descripcion</th>
                                    <th>Ejemplo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="status-code status-200">200</span></td>
                                    <td>Operacion exitosa</td>
                                    <td>GET, PUT exitoso</td>
                                </tr>
                                <tr>
                                    <td><span class="status-code status-201">201</span></td>
                                    <td>Recurso creado</td>
                                    <td>POST exitoso</td>
                                </tr>
                                <tr>
                                    <td><span class="status-code status-401">401</span></td>
                                    <td>No autenticado / token invalido</td>
                                    <td><code>{ "message": "Unauthenticated." }</code></td>
                                </tr>
                                <tr>
                                    <td><span class="status-code status-404">404</span></td>
                                    <td>Recurso no encontrado</td>
                                    <td><code>{ "message": "No query results..." }</code></td>
                                </tr>
                                <tr>
                                    <td><span class="status-code status-422">422</span></td>
                                    <td>Error de validacion</td>
                                    <td><code>{ "errors": { "username": ["..."] } }</code></td>
                                </tr>
                            </tbody>
                        </table>

                        <h6 class="fw-bold mt-4">Ejemplo de respuesta de error de validacion:</h6>
                        <pre class="code-block">{
    <span class="key">"message"</span>: <span class="string">"The given data was invalid."</span>,
    <span class="key">"errors"</span>: {
        <span class="key">"username"</span>: [<span class="string">"El campo username es obligatorio."</span>],
        <span class="key">"password"</span>: [<span class="string">"La contrasena debe tener al menos 8 caracteres."</span>]
    }
}</pre>
                    </div>
                </div>
            </div>

            {{-- Resumen de endpoints --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-3" style="color:#071426;">Resumen de Endpoints</h4>
                    <table class="table table-sm table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Metodo</th>
                                <th>Ruta</th>
                                <th>Descripcion</th>
                                <th>Auth</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td><span class="api-badge badge-post">POST</span></td><td><code>/api/register</code></td><td>Registrar usuario</td><td>No</td></tr>
                            <tr><td><span class="api-badge badge-post">POST</span></td><td><code>/api/login</code></td><td>Iniciar sesion</td><td>No</td></tr>
                            <tr><td><span class="api-badge badge-post">POST</span></td><td><code>/api/logout</code></td><td>Cerrar sesion</td><td>Si</td></tr>
                            <tr><td><span class="api-badge badge-get">GET</span></td><td><code>/api/profile</code></td><td>Ver perfil</td><td>Si</td></tr>
                            <tr><td><span class="api-badge badge-put">PUT</span></td><td><code>/api/profile</code></td><td>Actualizar perfil</td><td>Si</td></tr>
                            <tr><td><span class="api-badge badge-put">PUT</span></td><td><code>/api/profile/password</code></td><td>Cambiar contrasena</td><td>Si</td></tr>
                            <tr><td><span class="api-badge badge-get">GET</span></td><td><code>/api/dashboard</code></td><td>Estadisticas</td><td>Si</td></tr>
                            <tr><td><span class="api-badge badge-get">GET</span></td><td><code>/api/catalogo</code></td><td>Catalogo publico</td><td>No</td></tr>
                            <tr><td><span class="api-badge badge-get">GET</span></td><td><code>/api/catalogo/{id}</code></td><td>Vehiculo del catalogo</td><td>No</td></tr>
                            <tr><td><span class="api-badge badge-get">GET</span></td><td><code>/api/maletin</code></td><td>Info del maletin</td><td>No</td></tr>
                            <tr><td><span class="api-badge badge-get">GET</span></td><td><code>/api/vehiculos</code></td><td>Listar vehiculos</td><td>Si</td></tr>
                            <tr><td><span class="api-badge badge-get">GET</span></td><td><code>/api/vehiculos/{id}</code></td><td>Detalle vehiculo</td><td>Si</td></tr>
                            <tr><td><span class="api-badge badge-post">POST</span></td><td><code>/api/vehiculos</code></td><td>Crear vehiculo</td><td>Si</td></tr>
                            <tr><td><span class="api-badge badge-put">PUT</span></td><td><code>/api/vehiculos/{id}</code></td><td>Actualizar vehiculo</td><td>Si</td></tr>
                            <tr><td><span class="api-badge badge-delete">DELETE</span></td><td><code>/api/vehiculos/{id}</code></td><td>Eliminar vehiculo</td><td>Si</td></tr>
                            <tr><td><span class="api-badge badge-get">GET</span></td><td><code>/api/clientes</code></td><td>Listar clientes</td><td>Si</td></tr>
                            <tr><td><span class="api-badge badge-get">GET</span></td><td><code>/api/clientes/{id}</code></td><td>Detalle cliente</td><td>Si</td></tr>
                            <tr><td><span class="api-badge badge-post">POST</span></td><td><code>/api/clientes</code></td><td>Crear cliente</td><td>Si</td></tr>
                            <tr><td><span class="api-badge badge-put">PUT</span></td><td><code>/api/clientes/{id}</code></td><td>Actualizar cliente</td><td>Si</td></tr>
                            <tr><td><span class="api-badge badge-delete">DELETE</span></td><td><code>/api/clientes/{id}</code></td><td>Eliminar cliente</td><td>Si</td></tr>
                            <tr><td><span class="api-badge badge-get">GET</span></td><td><code>/api/ventas</code></td><td>Listar ventas</td><td>Si</td></tr>
                            <tr><td><span class="api-badge badge-get">GET</span></td><td><code>/api/ventas/{id}</code></td><td>Detalle venta</td><td>Si</td></tr>
                            <tr><td><span class="api-badge badge-post">POST</span></td><td><code>/api/ventas</code></td><td>Crear venta</td><td>Si</td></tr>
                            <tr><td><span class="api-badge badge-delete">DELETE</span></td><td><code>/api/ventas/{id}</code></td><td>Eliminar venta</td><td>Si</td></tr>
                            <tr><td><span class="api-badge badge-get">GET</span></td><td><code>/api/comisiones</code></td><td>Calcular comisiones</td><td>Si</td></tr>
                            <tr><td><span class="api-badge badge-get">GET</span></td><td><code>/api/maletin/detalle</code></td><td>Detalle maletin</td><td>Si</td></tr>
                            <tr><td><span class="api-badge badge-put">PUT</span></td><td><code>/api/maletin</code></td><td>Actualizar maletin</td><td>Si</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
