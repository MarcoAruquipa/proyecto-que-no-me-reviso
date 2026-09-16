import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../providers/client_provider.dart';
import '../../models/client.dart';
import '../../widgets/loading_indicator.dart';
import '../../widgets/error_view.dart';
import '../../widgets/empty_view.dart';
import '../../widgets/status_badge.dart';
import '../../widgets/confirm_dialog.dart';

class ClientListView extends StatefulWidget {
  const ClientListView({super.key});

  @override
  State<ClientListView> createState() => _ClientListViewState();
}

class _ClientListViewState extends State<ClientListView> {
  final _searchController = TextEditingController();
  final _scrollController = ScrollController();

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      context.read<ClientProvider>().loadClients();
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
      context.read<ClientProvider>().loadNextPage();
    }
  }

  Future<void> _deleteClient(Client client) async {
    final confirmed = await ConfirmDialog.show(
      context,
      title: 'Eliminar cliente',
      message: '¿Estás seguro de eliminar a ${client.nombre}?',
    );
    if (confirmed) {
      if (!mounted) return;
      final result = await context.read<ClientProvider>().deleteClient(client.id!);
      if (result) {
        if (!mounted) return;
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Cliente eliminado')),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final provider = context.watch<ClientProvider>();
    return Scaffold(
      appBar: AppBar(title: const Text('Clientes')),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () {
          Navigator.pushNamed(context, '/clientes/form');
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
                hintText: 'Buscar por nombre, CI, teléfono o email...',
                prefixIcon: const Icon(Icons.search),
                suffixIcon: IconButton(
                  icon: const Icon(Icons.clear),
                  onPressed: () {
                    _searchController.clear();
                    context.read<ClientProvider>().search('');
                  },
                ),
              ),
              onSubmitted: (value) {
                context.read<ClientProvider>().search(value.trim());
              },
            ),
          ),
          Expanded(
            child: provider.isLoading && provider.clients.isEmpty
                ? const LoadingIndicator()
                : provider.errorMessage != null && provider.clients.isEmpty
                    ? ErrorView(
                        message: provider.errorMessage!,
                        onRetry: () => context.read<ClientProvider>().loadClients(),
                      )
                    : provider.clients.isEmpty
                        ? const EmptyView(
                            message: 'No hay clientes registrados',
                            icon: Icons.people_outline,
                          )
                        : RefreshIndicator(
                            onRefresh: () => context.read<ClientProvider>().loadClients(),
                            child: ListView.builder(
                              controller: _scrollController,
                              physics: const AlwaysScrollableScrollPhysics(),
                              itemCount: provider.clients.length + 1,
                              itemBuilder: (context, index) {
                                if (index >= provider.clients.length) {
                                  return provider.hasNextPage
                                      ? const Padding(
                                          padding: EdgeInsets.all(16),
                                          child: Center(
                                            child: CircularProgressIndicator(),
                                          ),
                                        )
                                      : const SizedBox(height: 80);
                                }
                                final c = provider.clients[index];
                                return _ClientCard(
                                  client: c,
                                  onTap: () {
                                    Navigator.pushNamed(
                                      context,
                                      '/clientes/detail',
                                      arguments: c,
                                    );
                                  },
                                  onEdit: () {
                                    Navigator.pushNamed(
                                      context,
                                      '/clientes/form',
                                      arguments: {
                                        'client': c,
                                        'isEditing': true,
                                      },
                                    );
                                  },
                                  onDelete: () => _deleteClient(c),
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

class _ClientCard extends StatelessWidget {
  final Client client;
  final VoidCallback onTap;
  final VoidCallback onEdit;
  final VoidCallback onDelete;

  const _ClientCard({
    required this.client,
    required this.onTap,
    required this.onEdit,
    required this.onDelete,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      child: ListTile(
        leading: CircleAvatar(
          child: Text(
            (client.nombre ?? '?').substring(0, 1).toUpperCase(),
          ),
        ),
        title: Text(
          client.nombre ?? 'Sin nombre',
          style: const TextStyle(fontWeight: FontWeight.w600),
        ),
        subtitle: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            if (client.telefono != null && client.telefono!.isNotEmpty)
              Text(client.telefono!),
            if (client.email != null && client.email!.isNotEmpty)
              Text(client.email!, style: const TextStyle(fontSize: 12)),
            const SizedBox(height: 4),
            StatusBadge(status: client.estado ?? 'Desconocido', small: true),
          ],
        ),
        isThreeLine: true,
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
            ),
          ],
        ),
        onTap: onTap,
      ),
    );
  }
}