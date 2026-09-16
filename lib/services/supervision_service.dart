import 'api_service.dart';
import '../config/api_config.dart';
import '../models/supervision.dart';

class SupervisionService {
  final ApiService _api = ApiService();

  Future<SupervisionData> getSupervision() async {
    final response = await _api.get(ApiConfig.supervision);
    final data = _api.parseResponse(response);
    return SupervisionData.fromJson(data);
  }
}