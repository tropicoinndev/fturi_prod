<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class registro extends Model
{
    use HasFactory;

    public function orden()
    {
        if ($this->tipo_registros == 1)
            return $this->belongsTo(ordenes::class, 'registro');
    }

    public function estadia()
    {
        if ($this->tipo_registros == 2)
            return $this->belongsTo(recepciones::class, 'registro');
        return null;
    }

    public function comanda()
    {
        if ($this->tipo_registros == 3)
            return $this->belongsTo(comandas::class, 'registro');
    }
    public function comprobantes()
    {
        return $this->belongsTo(comprobantes::class, 'comprobantes_id');
    }
}
