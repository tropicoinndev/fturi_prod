<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class dtes extends Model
{
    use HasFactory;
    protected $appends = ['cid', 'tipo'];
    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn() => Crypt::encryptString($this->id)
        );
    }
    public function tipo(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->tipo_dte < 10 ? '0' . $this->tipo_dte : $this->tipo_dte
        );
    }
    public function comprobante()
    {
        return $this->belongsTo(comprobantes::class, 'comprobantes_id')->with("clientes");
    }

    public function sujeto()
    {
        return $this->belongsTo(sujeto_excluido::class, 'sujeto_excluidos_id')->with("clientes");
    }

    public function contingencia()
    {
        return $this->hasOne(mh_contingencia_items::class, 'dtes_id');
    }

    public function invalidado()
    {
        return $this->hasOne(dte_anulaciones::class, 'dtes_id')->where('error', false);
    }
}
