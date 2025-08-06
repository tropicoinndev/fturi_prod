<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dte_contingencias extends Model
{
    use HasFactory;

    public function dtes()
    {
        return $this->belongsTo(dtes::class, 'dtes_id')->with(['comprobante']);
    }
    public function contingencias()
    {
        return $this->belongsTo(contingencias::class, 'contingencias_id');
    }
}
