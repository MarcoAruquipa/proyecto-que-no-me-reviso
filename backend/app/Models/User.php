<?php

namespace App\Models;

use App\Notifications\ResetPasswordEs;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'telefono',
        'sucursal',
        'cargo',
        'supervisor',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordEs($token));
    }

    public function maletin()
    {
        return $this->hasOne(Maletin::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}