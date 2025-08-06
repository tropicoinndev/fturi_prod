<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#Add
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class solicitantes extends Model
{
    use HasFactory;

    protected $appends = ['cid'];

    public function cid(): Attribute{
        return Attribute::make(
            get: fn() => Crypt::encryptString($this->id)
        );
    }

    public function identificaciones(){
        return $this->belongsTo(identificaciones::class,'identificaciones_id');
    }
    public function solicitante_cliente()
    {
        return $this->hasMany(solicitantes_clientes::class, 'solicitantes_id');
    }
}
