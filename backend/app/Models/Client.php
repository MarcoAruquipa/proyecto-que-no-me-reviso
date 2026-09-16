<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'nombre',
        'ci',
        'telefono',
        'email',
        'direccion',
        'vehiculo_interes',
        'metodo_pago',
        'fuente',
        'estado',
        'notas',
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
