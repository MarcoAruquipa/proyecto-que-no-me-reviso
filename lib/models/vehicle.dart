import '../config/api_config.dart';
import '../utils/parsers.dart';

class Vehicle {
  final int? id;
  final int? userId;
  final String? marca;
  final String? modelo;
  final int? anio;
  final String? placa;
  final String? color;
  final double? precio;
  final String? estado;
  final String? descripcion;
  final String? imagen;
  final String? createdAt;
  final String? updatedAt;
  final Map<String, dynamic>? user;
  final List<dynamic>? sales;

  Vehicle({
    this.id,
    this.userId,
    this.marca,
    this.modelo,
    this.anio,
    this.placa,
    this.color,
    this.precio,
    this.estado,
    this.descripcion,
    this.imagen,
    this.createdAt,
    this.updatedAt,
    this.user,
    this.sales,
  });

  factory Vehicle.fromJson(Map<String, dynamic> json) {
    return Vehicle(
      id: parseInt(json['id']),
      userId: parseInt(json['user_id']),
      marca: json['marca'],
      modelo: json['modelo'],
      anio: parseInt(json['anio']),
      placa: json['placa'],
      color: json['color'],
      precio: parseDouble(json['precio']),
      estado: json['estado'],
      descripcion: json['descripcion'],
      imagen: json['imagen'],
      createdAt: json['created_at'],
      updatedAt: json['updated_at'],
      user: json['user'],
      sales: json['sales'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'marca': marca,
      'modelo': modelo,
      'anio': anio,
      'placa': placa,
      'color': color,
      'precio': precio,
      'estado': estado,
      'descripcion': descripcion,
    };
  }

  String get displayName => '${marca ?? ''} ${modelo ?? ''} ${anio ?? ''}'.trim();

  /// URL completa de la foto de la movilidad si existe.
  ///
  /// El backend guarda el campo `imagen` como ruta relativa
  /// (p. ej. `vehiculos/abc.jpg`) dentro de `storage/app/public`,
  /// expuesta públicamente en `.../storage/<ruta>`.
  String? get imagenUrl {
    final path = imagen;
    if (path == null || path.isEmpty) return null;
    final clean = path.startsWith('/') ? path.substring(1) : path;
    return '${ApiConfig.storageBaseUrl}/storage/$clean';
  }
}
