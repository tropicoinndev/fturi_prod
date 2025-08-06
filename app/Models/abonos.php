<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class abonos extends Model
{
    use HasFactory;

    public function clientes()
    {
        return $this->belongsTo(clientes::class, 'clientes_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
    public function forma_pagos()
    {
        return $this->belongsTo(forma_pagos::class, 'forma_pagos_id');
    }
}
