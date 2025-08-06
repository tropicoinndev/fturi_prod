<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class proveedores extends Model
{
    use HasFactory;

    public function relacionMunicipios(){
        return $this->belongsTo(municipios::class,'municipios_id');
    }
}
