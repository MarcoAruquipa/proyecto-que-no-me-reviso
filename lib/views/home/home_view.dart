import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../providers/auth_provider.dart';
import '../../providers/catalog_provider.dart';
import '../dashboard/dashboard_view.dart';
import '../vehicles/vehicle_list_view.dart';
import '../clients/client_list_view.dart';
import '../sales/sale_list_view.dart';
import '../../widgets/status_badge.dart';

class HomeView extends StatefulWidget {
  const HomeView({super.key});

  @override
  State<HomeView> createState() => _HomeViewState();
}

class _HomeViewState extends State<HomeView> {
  int _selectedIndex = 0;

  final _tabs = [
    const DashboardView(),
    const VehicleListView(),
    const ClientListView(),
    const SaleListView(),
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Pegaso Motors'),
      ),
      drawer: _buildDrawer(context),
      body: _tabs[_selectedIndex],
      bottomNavigationBar: NavigationBar(
        selectedIndex: _selectedIndex,
        onDestinationSelected: (index) {
          setState(() => _selectedIndex = index);
        },
        destinations: const [
          NavigationDestination(
            icon: Icon(Icons.space_dashboard_outlined),
            selectedIcon: Icon(Icons.space_dashboard),
            label: 'Panel',
          ),
          NavigationDestination(
            icon: Icon(Icons.directions_car_outlined),
            selectedIcon: Icon(Icons.directions_car),
            label: 'Vehículos',
          ),
          NavigationDestination(
            icon: Icon(Icons.people_outline),
            selectedIcon: Icon(Icons.people),
            label: 'Clientes',
          ),
          NavigationDestination(
            icon: Icon(Icons.point_of_sale_outlined),
            selectedIcon: Icon(Icons.point_of_sale),
            label: 'Ventas',
          ),
        ],
      ),
    );
  }

  Widget _buildDrawer(BuildContext context) {
    final authProvider = context.watch<AuthProvider>();
    final user = authProvider.user;
    return Drawer(
      child: ListView(
        padding: EdgeInsets.zero,
        children: [
          UserAccountsDrawerHeader(
            accountName: Text(user?.name ?? 'Usuario'),
            accountEmail: Text(user?.email ?? ''),
            currentAccountPicture: CircleAvatar(
              backgroundColor: Colors.white,
              child: Text(
                (user?.name ?? 'U').substring(0, 1).toUpperCase(),
                style: const TextStyle(
                  fontSize: 28,
                  fontWeight: FontWeight.bold,
                  color: Color(0xFF4B0082),
                ),
              ),
            ),
          ),
          if (user != null) ...[
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
              child: Row(
                children: [
                  StatusBadge(status: user.role ?? 'invitado', small: true),
                ],
              ),
            ),
            const Divider(),
          ],
          ListTile(
            leading: const Icon(Icons.space_dashboard),
            title: const Text('Panel de control'),
            onTap: () {
              Navigator.pop(context);
              _navigateTo(context, '/dashboard');
            },
          ),
          ListTile(
            leading: const Icon(Icons.directions_car),
            title: const Text('Vehículos'),
            onTap: () {
              Navigator.pop(context);
              _navigateTo(context, '/vehiculos');
            },
          ),
          ListTile(
            leading: const Icon(Icons.people),
            title: const Text('Clientes'),
            onTap: () {
              Navigator.pop(context);
              _navigateTo(context, '/clientes');
            },
          ),
          ListTile(
            leading: const Icon(Icons.point_of_sale),
            title: const Text('Ventas'),
            onTap: () {
              Navigator.pop(context);
              _navigateTo(context, '/ventas');
            },
          ),
          ListTile(
            leading: const Icon(Icons.percent),
            title: const Text('Comisiones'),
            onTap: () {
              Navigator.pop(context);
              _navigateTo(context, '/comisiones');
            },
          ),
          if (user != null &&
              (user.role == 'admin' || user.role == 'jefe')) ...[
            ListTile(
              leading: const Icon(Icons.supervisor_account),
              title: const Text('Supervisión'),
              subtitle: const Text('Panel de jefe de sucursal'),
              onTap: () {
                Navigator.pop(context);
                _navigateTo(context, '/supervision');
              },
            ),
          ],
          const Divider(),
          ListTile(
            leading: const Icon(Icons.campaign_outlined),
            title: const Text('Catálogo público'),
            subtitle: const Text('Vista para tus clientes'),
            onTap: () {
              Navigator.pop(context);
              _showCatalogSheet(context);
            },
          ),
          ListTile(
            leading: const Icon(Icons.person_outline),
            title: const Text('Mi perfil'),
            onTap: () {
              Navigator.pop(context);
              _navigateTo(context, '/perfil');
            },
          ),
          const Divider(),
          ListTile(
            leading: const Icon(Icons.logout, color: Colors.red),
            title: const Text('Cerrar sesión', style: TextStyle(color: Colors.red)),
            onTap: () async {
              Navigator.pop(context);
              await authProvider.logout();
              if (context.mounted) {
                Navigator.pushNamedAndRemoveUntil(context, '/login', (route) => false);
              }
            },
          ),
        ],
      ),
    );
  }

  void _navigateTo(BuildContext context, String route) {
    Navigator.pushNamed(context, route);
  }

  void _showCatalogSheet(BuildContext context) {
    final catalogProvider = context.read<CatalogProvider>();
    catalogProvider.loadCatalogo();
    catalogProvider.loadMaletin();
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      builder: (ctx) => const PublicCatalogSheet(),
    );
  }
}

class PublicCatalogSheet extends StatefulWidget {
  const PublicCatalogSheet({super.key});

  @override
  State<PublicCatalogSheet> createState() => _PublicCatalogSheetState();
}

class _PublicCatalogSheetState extends State<PublicCatalogSheet> {
  @override
  Widget build(BuildContext context) {
    final catalogProvider = context.watch<CatalogProvider>();
    return DraggableScrollableSheet(
      expand: false,
      initialChildSize: 0.85,
      maxChildSize: 0.95,
      builder: (context, scrollController) {
        return Column(
          children: [
            if (catalogProvider.maletin?.bannerUrl != null) ...[
              ClipRRect(
                borderRadius: BorderRadius.circular(12),
                child: Image.network(
                  catalogProvider.maletin!.bannerUrl!,
                  height: 110,
                  width: double.infinity,
                  fit: BoxFit.cover,
                  errorBuilder: (_, __, ___) => const SizedBox(
                    height: 4,
                    child: SizedBox(),
                  ),
                ),
              ),
              const SizedBox(height: 8),
            ],
            ListTile(
              leading: catalogProvider.maletin?.logoUrl != null
                  ? ClipRRect(
                      borderRadius: BorderRadius.circular(8),
                      child: Image.network(
                        catalogProvider.maletin!.logoUrl!,
                        width: 48,
                        height: 48,
                        fit: BoxFit.cover,
                        errorBuilder: (_, __, ___) => const Icon(
                          Icons.directions_car,
                          size: 32,
                        ),
                      ),
                    )
                  : const Icon(Icons.directions_car, size: 32),
              title: Text(
                catalogProvider.maletin?.titulo ?? 'Catálogo de Vehículos',
                style: const TextStyle(fontWeight: FontWeight.bold),
              ),
              subtitle: catalogProvider.maletin?.descripcion != null
                  ? Text(catalogProvider.maletin!.descripcion!)
                  : null,
            ),
            const Divider(),
            Expanded(
              child: catalogProvider.isLoading
                  ? const Center(child: CircularProgressIndicator())
                  : catalogProvider.vehicles.isEmpty
                      ? const Center(child: Text('No hay vehículos disponibles'))
                      : ListView.builder(
                          controller: scrollController,
                          itemCount: catalogProvider.vehicles.length,
                          itemBuilder: (context, index) {
                            final v = catalogProvider.vehicles[index];
                            return ListTile(
                              leading: SizedBox(
                                width: 56,
                                height: 56,
                                child: ClipRRect(
                                  borderRadius: BorderRadius.circular(8),
                                  child: v.imagenUrl != null
                                      ? Image.network(
                                          v.imagenUrl!,
                                          fit: BoxFit.cover,
                                          errorBuilder: (_, __, ___) => _catalogCarIcon(),
                                        )
                                      : _catalogCarIcon(),
                                ),
                              ),
                              title: Text(v.displayName),
                              subtitle: Text(
                                '${v.estado ?? ''} • ${v.color ?? 'Sin color'}',
                              ),
                              trailing: Text(
                                'Bs ${v.precio?.toStringAsFixed(2) ?? '0.00'}',
                                style: const TextStyle(fontWeight: FontWeight.bold),
                              ),
                            );
                          },
                        ),
            ),
          ],
        );
      },
    );
  }

  Widget _catalogCarIcon() {
    return Container(
      color: Colors.grey.shade200,
      child: const Icon(Icons.directions_car, size: 28),
    );
  }
}