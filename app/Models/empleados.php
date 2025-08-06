<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class empleados extends Model
{
    use HasFactory;
    protected $appends = ['cid', 'creacion'];
    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn () => Crypt::encryptString($this->id)
        );
    }
    public function creacion(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->attributes['created_at'])->format('d-m-Y H:i:s')
        );
    }
    public function user_empleado()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
    public function identificaciones()
    {
        return $this->belongsTo(identificaciones::class, 'identificaciones_id');
    }
}
