import 'package:flutter/foundation.dart';
import '../models/supervision.dart';
import '../services/supervision_service.dart';

class SupervisionProvider extends ChangeNotifier {
  final SupervisionService _service = SupervisionService();

  bool _isLoading = false;
  String? _errorMessage;
  SupervisionData? _data;

  bool get isLoading => _isLoading;
  String? get errorMessage => _errorMessage;
  SupervisionData? get data => _data;

  Future<void> load() async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();
    try {
      _data = await _service.getSupervision();
    } catch (e) {
      _errorMessage = e.toString().replaceAll('ApiException(403): ', '');
    }
    _isLoading = false;
    notifyListeners();
  }
}