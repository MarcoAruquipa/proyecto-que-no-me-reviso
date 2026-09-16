import '../utils/parsers.dart';

class Client {
  final int? id;
  final String? nombre;
  final String? ci;
  final String? telefono;
  final String? email;
  final String? direccion;
  final String? vehiculoInteres;
  final String? metodoPago;
  final String? fuente;
  final String? estado;
  final String? notas;
  final String? createdAt;
  final String? updatedAt;
  final List<dynamic>? sales;

  Client({
    this.id,
    this.nombre,
    this.ci,
    this.telefono,
    this.email,
    this.direccion,
    this.vehiculoInteres,
    this.metodoPago,
    this.fuente,
    this.estado,
    this.notas,
    this.createdAt,
    this.updatedAt,
    this.sales,
  });

  factory Client.fromJson(Map<String, dynamic> json) {
    return Client(
      id: parseInt(json['id']),
      nombre: json['nombre'],
      ci: json['ci'],
      telefono: json['telefono'],
      email: json['email'],
      direccion: json['direccion'],
      vehiculoInteres: json['vehiculo_interes'],
      metodoPago: json['metodo_pago'],
      fuente: json['fuente'],
      estado: json['estado'],
      notas: json['notas'],
      createdAt: json['created_at'],
      updatedAt: json['updated_at'],
      sales: json['sales'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'nombre': nombre,
      'ci': ci,
      'telefono': telefono,
      'email': email,
      'direccion': direccion,
      'vehiculo_interes': vehiculoInteres,
      'metodo_pago': metodoPago,
      'fuente': fuente,
      'estado': estado,
      'notas': notas,
    };
  }
}
