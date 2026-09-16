import 'dart:io';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:provider/provider.dart';
import '../../providers/vehicle_provider.dart';
import '../../models/vehicle.dart';
import '../../widgets/error_view.dart';

class VehicleFormView extends StatefulWidget {
  final Vehicle? vehicle;
  final bool isEditing;

  const VehicleFormView({
    super.key,
    this.vehicle,
    this.isEditing = false,
  });

  @override
  State<VehicleFormView> createState() => _VehicleFormViewState();
}

class _VehicleFormViewState extends State<VehicleFormView> {
  final _formKey = GlobalKey<FormState>();
  late final TextEditingController _marcaController;
  late final TextEditingController _modeloController;
  late final TextEditingController _anioController;
  late final TextEditingController _placaController;
  late final TextEditingController _colorController;
  late final TextEditingController _precioController;
  late final TextEditingController _descripcionController;
  String _estado = 'Disponible';
  File? _imageFile;
  bool _saving = false;

  @override
  void initState() {
    super.initState();
    final v = widget.vehicle;
    _marcaController = TextEditingController(text: v?.marca ?? '');
    _modeloController = TextEditingController(text: v?.modelo ?? '');
    _anioController = TextEditingController(
      text: v?.anio != null ? '${v!.anio}' : '',
    );
    _placaController = TextEditingController(text: v?.placa ?? '');
    _colorController = TextEditingController(text: v?.color ?? '');
    _precioController = TextEditingController(
      text: v?.precio != null ? v!.precio.toString() : '',
    );
    _descripcionController = TextEditingController(text: v?.descripcion ?? '');
    _estado = v != null && const ['Disponible', 'Reservado', 'Vendido'].contains(v.estado)
        ? v.estado!
        : 'Disponible';
  }

  @override
  void dispose() {
    _marcaController.dispose();
    _modeloController.dispose();
    _anioController.dispose();
    _placaController.dispose();
    _colorController.dispose();
    _precioController.dispose();
    _descripcionController.dispose();
    super.dispose();
  }

  Future<void> _pickImage() async {
    try {
      final picker = ImagePicker();
      final image = await picker.pickImage(source: ImageSource.gallery);
      if (image != null) {
        setState(() => _imageFile = File(image.path));
      }
    } catch (_) {}
  }

  Future<void> _save() async {
    if (!_formKey.currentState!.validate()) return;
    setState(() => _saving = true);
    final data = <String, dynamic>{
      'marca': _marcaController.text.trim(),
      'modelo': _modeloController.text.trim(),
      'anio': int.tryParse(_anioController.text.trim()),
      'placa': _placaController.text.trim().isEmpty
          ? null
          : _placaController.text.trim(),
      'color': _colorController.text.trim().isEmpty
          ? null
          : _colorController.text.trim(),
      'precio': double.tryParse(_precioController.text.trim()) ?? 0,
      'estado': _estado,
      'descripcion': _descripcionController.text.trim().isEmpty
          ? null
          : _descripcionController.text.trim(),
    };

    final provider = context.read<VehicleProvider>();
    late bool success;
    if (widget.isEditing) {
      success = await provider.updateVehicle(
        widget.vehicle!.id!,
        data: data,
        imageBytes: _imageFile != null ? await _imageFile!.readAsBytes() : null,
        imageFilename: _imageFile?.path.split(Platform.pathSeparator).last,
      );
    } else {
      success = await provider.createVehicle(
        data,
        imageBytes: _imageFile != null ? await _imageFile!.readAsBytes() : null,
        imageFilename: _imageFile?.path.split(Platform.pathSeparator).last,
      );
    }

    if (!mounted) return;
    setState(() => _saving = false);
    if (success) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(widget.isEditing
              ? 'Vehículo actualizado correctamente'
              : 'Vehículo creado correctamente'),
          backgroundColor: Colors.green,
        ),
      );
      Navigator.pop(context);
    } else {
      showErrorDialog(context, provider.errorMessage ?? 'Error al guardar vehículo');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(widget.isEditing ? 'Editar vehículo' : 'Nuevo vehículo'),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              _buildImagePicker(),
              const SizedBox(height: 16),
              TextFormField(
                controller: _marcaController,
                decoration: const InputDecoration(
                  labelText: 'Marca',
                  prefixIcon: Icon(Icons.branding_watermark_outlined),
                ),
                textInputAction: TextInputAction.next,
                validator: (value) {
                  if (value == null || value.trim().isEmpty) {
                    return 'La marca es obligatoria';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16),
              TextFormField(
                controller: _modeloController,
                decoration: const InputDecoration(
                  labelText: 'Modelo',
                  prefixIcon: Icon(Icons.model_training_outlined),
                ),
                textInputAction: TextInputAction.next,
                validator: (value) {
                  if (value == null || value.trim().isEmpty) {
                    return 'El modelo es obligatorio';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16),
              Row(
                children: [
                  Expanded(
                    child: TextFormField(
                      controller: _anioController,
                      decoration: const InputDecoration(
                        labelText: 'Año',
                        prefixIcon: Icon(Icons.calendar_today_outlined),
                      ),
                      keyboardType: TextInputType.number,
                      textInputAction: TextInputAction.next,
                      validator: (value) {
                        if (value == null || value.trim().isEmpty) {
                          return 'Año requerido';
                        }
                        final year = int.tryParse(value.trim());
                        if (year == null || year < 1900 || year > 2100) {
                          return 'Año inválido';
                        }
                        return null;
                      },
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: TextFormField(
                      controller: _placaController,
                      decoration: const InputDecoration(
                        labelText: 'Placa',
                        prefixIcon: Icon(Icons.confirmation_number_outlined),
                      ),
                      textInputAction: TextInputAction.next,
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 16),
              TextFormField(
                controller: _colorController,
                decoration: const InputDecoration(
                  labelText: 'Color',
                  prefixIcon: Icon(Icons.palette_outlined),
                ),
                textInputAction: TextInputAction.next,
              ),
              const SizedBox(height: 16),
              TextFormField(
                controller: _precioController,
                decoration: const InputDecoration(
                  labelText: 'Precio (Bs)',
                  prefixIcon: Icon(Icons.attach_money),
                ),
                keyboardType: TextInputType.number,
                textInputAction: TextInputAction.next,
                validator: (value) {
                  if (value == null || value.trim().isEmpty) {
                    return 'El precio es obligatorio';
                  }
                  final price = double.tryParse(value.trim());
                  if (price == null || price < 0) {
                    return 'Precio inválido';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16),
              DropdownButtonFormField<String>(
                initialValue: _estado,
                decoration: const InputDecoration(
                  labelText: 'Estado',
                  prefixIcon: Icon(Icons.info_outline),
                ),
                items: const [
                  DropdownMenuItem(value: 'Disponible', child: Text('Disponible')),
                  DropdownMenuItem(value: 'Reservado', child: Text('Reservado')),
                  DropdownMenuItem(value: 'Vendido', child: Text('Vendido')),
                ],
                onChanged: (value) {
                  if (value != null) setState(() => _estado = value);
                },
              ),
              const SizedBox(height: 16),
              TextFormField(
                controller: _descripcionController,
                decoration: const InputDecoration(
                  labelText: 'Descripción',
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
                          : 'Crear vehículo'),
                    ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildImagePicker() {
    final existingUrl = widget.vehicle?.imagenUrl;
    return GestureDetector(
      onTap: _pickImage,
      child: Container(
        height: 160,
        decoration: BoxDecoration(
          border: Border.all(color: Colors.grey.shade300),
          borderRadius: BorderRadius.circular(12),
        ),
        clipBehavior: Clip.antiAlias,
        child: _imageFile != null
            ? Image.file(
                _imageFile!,
                fit: BoxFit.cover,
                width: double.infinity,
              )
            : existingUrl != null
                ? Stack(
                    fit: StackFit.expand,
                    children: [
                      Image.network(
                        existingUrl,
                        fit: BoxFit.cover,
                        errorBuilder: (_, __, ___) => _pickerPlaceholder(),
                      ),
                      const Positioned(
                        right: 8,
                        top: 8,
                        child: Chip(
                          avatar: Icon(Icons.edit_outlined, size: 16),
                          label: Text('Cambiar foto'),
                          visualDensity: VisualDensity.compact,
                          backgroundColor: Colors.white70,
                        ),
                      ),
                    ],
                  )
                : _pickerPlaceholder(),
      ),
    );
  }

  Widget _pickerPlaceholder() {
    return Column(
      mainAxisAlignment: MainAxisAlignment.center,
      children: [
        Icon(Icons.add_a_photo_outlined, size: 48, color: Colors.grey.shade400),
        const SizedBox(height: 8),
        const Text('Toca para seleccionar una imagen',
            style: TextStyle(color: Colors.grey)),
      ],
    );
  }
}