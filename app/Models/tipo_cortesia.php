<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tipo_cortesia extends Model
{
    use HasFactory;
    public function titulares()
    {
        return $this->hasMany(control_cortesias::class, 'tipo_cortesias_id');
    }
}
