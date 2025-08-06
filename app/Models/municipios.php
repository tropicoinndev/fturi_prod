<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class municipios extends Model
{
    use HasFactory;

    protected $table = 'municipios';
    
    public function departamentos()
    {
        return $this->belongsTo(departamentos::class,"departamentos_id");
    }
}
