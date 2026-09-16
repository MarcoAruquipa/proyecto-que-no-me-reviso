import 'package:intl/intl.dart';

class Formatters {
  static final NumberFormat _currency = NumberFormat.currency(
    locale: 'es',
    symbol: 'Bs ',
    decimalDigits: 2,
  );

  static final DateFormat _date = DateFormat('dd/MM/yyyy');
  static final DateFormat _dateTime = DateFormat('dd/MM/yyyy HH:mm');

  static String currency(double? value) {
    return _currency.format(value ?? 0);
  }

  static String date(String? value) {
    if (value == null || value.isEmpty) return '-';
    try {
      final parsed = DateTime.parse(value);
      return _date.format(parsed);
    } catch (_) {
      return value;
    }
  }

  static String dateTime(String? value) {
    if (value == null || value.isEmpty) return '-';
    try {
      final parsed = DateTime.parse(value);
      return _dateTime.format(parsed);
    } catch (_) {
      return value;
    }
  }

  static String plural(int count, String singular, String plural) {
    return count == 1 ? singular : plural;
  }
}