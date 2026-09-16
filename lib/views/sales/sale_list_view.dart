import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../providers/sale_provider.dart';
import '../../models/sale.dart';
import '../../widgets/loading_indicator.dart';
import '../../widgets/error_view.dart';
import '../../widgets/empty_view.dart';
import '../../widgets/confirm_dialog.dart';
import '../../utils/formatters.dart';

class SaleListView extends StatefulWidget {
  const SaleListView({super.key});

  @override
  State<SaleListView> createState() => _SaleListViewState();
}

class _SaleListViewState extends State<SaleListView> {
  final _searchController = TextEditingController();
  final _scrollController = ScrollController();

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      context.read<SaleProvider>().loadSales();
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
      context.read<SaleProvider>().loadNextPage();
    }
  }

  Future<void> _deleteSale(Sale sale) async {
    final confirmed = await ConfirmDialog.show(
      context,
      title: 'Eliminar venta',
      message:
          '¿Estás seguro de eliminar la venta de ${sale.vehicleName} a ${sale.clientName}?',
    );
    if (confirmed) {
      if (!mounted) return;
      final result = await context.read<SaleProvider>().deleteSale(sale.id!);
      if (result) {
        if (!mounted) return;
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Venta eliminada')),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final provider = context.watch<SaleProvider>();
    return Scaffold(
      appBar: AppBar(title: const Text('Ventas')),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () {
          Navigator.pushNamed(context, '/ventas/form');
        },
        icon: const Icon(Icons.add),
        label: const Text('Nueva'),
      ),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(12),
            child: TextField(
              controller: _searchController,
              decoration: InputDecoration(
                hintText: 'Buscar por vehículo o cliente...',
                prefixIcon: const Icon(Icons.search),
                suffixIcon: IconButton(
                  icon: const Icon(Icons.clear),
                  onPressed: () {
                    _searchController.clear();
                    context.read<SaleProvider>().search('');
                  },
                ),
              ),
              onSubmitted: (value) {
                context.read<SaleProvider>().search(value.trim());
              },
            ),
          ),
          Expanded(
            child: provider.isLoading && provider.sales.isEmpty
                ? const LoadingIndicator()
                : provider.errorMessage != null && provider.sales.isEmpty
                    ? ErrorView(
                        message: provider.errorMessage!,
                        onRetry: () => context.read<SaleProvider>().loadSales(),
                      )
                    : provider.sales.isEmpty
                        ? const EmptyView(
                            message: 'No hay ventas registradas',
                            icon: Icons.point_of_sale_outlined,
                          )
                        : RefreshIndicator(
                            onRefresh: () => context.read<SaleProvider>().loadSales(),
                            child: ListView.builder(
                              controller: _scrollController,
                              physics: const AlwaysScrollableScrollPhysics(),
                              itemCount: provider.sales.length + 1,
                              itemBuilder: (context, index) {
                                if (index >= provider.sales.length) {
                                  return provider.hasNextPage
                                      ? const Padding(
                                          padding: EdgeInsets.all(16),
                                          child: Center(
                                            child: CircularProgressIndicator(),
                                          ),
                                        )
                                      : const SizedBox(height: 80);
                                }
                                final s = provider.sales[index];
                                return _SaleCard(
                                  sale: s,
                                  onDelete: () => _deleteSale(s),
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

class _SaleCard extends StatelessWidget {
  final Sale sale;
  final VoidCallback onDelete;

  const _SaleCard({required this.sale, required this.onDelete});

  @override
  Widget build(BuildContext context) {
    return Card(
      child: ListTile(
        leading: const CircleAvatar(
          child: Icon(Icons.sell_outlined),
        ),
        title: Text(
          sale.vehicleName,
          style: const TextStyle(fontWeight: FontWeight.w600),
        ),
        subtitle: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Cliente: ${sale.clientName}'),
            Text('Fecha: ${Formatters.date(sale.fecha)}'),
            const SizedBox(height: 4),
            Text(
              'Comisión (5%): ${Formatters.currency(sale.comision)}',
              style: const TextStyle(
                color: Colors.green,
                fontSize: 12,
                fontWeight: FontWeight.w600,
              ),
            ),
          ],
        ),
        isThreeLine: true,
        trailing: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          crossAxisAlignment: CrossAxisAlignment.end,
          children: [
            Text(
              Formatters.currency(sale.precioVenta),
              style: const TextStyle(fontWeight: FontWeight.bold),
            ),
            IconButton(
              icon: const Icon(Icons.delete_outline, color: Colors.red),
              onPressed: onDelete,
              visualDensity: VisualDensity.compact,
            ),
          ],
        ),
      ),
    );
  }
}