<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class detalle_ordenes extends Model
{
    use HasFactory;
    protected $appends = ['total', 'cid'];

    public function descuentos()
    {
        return $this->belongsTo(descuentos::class, 'descuentos_id');
    }

    public function servicios()
    {
        return $this->belongsTo(servicios::class, 'servicios_id');
    }

    public function ordenes()
    {
        return $this->belongsTo(ordenes::class, 'ordenes_id');
    }

    public function detalleOrdenes()
    {
        return $this->belongsTo(detalle_ordenes::class, 'detalle_ordenes_id');
    }

    public function total(): Attribute
    {
        return Attribute::make(
            get: fn () => ($this->neto + $this->iva + $this->cesc + $this->advalorem + $this->propina) * $this->cantidad
        );
    }
    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn () => Crypt::encryptString($this->id)
        );
    }
    public function user_detalle()
    {
        return $this->belongsTo(User::class, 'user_creacion_id');
    }
}
