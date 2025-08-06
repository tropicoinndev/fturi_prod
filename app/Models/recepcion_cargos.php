<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class recepcion_cargos extends Model
{
    //cSpell:ignore recepcion, cesc, tiva, tcesc, tpropina
    use HasFactory;
    protected $appends = ['total', 'tiva', 'tcesc', 'tpropina', 'tneto', 'neto'];

    public function total(): Attribute
    {
        return Attribute::make(fn() => $this->attributes['cantidad'] * $this->attributes['precio']);
    }
    public function tneto(): Attribute
    {
        return Attribute::make(
            fn() =>
            $this->attributes['cantidad'] * ($this->attributes['precio'] - $this->attributes['iva'] - $this->attributes['cesc'] - $this->attributes['propina'])
        );
    }
    public function neto(): Attribute
    {
        return Attribute::make(
            fn() =>
            $this->attributes['precio'] - $this->attributes['iva'] - $this->attributes['cesc'] - $this->attributes['propina']
        );
    }
    public function tiva(): Attribute
    {
        return Attribute::make(fn() => $this->attributes['cantidad'] * $this->attributes['iva']);
    }
    public function tcesc(): Attribute
    {
        return Attribute::make(fn() => $this->attributes['cantidad'] * $this->attributes['cesc']);
    }
    public function tpropina(): Attribute
    {
        return Attribute::make(fn() => $this->attributes['cantidad'] * $this->attributes['propina']);
    }
    public function cargos()
    {
        return $this->belongsTo(cargos::class, 'cargos_id');
    }
    public function usuarios()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
