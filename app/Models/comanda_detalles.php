<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class comanda_detalles extends Model
{
    use HasFactory;
    protected $appends = ['cid', 'total', 'creacion', 'solicitado', 'aceptaciontime', 'esperatime', 'incrementotime', 'status'];
    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn() => Crypt::encryptString($this->id)
        );
    }
    public function total(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->cantidad * $this->precio
        );
    }
    public function creacion(): Attribute
    {
        return Attribute::make(
            get: fn() => Carbon::parse($this->attributes['created_at'])->format('d-m-Y H:i:s')
        );
    }
    public function solicitado(): Attribute
    {
        return Attribute::make(
            get: fn() => Carbon::parse($this->attributes['solicitud'])->format('H:i:s')
        );
    }
    public function aceptaciontime(): Attribute
    {
        return Attribute::make(

            get: fn() => $this->attributes['aceptacion'] ? Carbon::parse($this->attributes['aceptacion'])->diffForHumans($this->attributes['solicitud']) : 'Aun sin aceptar'
        );
    }
    public function esperatime(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->formatTimeLetras($this->attributes['espera'])
        );
    }
    public function incrementotime(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->formatTimeLetras($this->attributes['incremento_tiempo'])
        );
    }
    public function status(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->cancelado)
                    return 'Cancelado: ' . $this->user_acepta->user . ' --' . $this->attributes['observacion_negacion'] . '--';
                if ($this->attributes['entregado'])
                    return 'Entregado: ' . $this->attributes['entregado'];
            }
        );
    }
    public function comandas()
    {
        return $this->belongsTo(comandas::class, 'comandas_id');
    }
    public function comandawtcaja()
    {
        return $this->belongsTo(comandas::class, 'comandas_id')->with(['cajas', 'clientes']);
    }
    public function precios()
    {
        return $this->belongsTo(precios::class, 'precios_id')->with('categorias_precios');
    }
    public function dprecio()
    {
        return $this->belongsTo(precios::class, 'precios_id')->with(['categorias_precios', 'detalle_producto']);
    }
    public function descuentos()
    {
        return $this->belongsTo(descuentos::class, 'descuentos_id');
    }
    public function user_comanda()
    {
        return $this->belongsTo(User::class, 'users_comanda_id');
    }
    public function user_solicita()
    {
        return $this->belongsTo(User::class, 'user_solicita_id');
    }
    public function user_asignado()
    {
        return $this->belongsTo(User::class, 'users_asigna_id');
    }
    public function user_acepta()
    {
        return $this->belongsTo(User::class, 'users_acepta_id');
    }
    public function lotes()
    {
        return $this->hasMany(comanda_existencias::class, 'comanda_detalles_id')->with('existencias');
    }
    public function turnos()
    {
        return $this->belongsTo(turnos::class, 'turnos_id');
    }
    public function detalle_producto_precio()
    {
        return $this->hasMany(precio_productos::class, 'precios_id')
            ->with('productos');
    }

    private function formatTimeLetras($time)
    {
        if (isset($time)) {
            list($h, $m, $s) = explode(':', $time);
            $time = "";
            if ($h > 0)
                $time = $time . intval($h) . ($h > 1 ? ' horas ' :  ' hora ');
            if ($m > 0)
                $time = $time . intval($m) . ' min ';
            if ($s > 0)
                $time = $time . intval($s) . ' seg';
            return $time;
        } else return '';
    }
}
