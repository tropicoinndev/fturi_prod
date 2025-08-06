<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class huesped_recepciones extends Model
{
    use HasFactory;
    public function huesped()
    {
        return $this->belongsTo(huespedes::class, 'huespedes_id')->with(['municipios','paises','identificaciones']);
    }
    public function recepcion()
    {
        return $this->belongsTo(recepciones::class, 'recepciones_id');
    }
}
