import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../providers/sale_provider.dart';
import '../../providers/vehicle_provider.dart';
import '../../providers/client_provider.dart';
import '../../models/vehicle.dart';
import '../../widgets/loading_indicator.dart';
import '../../widgets/error_view.dart';

class SaleFormView extends StatefulWidget {
  const SaleFormView({super.key});

  @override
  State<SaleFormView> createState() => _SaleFormViewState();
}

class _SaleFormViewState extends State<SaleFormView> {
  final _formKey = GlobalKey<FormState>();
  final _precioController = TextEditingController();
  final _observacionController = TextEditingController();
  final _fechaController = TextEditingController();
  int? _vehicleId;
  int? _clientId;
  bool _saving = false;
  bool _loading = true;

  @override
  void initState() {
    super.initState();
    _fechaController.text = DateTime.now().toString().split(' ').first;
    _loadData();
  }

  @override
  void dispose() {
    _precioController.dispose();
    _observacionController.dispose();
    _fechaController.dispose();
    super.dispose();
  }

  Future<void> _loadData() async {
    setState(() => _loading = true);
    final vehicleProvider = context.read<VehicleProvider>();
    final clientProvider = context.read<ClientProvider>();
    if (vehicleProvider.vehicles.isEmpty) {
      await vehicleProvider.loadVehicles();
    }
    if (clientProvider.clients.isEmpty) {
      await clientProvider.loadClients();
    }
    if (mounted) setState(() => _loading = false);
  }

  Future<void> _pickDate() async {
    final now = DateTime.now();
    final selected = await showDatePicker(
      context: context,
      initialDate: DateTime.tryParse(_fechaController.text) ?? now,
      firstDate: DateTime(2020),
      lastDate: DateTime(2030),
    );
    if (selected != null && mounted) {
      setState(() {
        _fechaController.text =
            '${selected.year}-${selected.month.toString().padLeft(2, '0')}-${selected.day.toString().padLeft(2, '0')}';
      });
    }
  }

  List<Vehicle> get _availableVehicles {
    final all = context.read<VehicleProvider>().vehicles;
    return all.where((v) => v.estado == 'Disponible').toList();
  }

  Future<void> _save() async {
    if (!_formKey.currentState!.validate()) return;
    if (_vehicleId == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Selecciona un vehículo')),
      );
      return;
    }
    if (_clientId == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Selecciona un cliente')),
      );
      return;
    }
    setState(() => _saving = true);

    final data = <String, dynamic>{
      'vehicle_id': _vehicleId,
      'client_id': _clientId,
      'fecha': _fechaController.text,
      'precio_venta': double.tryParse(_precioController.text.trim()) ?? 0,
      'observacion': _observacionController.text.trim().isEmpty
          ? null
          : _observacionController.text.trim(),
    };

    final provider = context.read<SaleProvider>();
    final success = await provider.createSale(data);

    if (!mounted) return;
    setState(() => _saving = false);
    if (success) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Venta registrada correctamente'),
          backgroundColor: Colors.green,
        ),
      );
      // Actualizar lista de vehículos (el vendido cambió su estado)
      context.read<VehicleProvider>().loadVehicles();
      Navigator.pop(context);
    } else {
      showErrorDialog(context, provider.errorMessage ?? 'Error al registrar venta');
    }
  }

  @override
  Widget build(BuildContext context) {
    final vehicles = _availableVehicles;
    final clients = context.watch<ClientProvider>().clients;

    return Scaffold(
      appBar: AppBar(title: const Text('Nueva venta')),
      body: _loading
          ? const LoadingIndicator()
          : SingleChildScrollView(
              padding: const EdgeInsets.all(16),
              child: Form(
                key: _formKey,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.stretch,
                  children: [
                    Text(
                      'Vehículos disponibles: ${vehicles.length}',
                      style: const TextStyle(fontWeight: FontWeight.bold),
                    ),
                    const SizedBox(height: 8),
                    DropdownButtonFormField<int>(
                      initialValue: vehicles.any((v) => v.id == _vehicleId)
                          ? _vehicleId
                          : null,
                      decoration: const InputDecoration(
                        labelText: 'Vehículo',
                        prefixIcon: Icon(Icons.directions_car_outlined),
                      ),
                      isExpanded: true,
                      items: vehicles.isEmpty
                          ? const [DropdownMenuItem<int>(child: Text('No hay vehículos disponibles'))]
                          : vehicles
                              .map((v) => DropdownMenuItem(
                                    value: v.id,
                                    child: Text(
                                      '${v.displayName} - ${v.placa ?? 'sin placa'}',
                                      overflow: TextOverflow.ellipsis,
                                    ),
                                  ))
                              .toList(),
                      onChanged: (value) {
                        setState(() {
                          _vehicleId = value;
                          if (_precioController.text.isEmpty && value != null) {
                            final v = vehicles.firstWhere(
                              (x) => x.id == value,
                              orElse: () => Vehicle(),
                            );
                            if (v.precio != null) {
                              _precioController.text = v.precio.toString();
                            }
                          }
                        });
                      },
                    ),
                    const SizedBox(height: 16),
                    DropdownButtonFormField<int>(
                      initialValue: clients.any((c) => c.id == _clientId)
                          ? _clientId
                          : null,
                      decoration: const InputDecoration(
                        labelText: 'Cliente',
                        prefixIcon: Icon(Icons.people_outline),
                      ),
                      isExpanded: true,
                      items: clients.isEmpty
                          ? const [DropdownMenuItem<int>(child: Text('No hay clientes'))]
                          : clients
                              .map((c) => DropdownMenuItem(
                                    value: c.id,
                                    child: Text(
                                      c.nombre ?? 'Sin nombre',
                                      overflow: TextOverflow.ellipsis,
                                    ),
                                  ))
                              .toList(),
                      onChanged: (value) {
                        setState(() => _clientId = value);
                      },
                    ),
                    const SizedBox(height: 16),
                    TextFormField(
                      controller: _fechaController,
                      decoration: const InputDecoration(
                        labelText: 'Fecha',
                        prefixIcon: Icon(Icons.calendar_today_outlined),
                      ),
                      readOnly: true,
                      onTap: _pickDate,
                      validator: (value) {
                        if (value == null || value.isEmpty) {
                          return 'La fecha es obligatoria';
                        }
                        return null;
                      },
                    ),
                    const SizedBox(height: 16),
                    TextFormField(
                      controller: _precioController,
                      decoration: const InputDecoration(
                        labelText: 'Precio de venta (Bs)',
                        prefixIcon: Icon(Icons.attach_money),
                      ),
                      keyboardType: TextInputType.number,
                      validator: (value) {
                        if (value == null || value.trim().isEmpty) {
                          return 'El precio es obligatorio';
                        }
                        if (double.tryParse(value.trim()) == null) {
                          return 'Precio inválido';
                        }
                        return null;
                      },
                    ),
                    const SizedBox(height: 16),
                    TextFormField(
                      controller: _observacionController,
                      decoration: const InputDecoration(
                        labelText: 'Observación',
                        alignLabelWithHint: true,
                      ),
                      maxLines: 3,
                    ),
                    const SizedBox(height: 24),
                    _saving
                        ? const Center(child: CircularProgressIndicator())
                        : ElevatedButton(
                            onPressed: _save,
                            child: const Text('Registrar venta'),
                          ),
                  ],
                ),
              ),
            ),
    );
  }
}