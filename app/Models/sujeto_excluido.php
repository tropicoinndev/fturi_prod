<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class sujeto_excluido extends Model
{
    use HasFactory;
    protected $appends = ['cid'];

    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn() => Crypt::encryptString($this->id)
        );
    }

    public function clientes()
    {
        return $this->belongsTo(clientes::class, 'clientes_id');
    }
    public function cajas()
    {
        return $this->belongsTo(cajas::class, 'cajas_id');
    }

    public function detalles()
    {
        return $this->hasMany(detalles_sujeto_excluidos::class, 'sujeto_excluidos_id');
    }
    public function dte()
    {
        return $this->hasOne(dtes::class, 'sujeto_excluidos_id');
    }
    public function realiza()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
    public function autoriza()
    {
        return $this->belongsTo(User::class, 'autoriza_users_id');
    }
}
