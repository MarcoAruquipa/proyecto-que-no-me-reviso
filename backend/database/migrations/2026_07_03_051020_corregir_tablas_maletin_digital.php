<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // USERS
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->unique()->nullable()->after('name');
            }

            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('invitado')->after('password');
            }

            if (!Schema::hasColumn('users', 'telefono')) {
                $table->string('telefono')->nullable();
            }

            if (!Schema::hasColumn('users', 'sucursal')) {
                $table->string('sucursal')->nullable();
            }

            if (!Schema::hasColumn('users', 'cargo')) {
                $table->string('cargo')->nullable();
            }

            if (!Schema::hasColumn('users', 'supervisor')) {
                $table->string('supervisor')->nullable();
            }
        });

        // VEHICLES
        Schema::table('vehicles', function (Blueprint $table) {
            if (!Schema::hasColumn('vehicles', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }

            if (!Schema::hasColumn('vehicles', 'marca')) {
                $table->string('marca')->nullable();
            }

            if (!Schema::hasColumn('vehicles', 'modelo')) {
                $table->string('modelo')->nullable();
            }

            if (!Schema::hasColumn('vehicles', 'anio')) {
                $table->integer('anio')->nullable();
            }

            if (!Schema::hasColumn('vehicles', 'placa')) {
                $table->string('placa')->nullable();
            }

            if (!Schema::hasColumn('vehicles', 'color')) {
                $table->string('color')->nullable();
            }

            if (!Schema::hasColumn('vehicles', 'precio')) {
                $table->decimal('precio', 12, 2)->default(0);
            }

            if (!Schema::hasColumn('vehicles', 'estado')) {
                $table->string('estado')->default('Disponible');
            }

            if (!Schema::hasColumn('vehicles', 'descripcion')) {
                $table->text('descripcion')->nullable();
            }

            if (!Schema::hasColumn('vehicles', 'imagen')) {
                $table->string('imagen')->nullable();
            }
        });

        // CLIENTS
        Schema::table('clients', function (Blueprint $table) {
            if (!Schema::hasColumn('clients', 'nombre')) {
                $table->string('nombre')->nullable();
            }

            if (!Schema::hasColumn('clients', 'ci')) {
                $table->string('ci')->nullable();
            }

            if (!Schema::hasColumn('clients', 'telefono')) {
                $table->string('telefono')->nullable();
            }

            if (!Schema::hasColumn('clients', 'email')) {
                $table->string('email')->nullable();
            }

            if (!Schema::hasColumn('clients', 'direccion')) {
                $table->string('direccion')->nullable();
            }

            if (!Schema::hasColumn('clients', 'vehiculo_interes')) {
                $table->string('vehiculo_interes')->nullable();
            }

            if (!Schema::hasColumn('clients', 'metodo_pago')) {
                $table->string('metodo_pago')->nullable();
            }

            if (!Schema::hasColumn('clients', 'fuente')) {
                $table->string('fuente')->nullable();
            }

            if (!Schema::hasColumn('clients', 'estado')) {
                $table->string('estado')->default('En negociación');
            }

            if (!Schema::hasColumn('clients', 'notas')) {
                $table->text('notas')->nullable();
            }
        });

        // SALES
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'vehicle_id')) {
                $table->unsignedBigInteger('vehicle_id')->nullable();
            }

            if (!Schema::hasColumn('sales', 'client_id')) {
                $table->unsignedBigInteger('client_id')->nullable();
            }

            if (!Schema::hasColumn('sales', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable();
            }

            if (!Schema::hasColumn('sales', 'fecha')) {
                $table->date('fecha')->nullable();
            }

            if (!Schema::hasColumn('sales', 'precio_venta')) {
                $table->decimal('precio_venta', 12, 2)->default(0);
            }

            if (!Schema::hasColumn('sales', 'observacion')) {
                $table->text('observacion')->nullable();
            }
        });

        // MALETINS
        Schema::table('maletins', function (Blueprint $table) {
            if (!Schema::hasColumn('maletins', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }

            if (!Schema::hasColumn('maletins', 'titulo')) {
                $table->string('titulo')->nullable();
            }

            if (!Schema::hasColumn('maletins', 'descripcion')) {
                $table->text('descripcion')->nullable();
            }

            if (!Schema::hasColumn('maletins', 'telefono')) {
                $table->string('telefono')->nullable();
            }

            if (!Schema::hasColumn('maletins', 'direccion')) {
                $table->string('direccion')->nullable();
            }

            if (!Schema::hasColumn('maletins', 'logo')) {
                $table->string('logo')->nullable();
            }

            if (!Schema::hasColumn('maletins', 'banner')) {
                $table->string('banner')->nullable();
            }
        });
    }

    public function down(): void
    {
        // No se eliminan columnas para evitar borrar datos del proyecto.
    }
};
