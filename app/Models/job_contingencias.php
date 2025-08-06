<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class job_contingencias extends Model
{
    use HasFactory;

    public function contigencias()
    {
        return $this->belongsTo(contingencias::class, 'contingencias_id');
    }
}
