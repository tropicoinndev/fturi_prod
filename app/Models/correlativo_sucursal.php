<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#Add
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class correlativo_sucursal extends Model
{
    use HasFactory;
    protected $appends = ['cid', 'documento'];
    private $documento = [
        "",
        "FACTURA", //1
        "NO CLASIFICADO", //2
        "COMPROBANTE DE CRÉDITO FISCAL", //3
        "NOTA DE REMISIÓN", //4
        "NOTA DE CRÉDITO", //5
        "NOTA DE DÉBITO", //6
        "COMPROBANTE DE RETENCIÓN", //7
        "COMPROBANTE DE LIQUIDACIÓN", //8
        "DOCUMENTO CONTABLE DE LIQUIDACIÓN", //9
        "NO CLASIFICADO", //10
        "FACTURA DE EXPORTACIÓN", //11
        "NO CLASIFICADO", //12
        "NO CLASIFICADO", //13
        "FACTURA DE SUJETO EXCLUIDO", //14
        "COMPROBANTE DE DONACIÓN" //15
    ];

    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn () => Crypt::encryptString($this->id)
        );
    }
    public function documento(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->documento??[$this->attributes['tipo_dte']]
        );
    }

    public function sucursales()
    {
        return $this->belongsTo(sucursales::class, 'sucursales_id');
    }
}
