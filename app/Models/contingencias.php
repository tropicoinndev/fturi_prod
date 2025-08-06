<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#Add
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Crypt;

class contingencias extends Model
{
    use HasFactory;

    protected $appends = ['cid'];

    public function cid(): Attribute{
        return Attribute::make(
            get: fn() => Crypt::encryptString($this->id)
        );
    }
}
