<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class vprecios extends Model
{
    use HasFactory;
    protected $table = "getprecios";
    protected $appends = ['cid', 'ven'];
    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn () => Crypt::encryptString($this->id)
        );
    }
    public function ven(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->vencimiento)->diffForHumans()
        );
    }
}
