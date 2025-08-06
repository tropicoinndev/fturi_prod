<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class cajas_users extends Model
{
    use HasFactory;

    protected $appends = ['cuser'];
    public function cuser(): Attribute
    {
        return Attribute::make(
            get: fn () => Crypt::encryptString($this->users_id)
        );
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function cajas()
    {
        return $this->belongsTo(cajas::class, 'cajas_id');
    }
}
