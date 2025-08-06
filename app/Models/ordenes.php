<?php

namespace App\Models;

use App\Models\detalle_ordenes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;

#Agregar.
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ordenes extends Model
{
    use HasFactory;

    private $orden;
    protected $appends = ['orden', 'sum_orden', 'creacion', 'count_orden'];

    public function __construct()
    {
        $this->orden = $this->id; #ID de la BD, no ID encriptado.
    }

    public function clientes()
    {
        return $this->belongsTo(clientes::class, 'clientes_id')->with('identificacion');
    }

    public function cajas()
    {
        return $this->belongsTo(cajas::class, 'cajas_id');
    }
    public function turnos()
    {
        return $this->belongsTo(turnos::class, 'turnos_id');
    }

    public static function detalle($caja)
    {
        return self::with('clientes')
            ->with('cajas')
            ->select('ordenes.*')
            ->addSelect([DB::raw("(SELECT COUNT(id) from detalle_ordenes where detalle_ordenes.ordenes_id = ordenes.id) as conceptos")])
            ->orderBy('id', 'DESC')
            ->where('estado', true)
            ->where('tipo_orden', '!=', 5)
            ->where('comprobante', false)
            ->where('cajas_id', $caja)
            ->get();
    }
    public function detalle_orden()
    {
        return $this->hasMany(detalle_ordenes::class, 'ordenes_id', 'orden')->with('servicios');
    }
    public function getSumDetalleOrden()
    {
        return $this->detalle_orden->sum('total');
    }
    public function creacion(): Attribute
    {
        return Attribute::make(
            get: fn() => Carbon::parse($this->created_at)->diffForHumans()
        );
    }

    public function sumOrden(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->detalle_orden->sum('total')
        );
    }
    public function countOrden(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->detalle_orden->count()
        );
    }
    public function id(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Crypt::encryptString($value)
        );
    }
    public function orden(): Attribute
    {
        return Attribute::make(
            get: fn() => Crypt::decryptString($this->id)
        );
    }
    public function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn($v) => Carbon::parse($v)->diffForHumans()
        );
    }

    public function usuarios()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
