import 'package:flutter/material.dart';
import '../../models/vehicle.dart';
import '../../utils/formatters.dart';
import '../../widgets/status_badge.dart';

class VehicleDetailView extends StatelessWidget {
  final Vehicle vehicle;

  const VehicleDetailView({super.key, required this.vehicle});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Detalle del vehículo'),
        actions: [
          IconButton(
            icon: const Icon(Icons.edit_outlined),
            onPressed: () {
              Navigator.pushNamed(
                context,
                '/vehiculos/form',
                arguments: {
                  'vehicle': vehicle,
                  'isEditing': true,
                },
              );
            },
          ),
        ],
      ),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          if (vehicle.imagenUrl != null) ...[
            ClipRRect(
              borderRadius: BorderRadius.circular(12),
              child: Image.network(
                vehicle.imagenUrl!,
                height: 220,
                width: double.infinity,
                fit: BoxFit.cover,
                errorBuilder: (_, __, ___) => Container(
                  height: 220,
                  color: Colors.grey.shade200,
                  child: const Icon(Icons.directions_car, size: 64),
                ),
              ),
            ),
            const SizedBox(height: 12),
          ],
          _SectionCard(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Expanded(
                      child: Text(
                        vehicle.displayName,
                        style: const TextStyle(
                          fontSize: 22,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                    StatusBadge(status: vehicle.estado ?? 'Desconocido'),
                  ],
                ),
                const SizedBox(height: 12),
                Text(
                  Formatters.currency(vehicle.precio),
                  style: TextStyle(
                    fontSize: 20,
                    fontWeight: FontWeight.bold,
                    color: Theme.of(context).colorScheme.primary,
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 12),
          _SectionCard(
            child: Column(
              children: [
                _InfoRow(label: 'Marca', value: vehicle.marca ?? '-'),
                _InfoRow(label: 'Modelo', value: vehicle.modelo ?? '-'),
                _InfoRow(label: 'Año', value: vehicle.anio?.toString() ?? '-'),
                _InfoRow(label: 'Placa', value: vehicle.placa ?? '-'),
                _InfoRow(label: 'Color', value: vehicle.color ?? '-'),
                _InfoRow(label: 'Estado', value: vehicle.estado ?? '-'),
              ],
            ),
          ),
          if (vehicle.descripcion != null && vehicle.descripcion!.isNotEmpty) ...[
            const SizedBox(height: 12),
            _SectionCard(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'Descripción',
                    style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
                  ),
                  const SizedBox(height: 8),
                  Text(vehicle.descripcion!),
                ],
              ),
            ),
          ],
          if (vehicle.user != null) ...[
            const SizedBox(height: 12),
            _SectionCard(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'Registrado por',
                    style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
                  ),
                  const SizedBox(height: 8),
                  Text(vehicle.user!['name'] ?? ''),
                ],
              ),
            ),
          ],
        ],
      ),
    );
  }
}

class _SectionCard extends StatelessWidget {
  final Widget child;

  const _SectionCard({required this.child});

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: EdgeInsets.zero,
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: child,
      ),
    );
  }
}

class _InfoRow extends StatelessWidget {
  final String label;
  final String value;

  const _InfoRow({required this.label, required this.value});

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 90,
            child: Text(
              label,
              style: const TextStyle(
                color: Colors.grey,
                fontWeight: FontWeight.w500,
              ),
            ),
          ),
          Expanded(
            child: Text(value, style: const TextStyle(fontWeight: FontWeight.w600)),
          ),
        ],
      ),
    );
  }
}