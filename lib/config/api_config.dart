/// Configuración centralizada de la API.
///
/// El backend Laravel (maletin_movilidades) debe estar en ejecución con:
///   cd C:\xampp\htdocs\proyect_m\maletin_movilidades
///   php artisan serve --host=0.0.0.0 --port=8000
///
/// Con eso la API queda en:
///   `http://<HOST>:8000/api`
///
/// ── Alternativa (Apache/XAMPP) ──────────────────────────────────────────
/// Solo si creas un VirtualHost apuntando a `.../maletin_movilidades/public`
/// (document root), tu vhost quedaría en:
///   `http://192.168.101.9/api`
/// En ese caso usa [ApiEnvironment.apacheVhost] y deja [port]=80.
class ApiConfig {
  // ===================== CONFIGURACIÓN DE ENTORNO =====================

  /// Entorno activo. Cambia AQUÍ según dónde corras la app:
  ///   - [ApiEnvironment.androidEmulator] -> apunta a 10.0.2.2 (emulador Android).
  ///   - [ApiEnvironment.physicalDevice]  -> apunta a [physicalDeviceIp] (celular real por WiFi).
  ///   - [ApiEnvironment.apacheVhost]     -> Apache/XAMPP con VirtualHost (puerto 80).
  ///   - [ApiEnvironment.production]      -> apunta a [productionBaseUrl].
  static const ApiEnvironment environment = ApiEnvironment.physicalDevice;

  /// IP local de tu PC en la red WiFi (obténla con `ipconfig`).
  /// Se usa con [ApiEnvironment.physicalDevice].
  /// Nota: cambia según el router (casa vs. universidad). La IP actual
  /// corresponde a tu casa (192.168.1.10). Para evitar depender de la IP,
  /// despliega el backend en un servidor y usa [ApiEnvironment.production].
  static const String physicalDeviceIp = '192.168.1.10';

  /// Puerto del backend:
  ///   - 8000 -> `php artisan serve --port=8000` (recomendado).
  ///   - 80   -> Apache/XAMPP con VirtualHost configurado.
  static const int port = 8000;

  /// Prefijo de ruta del backend:
  ///   - '' (vacío)                    -> `php artisan serve` (recomendado).
  ///   - '/maletin_movilidades/public' -> Apache sirviendo desde htdocs.
  static const String pathPrefix = '';

  /// URL base final de producción (cuando despliegues la API en un host).
  static const String productionBaseUrl = 'https://tu-dominio.com/api';

  /// Tiempo máximo de espera por petición HTTP.
  static const Duration timeout = Duration(seconds: 20);

  // ============================ URL BASE ==============================

  static String get baseUrl {
    switch (environment) {
      case ApiEnvironment.production:
        return productionBaseUrl;
      case ApiEnvironment.androidEmulator:
        return 'http://10.0.2.2:$port$pathPrefix/api';
      case ApiEnvironment.physicalDevice:
        return 'http://$physicalDeviceIp:$port$pathPrefix/api';
      case ApiEnvironment.apacheVhost:
        return 'http://$physicalDeviceIp:$port$pathPrefix/api';
    }
  }

  /// Origen del servidor (sin el sufijo `/api`) para construir URLs de
  /// archivos e imágenes del storage público (`.../storage/<ruta>`).
  static String get storageBaseUrl {
    return baseUrl.endsWith('/api') ? baseUrl.substring(0, baseUrl.length - 4) : baseUrl;
  }

  // ============================ ENDPOINTS =============================

  // Autenticación
  static const String login = '/login';
  static const String register = '/register';
  static const String logout = '/logout';
  static const String profile = '/profile';
  static const String changePassword = '/profile/password';

  // Vehículos
  static const String vehiculos = '/vehiculos';

  // Clientes
  static const String clientes = '/clientes';

  // Ventas y comisiones
  static const String ventas = '/ventas';
  static const String comisiones = '/comisiones';

  // Catálogo público
  static const String catalogo = '/catalogo';

  // Maletín
  static const String maletin = '/maletin';

  // Dashboard
  static const String dashboard = '/dashboard';

  // Supervisión (Jefe de Sucursal / Administrador)
  static const String supervision = '/supervision';
}

enum ApiEnvironment { androidEmulator, physicalDevice, apacheVhost, production }