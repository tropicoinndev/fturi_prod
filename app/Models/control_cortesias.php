<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class control_cortesias extends Model
{
    use HasFactory;
    protected $appends = ['cid', 'consumo'];
    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn () => Crypt::encryptString($this->id)
        );
    }


    public function consumo(): Attribute
    {
        return Attribute::make(
            get: fn () => (DB::table('cortesias')
                ->whereMonth('fecha', date('m'))
                ->whereYear('fecha', date('Y'))
                ->where('control_cortesias_id', $this->id)
                ->where('autorizado', true)
                ->groupBy('control_cortesias_id')
                ->selectRaw('SUM(monto) as monto')->first())->monto ?? 0
        );
    }

    public function tipo_cortesias()
    {
        return $this->belongsTo(tipo_cortesia::class, 'tipo_cortesias_id');
    }
}
