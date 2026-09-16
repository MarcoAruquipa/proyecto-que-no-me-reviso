import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../providers/vehicle_provider.dart';
import '../../models/vehicle.dart';
import '../../widgets/loading_indicator.dart';
import '../../widgets/error_view.dart';
import '../../widgets/empty_view.dart';
import '../../widgets/status_badge.dart';
import '../../widgets/confirm_dialog.dart';
import '../../utils/formatters.dart';

class VehicleListView extends StatefulWidget {
  const VehicleListView({super.key});

  @override
  State<VehicleListView> createState() => _VehicleListViewState();
}

class _VehicleListViewState extends State<VehicleListView> {
  final _searchController = TextEditingController();
  final _scrollController = ScrollController();

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      context.read<VehicleProvider>().loadVehicles();
    });
    _scrollController.addListener(_onScroll);
  }

  @override
  void dispose() {
    _searchController.dispose();
    _scrollController.dispose();
    super.dispose();
  }

  void _onScroll() {
    if (_scrollController.position.pixels >=
        _scrollController.position.maxScrollExtent - 200) {
      context.read<VehicleProvider>().loadNextPage();
    }
  }

  Future<void> _deleteVehicle(Vehicle vehicle) async {
    final confirmed = await ConfirmDialog.show(
      context,
      title: 'Eliminar vehículo',
      message: '¿Estás seguro de eliminar ${vehicle.displayName}?',
    );
    if (confirmed) {
      if (!mounted) return;
      final result = await context.read<VehicleProvider>().deleteVehicle(vehicle.id!);
      if (result) {
        if (!mounted) return;
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Vehículo eliminado')),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final provider = context.watch<VehicleProvider>();
    return Scaffold(
      appBar: AppBar(title: const Text('Vehículos')),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () {
          Navigator.pushNamed(context, '/vehiculos/form');
        },
        icon: const Icon(Icons.add),
        label: const Text('Nuevo'),
      ),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(12),
            child: TextField(
              controller: _searchController,
              decoration: InputDecoration(
                hintText: 'Buscar por marca, modelo o placa...',
                prefixIcon: const Icon(Icons.search),
                suffixIcon: IconButton(
                  icon: const Icon(Icons.clear),
                  onPressed: () {
                    _searchController.clear();
                    context.read<VehicleProvider>().search('');
                  },
                ),
              ),
              onSubmitted: (value) {
                context.read<VehicleProvider>().search(value.trim());
              },
            ),
          ),
          SizedBox(
            height: 44,
            child: ListView(
              scrollDirection: Axis.horizontal,
              padding: const EdgeInsets.symmetric(horizontal: 12),
              children: [
                _FilterChip(
                  label: 'Todos',
                  selected: provider.estadoFilter.isEmpty,
                  onTap: () => context.read<VehicleProvider>().setEstadoFilter(null),
                ),
                _FilterChip(
                  label: 'Disponible',
                  selected: provider.estadoFilter == 'Disponible',
                  onTap: () => context.read<VehicleProvider>().setEstadoFilter('Disponible'),
                ),
                _FilterChip(
                  label: 'Reservado',
                  selected: provider.estadoFilter == 'Reservado',
                  onTap: () => context.read<VehicleProvider>().setEstadoFilter('Reservado'),
                ),
                _FilterChip(
                  label: 'Vendido',
                  selected: provider.estadoFilter == 'Vendido',
                  onTap: () => context.read<VehicleProvider>().setEstadoFilter('Vendido'),
                ),
              ],
            ),
          ),
          Expanded(
            child: provider.isLoading && provider.vehicles.isEmpty
                ? const LoadingIndicator()
                : provider.errorMessage != null && provider.vehicles.isEmpty
                    ? ErrorView(
                        message: provider.errorMessage!,
                        onRetry: () => context.read<VehicleProvider>().loadVehicles(),
                      )
                    : provider.vehicles.isEmpty
                        ? const EmptyView(
                            message: 'No hay vehículos registrados',
                            icon: Icons.directions_car_outlined,
                          )
                        : RefreshIndicator(
                            onRefresh: () => context.read<VehicleProvider>().loadVehicles(),
                            child: ListView.builder(
                              controller: _scrollController,
                              physics: const AlwaysScrollableScrollPhysics(),
                              itemCount: provider.vehicles.length + 1,
                              itemBuilder: (context, index) {
                                if (index >= provider.vehicles.length) {
                                  return provider.hasNextPage
                                      ? const Padding(
                                          padding: EdgeInsets.all(16),
                                          child: Center(
                                            child: CircularProgressIndicator(),
                                          ),
                                        )
                                      : const SizedBox(height: 80);
                                }
                                final v = provider.vehicles[index];
                                return _VehicleCard(
                                  vehicle: v,
                                  onTap: () {
                                    Navigator.pushNamed(
                                      context,
                                      '/vehiculos/detail',
                                      arguments: v,
                                    );
                                  },
                                  onEdit: () {
                                    Navigator.pushNamed(
                                      context,
                                      '/vehiculos/form',
                                      arguments: {
                                        'vehicle': v,
                                        'isEditing': true,
                                      },
                                    );
                                  },
                                  onDelete: () => _deleteVehicle(v),
                                );
                              },
                            ),
                          ),
          ),
        ],
      ),
    );
  }
}

class _FilterChip extends StatelessWidget {
  final String label;
  final bool selected;
  final VoidCallback onTap;

  const _FilterChip({
    required this.label,
    required this.selected,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(right: 8),
      child: ChoiceChip(
        label: Text(label),
        selected: selected,
        onSelected: (_) => onTap(),
      ),
    );
  }
}

class _VehicleCard extends StatelessWidget {
  final Vehicle vehicle;
  final VoidCallback onTap;
  final VoidCallback onEdit;
  final VoidCallback onDelete;

  const _VehicleCard({
    required this.vehicle,
    required this.onTap,
    required this.onEdit,
    required this.onDelete,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      child: ListTile(
        leading: SizedBox(
          width: 56,
          height: 56,
          child: ClipRRect(
            borderRadius: BorderRadius.circular(8),
            child: vehicle.imagenUrl != null
                ? Image.network(
                    vehicle.imagenUrl!,
                    fit: BoxFit.cover,
                    errorBuilder: (_, __, ___) => _carIcon(),
                  )
                : _carIcon(),
          ),
        ),
        title: Text(
          vehicle.displayName,
          style: const TextStyle(fontWeight: FontWeight.w600),
        ),
        subtitle: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              Formatters.currency(vehicle.precio),
              style: const TextStyle(fontWeight: FontWeight.bold),
            ),
            if (vehicle.placa != null && vehicle.placa!.isNotEmpty)
              Text('Placa: ${vehicle.placa}'),
            const SizedBox(height: 4),
            StatusBadge(status: vehicle.estado ?? 'Desconocido', small: true),
          ],
        ),
        trailing: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            IconButton(
              icon: const Icon(Icons.edit_outlined),
              onPressed: onEdit,
              visualDensity: VisualDensity.compact,
              color: Theme.of(context).colorScheme.primary,
            ),
            IconButton(
              icon: const Icon(Icons.delete_outline, color: Colors.red),
              onPressed: onDelete,
              visualDensity: VisualDensity.compact,
            ),
          ],
        ),
        isThreeLine: true,
        onTap: onTap,
      ),
    );
  }

  Widget _carIcon() {
    return Container(
      color: Colors.grey.shade200,
      child: const Icon(Icons.directions_car, size: 28),
    );
  }
}