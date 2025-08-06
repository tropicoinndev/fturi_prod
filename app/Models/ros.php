<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class ros extends Model
{
    use HasFactory;

    protected $appends = ['cid'];

    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn() => Crypt::encryptString($this->id)
        );
    }
    public function sucursales()
    {
        return $this->belongsTo(sucursales::class, 'sucursales_id');
    }
    public function forma_pagos()
    {
        return $this->belongsTo(forma_pagos::class, 'forma_pagos_id');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
    public function identificaciones()
    {
        return $this->belongsTo(identificaciones::class, 'identificaciones_id');
    }
    public function comprobante()
    {
        return $this->belongsTo(comprobantes::class, 'comprobantes_id');
    }
}
