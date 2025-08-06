<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vventas_rubros extends Model
{
    use HasFactory;
    protected $table = 'ventas_rubros';

    public function caja()
    {
        return $this->belongsTo(cajas::class, 'cajas_id');
    }

    public function rubro()
    {
        return $this->belongsTo(rubro::class, 'rubros_id');
    }

    public function comprobante()
    {
        return $this->belongsTo(comprobantes::class, 'comprobantes_id');
    }
}
