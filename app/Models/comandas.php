<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class comandas extends Model
{
    use HasFactory;
    protected $appends = ['cid', 'creacion', 'modificacion', 'sum_comanda', 'count_comanda', 'fecha_creacion'];
    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn() => Crypt::encryptString($this->id)
        );
    }
    public function creacion(): Attribute
    {
        return Attribute::make(
            get: fn() => Carbon::parse($this->created_at)->diffForHumans()
        );
    }
    public function fechaCreacion(): Attribute
    {
        return Attribute::make(
            get: fn() => Carbon::parse($this->created_at)->format("d-m-Y H:i:s")
        );
    }
    public function modificacion(): Attribute
    {
        return Attribute::make(
            get: fn() => Carbon::parse($this->updated_at)->diffForHumans()
        );
    }
    public function sumComanda(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->detalles_comanda->sum('total')
        );
    }
    public function countComanda(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->detalles_comanda->count()
        );
    }
    public function turnos()
    {
        return $this->belongsTo(turnos::class, 'turnos_id');
    }
    public function cajas()
    {
        return $this->belongsTo(cajas::class, 'cajas_id');
    }
    public function clientes()
    {
        return $this->belongsTo(clientes::class, 'clientes_id');
    }
    public function usuarios()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
    public function detalles_comanda()
    {
        return $this->hasMany(comanda_detalles::class, 'comandas_id')->where('anulado', false)->with(["precios", "lotes"]);
    }
    public function detalles_comandas()
    {
        return $this->hasMany(comanda_detalles::class, 'comandas_id')->where('anulado', false)->with(["precios", "lotes", "user_comanda"]);
    }
    public function getTotal()
    {
        return DB::table("comanda_detalles")
            ->where('anulado', false)
            ->where("comandas_id", $this->id)
            ->select(DB::raw("sum(cantidad * precio) as total"))
            ->first();
    }

    public function cortesia()
    {
        return $this->hasMany(cortesias::class, 'origen_id', 'id')->where('origen', 3);
    }
}
