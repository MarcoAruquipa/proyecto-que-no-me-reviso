import 'api_service.dart';
import '../config/api_config.dart';
import '../models/vehicle.dart';
import '../models/maletin.dart';
import '../models/paginated_response.dart';

class CatalogService {
  final ApiService _api = ApiService();

  Future<PaginatedResponse<Vehicle>> getCatalogo({
    String? search,
    double? precioMin,
    double? precioMax,
    int page = 1,
    int perPage = 15,
  }) async {
    final query = <String, String>{
      'page': '$page',
      'per_page': '$perPage',
    };
    if (search != null && search.isNotEmpty) query['search'] = search;
    if (precioMin != null) query['precio_min'] = '$precioMin';
    if (precioMax != null) query['precio_max'] = '$precioMax';

    final response = await _api.get(ApiConfig.catalogo, queryParams: query);
    final data = _api.parseResponse(response);
    return PaginatedResponse<Vehicle>.fromJson(data, Vehicle.fromJson);
  }

  Future<Vehicle> getVehiculo(int id) async {
    final response = await _api.get('${ApiConfig.catalogo}/$id');
    final data = _api.parseResponse(response);
    return Vehicle.fromJson(data['vehicle'] ?? data);
  }

  Future<Maletin> getMaletin() async {
    final response = await _api.get(ApiConfig.maletin);
    final data = _api.parseResponse(response);
    return Maletin.fromJson(data['maletin'] ?? data);
  }
}