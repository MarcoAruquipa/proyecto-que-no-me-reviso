import 'dart:io';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:provider/provider.dart';
import '../../providers/maletin_provider.dart';
import '../../widgets/loading_indicator.dart';
import '../../widgets/error_view.dart';

class MaletinView extends StatefulWidget {
  const MaletinView({super.key});

  @override
  State<MaletinView> createState() => _MaletinViewState();
}

class _MaletinViewState extends State<MaletinView> {
  final _tituloController = TextEditingController();
  final _descripcionController = TextEditingController();
  final _telefonoController = TextEditingController();
  final _direccionController = TextEditingController();
  File? _logoFile;
  File? _bannerFile;
  bool _saving = false;

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      _load();
    });
  }

  Future<void> _load() async {
    final provider = context.read<MaletinProvider>();
    await provider.loadMaletin();
    if (!mounted || provider.maletin == null) return;
    final m = provider.maletin!;
    _tituloController.text = m.titulo ?? '';
    _descripcionController.text = m.descripcion ?? '';
    _telefonoController.text = m.telefono ?? '';
    _direccionController.text = m.direccion ?? '';
    setState(() {});
  }

  @override
  void dispose() {
    _tituloController.dispose();
    _descripcionController.dispose();
    _telefonoController.dispose();
    _direccionController.dispose();
    super.dispose();
  }

  Future<void> _pickImage(bool isLogo) async {
    try {
      final picker = ImagePicker();
      final image = await picker.pickImage(source: ImageSource.gallery);
      if (image != null) {
        setState(() {
          if (isLogo) {
            _logoFile = File(image.path);
          } else {
            _bannerFile = File(image.path);
          }
        });
      }
    } catch (_) {}
  }

  Future<void> _save() async {
    setState(() => _saving = true);
    final data = <String, dynamic>{
      'titulo': _tituloController.text.trim().isEmpty
          ? null
          : _tituloController.text.trim(),
      'descripcion': _descripcionController.text.trim().isEmpty
          ? null
          : _descripcionController.text.trim(),
      'telefono': _telefonoController.text.trim().isEmpty
          ? null
          : _telefonoController.text.trim(),
      'direccion': _direccionController.text.trim().isEmpty
          ? null
          : _direccionController.text.trim(),
    };

    final provider = context.read<MaletinProvider>();
    final success = await provider.updateMaletin(
      data,
      logoBytes: _logoFile != null ? await _logoFile!.readAsBytes() : null,
      logoFilename: _logoFile?.path.split(Platform.pathSeparator).last,
      bannerBytes: _bannerFile != null ? await _bannerFile!.readAsBytes() : null,
      bannerFilename: _bannerFile?.path.split(Platform.pathSeparator).last,
    );

    if (!mounted) return;
    setState(() => _saving = false);
    if (success) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Maletín actualizado correctamente'),
          backgroundColor: Colors.green,
        ),
      );
      Navigator.pop(context);
    } else {
      showErrorDialog(context, provider.errorMessage ?? 'Error al actualizar maletín');
    }
  }

  @override
  Widget build(BuildContext context) {
    final provider = context.watch<MaletinProvider>();
    return Scaffold(
      appBar: AppBar(title: const Text('Maletín digital')),
      body: provider.isLoading && provider.maletin == null
          ? const LoadingIndicator()
          : SingleChildScrollView(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  _buildImageSelector(
                    title: 'Logo',
                    hint: 'Toca para seleccionar el logo',
                    file: _logoFile,
                    existingUrl: provider.maletin?.logoUrl,
                    onTap: () => _pickImage(true),
                  ),
                  const SizedBox(height: 16),
                  _buildImageSelector(
                    title: 'Banner',
                    hint: 'Toca para seleccionar el banner',
                    file: _bannerFile,
                    existingUrl: provider.maletin?.bannerUrl,
                    onTap: () => _pickImage(false),
                  ),
                  const SizedBox(height: 16),
                  TextField(
                    controller: _tituloController,
                    decoration: const InputDecoration(
                      labelText: 'Título',
                      prefixIcon: Icon(Icons.title),
                    ),
                  ),
                  const SizedBox(height: 16),
                  TextField(
                    controller: _descripcionController,
                    decoration: const InputDecoration(
                      labelText: 'Descripción',
                      alignLabelWithHint: true,
                    ),
                    maxLines: 4,
                  ),
                  const SizedBox(height: 16),
                  TextField(
                    controller: _telefonoController,
                    decoration: const InputDecoration(
                      labelText: 'Teléfono',
                      prefixIcon: Icon(Icons.phone_outlined),
                    ),
                    keyboardType: TextInputType.phone,
                  ),
                  const SizedBox(height: 16),
                  TextField(
                    controller: _direccionController,
                    decoration: const InputDecoration(
                      labelText: 'Dirección',
                      prefixIcon: Icon(Icons.location_on_outlined),
                    ),
                  ),
                  const SizedBox(height: 24),
                  _saving
                      ? const Center(child: CircularProgressIndicator())
                      : ElevatedButton(
                          onPressed: _save,
                          child: const Text('Guardar maletín'),
                        ),
                ],
              ),
            ),
    );
  }

  Widget _buildImageSelector({
    required String title,
    required String hint,
    File? file,
    String? existingUrl,
    required VoidCallback onTap,
  }) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(title, style: const TextStyle(fontWeight: FontWeight.bold)),
        const SizedBox(height: 8),
        GestureDetector(
          onTap: onTap,
          child: Container(
            height: 120,
            decoration: BoxDecoration(
              border: Border.all(color: Colors.grey.shade300),
              borderRadius: BorderRadius.circular(12),
            ),
            clipBehavior: Clip.antiAlias,
            child: file != null
                ? Image.file(
                    file,
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
                            errorBuilder: (_, __, ___) => _placeholder(hint),
                          ),
                          const Positioned(
                            right: 8,
                            top: 8,
                            child: Chip(
                              avatar: Icon(Icons.edit_outlined, size: 16),
                              label: Text('Cambiar'),
                              visualDensity: VisualDensity.compact,
                              backgroundColor: Colors.white70,
                            ),
                          ),
                        ],
                      )
                    : _placeholder(hint),
          ),
        ),
      ],
    );
  }

  Widget _placeholder(String hint) {
    return Column(
      mainAxisAlignment: MainAxisAlignment.center,
      children: [
        Icon(Icons.add_a_photo_outlined, size: 36, color: Colors.grey.shade400),
        const SizedBox(height: 8),
        Text(hint, style: const TextStyle(color: Colors.grey)),
      ],
    );
  }
}