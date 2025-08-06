<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class bodegas extends Model
{
    use HasFactory;

    #Mutamos el campo id y lo retornamos encriptado
    /*public function getIdAttribute($value){
        return Crypt::encryptString($value);
    }*/

    public function relacionBodegasSalida()
    {
        return $this->belongsTo(bodegas::class, 'bodega_salida_id');
    }

    public function relacionBodegasEntrada()
    {
        return $this->belongsTo(bodegas::class, 'bodega_entrada_id');
    }

    public function relacionUserAutorizacion()
    {
        return $this->belongsTo(User::class, 'user_autorizacion_id');
    }

    public function relacionUserCreacion()
    {
        return $this->belongsTo(User::class, 'user_creacion_id');
    }

    /* public function relacionUserElimina(){
        return $this->belongsTo(User::class,'user_elimina_id');
    } */
}
