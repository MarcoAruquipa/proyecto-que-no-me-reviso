<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maletin extends Model
{
    protected $fillable = [
        'user_id',
        'titulo',
        'descripcion',
        'telefono',
        'direccion',
        'logo',
        'banner',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}