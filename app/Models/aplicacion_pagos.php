<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class aplicacion_pagos extends Model
{
    use HasFactory;
    protected $table = 'aplicacion_pagos';
    protected $appends = ['cid', 'creacion', 'modificacion'];
    public function cid(): Attribute
    {
        return Attribute::make(get: fn () => Crypt::encryptString($this->id));
    }
    public function creacion(): Attribute
    {
        return Attribute::make(get: fn () => Carbon::parse($this->created_at)->diffForHumans());
    }
    public function modificacion(): Attribute
    {
        return Attribute::make(get: fn () => Carbon::parse($this->updated_at)->diffForHumans());
    }

    public function usuarios(){

        return $this->belongsTo(User::class, 'users_id');
    }
    public function pago_anticipados()
    {

        return $this->belongsTo(pago_anticipado::class, 'pago_anticipados_id');
    }
    public function orden()
    {
        if ($this->origen == 1)
            return $this->belongsTo(ordenes::class, 'origen_id');
    }
    public function recepcion()
    {
        if ($this->origen == 2)
            return $this->belongsTo(recepciones::class, 'origen_id');
    }
    public function comanda()
    {
        if ($this->origen == 3)
            return $this->belongsTo(comandas::class, 'origen_id');
    }
}
