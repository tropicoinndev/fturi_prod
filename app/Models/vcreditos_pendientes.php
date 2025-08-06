<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class vcreditos_pendientes extends Model
{
    use HasFactory;

    protected $table = 'ventas_credito_pendientes';

    protected $appends = ['cid_comprobante', 'cid_cliente'];

    public function cidComprobante(): Attribute
    {
        return Attribute::make(
            get: fn() => Crypt::encryptString($this->id)
        );
    }
    public function cidCliente(): Attribute
    {
        return Attribute::make(
            get: fn() => Crypt::encryptString($this->clientes_id)
        );
    }
}
