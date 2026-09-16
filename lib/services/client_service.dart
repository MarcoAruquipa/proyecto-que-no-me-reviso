import 'api_service.dart';
import '../config/api_config.dart';
import '../models/client.dart';
import '../models/paginated_response.dart';

class ClientService {
  final ApiService _api = ApiService();

  Future<PaginatedResponse<Client>> getClients({
    String? estado,
    String? search,
    int page = 1,
    int perPage = 15,
  }) async {
    final query = <String, String>{
      'page': '$page',
      'per_page': '$perPage',
    };
    if (estado != null && estado.isNotEmpty) query['estado'] = estado;
    if (search != null && search.isNotEmpty) query['search'] = search;

    final response = await _api.get(ApiConfig.clientes, queryParams: query);
    final data = _api.parseResponse(response);
    return PaginatedResponse<Client>.fromJson(data, Client.fromJson);
  }

  Future<Client> getClient(int id) async {
    final response = await _api.get('${ApiConfig.clientes}/$id');
    final data = _api.parseResponse(response);
    return Client.fromJson(data['client'] ?? data);
  }

  Future<Client> createClient(Map<String, dynamic> data) async {
    final response = await _api.post(ApiConfig.clientes, body: data);
    final json = _api.parseResponse(response);
    return Client.fromJson(json['client'] ?? json);
  }

  Future<Client> updateClient(int id, Map<String, dynamic> data) async {
    final response = await _api.put('${ApiConfig.clientes}/$id', body: data);
    final json = _api.parseResponse(response);
    return Client.fromJson(json['client'] ?? json);
  }

  Future<void> deleteClient(int id) async {
    final response = await _api.delete('${ApiConfig.clientes}/$id');
    _api.parseResponse(response);
  }
}