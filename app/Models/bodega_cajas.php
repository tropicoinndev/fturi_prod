<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class bodega_cajas extends Model
{
    use HasFactory;
    protected $appends = ['cid'];
    
    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn () => Crypt::encryptString($this->bodegas_id)
        );
    }

    public function bodegas()
    {
        return $this->belongsTo(bodegas::class, 'bodegas_id');
    }
}
