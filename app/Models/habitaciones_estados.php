<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class habitaciones_estados extends Model
{
    use HasFactory;

    protected $appends = ['cid'];

    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn() => Crypt::encryptString($this->id)
        );
    }
    public function usuario()
    {
        return $this->belongsTo(User::class, "users_id");
    }
    public function habitacion()
    {
        return $this->belongsTo(habitaciones::class, "habitaciones_id");
    }
    public function estado()
    {
        return $this->belongsTo(estado_habitaciones::class, "estado_habitaciones_id");
    }
}
