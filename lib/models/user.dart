import '../utils/parsers.dart';

class User {
  final int? id;
  final String? name;
  final String? username;
  final String? email;
  final String? role;
  final String? telefono;
  final String? sucursal;
  final String? cargo;
  final String? supervisor;
  final String? createdAt;
  final String? updatedAt;

  User({
    this.id,
    this.name,
    this.username,
    this.email,
    this.role,
    this.telefono,
    this.sucursal,
    this.cargo,
    this.supervisor,
    this.createdAt,
    this.updatedAt,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: parseInt(json['id']),
      name: json['name'],
      username: json['username'],
      email: json['email'],
      role: json['role'],
      telefono: json['telefono'],
      sucursal: json['sucursal'],
      cargo: json['cargo'],
      supervisor: json['supervisor'],
      createdAt: json['created_at'],
      updatedAt: json['updated_at'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'name': name,
      'email': email,
      'telefono': telefono,
      'sucursal': sucursal,
      'cargo': cargo,
      'supervisor': supervisor,
    };
  }
}
