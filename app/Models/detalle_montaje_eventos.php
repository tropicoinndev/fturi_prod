<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class detalle_montaje_eventos extends Model
{
    use HasFactory;
    protected $table = 'detalle_montaje_eventos';

    public function evento()
    {
        return $this->belongsTo(eventos::class, 'eventos_id');
    }
}
