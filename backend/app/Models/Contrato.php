<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contrato extends Model
{
    protected $fillable = [
        'user_id',
        'client_id',
        'vehicle_id',
        'estado',
        'fecha_firma',
        'notas',
    ];

    protected $casts = [
        'client_id' => 'integer',
        'vehicle_id' => 'integer',
        'fecha_firma' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}