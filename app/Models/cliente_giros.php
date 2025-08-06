<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cliente_giros extends Model
{
    use HasFactory;
       protected $table = 'cliente_giros';
    public function cliente()
    {
        return $this->belongsTo(clientes::class,"clientes_id");
    }
    public function giro(){
        
        return $this->belongsTo(giros::class, "giros_id");
    }
}
