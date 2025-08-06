<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class galerias extends Model
{
    use HasFactory;
    protected $table= 'galerias';

    public function categoria_fotos(){
        return $this->belongsTo(categoria_fotos::class,'categoria_fotos_id');
    }
}

