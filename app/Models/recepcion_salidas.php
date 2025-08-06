<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class recepcion_salidas extends Model
{
    use HasFactory;
    protected $appends = ['creacion'];
    public function creacion(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->created_at)->diffForHumans()
        );
    }
    public function usuario()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
