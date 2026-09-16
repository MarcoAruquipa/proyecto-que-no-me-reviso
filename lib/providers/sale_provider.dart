import 'package:flutter/foundation.dart';
import '../models/sale.dart';
import '../services/sale_service.dart';

class SaleProvider extends ChangeNotifier {
  final SaleService _service = SaleService();

  bool _isLoading = false;
  String? _errorMessage;
  List<Sale> _sales = [];
  int _currentPage = 1;
  int _lastPage = 1;
  int _total = 0;
  String _searchQuery = '';

  bool get isLoading => _isLoading;
  String? get errorMessage => _errorMessage;
  List<Sale> get sales => _sales;
  int get currentPage => _currentPage;
  int get lastPage => _lastPage;
  int get total => _total;
  bool get hasNextPage => _currentPage < _lastPage;

  Future<void> loadSales({int page = 1}) async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();
    try {
      final result = await _service.getSales(
        search: _searchQuery.isEmpty ? null : _searchQuery,
        page: page,
      );
      _sales = result.data;
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
      final result = await _service.getSales(
        search: _searchQuery.isEmpty ? null : _searchQuery,
        page: _currentPage + 1,
      );
      _sales = [..._sales, ...result.data];
      _currentPage = result.currentPage;
      _lastPage = result.lastPage;
      _total = result.total;
      notifyListeners();
    } catch (e) {
      _errorMessage = e.toString();
      notifyListeners();
    }
  }

  void search(String query) {
    _searchQuery = query;
    loadSales();
  }

  Future<bool> createSale(Map<String, dynamic> data) async {
    _errorMessage = null;
    try {
      await _service.createSale(data);
      await loadSales();
      return true;
    } catch (e) {
      _errorMessage = e.toString();
      return false;
    }
  }

  Future<bool> deleteSale(int id) async {
    _errorMessage = null;
    try {
      await _service.deleteSale(id);
      await loadSales();
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