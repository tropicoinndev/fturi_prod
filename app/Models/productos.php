<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productos extends Model
{
    use HasFactory;
    protected $table = 'productos';
     public function categoria()
    {
        return $this->belongsTo(categorias::class, "categorias_id");
    }
     public function detalle()
    {
        return $this->belongsTo(detalle_productos::class, "productos_id");
    }
    
}
