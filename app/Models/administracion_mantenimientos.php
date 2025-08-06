<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class administracion_mantenimientos extends Model
{
    use HasFactory;
    public function tipo_mantenimientos()
    {
        return $this->belongsTo(tipo_mantenimientos::class, 'tipo_mantenimientos_id');
    }
    public function habitaciones()
    {
        return $this->belongsTo(habitaciones::class, 'habitaciones_id');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
