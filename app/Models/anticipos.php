<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;

class anticipos extends Model
{
    use HasFactory;
    protected $appends = ['cid', 'sum_aplicado', 'creacion', 'aplicacion'];
    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn() => Crypt::encryptString($this->id)
        );
    }
    public function sumAplicado(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->aplicado()->sum('monto')
        );
    }
    public function creacion(): Attribute
    {
        return Attribute::make(
            get: fn() => Carbon::parse($this->attributes['created_at'])->isoFormat('dddd, D [de] MMMM [de] YYYY') . ' a las ' . Carbon::parse($this->attributes['created_at'])->format('h:i a')
        );
    }
    public function aplicacion(): Attribute
    {
        return Attribute::make(
            get: fn() => Carbon::parse($this->attributes['fecha_aplicacion'])->isoFormat('dddd, D [de] MMMM [de] YYYY')
        );
    }

    public function clientes()
    {
        return $this->belongsTo(clientes::class, 'clientes_id');
    }
    public function forma_pagos()
    {
        return $this->belongsTo(forma_pagos::class, 'forma_pagos_id');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
    public function turnos()
    {
        return $this->belongsTo(turnos::class, 'turnos_id')->with('cajas');
    }
    public function anula_users()
    {
        return $this->belongsTo(User::class, 'users_anula_id');
    }
    public function aplicado()
    {
        return $this->hasMany(anticipos_cobros::class, 'anticipos_id')->where('turnos_id', $this->turnos_id)->where('aplicado', true);
    }
}
