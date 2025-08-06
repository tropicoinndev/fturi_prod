<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cargos extends Model
{
    use HasFactory;
    protected $appends = ['nombre'];
    public function nombre(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->cargo . ' -> Monto: $' . number_format($this->precio, 2)
        );
    }
}
