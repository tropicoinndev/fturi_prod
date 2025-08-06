<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class view_tarifas_habitaciones extends Model
{
    use HasFactory;

    protected $table = "gettarifashabitaciones";

    protected $appends = ['tarifas_cid'];
    public function tarifasCid(): Attribute
    {
        return Attribute::make(
            get: fn() => Crypt::encryptString($this->tarifas_id)
        );
    }
}
