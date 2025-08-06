<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#Agregar.
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class compras extends Model
{
    use HasFactory;


    protected $table = 'compras';
    protected $appends = ['formateada'];


    public function relacionProveedores()
    {
        return $this->belongsTo(proveedores::class, 'proveedores_id');
    }

    public function relacionTipoPagos()
    {
        return $this->belongsTo(tipo_pagos::class, 'tipo_pagos_id');
    }

    public function relacionUsuarios()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function relacionRequisiones()
    {
        return $this->belongsTo(requisiciones::class, 'requisiones_id');
    }

    public function id(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Crypt::encryptString($value)
        );
    }
    public function compra(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->attributes['id']
        );
    }
    public function formateada(): Attribute
    {
        return Attribute::make(get: fn() => Carbon::parse($this->fecha)->Format('Y-m-d'));
    }
}
