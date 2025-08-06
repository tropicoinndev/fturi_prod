<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class detalle_reservas extends Model
{
    use HasFactory;

    private $detalleReservasId;
    protected $appends = ['cid', 'dias', 'total'];

    public function relacionReservaciones()
    {
        return $this->belongsTo(reservaciones::class, 'reservaciones_id')->with('relacionClientes');
    }
    public function reservacion()
    {
        return $this->belongsTo(reservaciones::class, 'reservaciones_id')->with('forma_pago');
    }
    public function relacionHabitaciones()
    {
        return $this->belongsTo(habitaciones::class, 'habitaciones_id');
    }

    public function relacionTarifas()
    {
        return $this->belongsTo(tarifas::class, 'tarifas_id');
    }

    public function relacionUsuarios()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    #Encriptar el id.
    public function getDetalleReservasIdAttribute(): Attribute
    {
        return Attribute::make(
            get: fn() => Crypt::encryptString($this->id)
        );
    }

    public function huespedes()
    {
        return $this->hasMany(huesped_reservas::class, 'detalle_reservas_id')->with('huesped');
    }

    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn() => Crypt::encryptString($this->id)
        );
    }
    public function dias(): Attribute
    {
        return Attribute::make(
            get: fn() => (new DateTime($this->fecha_ingreso))->diff(new DateTime($this->fecha_salida))->days
        );
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
    public function total(): Attribute
    {
        return Attribute::make(
            get: fn() => (float) $this->dias * $this->relacionTarifas->precio
        );
    }
}
