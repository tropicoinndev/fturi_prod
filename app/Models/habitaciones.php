<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class habitaciones extends Model
{
    use HasFactory;
    protected $appends = ['cid'];
    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn() => Crypt::encryptString($this->id)
        );
    }
    public function relacionTipoHabitaciones()
    {
        return $this->belongsTo(tipo_habitaciones::class, 'tipo_habitaciones_id');
    }

    public function relacionFormaHabitaciones()
    {
        return $this->belongsTo(forma_habitaciones::class, 'forma_habitaciones_id');
    }
    public function sucursales()
    {
        return $this->belongsTo(sucursales::class, 'sucursales_id');
    }

    public function relacionEstadoHabitaciones()
    {
        return $this->belongsTo(estado_habitaciones::class, 'estado_habitaciones_id');
    }

    public function relacionUbicacionHabitaciones()
    {
        return $this->belongsTo(ubicacion_habitaciones::class, 'ubicacion_habitaciones_id');
    }

    public function getTarifaDetalle()
    {
        return $this->leftJoin('tarifa_detalles', function ($d) {
            $d->on('habitaciones.tipo_habitaciones_id', '=', 'tarifa_detalles.tipo_habitaciones_id')
                ->on('habitaciones.forma_habitaciones_id', '=', 'tarifa_detalles.forma_habitaciones_id');
        });
    }
    public function getTarifas()
    {
        return $this->hasMany(view_tarifas_habitaciones::class, 'id', 'id');
    }
    public function recepcion()
    {
        return $this->hasMany(recepciones::class, 'habitaciones_id')->where('estado', true);
    }
    public function reservas()
    {

        return $this->hasMany(detalle_reservas::class, 'habitaciones_id')
            ->where('estado', true)
            ->whereDate('fecha_ingreso', date('Y-m-d'))
            ->with(['relacionTarifas', 'reservacion']);
    }
    public function reserva()
    {
        return $this->hasMany(detalle_reservas::class, 'habitaciones_id')
            ->where('estado', true)
            ->whereDate('fecha_ingreso', date('Y-m-d'))
            ->with(['relacionTarifas', 'relacionReservaciones', 'huespedes']);
    }

    public function mantenimientos()
    {
        return $this->hasMany(mantenimientos::class, 'habitaciones_id')
            ->whereIn('estado', ['sin asignar', 'asignado', 'iniciado'])
            ->with(['asignado', 'tipo_mantenimientos']);
    }

    public function habitacionCamas()
    {
        return $this->hasMany(habitacion_camas::class, 'habitaciones_id')
            ->with(['tipo_camas']);
    }
}
