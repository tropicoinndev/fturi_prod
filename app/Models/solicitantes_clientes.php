<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class solicitantes_clientes extends Model
{
    use HasFactory;
    protected $appends = ['scliente'];
    public function scliente(): Attribute
    {
        return Attribute::make(
            get: fn () => Crypt::encryptString($this->clientes_id)
        );
    }

    public function solicitantes()
    {
        return $this->belongsTo(solicitantes::class, 'solicitantes_id');
    }
    public function clientes()
    {
        return $this->belongsTo(clientes::class, 'clientes_id');
    }
}
