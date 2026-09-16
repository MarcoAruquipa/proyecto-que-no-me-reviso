import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../providers/auth_provider.dart';
import '../../models/user.dart';
import '../../widgets/status_badge.dart';
import '../../widgets/error_view.dart';

class ProfileView extends StatefulWidget {
  const ProfileView({super.key});

  @override
  State<ProfileView> createState() => _ProfileViewState();
}

class _ProfileViewState extends State<ProfileView> {
  final _formKey = GlobalKey<FormState>();
  late final TextEditingController _nameController;
  late final TextEditingController _emailController;
  late final TextEditingController _telefonoController;
  late final TextEditingController _sucursalController;
  late final TextEditingController _cargoController;
  late final TextEditingController _supervisorController;
  bool _saving = false;

  final _passwordFormKey = GlobalKey<FormState>();
  final _currentPasswordController = TextEditingController();
  final _passwordController = TextEditingController();
  final _confirmPasswordController = TextEditingController();
  bool _changingPassword = false;

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      context.read<AuthProvider>().refreshProfile();
    });
    final u = context.read<AuthProvider>().user;
    _nameController = TextEditingController(text: u?.name ?? '');
    _emailController = TextEditingController(text: u?.email ?? '');
    _telefonoController = TextEditingController(text: u?.telefono ?? '');
    _sucursalController = TextEditingController(text: u?.sucursal ?? '');
    _cargoController = TextEditingController(text: u?.cargo ?? '');
    _supervisorController = TextEditingController(text: u?.supervisor ?? '');
  }

  @override
  void dispose() {
    _nameController.dispose();
    _emailController.dispose();
    _telefonoController.dispose();
    _sucursalController.dispose();
    _cargoController.dispose();
    _supervisorController.dispose();
    _currentPasswordController.dispose();
    _passwordController.dispose();
    _confirmPasswordController.dispose();
    super.dispose();
  }

  void _syncControllers(User? user) {
    if (user == null) return;
    _nameController.text = user.name ?? '';
    _emailController.text = user.email ?? '';
    _telefonoController.text = user.telefono ?? '';
    _sucursalController.text = user.sucursal ?? '';
    _cargoController.text = user.cargo ?? '';
    _supervisorController.text = user.supervisor ?? '';
  }

  Future<void> _saveProfile() async {
    if (!_formKey.currentState!.validate()) return;
    setState(() => _saving = true);
    final provider = context.read<AuthProvider>();
    final success = await provider.updateProfile(
      name: _nameController.text.trim(),
      email: _emailController.text.trim(),
      telefono: _telefonoController.text.trim().isEmpty
          ? null
          : _telefonoController.text.trim(),
      sucursal: _sucursalController.text.trim().isEmpty
          ? null
          : _sucursalController.text.trim(),
      cargo: _cargoController.text.trim().isEmpty
          ? null
          : _cargoController.text.trim(),
      supervisor: _supervisorController.text.trim().isEmpty
          ? null
          : _supervisorController.text.trim(),
    );
    if (!mounted) return;
    setState(() => _saving = false);
    if (success) {
      showSuccessSnackbar(context, 'Perfil actualizado correctamente');
    } else {
      showErrorDialog(context, provider.errorMessage ?? 'Error al actualizar perfil');
    }
  }

  Future<void> _changePassword() async {
    if (!_passwordFormKey.currentState!.validate()) return;
    setState(() => _changingPassword = true);
    final provider = context.read<AuthProvider>();
    final success = await provider.changePassword(
      currentPassword: _currentPasswordController.text,
      newPassword: _passwordController.text,
    );
    if (!mounted) return;
    setState(() => _changingPassword = false);
    if (success) {
      _currentPasswordController.clear();
      _passwordController.clear();
      _confirmPasswordController.clear();
      showSuccessSnackbar(context, 'Contraseña cambiada correctamente');
    } else {
      showErrorDialog(context, provider.errorMessage ?? 'Error al cambiar contraseña');
    }
  }

  @override
  Widget build(BuildContext context) {
    final authProvider = context.watch<AuthProvider>();
    final user = authProvider.user;
    if (user != null) _syncControllers(user);

    return Scaffold(
      appBar: AppBar(title: const Text('Mi perfil')),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          Card(
            margin: EdgeInsets.zero,
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Row(
                children: [
                  CircleAvatar(
                    radius: 30,
                    backgroundColor:
                        Theme.of(context).colorScheme.primary,
                    child: Text(
                      (user?.name ?? 'U').substring(0, 1).toUpperCase(),
                      style: const TextStyle(
                        fontSize: 30,
                        color: Colors.white,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
                  const SizedBox(width: 16),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          user?.name ?? 'Usuario',
                          style: const TextStyle(
                            fontSize: 18,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        const SizedBox(height: 4),
                        Text(
                          '@${user?.username ?? ''}',
                          style: const TextStyle(color: Colors.grey),
                        ),
                        const SizedBox(height: 4),
                        StatusBadge(status: user?.role ?? 'invitado', small: true),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ),
          const SizedBox(height: 16),
          const Text(
            'Información personal',
            style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 8),
          Form(
            key: _formKey,
            child: Column(
              children: [
                TextFormField(
                  controller: _nameController,
                  decoration: const InputDecoration(
                    labelText: 'Nombre completo',
                    prefixIcon: Icon(Icons.person_outline),
                  ),
                  validator: (value) {
                    if (value == null || value.trim().isEmpty) {
                      return 'El nombre es obligatorio';
                    }
                    return null;
                  },
                ),
                const SizedBox(height: 16),
                TextFormField(
                  controller: _emailController,
                  decoration: const InputDecoration(
                    labelText: 'Email',
                    prefixIcon: Icon(Icons.email_outlined),
                  ),
                  keyboardType: TextInputType.emailAddress,
                  validator: (value) {
                    if (value == null || value.trim().isEmpty) {
                      return 'El email es obligatorio';
                    }
                    return null;
                  },
                ),
                const SizedBox(height: 16),
                TextFormField(
                  controller: _telefonoController,
                  decoration: const InputDecoration(
                    labelText: 'Teléfono',
                    prefixIcon: Icon(Icons.phone_outlined),
                  ),
                  keyboardType: TextInputType.phone,
                ),
                const SizedBox(height: 16),
                TextFormField(
                  controller: _sucursalController,
                  decoration: const InputDecoration(
                    labelText: 'Sucursal',
                    prefixIcon: Icon(Icons.store_outlined),
                  ),
                ),
                const SizedBox(height: 16),
                TextFormField(
                  controller: _cargoController,
                  decoration: const InputDecoration(
                    labelText: 'Cargo',
                    prefixIcon: Icon(Icons.work_outline),
                  ),
                ),
                const SizedBox(height: 16),
                TextFormField(
                  controller: _supervisorController,
                  decoration: const InputDecoration(
                    labelText: 'Supervisor',
                    prefixIcon: Icon(Icons.supervisor_account_outlined),
                  ),
                ),
                const SizedBox(height: 24),
                _saving
                    ? const Center(child: CircularProgressIndicator())
                    : ElevatedButton(
                        onPressed: _saveProfile,
                        child: const Text('Guardar cambios'),
                      ),
              ],
            ),
          ),
          const SizedBox(height: 24),
          const Divider(),
          const SizedBox(height: 8),
          const Text(
            'Cambiar contraseña',
            style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 8),
          Form(
            key: _passwordFormKey,
            child: Column(
              children: [
                TextFormField(
                  controller: _currentPasswordController,
                  decoration: const InputDecoration(
                    labelText: 'Contraseña actual',
                    prefixIcon: Icon(Icons.lock_outline),
                  ),
                  obscureText: true,
                  validator: (value) {
                    if (value == null || value.isEmpty) {
                      return 'Ingresa tu contraseña actual';
                    }
                    return null;
                  },
                ),
                const SizedBox(height: 16),
                TextFormField(
                  controller: _passwordController,
                  decoration: const InputDecoration(
                    labelText: 'Nueva contraseña',
                    prefixIcon: Icon(Icons.lock_outline),
                  ),
                  obscureText: true,
                  validator: (value) {
                    if (value == null || value.isEmpty) {
                      return 'Ingresa la nueva contraseña';
                    }
                    if (value.length < 8) {
                      return 'Debe tener al menos 8 caracteres';
                    }
                    return null;
                  },
                ),
                const SizedBox(height: 16),
                TextFormField(
                  controller: _confirmPasswordController,
                  decoration: const InputDecoration(
                    labelText: 'Confirmar nueva contraseña',
                    prefixIcon: Icon(Icons.lock_outline),
                  ),
                  obscureText: true,
                  validator: (value) {
                    if (value != _passwordController.text) {
                      return 'Las contraseñas no coinciden';
                    }
                    return null;
                  },
                ),
                const SizedBox(height: 24),
                _changingPassword
                    ? const Center(child: CircularProgressIndicator())
                    : ElevatedButton.icon(
                        onPressed: _changePassword,
                        icon: const Icon(Icons.password_outlined),
                        label: const Text('Cambiar contraseña'),
                      ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}