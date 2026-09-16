import 'package:flutter/foundation.dart';
import '../models/dashboard_stats.dart';
import '../services/dashboard_service.dart';

class DashboardProvider extends ChangeNotifier {
  final DashboardService _service = DashboardService();

  bool _isLoading = false;
  String? _errorMessage;
  DashboardStats? _stats;

  bool get isLoading => _isLoading;
  String? get errorMessage => _errorMessage;
  DashboardStats? get stats => _stats;

  Future<void> loadStats() async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();
    try {
      _stats = await _service.getStats();
    } catch (e) {
      _errorMessage = e.toString();
    }
    _isLoading = false;
    notifyListeners();
  }
}