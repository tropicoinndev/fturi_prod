<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
class cajas_comprobantes extends Model
{
    use HasFactory;
    protected $table = 'cajas_comprobantes';
    protected $appends = [ 'cid'];
    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn () => Crypt::encryptString($this->id)
        );
    }
    public function cajas(){
        return $this->belongsTo(cajas::class, 'cajas_id');
    }
    public function cajas_origen(){
        return $this->belongsTo(cajas::class, 'origen_cajas_id');
    }
}
