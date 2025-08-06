<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class caja_turnos extends Model
{
    use HasFactory;

    public function opcion_turnos(){
        return $this->belongsTo(opcion_turnos::class,'opcion_turnos_id');
    }

    public function cajas(){
        return $this->belongsTo(cajas::class,'cajas_id');
    }
}
