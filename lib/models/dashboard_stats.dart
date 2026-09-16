import '../utils/parsers.dart';

class DashboardStats {
  final int totalVehiculos;
  final int totalClientes;
  final int totalVentas;
  final double ingresosTotales;
  final List<dynamic> vehiculosRecientes;
  final List<dynamic> clientesRecientes;
  final List<dynamic> ventasRecientes;

  DashboardStats({
    required this.totalVehiculos,
    required this.totalClientes,
    required this.totalVentas,
    required this.ingresosTotales,
    required this.vehiculosRecientes,
    required this.clientesRecientes,
    required this.ventasRecientes,
  });

  factory DashboardStats.fromJson(Map<String, dynamic> json) {
    return DashboardStats(
      totalVehiculos: parseIntOr(json['vehiculos'] ?? json['total_vehiculos'], 0),
      totalClientes: parseIntOr(json['clientes'] ?? json['total_clientes'], 0),
      totalVentas: parseIntOr(json['ventas'] ?? json['total_ventas'], 0),
      ingresosTotales: parseDoubleOr(
        json['monto_total_ventas'] ?? json['ingresos_totales'],
        0,
      ),
      vehiculosRecientes: json['vehiculos_recientes'] ?? [],
      clientesRecientes: json['clientes_recientes'] ?? [],
      ventasRecientes: json['ventas_recientes'] ?? [],
    );
  }
}
