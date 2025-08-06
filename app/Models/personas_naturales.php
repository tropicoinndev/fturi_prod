<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#Add
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class personas_naturales extends Model
{
    use HasFactory;

    #Campos adicionales
    protected $appends = ['cid','creacion','edicion'];

    public function cid(): Attribute {
        return Attribute::make(
            get: fn() => Crypt::encryptString($this->id)
        );
    }

    public function creacion(): Attribute {
        return Attribute::make(
            get: fn() => Carbon::parse($this->created_at)->diffForHumans()
        );
    }

    public function edicion(): Attribute {
        return Attribute::make(
            get: fn() => Carbon::parse($this->updated_at)->diffForHumans()
        );
    }

    #Relaciones
    public function departamentos(){
        return $this->belongsTo(departamentos::class,'departamentos_id');
    }

    public function paises(){
        return $this->belongsTo(paises::class,'paises_id');
    }

    public function identificaciones(){
        return $this->belongsTo(identificaciones::class,'identificaciones_id');
    }
}
