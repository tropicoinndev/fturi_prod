<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categorias extends Model
{
    use HasFactory;
     public function producto()
    {
        return $this->belongsTo(productos::class, "productos_id");
    }
}
