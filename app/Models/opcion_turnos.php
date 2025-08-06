<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#Add
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class opcion_turnos extends Model
{
    use HasFactory;

    protected $appends = ['cid', 'creacion', 'edicion'];

    public function cid(): Attribute {
        return Attribute::make(get: fn() => Crypt::encryptString($this->id));
    }

    public function creacion(): Attribute {
        return Attribute::make(get: fn() => Carbon::parse($this->created_at)->diffForHumans());
    }

    public function edicion(): Attribute {
        return Attribute::make(get: fn() => Carbon::parse($this->updated_at)->diffForHumans());
    }
}
