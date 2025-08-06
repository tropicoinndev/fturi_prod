<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class ajustes_existencias extends Model
{
    use HasFactory;

    protected $appends = ['cid'];

    public function cid(): Attribute {
        return Attribute::make(
            get: fn() => Crypt::encryptString($this->id)
        );
    }

    public function existencias(){
        return $this->belongsTo(existencias::class,'existencias_id');
    }

    public function userRealiza(){
        return $this->belongsTo(User::class,'users_id');
    }

    public function ajusInventario(){
        return $this->belongsTo(ajustes_inventario::class,'ajustes_inventarios_id');
    }
}
