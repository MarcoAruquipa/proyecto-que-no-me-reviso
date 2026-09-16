<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contrato;
use App\Models\Documento;
use App\Models\Maletin;
use App\Models\Sale;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class SistemaController extends Controller
{
    private const ROLES_CRUD = ['tecnico', 'admin', 'jefe'];
    private const ROLES_JEFE = ['admin', 'jefe'];

    private function proteger($roles = [])
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Primero debe iniciar sesión.');
        }

        if (!empty($roles) && !in_array(Auth::user()->role, $roles)) {
            return redirect()->route('panel.dashboard')->with('error', 'No tiene permiso para ingresar a esta página.');
        }

        return null;
    }

    private function limpiar(?string $valor): string
    {
        return trim(strip_tags($valor ?? ''));
    }

    public function inicio()
    {
        $vehiculos = Vehicle::latest()->take(3)->get();
        return view('inicio', compact('vehiculos'));
    }

    public function catalogo()
    {
        $vehiculos = Vehicle::latest()->get();
        return view('catalogo', compact('vehiculos'));
    }

    public function principal()
    {
        $vehiculos = Vehicle::latest()->get();
        $clientes = Client::latest()->take(8)->get();
        $ventas = Sale::with('vehicle', 'client', 'user')->latest()->take(8)->get();

        $maletin = Maletin::first();

        $disponibles = Schema::hasColumn('vehicles', 'estado')
            ? Vehicle::where('estado', 'Disponible')->count()
            : Vehicle::count();

        $stats = [
            'vehiculos' => Vehicle::count(),
            'clientes' => Client::count(),
            'ventas' => Sale::count(),
            'disponibles' => $disponibles,
        ];

        return view('maletin.principal', compact('vehiculos', 'clientes', 'ventas', 'maletin', 'stats'));
    }

    public function loginForm($tipo = null)
    {
        return view('auth.login', ['tipo' => $tipo ?? 'invitado']);
    }

    public function login(Request $request)
    {
        $identificador = strtolower($this->limpiar((string) $request->input('login', $request->input('username'))));

        $request->merge(['login' => $identificador]);

        $request->validate([
            'login' => 'required',
            'password' => 'required',
            'role_esperado' => 'nullable|in:admin,jefe,tecnico,invitado',
        ]);

        $campo = filter_var($identificador, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$campo => $identificador, 'password' => $request->password], $request->boolean('remember'))) {
            $request->session()->regenerate();

            if ($request->role_esperado && Auth::user()->role !== $request->role_esperado) {
                Auth::logout();
                return back()->with('error', 'El usuario ingresado no corresponde al tipo de acceso seleccionado.');
            }

            return redirect()->route('panel.dashboard')->with('success', 'Inicio de sesión correcto.');
        }

        return back()->with('error', 'Usuario o contraseña incorrectos.');
    }

    public function registroForm()
    {
        return view('auth.register');
    }

    public function registro(Request $request)
    {
        $request->merge([
            'name' => $this->limpiar($request->input('name')),
            'username' => $this->limpiar($request->input('username')),
            'email' => strtolower($this->limpiar($request->input('email'))),
        ]);

        $request->validate([
            'name' => 'required|min:3|max:100',
            'username' => 'required|alpha_dash|min:4|max:20|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required|in:tecnico,invitado',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        Auth::logout();

        return redirect()
            ->route('login', ['tipo' => $request->role])
            ->with('success', 'Te registraste exitosamente. Ahora inicia sesión con tu correo o usuario y contraseña.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('inicio')->with('success', 'Sesión cerrada correctamente.');
    }

    public function dashboard()
    {
        if ($r = $this->proteger(self::ROLES_CRUD)) return $r;

        $usuario = Auth::user();

        $clientes = Client::count();
        $vehiculos = Vehicle::count();
        $ventas = Sale::count();
        $ingresos = Sale::sum('precio_venta');
        $disponibles = Vehicle::where('estado', 'Disponible')->count();
        $prospectos = Client::where('estado', 'En negociación')->count();

        $ventasRecientes = Sale::with('vehicle', 'client', 'user')->latest()->take(6)->get();

        $misVentas = 0;
        $misComisiones = 0;
        if ($usuario->role === 'tecnico') {
            $misVentas = Sale::where('user_id', $usuario->id)->count();
            $misComisiones = Sale::where('user_id', $usuario->id)->sum('precio_venta') * 0.05;
        }

        $ejecutivos = 0;
        if (in_array($usuario->role, self::ROLES_JEFE)) {
            $ejecutivos = User::where('role', 'tecnico')->count();
        }

        return view('panel.dashboard', compact(
            'clientes',
            'vehiculos',
            'ventas',
            'ingresos',
            'disponibles',
            'prospectos',
            'ventasRecientes',
            'misVentas',
            'misComisiones',
            'ejecutivos'
        ));
    }

    public function tecnicoDashboard()
    {
        return redirect()->route('panel.dashboard');
    }

    public function adminDashboard()
    {
        return redirect()->route('panel.dashboard');
    }

    public function vehiculosIndex()
    {
        if ($r = $this->proteger(self::ROLES_CRUD)) return $r;

        $vehiculos = Vehicle::latest()->get();
        return view('vehicles.index', compact('vehiculos'));
    }

    public function vehiculosCrear()
    {
        if ($r = $this->proteger(self::ROLES_CRUD)) return $r;

        $vehiculo = new Vehicle(['estado' => 'Disponible']);
        return view('vehicles.form', compact('vehiculo'));
    }

    public function vehiculosGuardar(Request $request)
    {
        if ($r = $this->proteger(self::ROLES_CRUD)) return $r;

        $request->validate([
            'marca' => 'required',
            'modelo' => 'required',
            'anio' => 'required|integer|min:1950|max:2100',
            'precio' => 'required|numeric|min:0',
            'placa' => 'nullable',
            'color' => 'nullable',
            'estado' => 'required|in:Disponible,Reservado,Vendido',
            'descripcion' => 'nullable',
            'imagen' => 'nullable|image',
        ]);

        $datos = $request->only('marca', 'modelo', 'anio', 'placa', 'color', 'precio', 'estado', 'descripcion');
        $datos['user_id'] = Auth::id();

        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('vehiculos', 'public');
        }

        Vehicle::create($datos);

        return redirect()->route('vehiculos.index')->with('success', 'Movilidad registrada correctamente.');
    }

    public function vehiculosEditar(Vehicle $vehicle)
    {
        if ($r = $this->proteger(self::ROLES_CRUD)) return $r;

        return view('vehicles.form', ['vehiculo' => $vehicle]);
    }

    public function vehiculosActualizar(Request $request, Vehicle $vehicle)
    {
        if ($r = $this->proteger(self::ROLES_CRUD)) return $r;

        $request->validate([
            'marca' => 'required',
            'modelo' => 'required',
            'anio' => 'required|integer|min:1950|max:2100',
            'precio' => 'required|numeric|min:0',
            'placa' => 'nullable',
            'color' => 'nullable',
            'estado' => 'required|in:Disponible,Reservado,Vendido',
            'descripcion' => 'nullable',
            'imagen' => 'nullable|image',
        ]);

        $datos = $request->only('marca', 'modelo', 'anio', 'placa', 'color', 'precio', 'estado', 'descripcion');

        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('vehiculos', 'public');
        } elseif ($request->boolean('eliminar_imagen') && $vehicle->imagen) {
            Storage::disk('public')->delete($vehicle->imagen);
            $datos['imagen'] = null;
        }

        $vehicle->update($datos);

        return redirect()->route('vehiculos.index')->with('success', 'Movilidad actualizada correctamente.');
    }

    public function vehiculosEliminar(Vehicle $vehicle)
    {
        if ($r = $this->proteger(self::ROLES_CRUD)) return $r;

        if ($vehicle->imagen) {
            Storage::disk('public')->delete($vehicle->imagen);
        }

        $vehicle->delete();
        return back()->with('success', 'Movilidad eliminada correctamente.');
    }

    public function clientesIndex()
    {
        if ($r = $this->proteger(self::ROLES_CRUD)) return $r;

        $clientes = Client::latest()->get();
        return view('clientes.index', compact('clientes'));
    }

    public function clientesCrear()
    {
        if ($r = $this->proteger(self::ROLES_CRUD)) return $r;

        $cliente = new Client(['estado' => 'En negociación']);
        return view('clientes.form', compact('cliente'));
    }

    public function clientesGuardar(Request $request)
    {
        if ($r = $this->proteger(self::ROLES_CRUD)) return $r;

        $request->validate([
            'nombre' => 'required|min:3',
            'telefono' => 'nullable',
            'email' => 'nullable|email',
            'ci' => 'nullable',
            'direccion' => 'nullable',
            'vehiculo_interes' => 'nullable',
            'metodo_pago' => 'nullable',
            'fuente' => 'nullable',
            'estado' => 'nullable',
            'notas' => 'nullable',
        ]);

        $datos = $request->only(
            'nombre', 'ci', 'telefono', 'email', 'direccion',
            'vehiculo_interes', 'metodo_pago', 'fuente', 'estado', 'notas'
        );
        $datos['estado'] = $datos['estado'] ?: 'En negociación';

        Client::create($datos);

        return redirect()->route('clientes.index')->with('success', 'Cliente prospecto registrado correctamente.');
    }

    public function clientesEditar(Client $client)
    {
        if ($r = $this->proteger(self::ROLES_CRUD)) return $r;

        return view('clientes.form', ['cliente' => $client]);
    }

    public function clientesActualizar(Request $request, Client $client)
    {
        if ($r = $this->proteger(self::ROLES_CRUD)) return $r;

        $request->validate([
            'nombre' => 'required|min:3',
            'telefono' => 'nullable',
            'email' => 'nullable|email',
            'ci' => 'nullable',
            'direccion' => 'nullable',
            'vehiculo_interes' => 'nullable',
            'metodo_pago' => 'nullable',
            'fuente' => 'nullable',
            'estado' => 'nullable',
            'notas' => 'nullable',
        ]);

        $datos = $request->only(
            'nombre', 'ci', 'telefono', 'email', 'direccion',
            'vehiculo_interes', 'metodo_pago', 'fuente', 'estado', 'notas'
        );
        $datos['estado'] = $datos['estado'] ?: 'En negociación';

        $client->update($datos);

        return redirect()->route('clientes.index')->with('success', 'Cliente prospecto actualizado correctamente.');
    }

    public function clientesEliminar(Client $client)
    {
        if ($r = $this->proteger(self::ROLES_CRUD)) return $r;

        $client->delete();
        return back()->with('success', 'Cliente eliminado correctamente.');
    }

    public function ventasIndex()
    {
        if ($r = $this->proteger(self::ROLES_CRUD)) return $r;

        $ventas = Sale::with('vehicle', 'client', 'user')->latest()->get();
        return view('sales.index', compact('ventas'));
    }

    public function ventasCrear()
    {
        if ($r = $this->proteger(self::ROLES_CRUD)) return $r;

        $vehiculos = Vehicle::where('estado', '!=', 'Vendido')->get();
        $clientes = Client::all();

        return view('sales.form', compact('vehiculos', 'clientes'));
    }

    public function ventasGuardar(Request $request)
    {
        if ($r = $this->proteger(self::ROLES_CRUD)) return $r;

        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'client_id' => 'required|exists:clients,id',
            'fecha' => 'required|date',
            'precio_venta' => 'required|numeric|min:0',
            'observacion' => 'nullable',
        ]);

        Sale::create([
            'vehicle_id' => $request->vehicle_id,
            'client_id' => $request->client_id,
            'user_id' => Auth::id(),
            'fecha' => $request->fecha,
            'precio_venta' => $request->precio_venta,
            'observacion' => $request->observacion,
        ]);

        Vehicle::where('id', $request->vehicle_id)->update(['estado' => 'Vendido']);

        return redirect()->route('ventas.index')->with('success', 'Venta registrada correctamente.');
    }

    public function ventasEliminar(Sale $sale)
    {
        if ($r = $this->proteger(self::ROLES_CRUD)) return $r;

        if ($sale->vehicle) {
            $sale->vehicle->update(['estado' => 'Disponible']);
        }

        $sale->delete();
        return back()->with('success', 'Venta eliminada correctamente.');
    }

    public function documentos()
    {
        if ($r = $this->proteger(self::ROLES_CRUD)) return $r;

        $clientes = Client::orderBy('nombre')->get();
        $documentos = Documento::with('user', 'client')->latest()->get();

        return view('documentos.index', compact('clientes', 'documentos'));
    }

    public function documentosGuardar(Request $request)
    {
        if ($r = $this->proteger(self::ROLES_CRUD)) return $r;

        $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'tipo' => 'required|max:100',
            'titulo' => 'nullable|max:200',
            'descripcion' => 'nullable|max:500',
            'archivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:8192',
        ]);

        $datos = [
            'user_id' => Auth::id(),
            'client_id' => $request->client_id ?: null,
            'tipo' => $this->limpiar($request->tipo),
            'titulo' => $this->limpiar($request->titulo),
            'descripcion' => $this->limpiar($request->descripcion),
        ];

        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');
            $nombre = time() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', $archivo->getClientOriginalName());
            $datos['archivo'] = $archivo->storeAs('documentos', $nombre, 'public');
        }

        Documento::create($datos);

        return redirect()->route('documentos.index')->with('success', 'Documento registrado correctamente.');
    }

    public function documentosEliminar(Documento $documento)
    {
        if ($r = $this->proteger(self::ROLES_CRUD)) return $r;

        $puede = in_array(Auth::user()->role, self::ROLES_JEFE) || $documento->user_id === Auth::id();
        if (! $puede) {
            return back()->with('error', 'Solo el responsable del documento o el Jefe de Sucursal pueden eliminarlo.');
        }

        if ($documento->archivo) {
            Storage::disk('public')->delete($documento->archivo);
        }

        $documento->delete();
        return back()->with('success', 'Documento eliminado correctamente.');
    }

    public function contratos()
    {
        if ($r = $this->proteger(self::ROLES_CRUD)) return $r;

        $clientes = Client::orderBy('nombre')->get();
        $vehiculos = Vehicle::where('estado', '!=', 'Vendido')->get();
        $contratos = Contrato::with('user', 'client', 'vehicle')->latest()->get();

        return view('panel.contratos', compact('clientes', 'vehiculos', 'contratos'));
    }

    public function contratosGuardar(Request $request)
    {
        if ($r = $this->proteger(self::ROLES_CRUD)) return $r;

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'fecha_firma' => 'nullable|date',
            'notas' => 'nullable|max:500',
        ]);

        Contrato::create([
            'user_id' => Auth::id(),
            'client_id' => $request->client_id,
            'vehicle_id' => $request->vehicle_id ?: null,
            'estado' => $request->fecha_firma ? 'Firmado' : 'Pendiente',
            'fecha_firma' => $request->fecha_firma ?: null,
            'notas' => $this->limpiar($request->notas),
        ]);

        return redirect()->route('panel.contratos')->with('success', 'Contrato registrado correctamente.');
    }

    public function contratosEstado(Request $request, Contrato $contrato)
    {
        if ($r = $this->proteger(self::ROLES_JEFE)) return $r;

        $request->validate([
            'estado' => 'required|in:Pendiente,En revisión,Firmado',
            'fecha_firma' => 'nullable|date',
        ]);

        $contrato->update([
            'estado' => $request->estado,
            'fecha_firma' => $request->fecha_firma ?: ($request->estado === 'Firmado' ? now()->toDateString() : $contrato->fecha_firma),
            'notas' => $this->limpiar($request->notas),
        ]);

        return redirect()->route('panel.contratos')->with('success', 'Estado del contrato actualizado correctamente.');
    }

    public function comisiones()
    {
        if ($r = $this->proteger(self::ROLES_CRUD)) return $r;

        $usuario = Auth::user();

        $consulta = Sale::with('vehicle', 'client', 'user')->latest();

        if ($usuario->role === 'tecnico') {
            $consulta->where('user_id', $usuario->id);
        }

        $ventas = $consulta->get();

        $total = $ventas->sum(fn ($venta) => $venta->precio_venta * 0.05);

        $porUsuario = $ventas->groupBy('user_id')->map(function ($grupo) {
            $monto = $grupo->sum('precio_venta');
            return [
                'usuario' => $grupo->first()->user,
                'ventas' => $grupo->count(),
                'monto' => $monto,
                'comision' => $monto * 0.05,
            ];
        })->values();

        return view('comisiones.index', compact('ventas', 'total', 'porUsuario'));
    }

    public function supervision()
    {
        if ($r = $this->proteger(self::ROLES_JEFE)) return $r;

        $ejecutivos = User::where('role', 'tecnico')->with('sales')->get();

        $totalClientes = Client::count();
        $totalVentas = Sale::count();
        $totalIngresos = Sale::sum('precio_venta');
        $totalComisiones = $totalIngresos * 0.05;
        $totalDocumentos = Documento::count();
        $totalContratos = Contrato::count();
        $totalFirmados = Contrato::where('estado', 'Firmado')->count();

        $filas = $ejecutivos->map(function ($usu) use ($totalClientes) {
            $ventas = $usu->sales;
            $monto = $ventas->sum('precio_venta');
            $clientesAtendidos = $ventas->pluck('client_id')->unique()->filter()->count();
            $documentos = Documento::where('user_id', $usu->id)->count();
            $contratos = Contrato::where('user_id', $usu->id)->get();
            $firmados = $contratos->where('estado', 'Firmado')->count();
            $pendientes = $contratos->whereIn('estado', ['Pendiente', 'En revisión'])->count();

            return [
                'usuario' => $usu,
                'ventas' => $ventas->count(),
                'monto' => $monto,
                'comision' => $monto * 0.05,
                'clientes_atendidos' => $clientesAtendidos,
                'pct_clientes' => $totalClientes > 0 ? round(($clientesAtendidos / $totalClientes) * 100, 1) : 0,
                'documentos' => $documentos,
                'contratos' => $contratos->count(),
                'firmados' => $firmados,
                'pendientes' => $pendientes,
                'avance' => $contratos->count() > 0 ? round(($firmados / $contratos->count()) * 100, 1) : 0,
            ];
        });

        return view('panel.supervision', compact(
            'filas',
            'totalClientes',
            'totalVentas',
            'totalIngresos',
            'totalComisiones',
            'totalDocumentos',
            'totalContratos',
            'totalFirmados'
        ));
    }

    public function perfil()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Primero debe iniciar sesión.');
        }

        $usuario = Auth::user();

        return view('perfil', compact('usuario'));
    }

    public function actualizarPerfil(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Primero debe iniciar sesión.');
        }

        $usuario = Auth::user();

        $request->merge([
            'name' => $this->limpiar($request->input('name')),
            'username' => $this->limpiar($request->input('username')),
            'email' => strtolower($this->limpiar($request->input('email'))),
            'telefono' => $this->limpiar($request->input('telefono')),
            'sucursal' => $this->limpiar($request->input('sucursal')),
            'cargo' => $this->limpiar($request->input('cargo')),
            'supervisor' => $this->limpiar($request->input('supervisor')),
        ]);

        $request->validate([
            'name' => 'required|min:3|max:100',
            'username' => ['required', 'min:4', 'max:20', 'unique:users,username,' . $usuario->id],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $usuario->id],
            'telefono' => 'nullable|max:30',
            'sucursal' => 'nullable|max:100',
            'cargo' => 'nullable|max:100',
            'supervisor' => 'nullable|max:100',
        ]);

        $datos = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
        ];

        if (Schema::hasColumn('users', 'telefono')) {
            $datos['telefono'] = $request->telefono;
        }

        if (Schema::hasColumn('users', 'sucursal')) {
            $datos['sucursal'] = $request->sucursal;
        }

        if (Schema::hasColumn('users', 'cargo')) {
            $datos['cargo'] = $request->cargo;
        }

        if (Schema::hasColumn('users', 'supervisor')) {
            $datos['supervisor'] = $request->supervisor;
        }

        $usuario->forceFill($datos)->save();

        return redirect()->route('perfil')->with('success', 'Tus datos fueron actualizados correctamente.');
    }

    public function cambiarContrasena(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Primero debe iniciar sesión.');
        }

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Debes escribir tu contraseña actual.',
            'password.confirmed' => 'La confirmación de la nueva contraseña no coincide.',
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
        ]);

        $usuario = Auth::user();

        if (!Hash::check($request->current_password, $usuario->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.']);
        }

        $usuario->forceFill([
            'password' => Hash::make($request->password),
        ])->save();

        $request->session()->regenerate();

        return back()->with('success', 'Tu contraseña fue cambiada correctamente.');
    }
}