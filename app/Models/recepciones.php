<?php

namespace App\Models;

use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class recepciones extends Model
{
    use HasFactory;
    protected $appends = ['cid', 'dias', 'modificacion', 'creacion'];
    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn() => Crypt::encryptString($this->id)
        );
    }
    public function dias(): Attribute
    {
        return Attribute::make(
            get: fn() => ((new DateTime($this->fecha_ingreso))
                ->diff((new DateTime($this->fecha_salida))))
                ->days
        );
    }
    public function modificacion(): Attribute
    {
        return Attribute::make(
            get: fn() => Carbon::parse($this->updated_at)->diffForHumans()
        );
    }
    public function creacion(): Attribute
    {
        return Attribute::make(
            get: fn() => Carbon::parse($this->created_at)->diffForHumans()
        );
    }
    public function clientes()
    {
        return $this->belongsTo(clientes::class, 'clientes_id');
    }
    public function habitaciones()
    {
        return $this->belongsTo(habitaciones::class, 'habitaciones_id');
    }
    public function tarifas()
    {
        return $this->belongsTo(tarifas::class, 'tarifas_id');
    }
    public function huespedes()
    {
        return $this->hasMany(huesped_recepciones::class, 'recepciones_id')->with('huesped');
    }
    public function huesped()
    {
        return $this->hasOne(huesped_recepciones::class, 'recepciones_id')->with('huesped');
    }
    public function getAnticipos()
    {
        return $this->hasMany(anticipo_reservacion::class, 'reservacion_id')->where('tipo_reservacion', 2)->with('anticipos')->withSum('anticipos', 'monto');
    }
    public function usuarios()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
    public function userElimina()
    {
        return $this->belongsTo(User::class,'users_eliminado_id');
    }
    public function reservaciones()
    {
        return $this->belongsTo(detalle_reservas::class, 'detalle_reservas_id');
    }
    public function pospago()
    {
        return $this->hasMany(recepcion_salidas::class, 'recepciones_id')->with(['usuario']);
    }

    public function cargos()
    {
        return $this->hasMany(recepcion_cargos::class, 'recepciones_id')->where('eliminacion_users_id', null)->with('cargos');
    }

    public function descuentos()
    {
        return $this->belongsTo(descuentos::class, 'descuentos_id');
    }
}
