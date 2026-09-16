import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../providers/client_provider.dart';
import '../../models/client.dart';
import '../../widgets/error_view.dart';

class ClientFormView extends StatefulWidget {
  final Client? client;
  final bool isEditing;

  const ClientFormView({
    super.key,
    this.client,
    this.isEditing = false,
  });

  @override
  State<ClientFormView> createState() => _ClientFormViewState();
}

class _ClientFormViewState extends State<ClientFormView> {
  final _formKey = GlobalKey<FormState>();

  /// Estados válidos que acepta el backend. `key` = valor enviado,
  /// `value` = etiqueta mostrada (los datos web pueden traer "En negociación"
  /// con tilde, por eso se normaliza con [_canonicalEstado]).
  static const Map<String, String> _estadoItems = {
    'En negociacion': 'En negociación',
    'Ganado': 'Ganado',
    'Perdido': 'Perdido',
  };

  String _canonicalEstado(String? value) {
    if (value == null || value.trim().isEmpty) return 'En negociacion';
    final v = value.trim().toLowerCase().replaceAll('ó', 'o');
    for (final key in _estadoItems.keys) {
      if (key.toLowerCase().replaceAll('ó', 'o') == v) return key;
    }
    return 'En negociacion';
  }

  late final TextEditingController _nombreController;
  late final TextEditingController _ciController;
  late final TextEditingController _telefonoController;
  late final TextEditingController _emailController;
  late final TextEditingController _direccionController;
  late final TextEditingController _vehiculoInteresController;
  late final TextEditingController _metodoPagoController;
  late final TextEditingController _fuenteController;
  late final TextEditingController _notasController;
  String _estado = 'En negociacion';
  bool _saving = false;

  @override
  void initState() {
    super.initState();
    final c = widget.client;
    _nombreController = TextEditingController(text: c?.nombre ?? '');
    _ciController = TextEditingController(text: c?.ci ?? '');
    _telefonoController = TextEditingController(text: c?.telefono ?? '');
    _emailController = TextEditingController(text: c?.email ?? '');
    _direccionController = TextEditingController(text: c?.direccion ?? '');
    _vehiculoInteresController =
        TextEditingController(text: c?.vehiculoInteres ?? '');
    _metodoPagoController = TextEditingController(text: c?.metodoPago ?? '');
    _fuenteController = TextEditingController(text: c?.fuente ?? '');
    _notasController = TextEditingController(text: c?.notas ?? '');
    _estado = _canonicalEstado(c?.estado);
  }

  @override
  void dispose() {
    _nombreController.dispose();
    _ciController.dispose();
    _telefonoController.dispose();
    _emailController.dispose();
    _direccionController.dispose();
    _vehiculoInteresController.dispose();
    _metodoPagoController.dispose();
    _fuenteController.dispose();
    _notasController.dispose();
    super.dispose();
  }

  Future<void> _save() async {
    if (!_formKey.currentState!.validate()) return;
    setState(() => _saving = true);

    final data = <String, dynamic>{
      'nombre': _nombreController.text.trim(),
      'ci': _textOrNull(_ciController),
      'telefono': _textOrNull(_telefonoController),
      'email': _textOrNull(_emailController),
      'direccion': _textOrNull(_direccionController),
      'vehiculo_interes': _textOrNull(_vehiculoInteresController),
      'metodo_pago': _textOrNull(_metodoPagoController),
      'fuente': _textOrNull(_fuenteController),
      'estado': _estado,
      'notas': _textOrNull(_notasController),
    };

    final provider = context.read<ClientProvider>();
    late bool success;
    if (widget.isEditing) {
      success = await provider.updateClient(widget.client!.id!, data);
    } else {
      success = await provider.createClient(data);
    }

    if (!mounted) return;
    setState(() => _saving = false);
    if (success) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(widget.isEditing
              ? 'Cliente actualizado correctamente'
              : 'Cliente creado correctamente'),
          backgroundColor: Colors.green,
        ),
      );
      Navigator.pop(context);
    } else {
      showErrorDialog(context, provider.errorMessage ?? 'Error al guardar cliente');
    }
  }

  String? _textOrNull(TextEditingController controller) {
    final text = controller.text.trim();
    return text.isEmpty ? null : text;
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(widget.isEditing ? 'Editar cliente' : 'Nuevo cliente'),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              TextFormField(
                controller: _nombreController,
                decoration: const InputDecoration(
                  labelText: 'Nombre',
                  prefixIcon: Icon(Icons.person_outline),
                ),
                textInputAction: TextInputAction.next,
                validator: (value) {
                  if (value == null || value.trim().isEmpty) {
                    return 'El nombre es obligatorio';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16),
              Row(
                children: [
                  Expanded(
                    child: TextFormField(
                      controller: _ciController,
                      decoration: const InputDecoration(
                        labelText: 'CI',
                        prefixIcon: Icon(Icons.badge_outlined),
                      ),
                      textInputAction: TextInputAction.next,
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: TextFormField(
                      controller: _telefonoController,
                      decoration: const InputDecoration(
                        labelText: 'Teléfono',
                        prefixIcon: Icon(Icons.phone_outlined),
                      ),
                      keyboardType: TextInputType.phone,
                      textInputAction: TextInputAction.next,
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 16),
              TextFormField(
                controller: _emailController,
                decoration: const InputDecoration(
                  labelText: 'Email',
                  prefixIcon: Icon(Icons.email_outlined),
                ),
                keyboardType: TextInputType.emailAddress,
                textInputAction: TextInputAction.next,
                validator: (value) {
                  if (value != null &&
                      value.trim().isNotEmpty &&
                      !value.trim().contains('@')) {
                    return 'Email inválido';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16),
              TextFormField(
                controller: _direccionController,
                decoration: const InputDecoration(
                  labelText: 'Dirección',
                  prefixIcon: Icon(Icons.home_outlined),
                ),
                textInputAction: TextInputAction.next,
              ),
              const SizedBox(height: 16),
              TextFormField(
                controller: _vehiculoInteresController,
                decoration: const InputDecoration(
                  labelText: 'Vehículo de interés',
                  prefixIcon: Icon(Icons.directions_car_outlined),
                ),
                textInputAction: TextInputAction.next,
              ),
              const SizedBox(height: 16),
              Row(
                children: [
                  Expanded(
                    child: TextFormField(
                      controller: _metodoPagoController,
                      decoration: const InputDecoration(
                        labelText: 'Método de pago',
                        prefixIcon: Icon(Icons.payment_outlined),
                      ),
                      textInputAction: TextInputAction.next,
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: TextFormField(
                      controller: _fuenteController,
                      decoration: const InputDecoration(
                        labelText: 'Fuente',
                        prefixIcon: Icon(Icons.source_outlined),
                      ),
                      textInputAction: TextInputAction.next,
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 16),
              DropdownButtonFormField<String>(
                initialValue: _estado,
                decoration: const InputDecoration(
                  labelText: 'Estado',
                  prefixIcon: Icon(Icons.info_outline),
                ),
                items: _estadoItems.entries
                    .map(
                      (e) => DropdownMenuItem(
                        value: e.key,
                        child: Text(e.value),
                      ),
                    )
                    .toList(),
                onChanged: (value) {
                  if (value != null) setState(() => _estado = value);
                },
              ),
              const SizedBox(height: 16),
              TextFormField(
                controller: _notasController,
                decoration: const InputDecoration(
                  labelText: 'Notas',
                  alignLabelWithHint: true,
                ),
                maxLines: 4,
              ),
              const SizedBox(height: 24),
              _saving
                  ? const Center(child: CircularProgressIndicator())
                  : ElevatedButton(
                      onPressed: _save,
                      child: Text(widget.isEditing
                          ? 'Guardar cambios'
                          : 'Crear cliente'),
                    ),
            ],
          ),
        ),
      ),
    );
  }
}