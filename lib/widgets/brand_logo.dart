import 'package:flutter/material.dart';

/// Identidad visual de Pegaso Motors.
///
/// Muestra `assets/images/logo.png` si está disponible (declarado en
/// `pubspec.yaml`) y, si no existe, cae al monograma por defecto.
class BrandLogo extends StatelessWidget {
  final bool showAsset;
  final double size;
  final bool showTagline;

  /// Si es `true`, el texto de la marca se pinta en blanco (ideal para fondos
  /// morados oscuros como la pantalla de login).
  final bool light;

  const BrandLogo({
    super.key,
    this.showAsset = true,
    this.size = 120,
    this.showTagline = true,
    this.light = false,
  });

  static const Color _primary = Color(0xFF4B0082);
  static const Color _dark = Color(0xFF2B1552);

  @override
  Widget build(BuildContext context) {
    final scale = size / 120;
    return Column(
      mainAxisSize: MainAxisSize.min,
      children: [
        Container(
          width: size,
          height: size,
          decoration: BoxDecoration(
            gradient: const LinearGradient(
              begin: Alignment.topLeft,
              end: Alignment.bottomRight,
              colors: [_primary, _dark],
            ),
            borderRadius: BorderRadius.circular(28 * scale),
            boxShadow: [
              BoxShadow(
                color: _dark.withValues(alpha: 0.35),
                blurRadius: 20 * scale,
                offset: Offset(0, 8 * scale),
              ),
            ],
          ),
          child: Stack(
            alignment: Alignment.center,
            children: [
              // Ala decorativa al fondo (evoca al Pegaso alado)
              Positioned(
                top: -6 * scale,
                right: -4 * scale,
                child: Icon(
                  Icons.airlines,
                  size: 46 * scale,
                  color: Colors.white.withValues(alpha: 0.12),
                ),
              ),
              if (showAsset)
                ClipRRect(
                  borderRadius: BorderRadius.circular(20 * scale),
                  child: Image.asset(
                    'assets/images/logo.png',
                    width: size * 0.72,
                    height: size * 0.72,
                    fit: BoxFit.contain,
                    errorBuilder: (_, __, ___) =>
                        const BrandMonogram(showAsset: false),
                  ),
                )
              else
                const BrandMonogram(showAsset: false),
            ],
          ),
        ),
        SizedBox(height: 18 * scale),
        Text(
          'PEGASO',
          style: TextStyle(
            fontSize: 26 * scale,
            fontWeight: FontWeight.w800,
            letterSpacing: 6 * scale,
            color: light ? Colors.white : _dark,
            height: 1,
          ),
        ),
        SizedBox(height: 4 * scale),
        Text(
          'MOTORS',
          style: TextStyle(
            fontSize: 12 * scale,
            fontWeight: FontWeight.w600,
            letterSpacing: 10 * scale,
            color: light ? Colors.white.withValues(alpha: 0.92) : _primary,
            height: 1,
          ),
        ),
        if (showTagline) ...[
          SizedBox(height: 10 * scale),
          Text(
            'VEHÍCULOS · CLIENTES · VENTAS',
            style: TextStyle(
              fontSize: 9 * scale,
              letterSpacing: 2.5 * scale,
              color: light
                  ? Colors.white.withValues(alpha: 0.75)
                  : Colors.grey.shade600,
              height: 1,
            ),
          ),
        ],
      ],
    );
  }
}

/// Monograma por defecto de la marca (ícono vectorial incluido en Material).
class BrandMonogram extends StatelessWidget {
  final bool showAsset;

  const BrandMonogram({super.key, this.showAsset = false});

  @override
  Widget build(BuildContext context) {
    return Column(
      mainAxisSize: MainAxisSize.min,
      children: [
        const Icon(
          Icons.directions_car_filled,
          color: Colors.white,
          size: 34,
        ),
        const SizedBox(height: 2),
        Text(
          'PM',
          style: TextStyle(
            color: Colors.white.withValues(alpha: 0.95),
            fontSize: 12,
            fontWeight: FontWeight.bold,
            letterSpacing: 2,
          ),
        ),
      ],
    );
  }
}