import 'api_service.dart';
import '../config/api_config.dart';
import '../models/dashboard_stats.dart';

class DashboardService {
  final ApiService _api = ApiService();

  Future<DashboardStats> getStats() async {
    final response = await _api.get(ApiConfig.dashboard);
    final data = _api.parseResponse(response);
    return DashboardStats.fromJson(data);
  }
}