import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../providers/dashboard_provider.dart';
import '../../providers/vehicle_provider.dart';
import '../../providers/client_provider.dart';
import '../../providers/sale_provider.dart';
import '../../widgets/loading_indicator.dart';
import '../../utils/formatters.dart';

class DashboardView extends StatefulWidget {
  const DashboardView({super.key});

  @override
  State<DashboardView> createState() => _DashboardViewState();
}

class _DashboardViewState extends State<DashboardView> {
  @override
  void initState() {
    super.initState();
    _loadData();
  }

  Future<void> _loadData() async {
    final dashboardProvider = context.read<DashboardProvider>();
    final vehicleProvider = context.read<VehicleProvider>();
    final clientProvider = context.read<ClientProvider>();
    final saleProvider = context.read<SaleProvider>();
    await dashboardProvider.loadStats();
    await vehicleProvider.loadVehicles();
    await clientProvider.loadClients();
    await saleProvider.loadSales();
  }

  @override
  Widget build(BuildContext context) {
    final dashboardProvider = context.watch<DashboardProvider>();
    return RefreshIndicator(
      onRefresh: _loadData,
      child: dashboardProvider.isLoading && dashboardProvider.stats == null
          ? ListView(
              physics: const AlwaysScrollableScrollPhysics(),
              children: const [SizedBox(height: 120), LoadingIndicator()],
            )
          : dashboardProvider.errorMessage != null &&
                  dashboardProvider.stats == null
              ? ListView(
                  physics: const AlwaysScrollableScrollPhysics(),
                  children: [
                    const SizedBox(height: 60),
                    Center(
                      child: Column(
                        children: [
                          const Icon(Icons.error_outline,
                              color: Colors.red, size: 56),
                          const SizedBox(height: 16),
                          const Text('No se pudo cargar el panel'),
                          const SizedBox(height: 8),
                          Text(
                            dashboardProvider.errorMessage!,
                            style: const TextStyle(color: Colors.grey),
                            textAlign: TextAlign.center,
                          ),
                          const SizedBox(height: 16),
                          OutlinedButton(onPressed: _loadData, child: const Text('Reintentar')),
                        ],
                      ),
                    ),
                  ],
                )
              : _buildContent(dashboardProvider),
    );
  }

  Widget _buildContent(DashboardProvider provider) {
    final stats = provider.stats;
    if (stats == null) {
      return const LoadingIndicator();
    }
    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        Row(
          children: [
            Expanded(
              child: _StatCard(
                label: 'Vehículos',
                value: '${stats.totalVehiculos}',
                icon: Icons.directions_car,
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: _StatCard(
                label: 'Clientes',
                value: '${stats.totalClientes}',
                icon: Icons.people,
              ),
            ),
          ],
        ),
        const SizedBox(height: 12),
        Row(
          children: [
            Expanded(
              child: _StatCard(
                label: 'Ventas',
                value: '${stats.totalVentas}',
                icon: Icons.point_of_sale,
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: _StatCard(
                label: 'Ingresos',
                value: Formatters.currency(stats.ingresosTotales),
                icon: Icons.attach_money,
              ),
            ),
          ],
        ),
        if (stats.vehiculosRecientes.isNotEmpty) ...[
          const SizedBox(height: 24),
          const Text(
            'Vehículos recientes',
            style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 8),
          ...stats.vehiculosRecientes.map(
            (v) => Card(
              child: ListTile(
                leading: const Icon(Icons.directions_car),
                title: Text('${v['marca'] ?? ''} ${v['modelo'] ?? ''}'),
                subtitle: Text(v['estado'] ?? ''),
                trailing: Text(
                  v['precio'] != null
                      ? 'Bs ${double.tryParse(v['precio'].toString())?.toStringAsFixed(2) ?? '0.00'}'
                      : '',
                  style: const TextStyle(fontWeight: FontWeight.bold),
                ),
              ),
            ),
          ),
        ],
        if (stats.ventasRecientes.isNotEmpty) ...[
          const SizedBox(height: 24),
          const Text(
            'Ventas recientes',
            style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 8),
          ...stats.ventasRecientes.map(
            (s) => Card(
              child: ListTile(
                leading: const Icon(Icons.sell_outlined, color: Colors.green),
                title: Text(s['vehicle'] != null
                    ? '${s['vehicle']['marca'] ?? ''} ${s['vehicle']['modelo'] ?? ''}'
                    : 'Venta #${s['id']}'),
                subtitle: Text(
                  s['client'] != null ? s['client']['nombre'] ?? '' : '',
                ),
                trailing: Text(
                  s['precio_venta'] != null
                      ? 'Bs ${double.tryParse(s['precio_venta'].toString())?.toStringAsFixed(2) ?? '0.00'}'
                      : '',
                  style: const TextStyle(fontWeight: FontWeight.bold),
                ),
              ),
            ),
          ),
        ],
      ],
    );
  }
}

class _StatCard extends StatelessWidget {
  final String label;
  final String value;
  final IconData icon;

  const _StatCard({
    required this.label,
    required this.value,
    required this.icon,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: EdgeInsets.zero,
      child: Container(
        decoration: BoxDecoration(
          gradient: const LinearGradient(
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
            colors: [Color(0xFF4B0082), Color(0xFF2B1552)],
          ),
          borderRadius: BorderRadius.circular(12),
        ),
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Container(
              padding: const EdgeInsets.all(8),
              decoration: BoxDecoration(
                color: Colors.white.withValues(alpha: 0.15),
                shape: BoxShape.circle,
              ),
              child: Icon(icon, color: Colors.white, size: 22),
            ),
            const SizedBox(height: 12),
            Text(
              value,
              style: const TextStyle(
                fontSize: 20,
                fontWeight: FontWeight.bold,
                color: Colors.white,
              ),
              overflow: TextOverflow.ellipsis,
            ),
            const SizedBox(height: 4),
            Text(
              label,
              style: TextStyle(
                fontSize: 13,
                color: Colors.white.withValues(alpha: 0.85),
              ),
            ),
          ],
        ),
      ),
    );
  }
}