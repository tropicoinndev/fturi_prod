<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class tarifa_detalles extends Model
{
    use HasFactory;
    public function tipo_habitaciones()
    {
        return $this->belongsTo(tipo_habitaciones::class, 'tipo_habitaciones_id');
    }

    public function forma_habitaciones()
    {
        return $this->belongsTo(forma_habitaciones::class, 'forma_habitaciones_id');
    }

    public function tarifas()
    {
        return $this->belongsTo(tarifas::class, 'tarifas_id');
    }

    public function habitaciones()
    {
        return $this->hasMany(view_tarifas_habitaciones::class, 'tarifas_id', 'tarifas_id');
    }

    public static function getHabitaciones()
    {
        return self::leftJoin('habitaciones', function ($q) {
            $q->on('habitaciones.tipo_habitaciones_id', 'tarifa_detalles.tipo_habitaciones_id')
                ->on('habitaciones.forma_habitaciones_id', 'tarifa_detalles.forma_habitaciones_id');
        });
    }
}
