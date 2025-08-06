<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class clientes_identificaciones extends Model
{
    use HasFactory;

    public function identificaciones(){
        return $this->belongsTo(identificaciones::class,'identificaciones_id');
    }

    public function clientes(){
        return $this->belongsTo(clientes::class,'clientes_id');
    }
}
