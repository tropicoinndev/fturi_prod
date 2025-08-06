<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class turnos extends Model
{
    use HasFactory;
    protected $table = 'turnos';
    protected $appends = ['cid'];
    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn() => Crypt::encryptString($this->id)
        );
    }
    public function cajas()
    {
        return $this->belongsTo(cajas::class, "cajas_id");
    }
    
    public function uapertura()
    {
        return $this->belongsTo(User::class, 'apertura_users_id');
    }
    public function ucierre()
    {
        return $this->belongsTo(User::class, 'cierre_users_id');
    }
    public function opcion()
    {
        return $this->belongsTo(opcion_turnos::class, "opcion_turnos_id");
    }
    public function cajasSucursales()
    {
        return $this->belongsTo(cajas::class, "cajas_id")->with("Sucursales");
    }
}
