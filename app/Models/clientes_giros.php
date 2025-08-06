<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class clientes_giros extends Model
{
    use HasFactory;

    public function giros(){
        return $this->belongsTo(giros::class,'giros_id');
    }

    public function clientes(){
        return $this->belongsTo(clientes::class,'clientes_id');
    }
}
