<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class salones extends Model
{
    use HasFactory;
    protected $table = 'salones';
    protected $appends = ['cid'];
    public function cid(): Attribute
    {
        return Attribute::make(get: fn() => Crypt::encryptString($this->id));
    }
}
