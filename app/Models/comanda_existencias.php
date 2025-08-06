<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class comanda_existencias extends Model
{
    use HasFactory;
    protected $appends = [ 'creacion'];


    public function creacion(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->attributes['created_at'])->format('d-m-Y H:i:s')
        );
    }

    public function existencias()
    {
        return $this->belongsTo(existencias::class, 'existencias_id')->with('bodegas');
    }
    public function productos()
    {
        return $this->belongsTo(productos::class, 'productos_id');
    }
}
