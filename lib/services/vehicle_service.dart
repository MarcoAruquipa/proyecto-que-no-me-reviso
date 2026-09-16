import 'dart:typed_data';
import 'api_service.dart';
import '../config/api_config.dart';
import '../models/vehicle.dart';
import '../models/paginated_response.dart';

class VehicleService {
  final ApiService _api = ApiService();

  Future<PaginatedResponse<Vehicle>> getVehicles({
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

    final response = await _api.get(ApiConfig.vehiculos, queryParams: query);
    final data = _api.parseResponse(response);
    return PaginatedResponse<Vehicle>.fromJson(data, Vehicle.fromJson);
  }

  Future<Vehicle> getVehicle(int id) async {
    final response = await _api.get('${ApiConfig.vehiculos}/$id');
    final data = _api.parseResponse(response);
    return Vehicle.fromJson(data['vehicle'] ?? data);
  }

  Future<Vehicle> createVehicle(
    Map<String, dynamic> data, {
    Uint8List? imageBytes,
    String? imageFilename,
  }) async {
    if (imageBytes != null) {
      final response = await _api.postMultipart(
        ApiConfig.vehiculos,
        fields: data,
        files: [
          MultipartFileData(
            field: 'imagen',
            bytes: imageBytes,
            filename: imageFilename ?? 'imagen.jpg',
          ),
        ],
      );
      final json = _api.parseResponse(response);
      return Vehicle.fromJson(json['vehicle'] ?? json);
    }
    final response = await _api.post(ApiConfig.vehiculos, body: data);
    final json = _api.parseResponse(response);
    return Vehicle.fromJson(json['vehicle'] ?? json);
  }

  Future<Vehicle> updateVehicle(
    int id, {
    required Map<String, dynamic> data,
    Uint8List? imageBytes,
    String? imageFilename,
  }) async {
    if (imageBytes != null) {
      final response = await _api.putMultipart(
        '${ApiConfig.vehiculos}/$id',
        fields: data,
        files: [
          MultipartFileData(
            field: 'imagen',
            bytes: imageBytes,
            filename: imageFilename ?? 'imagen.jpg',
          ),
        ],
      );
      final json = _api.parseResponse(response);
      return Vehicle.fromJson(json['vehicle'] ?? json);
    }
    final response = await _api.put('${ApiConfig.vehiculos}/$id', body: data);
    final json = _api.parseResponse(response);
    return Vehicle.fromJson(json['vehicle'] ?? json);
  }

  Future<void> deleteVehicle(int id) async {
    final response = await _api.delete('${ApiConfig.vehiculos}/$id');
    _api.parseResponse(response);
  }
}