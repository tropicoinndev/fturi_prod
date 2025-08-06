<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class cajas extends Model
{
    use HasFactory;

    protected $appends = ['cid'];
    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn() => Crypt::encryptString($this->id)
        );
    }
    public function Sucursales()
    {
        return $this->belongsTo(sucursales::class, 'sucursales_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function turnos()
    {
        return $this->hasMany(caja_turnos::class, 'cajas_id')->with('opcion_turnos');
    }

    public function turnoActivo()
    {
        return $this->hasOne(turnos::class, 'cajas_id')->where('estado', true)->with('opcion');
    }


    public function cajas_comprobantes()
    {
        return $this->hasMany(cajas_comprobantes::class, 'cajas_id')->where('estado', true);
    }

    public static function getUser($user)
    {
        $cajas = cajas_users::where('users_id', $user)->pluck('cajas_id');
        return cajas::whereIn('id', $cajas)->get();
    }
}
