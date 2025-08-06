<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class anticipos_cobros extends Model
{
    use HasFactory;
    protected $appends = ['cid'];
    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn() => Crypt::encryptString($this->id)
        );
    }
    public function anticipos()
    {
        return $this->belongsTo(anticipos::class, 'anticipos_id');
    }
    public function anticipo()
    {
        return $this->belongsTo(anticipos::class, 'anticipos_id');
    }
}
