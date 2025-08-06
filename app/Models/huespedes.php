<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class huespedes extends Model
{
    use HasFactory;
    protected $appends = ['edad'];
    public function edad(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::now()->diffInYears(Carbon::parse($this->nacimiento))
        );
    }
    public function municipios()
    {
        return $this->belongsTo(municipios::class, 'municipios_id');
    }
    public function paises()
    {
        return $this->belongsTo(paises::class, 'paises_id');
    }
    public function identificaciones()
    {
        return $this->belongsTo(identificaciones::class, 'identificaciones_id');
    }
    public function recepciones()
    {
        return $this->hasMany(huesped_recepciones::class, 'huespedes_id')->with('recepcion');
    }
    public function recepcion()
    {
        return $this->hasOne(huesped_recepciones::class)
            ->with('recepcion'); // Asegúrate de que cargues la relación de recepcion
    }
}
