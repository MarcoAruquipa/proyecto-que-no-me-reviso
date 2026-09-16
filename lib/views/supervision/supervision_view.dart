import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../providers/supervision_provider.dart';
import '../../models/supervision.dart';
import '../../widgets/loading_indicator.dart';
import '../../utils/formatters.dart';

class SupervisionView extends StatefulWidget {
  const SupervisionView({super.key});

  @override
  State<SupervisionView> createState() => _SupervisionViewState();
}

class _SupervisionViewState extends State<SupervisionView> {
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      context.read<SupervisionProvider>().load();
    });
  }

  Future<void> _refresh() async {
    await context.read<SupervisionProvider>().load();
  }

  @override
  Widget build(BuildContext context) {
    final provider = context.watch<SupervisionProvider>();
    return Scaffold(
      appBar: AppBar(title: const Text('Supervisión de ejecutivos')),
      body: provider.isLoading && provider.data == null
          ? const LoadingIndicator()
          : provider.errorMessage != null && provider.data == null
              ? _errorView(context, provider.errorMessage!)
              : _content(context, provider.data!),
    );
  }

  Widget _errorView(BuildContext context, String message) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(Icons.verified_user_outlined,
                color: Colors.red, size: 56),
            const SizedBox(height: 16),
            Text(message, textAlign: TextAlign.center),
            const SizedBox(height: 16),
            OutlinedButton(onPressed: _refresh, child: const Text('Reintentar')),
          ],
        ),
      ),
    );
  }

  Widget _content(BuildContext context, SupervisionData data) {
    return RefreshIndicator(
      onRefresh: _refresh,
      child: ListView(
        physics: const AlwaysScrollableScrollPhysics(),
        padding: const EdgeInsets.all(16),
        children: [
          _summarySection(data),
          const SizedBox(height: 24),
          const Text(
            'Ejecutivos de ventas',
            style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 8),
          if (data.ejecutivos.isEmpty)
            const Card(
              child: Padding(
                padding: EdgeInsets.all(24),
                child: Center(child: Text('No hay ejecutivos registrados')),
              ),
            )
          else
            ...data.ejecutivos.map(
              (e) => Padding(
                padding: const EdgeInsets.only(bottom: 8),
                child: _EjecutivoCard(ejecutivo: e),
              ),
            ),
        ],
      ),
    );
  }

  Widget _summarySection(SupervisionData data) {
    return Column(
      children: [
        Row(
          children: [
            Expanded(
              child: _SummaryCard(
                label: 'Ventas totales',
                value: '${data.totalVentas}',
                icon: Icons.sell_outlined,
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: _SummaryCard(
                label: 'Comisiones',
                value: Formatters.currency(data.totalComisiones),
                icon: Icons.percent,
              ),
            ),
          ],
        ),
        const SizedBox(height: 12),
        Row(
          children: [
            Expanded(
              child: _SummaryCard(
                label: 'Clientes',
                value: '${data.totalClientes}',
                icon: Icons.people_outline,
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: _SummaryCard(
                label: 'Documentos',
                value: '${data.totalDocumentos}',
                icon: Icons.insert_drive_file_outlined,
              ),
            ),
          ],
        ),
        const SizedBox(height: 12),
        Card(
          margin: EdgeInsets.zero,
          child: Padding(
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  'Contratos: ${data.totalFirmados} firmados · '
                  '${data.totalPendientes} pendientes',
                  style: const TextStyle(fontWeight: FontWeight.bold),
                ),
                const SizedBox(height: 8),
                ClipRRect(
                  borderRadius: BorderRadius.circular(4),
                  child: LinearProgressIndicator(
                    value:
                        data.totalContratos > 0 ? data.pctFirmados / 100 : 0,
                    minHeight: 8,
                    backgroundColor: Colors.grey.shade200,
                    valueColor: const AlwaysStoppedAnimation(
                      Color(0xFF4B0082),
                    ),
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  'Avance de firmas: ${data.pctFirmados.toStringAsFixed(1)}%',
                  style: TextStyle(fontSize: 13, color: Colors.grey.shade700),
                ),
              ],
            ),
          ),
        ),
      ],
    );
  }
}

class _SummaryCard extends StatelessWidget {
  final String label;
  final String value;
  final IconData icon;

  const _SummaryCard({
    required this.label,
    required this.value,
    required this.icon,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: EdgeInsets.zero,
      child: Container(
        padding: const EdgeInsets.all(14),
        decoration: BoxDecoration(
          gradient: const LinearGradient(
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
            colors: [Color(0xFF4B0082), Color(0xFF2B1552)],
          ),
          borderRadius: BorderRadius.circular(12),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Icon(icon, color: Colors.white, size: 22),
            const SizedBox(height: 10),
            Text(
              value,
              style: const TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
                color: Colors.white,
              ),
              overflow: TextOverflow.ellipsis,
            ),
            const SizedBox(height: 2),
            Text(
              label,
              style: TextStyle(
                fontSize: 12,
                color: Colors.white.withValues(alpha: 0.85),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class _EjecutivoCard extends StatelessWidget {
  final SupervisionEjecutivo ejecutivo;

  const _EjecutivoCard({required this.ejecutivo});

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: EdgeInsets.zero,
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                CircleAvatar(
                  backgroundColor: const Color(0xFF4B0082),
                  child: Text(
                    ejecutivo.nombre.substring(0, 1).toUpperCase(),
                    style: const TextStyle(color: Colors.white, fontSize: 18),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        ejecutivo.nombre,
                        style: const TextStyle(
                          fontSize: 15,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      if (ejecutivo.sucursal.isNotEmpty)
                        Text(
                          'Sucursal: ${ejecutivo.sucursal}',
                          style: TextStyle(
                            fontSize: 12,
                            color: Colors.grey.shade600,
                          ),
                        ),
                    ],
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),
            Wrap(
              spacing: 16,
              runSpacing: 8,
              children: [
                _MetricChip(
                  icon: Icons.sell_outlined,
                  text: '${ejecutivo.ventas} ventas',
                ),
                _MetricChip(
                  icon: Icons.attach_money,
                  text: 'Bs ${ejecutivo.monto.toStringAsFixed(0)}',
                ),
                _MetricChip(
                  icon: Icons.percent,
                  text: 'Bs ${ejecutivo.comision.toStringAsFixed(0)}',
                ),
                _MetricChip(
                  icon: Icons.people_outline,
                  text:
                      '${ejecutivo.clientesAtendidos} clientes (${ejecutivo.pctClientes.toStringAsFixed(1)}%)',
                ),
                _MetricChip(
                  icon: Icons.insert_drive_file_outlined,
                  text: '${ejecutivo.documentos} documentos',
                ),
                _MetricChip(
                  icon: Icons.description_outlined,
                  text:
                      'Contratos: ${ejecutivo.firmados} firmados · ${ejecutivo.pendientes} pendientes',
                ),
              ],
            ),
            const SizedBox(height: 12),
            Row(
              children: [
                Expanded(
                  child: ClipRRect(
                    borderRadius: BorderRadius.circular(4),
                    child: LinearProgressIndicator(
                      value: ejecutivo.contratos > 0 ? ejecutivo.avance / 100 : 0,
                      minHeight: 6,
                      backgroundColor: Colors.grey.shade200,
                      valueColor: const AlwaysStoppedAnimation(
                        Color(0xFF4B0082),
                      ),
                    ),
                  ),
                ),
                const SizedBox(width: 10),
                Text(
                  'Avance ${ejecutivo.avance.toStringAsFixed(0)}%',
                  style: const TextStyle(
                    fontWeight: FontWeight.bold,
                    fontSize: 13,
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}

class _MetricChip extends StatelessWidget {
  final IconData icon;
  final String text;

  const _MetricChip({required this.icon, required this.text});

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisSize: MainAxisSize.min,
      children: [
        Icon(icon, size: 15, color: Theme.of(context).colorScheme.primary),
        const SizedBox(width: 4),
        Text(text, style: const TextStyle(fontSize: 12.5)),
      ],
    );
  }
}