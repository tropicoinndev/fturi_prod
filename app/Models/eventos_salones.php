<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class eventos_salones extends Model
{
    use HasFactory;
        protected $appends = [ 'creacion', 'modificacion'];
    public function creacion(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->created_at)->diffForHumans()
        );
    }
    public function modificacion(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->updated_at)->diffForHumans()
        );
    }
    public function salones()
    {
        return $this->belongsTo(salones::class, 'salones_id');
    }
    public function eventos(){
        return $this->belongsTo(eventos::class, 'eventos_id');
    }


}
