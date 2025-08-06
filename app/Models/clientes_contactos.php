<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class clientes_contactos extends Model
{
    use HasFactory;

    public function contactos(){
        return $this->belongsTo(contactos::class,'contactos_id');
    }

    public function clientes(){
        return $this->belongsTo(clientes::class,'clientes_id');
    }
}
