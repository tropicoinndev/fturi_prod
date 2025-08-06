<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#Add
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class periodos_creditos extends Model
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
    public function clientes(){
        return $this->belongsTo(clientes::class,'clientes_id');
    }

    public function personas_naturales(){
        return $this->belongsTo(personas_naturales::class,'personas_naturales_id');
    }
}
