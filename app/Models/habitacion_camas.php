<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class habitacion_camas extends Model
{
    use HasFactory;

    protected $appends = ['cid'];

    public function cid(): Attribute {
        return Attribute::make(
            get: fn() => Crypt::encryptString($this->id)
        );
    }

    public function habitaciones()
    {
        return $this->belongsTo(habitaciones::class,"habitaciones_id");
    }
    public function tipo_camas()
    {
        return $this->belongsTo(tipo_camas::class,"tipo_camas_id");
    }
}
