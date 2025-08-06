<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mh_contingencia_items extends Model
{
    use HasFactory;
    public function dtes()
    {
        return $this->belongsTo(dtes::class, 'dtes_id')->with(["comprobante"]);
    }

    public function mh_contingencia()
    {
        return $this->belongsTo(mh_contingencias::class, 'mh_contingencias_id');
    }

    public function inLote()
    {
        return $this->hasMany(dte_item_lotes::class, 'dtes_id');
    }
}
