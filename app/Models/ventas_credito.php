<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ventas_credito extends Model
{
    use HasFactory;
    protected $table = 'ventas_credito';

    public function abono(){
        return $this->hasOne(abonos_detalles::class, 'comprobantes_id')->with('users');
    }
}
