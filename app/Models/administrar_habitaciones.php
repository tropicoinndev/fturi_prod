<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class administrar_habitaciones extends Model
{
    use HasFactory;

    public function relacionTipoHabitaciones(){
        return $this->belongsTo(tipo_habitaciones::class,'tipo_habitaciones_id');
    }

    public function relacionFormaHabitaciones(){
        return $this->belongsTo(forma_habitaciones::class,'forma_habitaciones_id');
    }

    public function relacionEstadoHabitaciones(){
        return $this->belongsTo(estado_habitaciones::class,'estado_habitaciones_id');
    }

    public function relacionUbicacionHabitaciones(){
        return $this->belongsTo(ubicacion_habitaciones::class,'ubicacion_habitaciones_id');
    }
}
