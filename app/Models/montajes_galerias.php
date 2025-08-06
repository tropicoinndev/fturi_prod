<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class montajes_galerias extends Model
{
    use HasFactory;
    public function galerias(){
        return $this->belongsTo(galerias::class,'galerias_id');
    }
        public function montajes(){
        return $this->belongsTo(montajes::class,'montajes_id');
    }
}
