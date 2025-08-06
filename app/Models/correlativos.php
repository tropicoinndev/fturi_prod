<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class correlativos extends Model
{
    use HasFactory;

    protected $table = 'correlativos';

    public function cajas()
    {
        return $this->belongsTo(cajas::class,"cajas_id");
    }

    public function usuario(){
        return $this->belongsTo(User::class, "users_id");
    }
    
    public function tipo_comprobantes(){
        return $this->belongsTo(tipo_comprobantes::class, "tipo_comprobantes_id");
    }
}
