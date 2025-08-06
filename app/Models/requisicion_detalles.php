<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class requisicion_detalles extends Model
{
    use HasFactory;
    protected $fillable = ['productos_id', 'lotes_id', 'cantidad', 'users_id', 'requisiciones_id'];

    public function relacionProductos()
    {
        return $this->belongsTo(productos::class, 'productos_id');
    }
    public function productos()
    {
        return $this->belongsTo(productos::class, 'productos_id');
    }

    public function relacionUsuarios()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function relacionLotes()
    {
        return $this->belongsTo(lotes::class, 'lotes_id');
    }

    public function relacionExistencias()
    {
        return $this->hasOne(existencias::class, 'requisicion_detalles_id');
    }
    public function relacionRequisiciones()
    {
        return $this->belongsTo(requisiciones::class, 'requisiciones_id');
    }
}
