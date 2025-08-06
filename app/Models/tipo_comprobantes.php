<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class tipo_comprobantes extends Model
{
    use HasFactory;

    protected $appends = ['cid', 'ctoken'];
    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn () => Crypt::encryptString($this->id)
        );
    }
    public function ctoken(): Attribute
    {
        return Attribute::make(
            get: fn () => Crypt::encryptString($this->token)
        );
    }
    public function comprobantes()
    {
        return $this->hasMany(comprobantes::class, 'tipo_comprobantes_id');

    }
}
