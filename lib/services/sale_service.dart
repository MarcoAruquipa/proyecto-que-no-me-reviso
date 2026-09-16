import 'api_service.dart';
import '../config/api_config.dart';
import '../models/sale.dart';
import '../models/paginated_response.dart';

class SaleService {
  final ApiService _api = ApiService();

  Future<PaginatedResponse<Sale>> getSales({
    String? search,
    int page = 1,
    int perPage = 15,
  }) async {
    final query = <String, String>{
      'page': '$page',
      'per_page': '$perPage',
    };
    if (search != null && search.isNotEmpty) query['search'] = search;

    final response = await _api.get(ApiConfig.ventas, queryParams: query);
    final data = _api.parseResponse(response);
    return PaginatedResponse<Sale>.fromJson(data, Sale.fromJson);
  }

  Future<Sale> getSale(int id) async {
    final response = await _api.get('${ApiConfig.ventas}/$id');
    final data = _api.parseResponse(response);
    return Sale.fromJson(data['sale'] ?? data);
  }

  Future<Sale> createSale(Map<String, dynamic> data) async {
    final response = await _api.post(ApiConfig.ventas, body: data);
    final json = _api.parseResponse(response);
    return Sale.fromJson(json['sale'] ?? json);
  }

  Future<void> deleteSale(int id) async {
    final response = await _api.delete('${ApiConfig.ventas}/$id');
    _api.parseResponse(response);
  }

  Future<List<Sale>> getComisiones() async {
    final response = await _api.get(ApiConfig.comisiones);
    final data = _api.parseResponse(response);
    final sales = data['sales'] ?? data['ventas'] ?? data['data'] ?? data;
    if (sales is List) {
      return sales.map((e) => Sale.fromJson(e)).toList();
    }
    return [];
  }
}