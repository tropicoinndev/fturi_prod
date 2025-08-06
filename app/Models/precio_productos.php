<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;


class precio_productos extends Model
{
    use HasFactory;
    private $precio_productos;

    public function precios()
    {
        return $this->belongsTo(precios::class, 'precios_id');
    }


    public function productos()
    {
        return $this->belongsTo(productos::class, 'productos_id')
            ->with("detalle");
    }
}
