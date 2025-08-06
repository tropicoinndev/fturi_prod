<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class precios extends Model
{
    use HasFactory;

    protected $table = 'precios';

    #Mutamos el campo id y lo retornamos encriptado
    /*public function getIdAttribute($value){
        return Crypt::encryptString($value);
    }*/

    public function categorias_precios()
    {
        return $this->belongsTo(categorias_precios::class, "categorias_precios_id")->with('rubros');
    }

    public function detalle_producto()
    {
        return $this->hasMany(precio_productos::class, 'precios_id')
            ->with('productos');
    }
    public function precio_cajas()
    {
        return $this->hasMany(caja_precios::class, 'precios_id')->with('cajas');
    }
    public function detalle_existencias()
    {
        return $this->hasMany(existencias::class, 'id')->with('pruductos');
    }
}
