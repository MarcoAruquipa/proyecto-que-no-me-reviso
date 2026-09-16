<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','jefe','tecnico','invitado') NOT NULL DEFAULT 'invitado'");

        if (! DB::table('users')->where('role', 'jefe')->exists()) {
            DB::table('users')->insert([
                'name' => 'Jefe de Sucursal',
                'username' => 'jefe1',
                'email' => 'jefe1@pegaso.com',
                'password' => Hash::make('jefe123'),
                'role' => 'jefe',
                'sucursal' => 'Bolivia Central',
                'cargo' => 'Jefe de Sucursal',
                'supervisor' => 'Administrador General',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','tecnico','invitado') NOT NULL DEFAULT 'invitado'");
    }
};