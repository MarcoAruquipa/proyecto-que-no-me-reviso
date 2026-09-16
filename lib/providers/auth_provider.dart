import 'package:flutter/foundation.dart';
import '../models/user.dart';
import '../services/auth_service.dart';

class AuthProvider extends ChangeNotifier {
  final AuthService _authService = AuthService();

  bool _isLoading = false;
  String? _errorMessage;
  User? _user;
  bool _isAuthenticated = false;

  bool get isLoading => _isLoading;
  String? get errorMessage => _errorMessage;
  User? get user => _user;
  bool get isAuthenticated => _isAuthenticated;

  Future<void> checkAuth() async {
    _isLoading = true;
    notifyListeners();
    _isAuthenticated = await _authService.isLoggedIn();
    if (_isAuthenticated) {
      _user = await _authService.getStoredUser();
    }
    _isLoading = false;
    notifyListeners();
  }

  Future<bool> login(String username, String password) async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();
    try {
      final data = await _authService.login(username, password);
      _user = User.fromJson(data['user'] ?? {});
      _isAuthenticated = true;
      _isLoading = false;
      notifyListeners();
      return true;
    } catch (e) {
      _isLoading = false;
      _errorMessage = e.toString().replaceAll('ApiException(401): ', '').replaceAll('ApiException(): ', '');
      notifyListeners();
      return false;
    }
  }

  Future<bool> register({
    required String name,
    required String username,
    required String email,
    required String password,
    String? passwordConfirmation,
    String? telefono,
    String? sucursal,
    String? cargo,
  }) async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();
    try {
      final data = await _authService.register(
        name: name,
        username: username,
        email: email,
        password: password,
        passwordConfirmation: passwordConfirmation,
        telefono: telefono,
        sucursal: sucursal,
        cargo: cargo,
      );
      _user = User.fromJson(data['user'] ?? {});
      _isAuthenticated = true;
      _isLoading = false;
      notifyListeners();
      return true;
    } catch (e) {
      _isLoading = false;
      _errorMessage = e.toString();
      notifyListeners();
      return false;
    }
  }

  Future<void> logout() async {
    _isLoading = true;
    notifyListeners();
    await _authService.logout();
    _user = null;
    _isAuthenticated = false;
    _isLoading = false;
    notifyListeners();
  }

  Future<void> refreshProfile() async {
    if (!_isAuthenticated) return;
    try {
      _user = await _authService.getProfile();
      notifyListeners();
    } catch (_) {}
  }

  Future<bool> updateProfile({
    String? name,
    String? email,
    String? telefono,
    String? sucursal,
    String? cargo,
    String? supervisor,
  }) async {
    _errorMessage = null;
    try {
      _user = await _authService.updateProfile(
        name: name,
        email: email,
        telefono: telefono,
        sucursal: sucursal,
        cargo: cargo,
        supervisor: supervisor,
      );
      notifyListeners();
      return true;
    } catch (e) {
      _errorMessage = e.toString();
      return false;
    }
  }

  Future<bool> changePassword({
    required String currentPassword,
    required String newPassword,
  }) async {
    _errorMessage = null;
    try {
      await _authService.changePassword(
        currentPassword: currentPassword,
        newPassword: newPassword,
      );
      return true;
    } catch (e) {
      _errorMessage = e.toString();
      return false;
    }
  }

  clearError() {
    _errorMessage = null;
    notifyListeners();
  }
}