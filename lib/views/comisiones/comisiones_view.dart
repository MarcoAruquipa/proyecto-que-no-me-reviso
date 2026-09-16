import 'package:flutter/material.dart';
import '../../services/sale_service.dart';
import '../../models/sale.dart';
import '../../widgets/loading_indicator.dart';
import '../../widgets/error_view.dart';
import '../../widgets/empty_view.dart';
import '../../utils/formatters.dart';

class ComisionesView extends StatefulWidget {
  const ComisionesView({super.key});

  @override
  State<ComisionesView> createState() => _ComisionesViewState();
}

class _ComisionesViewState extends State<ComisionesView> {
  final SaleService _service = SaleService();
  late Future<List<Sale>> _future;
  String? _error;

  @override
  void initState() {
    super.initState();
    _future = _load();
  }

  Future<List<Sale>> _load() async {
    try {
      final sales = await _service.getComisiones();
      if (mounted) setState(() => _error = null);
      return sales;
    } catch (e) {
      if (mounted) setState(() => _error = e.toString());
      return [];
    }
  }

  double _totalComisiones(List<Sale> sales) {
    return sales.fold(0.0, (sum, s) => sum + s.comision);
  }

  Future<void> _refresh() async {
    setState(() {
      _future = _load();
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Comisiones')),
      body: _error != null
          ? ErrorView(message: _error!, onRetry: _refresh)
          : FutureBuilder<List<Sale>>(
              future: _future,
              builder: (context, snapshot) {
                if (snapshot.connectionState == ConnectionState.waiting) {
                  return const LoadingIndicator();
                }
                final sales = snapshot.data ?? [];
                if (sales.isEmpty) {
                  return const EmptyView(
                    message: 'No hay ventas para calcular comisiones',
                    icon: Icons.percent,
                  );
                }
                return Column(
                  children: [
                    Padding(
                      padding: const EdgeInsets.all(16),
                      child: Card(
                        margin: EdgeInsets.zero,
                        child: Padding(
                          padding: const EdgeInsets.all(20),
                          child: Column(
                            children: [
                              const Text(
                                'Comisión total (5%)',
                                style: TextStyle(
                                  fontSize: 14,
                                  color: Colors.grey,
                                  fontWeight: FontWeight.w500,
                                ),
                              ),
                              const SizedBox(height: 8),
                              Text(
                                Formatters.currency(_totalComisiones(sales)),
                                style: TextStyle(
                                  fontSize: 28,
                                  fontWeight: FontWeight.bold,
                                  color: Theme.of(context).colorScheme.primary,
                                ),
                              ),
                            ],
                          ),
                        ),
                      ),
                    ),
                    Expanded(
                      child: RefreshIndicator(
                        onRefresh: _refresh,
                        child: ListView.builder(
                          itemCount: sales.length,
                          itemBuilder: (context, index) {
                            final s = sales[index];
                            return Card(
                              child: ListTile(
                                leading: const CircleAvatar(
                                  child: Icon(Icons.percent),
                                ),
                                title: Text(s.vehicleName),
                                subtitle: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Text('Cliente: ${s.clientName}'),
                                    Text('Fecha: ${Formatters.date(s.fecha)}'),
                                  ],
                                ),
                                trailing: Column(
                                  mainAxisAlignment: MainAxisAlignment.center,
                                  crossAxisAlignment: CrossAxisAlignment.end,
                                  children: [
                                    Text(
                                      'Venta: ${Formatters.currency(s.precioVenta)}',
                                      style: const TextStyle(fontSize: 12),
                                    ),
                                    Text(
                                      'Comisión: ${Formatters.currency(s.comision)}',
                                      style: const TextStyle(
                                        fontWeight: FontWeight.bold,
                                        color: Colors.green,
                                      ),
                                    ),
                                  ],
                                ),
                                isThreeLine: true,
                              ),
                            );
                          },
                        ),
                      ),
                    ),
                  ],
                );
              },
            ),
    );
  }
}