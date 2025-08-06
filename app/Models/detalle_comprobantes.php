<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class detalle_comprobantes extends Model
{
    use HasFactory;
    protected $appends = ['cid'];
    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn() => Crypt::encryptString($this->id)
        );
    }
    public function comprobantes()
    {
        return $this->belongsTo(comprobantes::class, 'comprobantes_id');
    }

    public function rubros()
    {
        return $this->belongsTo(rubro::class, 'rubros_id');
    }
}
