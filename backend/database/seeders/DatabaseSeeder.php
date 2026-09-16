<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Maletin;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Administrador',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        $tecnico = User::create([
            'name' => 'Técnico 1',
            'username' => 'tecnico1',
            'email' => 'tecnico1@gmail.com',
            'password' => Hash::make('tecnico123'),
            'role' => 'tecnico',
        ]);

        User::create([
            'name' => 'Invitado',
            'username' => 'invitado',
            'email' => 'invitado@gmail.com',
            'password' => Hash::make('invitado123'),
            'role' => 'invitado',
        ]);

        Maletin::create([
            'user_id' => $tecnico->id,
            'titulo' => 'Maletín Digital Técnico 1',
            'descripcion' => 'Venta de movilidades nuevas y usadas.',
            'telefono' => '70000000',
            'direccion' => 'Bolivia',
        ]);

        Vehicle::create([
            'user_id' => $tecnico->id,
            'marca' => 'Toyota',
            'modelo' => 'Corolla',
            'anio' => 2018,
            'placa' => '1234ABC',
            'color' => 'Blanco',
            'precio' => 85000,
            'estado' => 'Disponible',
            'descripcion' => 'Movilidad en buen estado.',
        ]);

        Vehicle::create([
            'user_id' => $tecnico->id,
            'marca' => 'Suzuki',
            'modelo' => 'Swift',
            'anio' => 2020,
            'placa' => '5678DEF',
            'color' => 'Rojo',
            'precio' => 95000,
            'estado' => 'Disponible',
            'descripcion' => 'Auto económico y moderno.',
        ]);

        Client::create([
            'nombre' => 'Juan Pérez',
            'ci' => '1234567',
            'telefono' => '76543210',
            'email' => 'juan@gmail.com',
            'direccion' => 'La Paz',
        ]);
    }
}