import 'package:flutter/foundation.dart';
import '../models/vehicle.dart';
import '../services/catalog_service.dart';
import '../models/maletin.dart';

class CatalogProvider extends ChangeNotifier {
  final CatalogService _service = CatalogService();

  bool _isLoading = false;
  String? _errorMessage;
  List<Vehicle> _vehicles = [];
  int _currentPage = 1;
  int _lastPage = 1;
  Maletin? _maletin;

  bool get isLoading => _isLoading;
  String? get errorMessage => _errorMessage;
  List<Vehicle> get vehicles => _vehicles;
  int get currentPage => _currentPage;
  int get lastPage => _lastPage;
  bool get hasNextPage => _currentPage < _lastPage;
  Maletin? get maletin => _maletin;

  Future<void> loadCatalogo({int page = 1}) async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();
    try {
      final result = await _service.getCatalogo(page: page);
      _vehicles = result.data;
      _currentPage = result.currentPage;
      _lastPage = result.lastPage;
    } catch (e) {
      _errorMessage = e.toString();
    }
    _isLoading = false;
    notifyListeners();
  }

  Future<void> loadNextPage() async {
    if (!hasNextPage || _isLoading) return;
    try {
      final result = await _service.getCatalogo(page: _currentPage + 1);
      _vehicles = [..._vehicles, ...result.data];
      _currentPage = result.currentPage;
      _lastPage = result.lastPage;
      notifyListeners();
    } catch (e) {
      _errorMessage = e.toString();
      notifyListeners();
    }
  }

  Future<void> loadMaletin() async {
    try {
      _maletin = await _service.getMaletin();
      notifyListeners();
    } catch (_) {}
  }

  void clearError() {
    _errorMessage = null;
    notifyListeners();
  }
}