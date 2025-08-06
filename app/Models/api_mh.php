<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class api_mh extends Model
{
    use HasFactory;
    protected $appends = ['terminacion'];

    public function terminacion(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->attributes['finalizacion'])->diffForHumans()
        );
    }
}
