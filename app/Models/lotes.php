<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lotes extends Model
{
    use HasFactory;

    public function relacionCompras(){
        return $this->belongsTo(compras::class,'compras_id');
    }

    public function relacionProductos(){
        return $this->belongsTo(productos::class,'productos_id');
    }
}
