import '../config/api_config.dart';
import '../utils/parsers.dart';

class Maletin {
  final int? id;
  final int? userId;
  final String? titulo;
  final String? descripcion;
  final String? telefono;
  final String? direccion;
  final String? logo;
  final String? banner;
  final String? createdAt;
  final String? updatedAt;

  Maletin({
    this.id,
    this.userId,
    this.titulo,
    this.descripcion,
    this.telefono,
    this.direccion,
    this.logo,
    this.banner,
    this.createdAt,
    this.updatedAt,
  });

  factory Maletin.fromJson(Map<String, dynamic> json) {
    return Maletin(
      id: parseInt(json['id']),
      userId: parseInt(json['user_id']),
      titulo: json['titulo'],
      descripcion: json['descripcion'],
      telefono: json['telefono'],
      direccion: json['direccion'],
      logo: json['logo'],
      banner: json['banner'],
      createdAt: json['created_at'],
      updatedAt: json['updated_at'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'titulo': titulo,
      'descripcion': descripcion,
      'telefono': telefono,
      'direccion': direccion,
    };
  }

  String? _storageUrl(String? path) {
    if (path == null || path.isEmpty) return null;
    final clean = path.startsWith('/') ? path.substring(1) : path;
    return '${ApiConfig.storageBaseUrl}/storage/$clean';
  }

  String? get logoUrl => _storageUrl(logo);
  String? get bannerUrl => _storageUrl(banner);
}
