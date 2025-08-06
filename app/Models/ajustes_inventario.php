<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#Add
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Crypt;

class ajustes_inventario extends Model
{
    use HasFactory;

    protected $appends = ['cid'];

    public function cid(): Attribute {
        return Attribute::make(
            get: fn() => Crypt::encryptString($this->id)
        );
    }

    public function userSolicitante(){
        return $this->belongsTo(User::class,'solicitante_users_id');
    }

    public function userRealiza(){
        return $this->belongsTo(User::class,'realiza_users_id');
    }

    public function userAutoriza(){
        return $this->belongsTo(User::class,'autoriza_users_id');
    }

    public function ajustesExistencias(){
        return $this->hasMany(ajustes_existencias::class, 'ajustes_inventarios_id');
    }
}
