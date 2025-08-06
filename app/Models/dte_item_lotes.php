<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dte_item_lotes extends Model
{
    use HasFactory;

    public function dte()
    {
        return $this->belongsTo(dtes::class, 'dtes_id');
    }
}
