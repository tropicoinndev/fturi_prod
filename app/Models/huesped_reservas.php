<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Crypt;


class huesped_reservas extends Model
{
    use HasFactory;
    public function huesped()
    {
        return $this->belongsTo(huespedes::class, 'huespedes_id');
    }

    public function detalle_reservas()
    {
        return $this->belongsTo(detalle_reservas::class, 'detalle_reservas_id');
    }
    public function id(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Crypt::encryptString($value)
        );
    }
}
