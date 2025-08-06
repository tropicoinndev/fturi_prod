<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class tipo_eventos extends Model
{
    use HasFactory;
    protected $table = 'tipo_eventos';
    protected $appends = [ 'cid','creacion', 'modificacion'];
    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn () => Crypt::encryptString($this->id)
        );
    }
    public function creacion(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->created_at)->diffForHumans()
        );
    }
    public function modificacion(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->updated_at)->diffForHumans());
    }    
}
