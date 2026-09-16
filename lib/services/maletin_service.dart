import 'dart:typed_data';
import 'api_service.dart';
import '../config/api_config.dart';
import '../models/maletin.dart';

class MaletinService {
  final ApiService _api = ApiService();

  Future<Maletin> getMaletinDetail() async {
    final response = await _api.get('${ApiConfig.maletin}/detalle');
    final data = _api.parseResponse(response);
    return Maletin.fromJson(data['maletin'] ?? data);
  }

  Future<Maletin> getPublicMaletin() async {
    final response = await _api.get(ApiConfig.maletin);
    final data = _api.parseResponse(response);
    return Maletin.fromJson(data['maletin'] ?? data);
  }

  Future<Maletin> updateMaletin(
    Map<String, dynamic> data, {
    Uint8List? logoBytes,
    String? logoFilename,
    Uint8List? bannerBytes,
    String? bannerFilename,
  }) async {
    final files = <MultipartFileData>[];
    if (logoBytes != null) {
      files.add(MultipartFileData(
        field: 'logo',
        bytes: logoBytes,
        filename: logoFilename ?? 'logo.png',
      ));
    }
    if (bannerBytes != null) {
      files.add(MultipartFileData(
        field: 'banner',
        bytes: bannerBytes,
        filename: bannerFilename ?? 'banner.png',
      ));
    }
    if (files.isNotEmpty) {
      final response =
          await _api.putMultipart(ApiConfig.maletin, fields: data, files: files);
      final json = _api.parseResponse(response);
      return Maletin.fromJson(json['maletin'] ?? json);
    }
    final response = await _api.put(ApiConfig.maletin, body: data);
    final json = _api.parseResponse(response);
    return Maletin.fromJson(json['maletin'] ?? json);
  }
}