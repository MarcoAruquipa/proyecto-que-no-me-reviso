import 'dart:async';
import 'dart:convert';
import 'dart:io';
import 'dart:typed_data';
import 'package:http/http.dart' as http;
import '../config/api_config.dart';
import 'storage_service.dart';

class ApiService {
  static final ApiService _instance = ApiService._internal();
  factory ApiService() => _instance;
  ApiService._internal();

  final StorageService _storage = StorageService();
  final http.Client _client = http.Client();

  Future<String?> getToken() async {
    return await _storage.getToken();
  }

  Future<Map<String, String>> _headers() async {
    final token = await getToken();
    final headers = <String, String>{
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      // El servidor PHP de Laravel (php artisan serve) cierra mal las
      // conexiones keep-alive en Windows y provoca el error
      // "Connection closed before full header was received".
      // Forzar cierre por petición evita reutilizar conexiones "muertas".
      'Connection': 'close',
    };
    if (token != null) {
      headers['Authorization'] = 'Bearer $token';
    }
    return headers;
  }

  Uri _buildUri(String endpoint, [Map<String, String>? queryParams]) {
    final uri = Uri.parse('${ApiConfig.baseUrl}$endpoint');
    if (queryParams != null && queryParams.isNotEmpty) {
      return uri.replace(queryParameters: queryParams);
    }
    return uri;
  }

  /// Ejecuta una petición capturando errores de red (timeout, sin conexión,
  /// servidor apagado) y los convierte en [ApiException] con mensajes claros.
  Future<http.Response> _guard(Future<http.Response> Function() request) async {
    try {
      return await request().timeout(ApiConfig.timeout);
    } on TimeoutException {
      throw ApiException(
        statusCode: 0,
        message: 'Tiempo de espera agotado. Verifica que el backend esté activo '
            'y que la URL en ApiConfig (${ApiConfig.baseUrl}) sea correcta.',
      );
    } on SocketException catch (e) {
      throw ApiException(
        statusCode: 0,
        message: 'No se pudo conectar con el servidor (${ApiConfig.baseUrl}). '
            'Revisa que XAMPP esté en ejecución, que la IP/puerto en ApiConfig '
            'sean correctos y que el dispositivo esté en la misma red '
            '(${e.osError?.message ?? 'sin conexión'}).',
      );
    } on http.ClientException catch (e) {
      throw ApiException(
        statusCode: 0,
        message: 'Error de conexión con el servidor. '
            'Revisa la configuración en ApiConfig (${e.message}).',
      );
    } on ApiException {
      rethrow;
    } catch (e) {
      throw ApiException(
        statusCode: 0,
        message: 'Error inesperado de red: $e',
      );
    }
  }

  Future<http.Response> get(
    String endpoint, {
    Map<String, String>? queryParams,
  }) async {
    final headers = await _headers();
    final uri = _buildUri(endpoint, queryParams);
    return _guard(() => _client.get(uri, headers: headers));
  }

  Future<http.Response> post(
    String endpoint, {
    Map<String, dynamic>? body,
  }) async {
    final headers = await _headers();
    final uri = _buildUri(endpoint);
    return _guard(
      () => _client.post(uri, headers: headers, body: jsonEncode(body)),
    );
  }

  Future<http.Response> put(
    String endpoint, {
    Map<String, dynamic>? body,
  }) async {
    final headers = await _headers();
    final uri = _buildUri(endpoint);
    return _guard(
      () => _client.put(uri, headers: headers, body: jsonEncode(body)),
    );
  }

  Future<http.Response> delete(String endpoint) async {
    final headers = await _headers();
    final uri = _buildUri(endpoint);
    return _guard(() => _client.delete(uri, headers: headers));
  }

  /// Prueba rápida de conectividad contra un endpoint público del backend
  /// (no requiere token). Devuelve si hay respuesta y un mensaje de ayuda.
  Future<({bool ok, String message})> checkConnection() async {
    try {
      final res = await _getRaw(ApiConfig.catalogo);
      return (
        ok: res.statusCode >= 200 && res.statusCode < 300,
        message:
            'El servidor responde HTTP ${res.statusCode} en ${ApiConfig.baseUrl}',
      );
    } on TimeoutException {
      return (
        ok: false,
        message: 'Sin respuesta en ${ApiConfig.baseUrl} (tiempo de espera).\n'
            'Comprueba que "php artisan serve --host=0.0.0.0 --port=8000" '
            'esté corriendo en la PC.',
      );
    } on SocketException catch (e) {
      return (
        ok: false,
        message: 'No se pudo conectar a ${ApiConfig.baseUrl}.\n'
            '${e.osError?.message ?? 'sin conexión'}\n'
            'Verifica que la PC y el celular estén en la misma red WiFi, '
            'que la IP del PC sea correcta (ipconfig) y que el firewall '
            'permita el puerto 8000.',
      );
    } on http.ClientException catch (e) {
      // El servidor PHP a veces cierra la conexión antes de terminar la
      // cabecera; reintentamos una vez antes de reportar el fallo.
      if (e.message.contains('Connection closed before full header')) {
        try {
          final res = await _getRaw(ApiConfig.catalogo);
          return (
            ok: res.statusCode >= 200 && res.statusCode < 300,
            message:
                'El servidor responde HTTP ${res.statusCode} en ${ApiConfig.baseUrl} '
                '(se corrigió con un reintento)',
          );
        } on http.ClientException {
          return (
            ok: false,
            message: 'El servidor cerró la conexión antes de responder '
                '(XAMPP/artisan inestable). Reinicia el backend con '
                '"php artisan serve --host=0.0.0.0 --port=8000".',
          );
        }
      }
      return (
        ok: false,
        message: 'Error HTTP/red: ${e.message}\nRevisa la URL en ApiConfig.',
      );
    } catch (e) {
      return (ok: false, message: 'Error inesperado: $e');
    }
  }

  /// GET sin gestión de autenticación previa, ideal para diagnósticos.
  Future<http.Response> _getRaw(String endpoint) async {
    final headers = await _headers();
    final uri = _buildUri(endpoint);
    return _client.get(uri, headers: headers).timeout(ApiConfig.timeout);
  }

  /// Envío multipart para subida de archivos (imágenes) sin token en body JSON
  Future<http.Response> postMultipart(
    String endpoint, {
    Map<String, dynamic>? fields,
    List<MultipartFileData>? files,
  }) async {
    return _guardMultipart(
      () async {
        final uri = _buildUri(endpoint);
        final request = http.MultipartRequest('POST', uri)
          ..headers.addAll(await _headers());
        if (fields != null) {
          fields.forEach((key, value) {
            if (value != null) request.fields[key] = value.toString();
          });
        }
        if (files != null) {
          for (final file in files) {
            request.files.add(http.MultipartFile.fromBytes(
              file.field,
              file.bytes,
              filename: file.filename,
            ));
          }
        }
        final streamed = await request.send().timeout(ApiConfig.timeout);
        return await http.Response.fromStream(streamed);
      },
    );
  }

  /// Envío multipart para actualización con archivos
  Future<http.Response> putMultipart(
    String endpoint, {
    Map<String, dynamic>? fields,
    List<MultipartFileData>? files,
  }) async {
    return _guardMultipart(
      () async {
        final uri = _buildUri(endpoint);
        final request = http.MultipartRequest('PUT', uri)
          ..headers.addAll(await _headers());
        if (fields != null) {
          fields.forEach((key, value) {
            if (value != null) request.fields[key] = value.toString();
          });
        }
        if (files != null) {
          for (final file in files) {
            request.files.add(http.MultipartFile.fromBytes(
              file.field,
              file.bytes,
              filename: file.filename,
            ));
          }
        }
        final streamed = await request.send().timeout(ApiConfig.timeout);
        return await http.Response.fromStream(streamed);
      },
    );
  }

  Future<http.Response> _guardMultipart(
    Future<http.Response> Function() request,
  ) async {
    try {
      return await request();
    } on TimeoutException {
      throw ApiException(
        statusCode: 0,
        message: 'Tiempo de espera agotado. Verifica que el backend esté activo '
            'y que la URL en ApiConfig (${ApiConfig.baseUrl}) sea correcta.',
      );
    } on SocketException catch (e) {
      throw ApiException(
        statusCode: 0,
        message: 'No se pudo conectar con el servidor (${ApiConfig.baseUrl}). '
            'Revisa que XAMPP esté en ejecución y que la IP/puerto en ApiConfig '
            'sean correctos (${e.osError?.message ?? 'sin conexión'}).',
      );
    } on http.ClientException catch (e) {
      throw ApiException(
        statusCode: 0,
        message: 'Error de conexión con el servidor. '
            'Revisa la configuración en ApiConfig (${e.message}).',
      );
    } on ApiException {
      rethrow;
    } catch (e) {
      throw ApiException(
        statusCode: 0,
        message: 'Error inesperado de red: $e',
      );
    }
  }

  /// Parsea la respuesta y lanza excepciones para errores HTTP con mensajes claros.
  dynamic parseResponse(http.Response response) {
    if (response.statusCode >= 200 && response.statusCode < 300) {
      if (response.body.isEmpty) return {};
      return jsonDecode(response.body);
    }

    final message = _extractHttpError(response);
    throw ApiException(statusCode: response.statusCode, message: message);
  }

  String _extractHttpError(http.Response response) {
    try {
      final body = jsonDecode(response.body);
      if (body['message'] != null) {
        final raw = body['message'].toString();
        return _translateValidationMessage(raw);
      }
      if (body['error'] != null) return body['error'].toString();
      // Errores de validación de Laravel: { "errors": { "campo": [...] } }
      if (body['errors'] is Map) {
        final errors = body['errors'] as Map;
        if (errors.isNotEmpty) {
          final first = errors.values.first;
          if (first is List && first.isNotEmpty) {
            return first.first.toString();
          }
          return first.toString();
        }
      }
    } catch (_) {
      // cuerpo no JSON
    }

    switch (response.statusCode) {
      case 400:
        return 'Solicitud inválida (400). Revisa los datos enviados.';
      case 401:
        return 'Credenciales incorrectas (401). Verifica tu usuario y contraseña.';
      case 403:
        return 'No tienes permiso para realizar esta acción (403).';
      case 404:
        return 'Recurso no encontrado (404).';
      case 422:
        return 'Datos no válidos (422). Revisa los campos del formulario.';
      case 500:
        return 'Error interno del servidor (500). Intenta de nuevo más tarde.';
      default:
        return 'Error del servidor (${response.statusCode}).';
    }
  }

  /// Convierte los mensajes de validación de Laravel a texto amigable.
  String _translateValidationMessage(String raw) {
    if (raw.startsWith('The ')) {
      final readable = raw
          .replaceAll('The selected', 'El campo')
          .replaceAll(' field', '')
          .replaceAll(' is required.', ' es obligatorio.')
          .replaceAll(' has already been taken.', ' ya está en uso.')
          .replaceAll(' are required.', ' son obligatorios.')
          .replaceAll(' must be a valid email address.', ' debe ser un email válido.')
          .replaceAll(' must be at least 8 characters.', ' debe tener al menos 8 caracteres.')
          .replaceFirst('The ', 'El campo ')
          .replaceFirst('field', '');
      return readable.trim();
    }
    return raw;
  }
}

class ApiException implements Exception {
  final int statusCode;
  final String message;

  ApiException({required this.statusCode, required this.message});

  @override
  String toString() => 'ApiException($statusCode): $message';
}

class MultipartFileData {
  final String field;
  final Uint8List bytes;
  final String filename;

  MultipartFileData({
    required this.field,
    required this.bytes,
    required this.filename,
  });
}