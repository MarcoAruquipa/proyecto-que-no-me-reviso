import 'package:flutter/material.dart';
import '../../models/client.dart';
import '../../widgets/status_badge.dart';

class ClientDetailView extends StatelessWidget {
  final Client client;

  const ClientDetailView({super.key, required this.client});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Detalle de cliente'),
        actions: [
          IconButton(
            icon: const Icon(Icons.edit_outlined),
            onPressed: () {
              Navigator.pushNamed(
                context,
                '/clientes/form',
                arguments: {
                  'client': client,
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
          Card(
            margin: EdgeInsets.zero,
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      Expanded(
                        child: Text(
                          client.nombre ?? 'Sin nombre',
                          style: const TextStyle(
                            fontSize: 22,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ),
                      StatusBadge(status: client.estado ?? 'Desconocido'),
                    ],
                  ),
                ],
              ),
            ),
          ),
          const SizedBox(height: 12),
          Card(
            margin: EdgeInsets.zero,
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                children: [
                  _InfoRow(label: 'CI', value: client.ci ?? '-'),
                  _InfoRow(label: 'Teléfono', value: client.telefono ?? '-'),
                  _InfoRow(label: 'Email', value: client.email ?? '-'),
                  _InfoRow(label: 'Dirección', value: client.direccion ?? '-'),
                  _InfoRow(
                    label: 'Vehículo de interés',
                    value: client.vehiculoInteres ?? '-',
                  ),
                  _InfoRow(
                    label: 'Método de pago',
                    value: client.metodoPago ?? '-',
                  ),
                  _InfoRow(label: 'Fuente', value: client.fuente ?? '-'),
                ],
              ),
            ),
          ),
          if (client.notas != null && client.notas!.isNotEmpty) ...[
            const SizedBox(height: 12),
            Card(
              margin: EdgeInsets.zero,
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text(
                      'Notas',
                      style:
                          TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
                    ),
                    const SizedBox(height: 8),
                    Text(client.notas!),
                  ],
                ),
              ),
            ),
          ],
          if (client.sales != null && client.sales!.isNotEmpty) ...[
            const SizedBox(height: 12),
            Card(
              margin: EdgeInsets.zero,
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text(
                      'Compras',
                      style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
                    ),
                    const SizedBox(height: 8),
                    ...client.sales!.map(
                      (s) => ListTile(
                        contentPadding: EdgeInsets.zero,
                        leading: const Icon(Icons.sell_outlined),
                        title: Text(
                          s['vehicle'] != null
                              ? '${s['vehicle']['marca'] ?? ''} ${s['vehicle']['modelo'] ?? ''}'
                              : 'Venta #${s['id']}',
                        ),
                        trailing: Text(
                          s['precio_venta'] != null
                              ? 'Bs ${double.tryParse(s['precio_venta'].toString())?.toStringAsFixed(2)}'
                              : '',
                          style: const TextStyle(fontWeight: FontWeight.bold),
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ],
        ],
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
            width: 130,
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