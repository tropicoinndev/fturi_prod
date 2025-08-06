<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#Add
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Crypt;

class sucursales extends Model
{
    use HasFactory;

    protected $table   = 'sucursales';
    protected $appends = ['cid'];

    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn () => Crypt::encryptString($this->id)
        );
    }

    public function municipios()
    {
        return $this->belongsTo(municipios::class, 'municipios_id');
    }

    public function getCorrelativoSucursal()
    {
        return $this->belongsTo(correlativo_sucursal::class, 'sucursales_id')->where("estado", true)->whereColumn("actual", "<", "final");
    }
    public function allCorrelativoSucursal()
    {
        return $this->hasMany(correlativo_sucursal::class, 'sucursales_id')->where("estado", true)->whereColumn("actual", "<", "final");
    }
}
