<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class caja_precios extends Model
{
    use HasFactory;
    
    public function cajas(){
        return $this->belongsTo(cajas::class,'cajas_id');
    }

    public function precios(){
        return $this->belongsTo(precios::class,'precios_id')
            ->with('categorias_precios');
    }
}
