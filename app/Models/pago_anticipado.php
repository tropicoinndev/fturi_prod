<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class pago_anticipado extends Model
{
    use HasFactory;
    protected $table = 'pago_anticipados';
    protected $appends = ['cid','creacion','modificacion'];
    public function cid(): Attribute
    {
        return Attribute::make(get: fn () => Crypt::encryptString($this->id));
    }
    public function creacion(): Attribute
    {
        return Attribute::make(get: fn() => Carbon::parse($this->created_at)->diffForHumans());
    }
    public function modificacion(): Attribute{
        return Attribute::make(get: fn() => Carbon::parse($this->updated_at)->diffForHumans());
    }
    public function forma_pagos()
    {
        return $this->belongsTo(forma_pagos::class, 'forma_pagos_id');
    }
    public function clientes()
    {
        return $this->belongsTo(clientes::class, 'clientes_id');
    }
    public function cajas()
    {
        return $this->belongsTo(cajas::class, 'cajas_id');
    }
    public function usuarios(){
        return $this->belongsTo(User::class, 'users_id');
    }
    public function turnos()
    {
        return $this->belongsTo(turnos::class, 'turnos_id');
    }
    public function  aplicacion_pagos()
    {
        return $this->hasMany(aplicacion_pagos::class, 'pago_anticipados_id')->where('estado',true);
    }
    public function comprobantes()
    {
        return $this->belongsTo(comprobantes::class, 'comprobantes_id');
    }
    public function cobros()
    {
        return $this->belongsTo(cobros::class, 'cobros_id');
    }
}
