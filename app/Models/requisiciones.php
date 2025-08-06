<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;




class requisiciones extends Model
{
    use HasFactory;
    protected $appends = ['cid'];
    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn() => Crypt::encryptString($this->id)
        );
    }

    public function relacionUsuarios()
    {
        return $this->belongsTo(User::class, 'user_creacion_id');
    }

    public function relacionBodegasEntrada()
    {
        return $this->belongsTo(bodegas::class, 'bodega_entrada_id');
    }

    public function relacionBodegasSalida()
    {
        return $this->belongsTo(bodegas::class, 'bodega_salida_id');
    }

    #Para mostrar las bodegas disponibles para ese usuario.
    public function relacionBodegas()
    {
        return $this->belongsTo(bodegas::class, 'bodegas_id');
    }

    public function relacionUserAutorizacion()
    {
        return $this->belongsTo(User::class, 'user_autorizacion_id');
    }
    public function elimina()
    {
        return $this->belongsTo(User::class, 'user_elimina_id');
    }

    public function relacionUserCreacion()
    {
        return $this->belongsTo(User::class, 'user_creacion_id');
    }
    public function detalle_requisicion()
    {
        return $this->hasMany(requisicion_detalles::class, 'requisiciones_id');
    }

    public function detalle()
    {
        return $this->hasMany(requisicion_detalles::class, 'requisiciones_id')->with('productos');
    }
}
