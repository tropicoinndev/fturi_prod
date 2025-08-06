<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class anticipo_reservacion extends Model
{
    use HasFactory;
    public function anticipos()
    {
        return $this->belongsTo(anticipos::class, 'anticipos_id')->with('clientes');
    }

    public function evento()
    {
        if ($this->tipo_reservacion == 4)
            return $this->belongsTo(eventos::class, 'reservacion_id');
    }
    public function recepcion()
    {
        if ($this->tipo_reservacion == 2)
            return $this->belongsTo(recepciones::class, 'reservacion_id');
    }
}
