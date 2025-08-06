<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

class anulacion_comprobantes extends Model
{
    use HasFactory;

    protected $appends = ['cid'];
    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn() => Crypt::encryptString($this->id)
        );
    }
    public function comprobantes()
    {
        return $this->BelongsTo(comprobantes::class, 'comprobantes_id');
    }
    public function anulaciones()
    {
        return $this->BelongsTo(anulaciones::class, 'anulaciones_id');
    }
    public function users()
    {
        return $this->BelongsTo(User::class, 'users_id');
    }
    public function invalidacion()
    {
        return $this->hasOne(dte_anulaciones::class, 'anulacion_comprobantes_id');
    }
}
