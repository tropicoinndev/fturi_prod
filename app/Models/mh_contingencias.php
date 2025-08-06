<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mh_contingencias extends Model
{
    use HasFactory;

    public function sucursales()
    {
        return $this->belongsTo(sucursales::class, 'sucursales_id');
    }
    public function items()
    {
        return $this->hasMany(mh_contingencia_items::class, 'mh_contingencias_id')->with(['dtes']);
    }

    public function items_pendientes()
    {
        return $this->hasMany(mh_contingencia_items::class, 'mh_contingencias_id')
            ->whereDoesntHave('inLote')
            ->whereHas('dtes', function ($q) {
                $q->where('error', true);
            })->with(['dtes']);
    }
    public function items_procesados()
    {
        return $this->hasMany(mh_contingencia_items::class, 'mh_contingencias_id')
            ->whereHas('dtes', function ($query) {
                $query->where('error', false);
            })
            ->with(['dtes']);
    }
    public function items_lotes_procesados()
    {
        return $this->hasMany(mh_contingencia_items::class, 'mh_contingencias_id')
            ->whereHas('inLote')
            ->with(['dtes']);
    }
}
