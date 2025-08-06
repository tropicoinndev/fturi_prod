<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class anulaciones_detalle_comanda extends Model
{
    use HasFactory;
    protected $appends = ['cid',  'total'];
    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn() => Crypt::encryptString($this->id)
        );
    }
    public function comanda_detalles()
    {
        return $this->belongsTo(comanda_detalles::class, 'comanda_detalles_id');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
    public function total(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->cantidad * $this->comanda_detalles->precios->precio
        );
    }
}
