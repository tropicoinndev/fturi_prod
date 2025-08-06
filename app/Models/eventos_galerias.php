<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class eventos_galerias extends Model
{
    use HasFactory;
    public function galerias()
    {
        return $this->belongsTo(galerias::class, 'galerias_id');
    }
    public function eventos()
    {
        return $this->belongsTo(eventos::class, 'eventos_id');
    }

}
