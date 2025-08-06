<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bodega_users extends Model
{
    use HasFactory;

    public function relacionBodegas(){
        return $this->belongsTo(bodegas::class,'bodegas_id');
    }

    public function relacionUsuarios(){
        return $this->belongsTo(User::class,'users_id');
    }
}
