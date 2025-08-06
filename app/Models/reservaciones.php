<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class reservaciones extends Model
{
    use HasFactory;

    private $cid;
    protected $appends = ['cid', 'monto_reserva'];
    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn() => Crypt::encryptString($this->id)
        );
    }
    public function relacionClientes()
    {
        return $this->belongsTo(clientes::class, 'clientes_id')->with('contactos');
    }

    public function relacionTipoReservaciones()
    {
        return $this->belongsTo(tipo_reservaciones::class, 'tipo_reservaciones_id');
    }
    public function detalleReservaciones()
    {
        return $this->hasMany(detalle_reservas::class, 'reservaciones_id', 'id')->with(['relacionHabitaciones', 'relacionTarifas', 'relacionUsuarios', 'huespedes']);
    }
    public function anticipos()
    {
        return $this->hasMany(anticipo_reservacion::class, 'reservacion_id', 'id')->where('tipo_reservacion', 1)->with('anticipos')->withSum('anticipos', 'monto');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
    public function forma_pago()
    {
        return $this->belongsTo(forma_pagos::class, 'forma_pagos_id');
    }
    public function montoReserva(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->detalleReservaciones->reduce(function ($total, $detalle) {

                return $total + ($detalle->relacionTarifas->precio * $detalle->dias);
            }, 0.0)
        );
    }
}
