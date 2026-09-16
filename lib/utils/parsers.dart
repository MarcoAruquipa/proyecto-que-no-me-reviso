/// Convierte cualquier valor JSON (int, String, double, null) a `int?` de forma segura.
///
/// Evita el error de tipos `type 'String' is not a subtype of type 'int?'`
/// cuando el backend devuelve números como texto (p. ej. `"2024"`).
int? parseInt(dynamic value) {
  if (value == null) return null;
  if (value is int) return value;
  if (value is double) return value.toInt();
  return int.tryParse(value.toString().trim());
}

/// Convierte cualquier valor JSON (double, int, String, null) a `double?` de forma segura.
///
/// Útil porque Eloquent/Laravel devuelve las columnas decimales como texto
/// (p. ej. `"25000.00"`).
double? parseDouble(dynamic value) {
  if (value == null) return null;
  if (value is double) return value;
  if (value is int) return value.toDouble();
  return double.tryParse(value.toString().trim());
}

/// Versiones no-nulas para modelos con campos obligatorios.
int parseIntOr(dynamic value, int fallback) => parseInt(value) ?? fallback;
double parseDoubleOr(dynamic value, double fallback) =>
    parseDouble(value) ?? fallback;