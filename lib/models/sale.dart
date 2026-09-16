import '../utils/parsers.dart';

class Sale {
  final int? id;
  final int? vehicleId;
  final int? clientId;
  final int? userId;
  final String? fecha;
  final double? precioVenta;
  final String? observacion;
  final String? createdAt;
  final String? updatedAt;
  final Map<String, dynamic>? vehicle;
  final Map<String, dynamic>? client;
  final Map<String, dynamic>? user;

  Sale({
    this.id,
    this.vehicleId,
    this.clientId,
    this.userId,
    this.fecha,
    this.precioVenta,
    this.observacion,
    this.createdAt,
    this.updatedAt,
    this.vehicle,
    this.client,
    this.user,
  });

  factory Sale.fromJson(Map<String, dynamic> json) {
    return Sale(
      id: parseInt(json['id']),
      vehicleId: parseInt(json['vehicle_id']),
      clientId: parseInt(json['client_id']),
      userId: parseInt(json['user_id']),
      fecha: json['fecha'],
      precioVenta: parseDouble(json['precio_venta']),
      observacion: json['observacion'],
      createdAt: json['created_at'],
      updatedAt: json['updated_at'],
      vehicle: json['vehicle'],
      client: json['client'],
      user: json['user'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'vehicle_id': vehicleId,
      'client_id': clientId,
      'fecha': fecha,
      'precio_venta': precioVenta,
      'observacion': observacion,
    };
  }

  String get vehicleName {
    if (vehicle == null) return 'N/A';
    return '${vehicle!['marca'] ?? ''} ${vehicle!['modelo'] ?? ''}'.trim();
  }

  String get clientName {
    if (client == null) return 'N/A';
    return client!['nombre'] ?? 'N/A';
  }

  double get comision => (precioVenta ?? 0) * 0.05;
}
