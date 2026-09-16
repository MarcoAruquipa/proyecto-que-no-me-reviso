import 'api_service.dart';
import 'storage_service.dart';
import '../config/api_config.dart';
import '../models/user.dart';

class AuthService {
  final ApiService _api = ApiService();
  final StorageService _storage = StorageService();

  Future<Map<String, dynamic>> login(String login, String password) async {
    final response = await _api.post(
      ApiConfig.login,
      body: {'login': login, 'username': login, 'password': password},
    );
    final data = _api.parseResponse(response);
    if (data['token'] != null) {
      await _storage.saveToken(data['token']);
      if (data['user'] != null) {
        await _storage.saveUser(data['user']);
      }
    }
    return data;
  }

  Future<Map<String, dynamic>> register({
    required String name,
    required String username,
    required String email,
    required String password,
    String? passwordConfirmation,
    String? telefono,
    String? sucursal,
    String? cargo,
  }) async {
    final body = <String, dynamic>{
      'name': name,
      'username': username,
      'email': email,
      'password': password,
      'password_confirmation': passwordConfirmation ?? password,
    };
    if (telefono != null && telefono.isNotEmpty) body['telefono'] = telefono;
    if (sucursal != null && sucursal.isNotEmpty) body['sucursal'] = sucursal;
    if (cargo != null && cargo.isNotEmpty) body['cargo'] = cargo;

    final response = await _api.post(ApiConfig.register, body: body);
    final data = _api.parseResponse(response);
    if (data['token'] != null) {
      await _storage.saveToken(data['token']);
      if (data['user'] != null) {
        await _storage.saveUser(data['user']);
      }
    }
    return data;
  }

  Future<void> logout() async {
    try {
      await _api.post(ApiConfig.logout);
    } catch (_) {}
    await _storage.clearAll();
  }

  Future<User> getProfile() async {
    final response = await _api.get(ApiConfig.profile);
    final data = _api.parseResponse(response);
    final user = User.fromJson(data['user'] ?? data);
    await _storage.saveUser(data['user'] ?? data);
    return user;
  }

  Future<User> updateProfile({
    String? name,
    String? email,
    String? telefono,
    String? sucursal,
    String? cargo,
    String? supervisor,
  }) async {
    final body = <String, dynamic>{};
    if (name != null) body['name'] = name;
    if (email != null) body['email'] = email;
    if (telefono != null) body['telefono'] = telefono;
    if (sucursal != null) body['sucursal'] = sucursal;
    if (cargo != null) body['cargo'] = cargo;
    if (supervisor != null) body['supervisor'] = supervisor;

    final response = await _api.put(ApiConfig.profile, body: body);
    final data = _api.parseResponse(response);
    final user = User.fromJson(data['user'] ?? data);
    await _storage.saveUser(data['user'] ?? data);
    return user;
  }

  Future<void> changePassword({
    required String currentPassword,
    required String newPassword,
  }) async {
    final response = await _api.put(
      ApiConfig.changePassword,
      body: {
        'current_password': currentPassword,
        'password': newPassword,
        'password_confirmation': newPassword,
      },
    );
    _api.parseResponse(response);
  }

  Future<bool> isLoggedIn() async {
    return await _storage.hasToken();
  }

  Future<User?> getStoredUser() async {
    final data = await _storage.getUser();
    if (data == null) return null;
    return User.fromJson(data);
  }
}
