<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class departamentos extends Model
{
    use HasFactory;
    
    protected $table = 'departamentos';
    
    public function paises()
    {
        return $this->belongsTo(paises::class,"paises_id");
    }
}
