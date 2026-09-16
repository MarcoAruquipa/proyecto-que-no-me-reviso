import '../utils/parsers.dart';

class SupervisionData {
  final List<SupervisionEjecutivo> ejecutivos;
  final int totalClientes;
  final int totalVentas;
  final double totalIngresos;
  final double totalComisiones;
  final int totalDocumentos;
  final int totalContratos;
  final int totalFirmados;
  final int totalPendientes;

  SupervisionData({
    required this.ejecutivos,
    required this.totalClientes,
    required this.totalVentas,
    required this.totalIngresos,
    required this.totalComisiones,
    required this.totalDocumentos,
    required this.totalContratos,
    required this.totalFirmados,
    required this.totalPendientes,
  });

  double get pctFirmados =>
      totalContratos > 0 ? (totalFirmados / totalContratos) * 100 : 0;

  factory SupervisionData.fromJson(Map<String, dynamic> json) {
    final raw = json['ejecutivos'];
    final list = raw is List
        ? raw
            .whereType<Map<String, dynamic>>()
            .map(SupervisionEjecutivo.fromJson)
            .toList()
        : <SupervisionEjecutivo>[];
    return SupervisionData(
      ejecutivos: list,
      totalClientes: parseIntOr(json['total_clientes'], 0),
      totalVentas: parseIntOr(json['total_ventas'], 0),
      totalIngresos: parseDoubleOr(json['total_ingresos'], 0),
      totalComisiones: parseDoubleOr(json['total_comisiones'], 0),
      totalDocumentos: parseIntOr(json['total_documentos'], 0),
      totalContratos: parseIntOr(json['total_contratos'], 0),
      totalFirmados: parseIntOr(json['total_firmados'], 0),
      totalPendientes: parseIntOr(json['total_pendientes'], 0),
    );
  }
}

class SupervisionEjecutivo {
  final Map<String, dynamic>? usuario;
  final int ventas;
  final double monto;
  final double comision;
  final int clientesAtendidos;
  final double pctClientes;
  final int documentos;
  final int contratos;
  final int firmados;
  final int pendientes;
  final double avance;

  SupervisionEjecutivo({
    required this.usuario,
    required this.ventas,
    required this.monto,
    required this.comision,
    required this.clientesAtendidos,
    required this.pctClientes,
    required this.documentos,
    required this.contratos,
    required this.firmados,
    required this.pendientes,
    required this.avance,
  });

  factory SupervisionEjecutivo.fromJson(Map<String, dynamic> json) {
    return SupervisionEjecutivo(
      usuario: _asMap(json['usuario']),
      ventas: parseIntOr(json['ventas'], 0),
      monto: parseDoubleOr(json['monto'], 0),
      comision: parseDoubleOr(json['comision'], 0),
      clientesAtendidos: parseIntOr(json['clientes_atendidos'], 0),
      pctClientes: parseDoubleOr(json['pct_clientes'], 0),
      documentos: parseIntOr(json['documentos'], 0),
      contratos: parseIntOr(json['contratos'], 0),
      firmados: parseIntOr(json['firmados'], 0),
      pendientes: parseIntOr(json['pendientes'], 0),
      avance: parseDoubleOr(json['avance'], 0),
    );
  }

  String get nombre => usuario?['name'] ?? 'Ejecutivo';
  String get sucursal => (usuario?['sucursal'] ?? '') as String;
  String? get email => usuario?['email'] as String?;

  static Map<String, dynamic>? _asMap(dynamic value) {
    if (value is Map<String, dynamic>) return value;
    if (value is Map) return Map<String, dynamic>.from(value);
    return null;
  }
}