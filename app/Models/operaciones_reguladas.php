<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class operaciones_reguladas extends Model
{
    use HasFactory;
    protected $appends = ['cid'];
    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn() => Crypt::encryptString($this->id)
        );
    }

    public function comprobante()
    {
        return $this->belongsTo(comprobantes::class, 'comprobantes_id');
    }
    public function pago()
    {
        return $this->belongsTo(forma_pagos::class, 'forma_pagos_id');
    }

    public function apersona()
    {
        return $this->belongsTo(personas_naturales::class, 'seccion_a_persona_id');
    }

    public function bpersona()
    {
        return $this->belongsTo(personas_naturales::class, 'seccion_b_persona_id');
    }
    public function bjuridico()
    {
        return $this->belongsTo(clientes::class, 'seccion_b_juridico_id');
    }

    public function empleado()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
    public function supervisa()
    {
        return $this->belongsTo(User::class, 'users_revisa_id');
    }
    public function autoriza()
    {
        return $this->belongsTo(User::class, 'users_autoriza_id');
    }

    public function caja()
    {
        return $this->belongsTo(cajas::class, 'cajas_id');
    }
}
