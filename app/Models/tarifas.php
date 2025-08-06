<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class tarifas extends Model
{
    use HasFactory;
    protected $table = 'tarifas';
    protected $appends = ['cid', 'monto'];
    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn() => Crypt::encryptString($this->id)
        );
    }
    public function monto(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->attributes['precio'] / intval($this->attributes['numero_dias'] ?? 1)
        );
    }
    public function temporadas()
    {
        return $this->belongsTo(temporadas::class, 'temporadas_id');
    }
    public function forma_habitaciones()
    {
        return $this->belongsTo(forma_habitaciones::class, 'forma_habitaciones_id');
    }
}
