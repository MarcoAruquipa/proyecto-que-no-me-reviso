import 'package:flutter/material.dart';
import '../config/theme.dart';

class ErrorView extends StatelessWidget {
  final String message;
  final VoidCallback? onRetry;

  const ErrorView({super.key, required this.message, this.onRetry});

  String get _cleanMessage {
    return message
        .replaceAll('ApiException(): ', '')
        .replaceAll('ApiException(', '')
        .replaceAll('): ', ': ')
        .replaceAll('Exception: ', '');
  }

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(Icons.error_outline, color: AppTheme.errorColor, size: 56),
            const SizedBox(height: 16),
            Text(
              'Ha ocurrido un error',
              style: Theme.of(context).textTheme.titleMedium,
              textAlign: TextAlign.center,
            ),
            const SizedBox(height: 8),
            Text(
              _cleanMessage,
              style: Theme.of(context).textTheme.bodyMedium,
              textAlign: TextAlign.center,
            ),
            if (onRetry != null) ...[
              const SizedBox(height: 16),
              OutlinedButton.icon(
                onPressed: onRetry,
                icon: const Icon(Icons.refresh),
                label: const Text('Reintentar'),
              ),
            ],
          ],
        ),
      ),
    );
  }
}

void showErrorDialog(BuildContext context, String message) {
  showDialog(
    context: context,
    builder: (ctx) => AlertDialog(
      title: const Text('Error'),
      content: Text(
        message
            .replaceAll('ApiException(): ', '')
            .replaceAll('ApiException(', '')
            .replaceAll('): ', ': '),
      ),
      actions: [
        TextButton(onPressed: () => Navigator.pop(ctx), child: const Text('Aceptar')),
      ],
    ),
  );
}

void showSuccessSnackbar(BuildContext context, String message) {
  ScaffoldMessenger.of(context).showSnackBar(
    SnackBar(
      content: Row(
        children: [
          const Icon(Icons.check_circle, color: Colors.white),
          const SizedBox(width: 12),
          Expanded(child: Text(message)),
        ],
      ),
      backgroundColor: AppTheme.successColor,
      behavior: SnackBarBehavior.floating,
    ),
  );
}

void showErrorSnackbar(BuildContext context, String message) {
  ScaffoldMessenger.of(context).showSnackBar(
    SnackBar(
      content: Row(
        children: [
          const Icon(Icons.error, color: Colors.white),
          const SizedBox(width: 12),
          Expanded(
            child: Text(
              message
                  .replaceAll('ApiException(): ', '')
                  .replaceAll('ApiException(', '')
                  .replaceAll('): ', ': '),
            ),
          ),
        ],
      ),
      backgroundColor: AppTheme.errorColor,
      behavior: SnackBarBehavior.floating,
    ),
  );
}