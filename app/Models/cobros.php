<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

class cobros extends Model
{
    use HasFactory;
    protected $appends = ['cid'];
    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn () => Crypt::encryptString($this->id)
        );
    }
    public function clientes()
    {
        return $this->belongsTo(clientes::class, 'clientes_id');
    }
    public function detalleCobros()
    {
        return $this->hasMany(detalle_cobros::class, 'cobros_id');
    }
    public function anticipos()
    {
        return $this->hasMany(anticipos_cobros::class, 'cobros_id')->with('anticipos');
    }
    
}
