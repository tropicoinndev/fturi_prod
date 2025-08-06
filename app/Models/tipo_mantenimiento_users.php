<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tipo_mantenimiento_users extends Model
{
    use HasFactory;
    public function users(){
        return $this->belongsTo(User::class,'users_id');
    }
        public function tipo_mantenimientos(){
        return $this->belongsTo(tipo_mantenimientos::class,'tipo_mantenimientos_id');
    }
}