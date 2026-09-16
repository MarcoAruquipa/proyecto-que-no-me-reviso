import 'package:flutter/material.dart';
import '../config/theme.dart';

class StatusBadge extends StatelessWidget {
  final String status;
  final bool small;

  const StatusBadge({super.key, required this.status, this.small = false});

  Color get _color {
    final s = status.toLowerCase();
    if (s.contains('disponible') || s.contains('vendido') || s.contains('ganado')) {
      return AppTheme.successColor;
    }
    if (s.contains('reservado') || s.contains('negociac') || s.contains('proceso')) {
      return Colors.orange;
    }
    return Colors.blueGrey;
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: EdgeInsets.symmetric(
        horizontal: small ? 6 : 10,
        vertical: small ? 2 : 4,
      ),
      decoration: BoxDecoration(
        color: _color.withValues(alpha: 0.15),
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: _color, width: 1),
      ),
      child: Text(
        status,
        style: TextStyle(
          color: _color,
          fontWeight: FontWeight.w600,
          fontSize: small ? 11 : 12,
        ),
      ),
    );
  }
}