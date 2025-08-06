<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dte_lotes extends Model
{
    use HasFactory;
    public function items()
    {
        return $this->hasMany(dte_item_lotes::class, 'dte_lotes_id')->with(["dte"]);
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
