<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Contrato;
use App\Models\Documento;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class SupervisionController extends Controller
{
    private const ROLES_JEFE = ['admin', 'jefe'];

    public function index(): JsonResponse
    {
        if (! in_array(request()->user()->role, self::ROLES_JEFE)) {
            return response()->json([
                'message' => 'Solo el Jefe de Sucursal y el Administrador pueden acceder a la supervisión.',
            ], 403);
        }

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
                'monto' => round($monto, 2),
                'comision' => round($monto * 0.05, 2),
                'clientes_atendidos' => $clientesAtendidos,
                'pct_clientes' => $totalClientes > 0 ? round(($clientesAtendidos / $totalClientes) * 100, 1) : 0,
                'documentos' => $documentos,
                'contratos' => $contratos->count(),
                'firmados' => $firmados,
                'pendientes' => $pendientes,
                'avance' => $contratos->count() > 0 ? round(($firmados / $contratos->count()) * 100, 1) : 0,
            ];
        })->values();

        return response()->json([
            'ejecutivos' => $filas,
            'total_clientes' => $totalClientes,
            'total_ventas' => $totalVentas,
            'total_ingresos' => round($totalIngresos, 2),
            'total_comisiones' => round($totalComisiones, 2),
            'total_documentos' => $totalDocumentos,
            'total_contratos' => $totalContratos,
            'total_firmados' => $totalFirmados,
            'total_pendientes' => $totalContratos - $totalFirmados,
        ]);
    }
}