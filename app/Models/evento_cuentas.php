<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class evento_cuentas extends Model
{
    use HasFactory;
    protected $table = 'evento_cuentas';
    protected $appends = ['cid', 'creacion', 'modificado'];
    public function cid(): Attribute
    {
        return Attribute::make(get: fn() => Crypt::encryptString($this->id));
    }
    public function creacion(): Attribute
    {
        return Attribute::make(get: fn() => Carbon::parse($this->created_at)->diffForHumans());
    }
    public function modificado(): Attribute
    {
        return Attribute::make(get: fn() => Carbon::parse($this->updated_at)->diffForHumans());
    }
    public function eventos()
    {
        return $this->belongsTo(eventos::class, 'eventos_id');
    }
    public function usuario()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
    public function orden()
    {
        if ($this->origen == 1)
        return $this->belongsTo(ordenes::class, 'origen_id')->with('detalle_orden', 'cajas');
    }
    public function comanda()
    {
        if ($this->origen == 3)
        return $this->belongsTo(comandas::class, 'origen_id');
    }
    public function reservacion()
    {
        if ($this->origen == 2)
            return $this->belongsTo(reservaciones::class, 'origen_id');
    }

}
