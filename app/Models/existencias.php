<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class existencias extends Model
{
    use HasFactory;

    protected $appends = ['cid','ven','creacion'];

    public function ven(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->vencimiento)->diffForHumans()
        );
    }
    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn () => Crypt::encryptString($this->id)
        );
    }

    public function creacion(): Attribute {
        return Attribute::make(
            get: fn() => $this->created_at?->format('Y-m-d')
        );
    }

    public function productosExistencias()
    {
        return $this->belongsTo(productos::class, 'productos_id');
    }
    public function bodegasExistencias()
    {
        return $this->belongsTo(bodegas::class, 'bodegas_id');
    }
    public function bodegas()
    {
        return $this->belongsTo(bodegas::class, 'bodegas_id');
    }
    public function requisicionExistencias()
    {
        return $this->belongsTo(requisicion_detalles::class, 'requisicion_detalles_id');
    }
}
