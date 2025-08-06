<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class detalle_cobros extends Model
{
    use HasFactory;

    public function orden()
    {
        if ($this->origen == 1)
            return $this->belongsTo(ordenes::class, 'origen_id');
    }
    public function recepcion()
    {
        if ($this->origen == 2)
            return $this->belongsTo(recepciones::class, 'origen_id');
    }
    public function comanda()
    {
        if ($this->origen == 3)
            return $this->belongsTo(comandas::class, 'origen_id');
    }
    

}
