<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class dte_anulaciones extends Model
{
    use HasFactory;
    protected $appends = ['cid'];
    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn() => Crypt::encryptString($this->id)
        );
    }

    public function dte()
    {
        return $this->belongsTo(dtes::class, 'dtes_id');
    }
}
