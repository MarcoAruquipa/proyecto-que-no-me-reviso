import 'package:flutter/foundation.dart';
import '../models/client.dart';
import '../services/client_service.dart';

class ClientProvider extends ChangeNotifier {
  final ClientService _service = ClientService();

  bool _isLoading = false;
  String? _errorMessage;
  List<Client> _clients = [];
  int _currentPage = 1;
  int _lastPage = 1;
  int _total = 0;
  String _estadoFilter = '';
  String _searchQuery = '';

  bool get isLoading => _isLoading;
  String? get errorMessage => _errorMessage;
  List<Client> get clients => _clients;
  int get currentPage => _currentPage;
  int get lastPage => _lastPage;
  int get total => _total;
  bool get hasNextPage => _currentPage < _lastPage;

  Future<void> loadClients({int page = 1}) async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();
    try {
      final result = await _service.getClients(
        estado: _estadoFilter.isEmpty ? null : _estadoFilter,
        search: _searchQuery.isEmpty ? null : _searchQuery,
        page: page,
      );
      _clients = result.data;
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
      final result = await _service.getClients(
        estado: _estadoFilter.isEmpty ? null : _estadoFilter,
        search: _searchQuery.isEmpty ? null : _searchQuery,
        page: _currentPage + 1,
      );
      _clients = [..._clients, ...result.data];
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
    loadClients();
  }

  void search(String query) {
    _searchQuery = query;
    loadClients();
  }

  Future<bool> createClient(Map<String, dynamic> data) async {
    _errorMessage = null;
    try {
      await _service.createClient(data);
      await loadClients();
      return true;
    } catch (e) {
      _errorMessage = e.toString();
      return false;
    }
  }

  Future<bool> updateClient(int id, Map<String, dynamic> data) async {
    _errorMessage = null;
    try {
      await _service.updateClient(id, data);
      await loadClients();
      return true;
    } catch (e) {
      _errorMessage = e.toString();
      return false;
    }
  }

  Future<bool> deleteClient(int id) async {
    _errorMessage = null;
    try {
      await _service.deleteClient(id);
      await loadClients();
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