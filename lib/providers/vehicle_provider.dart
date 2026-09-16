import 'package:flutter/foundation.dart';
import '../models/vehicle.dart';
import '../services/vehicle_service.dart';

class VehicleProvider extends ChangeNotifier {
  final VehicleService _service = VehicleService();

  bool _isLoading = false;
  String? _errorMessage;
  List<Vehicle> _vehicles = [];
  int _currentPage = 1;
  int _lastPage = 1;
  int _total = 0;
  String _estadoFilter = '';
  String _searchQuery = '';

  bool get isLoading => _isLoading;
  String? get errorMessage => _errorMessage;
  List<Vehicle> get vehicles => _vehicles;
  int get currentPage => _currentPage;
  int get lastPage => _lastPage;
  int get total => _total;
  bool get hasNextPage => _currentPage < _lastPage;
  String get estadoFilter => _estadoFilter;

  Future<void> loadVehicles({int page = 1}) async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();
    try {
      final result = await _service.getVehicles(
        estado: _estadoFilter.isEmpty ? null : _estadoFilter,
        search: _searchQuery.isEmpty ? null : _searchQuery,
        page: page,
      );
      _vehicles = result.data;
      _currentPage = result.currentPage;
      _lastPage = result.lastPage;
      _total = result.total;
    } catch (e) {
      _errorMessage = e.toString();
    }
    _isLoading = false;
    notifyListeners();
  }

  Future<void> loadNextPage() async {
    if (!hasNextPage || _isLoading) return;
    try {
      final result = await _service.getVehicles(
        estado: _estadoFilter.isEmpty ? null : _estadoFilter,
        search: _searchQuery.isEmpty ? null : _searchQuery,
        page: _currentPage + 1,
      );
      _vehicles = [..._vehicles, ...result.data];
      _currentPage = result.currentPage;
      _lastPage = result.lastPage;
      _total = result.total;
      notifyListeners();
    } catch (e) {
      _errorMessage = e.toString();
      notifyListeners();
    }
  }

  void setEstadoFilter(String? estado) {
    _estadoFilter = estado ?? '';
    loadVehicles();
  }

  void search(String query) {
    _searchQuery = query;
    loadVehicles();
  }

  Future<bool> createVehicle(
    Map<String, dynamic> data, {
    Uint8List? imageBytes,
    String? imageFilename,
  }) async {
    _errorMessage = null;
    try {
      await _service.createVehicle(
        data,
        imageBytes: imageBytes,
        imageFilename: imageFilename,
      );
      await loadVehicles();
      return true;
    } catch (e) {
      _errorMessage = e.toString();
      return false;
    }
  }

  Future<bool> updateVehicle(
    int id, {
    required Map<String, dynamic> data,
    Uint8List? imageBytes,
    String? imageFilename,
  }) async {
    _errorMessage = null;
    try {
      await _service.updateVehicle(
        id,
        data: data,
        imageBytes: imageBytes,
        imageFilename: imageFilename,
      );
      await loadVehicles();
      return true;
    } catch (e) {
      _errorMessage = e.toString();
      return false;
    }
  }

  Future<bool> deleteVehicle(int id) async {
    _errorMessage = null;
    try {
      await _service.deleteVehicle(id);
      await loadVehicles();
      return true;
    } catch (e) {
      _errorMessage = e.toString();
      return false;
    }
  }

  void clearError() {
    _errorMessage = null;
    notifyListeners();
  }
}