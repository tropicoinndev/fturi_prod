<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class comprobantes extends Model
{
    use HasFactory;
    protected $appends = ['cid', 'creacion', 'turnos_cid'];
    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn() => Crypt::encryptString($this->id)
        );
    }

    public function creacion(): Attribute
    {
        return Attribute::make(
            get: fn() => Carbon::parse($this->created_at)->diffForHumans()

        );
    }
    public function turnosCid(): Attribute
    {
        return Attribute::make(
            get: fn() => Crypt::encryptString($this->turnos_id)

        );
    }

    public function clientes()
    {
        return $this->belongsTo(clientes::class, 'clientes_id');
    }

    public function clientes_solicitante()
    {
        return $this->belongsTo(clientes::class, 'clientes_id')->with('solicitantes');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function turnos()
    {
        return $this->belongsTo(turnos::class, 'turnos_id');
    }

    public function tipoComprobantes()
    {
        return $this->belongsTo(tipo_comprobantes::class, 'tipo_comprobantes_id');
    }
    public function pagos()
    {
        return $this->hasMany(comprobantes_pagos::class, 'comprobantes_id')->with('forma_pagos');
    }
    public function detalles()
    {
        return $this->hasMany(detalle_comprobantes::class, 'comprobantes_id');
    }
    public function anulacion()
    {
        return $this->hasMany(anulacion_comprobantes::class, 'comprobantes_id');
    }
    public function anulacionState()
    {
        return $this->hasOne(anulacion_comprobantes::class, 'comprobantes_id')->with('invalidacion');
    }
    public function getAnulacionOrdenes()
    {
        return $this->hasMany(anulacion_registros::class, 'comprobantes_id')->where('tipo_registros', 1);
    }
    public function getAnulacionEstadias()
    {
        return $this->hasMany(anulacion_registros::class, 'comprobantes_id')->where('tipo_registros', 2);
    }
    public function getAnulacionComandas()
    {
        return $this->hasMany(anulacion_registros::class, 'comprobantes_id')->where('tipo_registros', 3);
    }

    public function turnosCajas()
    {
        return $this->belongsTo(turnos::class, 'turnos_id')->with('cajasSucursales');
    }

    public function dte()
    {
        return $this->hasMany(dtes::class, 'comprobantes_id');
    }
    public function dteOne()
    {
        return $this->hasOne(dtes::class, 'comprobantes_id');
    }

    public function turnosOpcion()
    {
        return $this->belongsTo(turnos::class, 'turnos_id')->with(['opcion']);
    }

    public function cobro()
    {
        return $this->hasOne(cobros::class, 'comprobantes_id')->with(['anticipos']);
    }
    public function regestadia()
    {
        return $this->hasOne(registro::class, 'comprobantes_id')->where('tipo_registros', 2);
    }

    public function regulada()
    {
        return $this->hasOne(operaciones_reguladas::class, 'comprobantes_id');
    }

    public function sucursal()
    {
        return $this->belongsTo(sucursales::class, 'sucursales_id');
    }
}
