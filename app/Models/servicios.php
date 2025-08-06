<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class servicios extends Model
{
    use HasFactory;

    public function rubros(){
        return $this->belongsTo(rubro::class,'rubros_id');
    }
}
