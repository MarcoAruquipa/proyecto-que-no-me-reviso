import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'config/theme.dart';
import 'models/vehicle.dart';
import 'models/client.dart';
import 'providers/auth_provider.dart';
import 'providers/vehicle_provider.dart';
import 'providers/client_provider.dart';
import 'providers/sale_provider.dart';
import 'providers/maletin_provider.dart';
import 'providers/dashboard_provider.dart';
import 'providers/catalog_provider.dart';
import 'providers/supervision_provider.dart';
import 'views/auth/login_view.dart';
import 'views/auth/register_view.dart';
import 'views/auth/splash_view.dart';
import 'views/home/home_view.dart';
import 'views/vehicles/vehicle_list_view.dart';
import 'views/vehicles/vehicle_form_view.dart';
import 'views/vehicles/vehicle_detail_view.dart';
import 'views/clients/client_list_view.dart';
import 'views/clients/client_form_view.dart';
import 'views/clients/client_detail_view.dart';
import 'views/sales/sale_list_view.dart';
import 'views/sales/sale_form_view.dart';
import 'views/maletin/maletin_view.dart';
import 'views/dashboard/dashboard_view.dart';
import 'views/comisiones/comisiones_view.dart';
import 'views/profile/profile_view.dart';
import 'views/supervision/supervision_view.dart';

void main() {
  runApp(const MaletinApp());
}

class MaletinApp extends StatelessWidget {
  const MaletinApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => AuthProvider()),
        ChangeNotifierProvider(create: (_) => VehicleProvider()),
        ChangeNotifierProvider(create: (_) => ClientProvider()),
        ChangeNotifierProvider(create: (_) => SaleProvider()),
        ChangeNotifierProvider(create: (_) => MaletinProvider()),
        ChangeNotifierProvider(create: (_) => DashboardProvider()),
        ChangeNotifierProvider(create: (_) => CatalogProvider()),
        ChangeNotifierProvider(create: (_) => SupervisionProvider()),
      ],
      child: MaterialApp(
        title: 'Pegaso Motors',
        debugShowCheckedModeBanner: false,
        theme: AppTheme.lightTheme,
        initialRoute: '/',
        routes: {
          '/': (context) => const SplashView(),
          '/login': (context) => const LoginView(),
          '/register': (context) => const RegisterView(),
          '/home': (context) => const HomeView(),
          '/dashboard': (context) => const DashboardView(),
          '/vehiculos': (context) => const VehicleListView(),
          '/clientes': (context) => const ClientListView(),
          '/ventas': (context) => const SaleListView(),
          '/ventas/form': (context) => const SaleFormView(),
          '/maletin': (context) => const MaletinView(),
          '/comisiones': (context) => const ComisionesView(),
          '/perfil': (context) => const ProfileView(),
          '/supervision': (context) => const SupervisionView(),
        },
        onGenerateRoute: (settings) {
          switch (settings.name) {
            case '/vehiculos/form':
              final args = settings.arguments as Map<String, dynamic>?;
              return MaterialPageRoute(
                builder: (_) => VehicleFormView(
                  vehicle: args?['vehicle'] as Vehicle?,
                  isEditing: args?['isEditing'] as bool? ?? false,
                ),
              );
            case '/vehiculos/detail':
              final vehicle = settings.arguments as Vehicle?;
              return MaterialPageRoute(
                builder: (_) => VehicleDetailView(vehicle: vehicle!),
              );
            case '/clientes/form':
              final args = settings.arguments as Map<String, dynamic>?;
              return MaterialPageRoute(
                builder: (_) => ClientFormView(
                  client: args?['client'] as Client?,
                  isEditing: args?['isEditing'] as bool? ?? false,
                ),
              );
            case '/clientes/detail':
              final client = settings.arguments as Client?;
              return MaterialPageRoute(
                builder: (_) => ClientDetailView(client: client!),
              );
            default:
              return MaterialPageRoute(builder: (_) => const SplashView());
          }
        },
      ),
    );
  }
}