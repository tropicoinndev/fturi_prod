<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class detalle_productos extends Model
{
    use HasFactory;
     protected $table = 'detalle_productos';
     public function producto()
    {
        return $this->belongsTo(productos::class, "productos_id");
    }
}
