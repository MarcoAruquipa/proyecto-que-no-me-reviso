import 'package:flutter/foundation.dart';
import '../models/maletin.dart';
import '../services/maletin_service.dart';

class MaletinProvider extends ChangeNotifier {
  final MaletinService _service = MaletinService();

  bool _isLoading = false;
  String? _errorMessage;
  Maletin? _maletin;

  bool get isLoading => _isLoading;
  String? get errorMessage => _errorMessage;
  Maletin? get maletin => _maletin;

  Future<void> loadMaletin() async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();
    try {
      _maletin = await _service.getMaletinDetail();
    } catch (e) {
      _errorMessage = e.toString();
    }
    _isLoading = false;
    notifyListeners();
  }

  Future<bool> updateMaletin(
    Map<String, dynamic> data, {
    Uint8List? logoBytes,
    String? logoFilename,
    Uint8List? bannerBytes,
    String? bannerFilename,
  }) async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();
    try {
      _maletin = await _service.updateMaletin(
        data,
        logoBytes: logoBytes,
        logoFilename: logoFilename,
        bannerBytes: bannerBytes,
        bannerFilename: bannerFilename,
      );
      _isLoading = false;
      notifyListeners();
      return true;
    } catch (e) {
      _errorMessage = e.toString();
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  void clearError() {
    _errorMessage = null;
    notifyListeners();
  }
}