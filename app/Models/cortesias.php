<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use stdClass;
class cortesias extends Model
{
    use HasFactory;
    protected $appends = ['cid', 'cuenta', 'detalle'];
    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn () => Crypt::encryptString($this->id)
        );
    }
    public function cuenta(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->origen == 1 ? 'Orden' : ($this->origen == 2 ? 'Estadía' : ($this->origen == 3 ? 'Comanda' : 'No reconocida'))
        );
    }
    public function usuario_autoriza()
    {
        return $this->belongsTo(User::class, 'autoriza_users_id');
    }

    public function titular()
    {
        return $this->belongsTo(control_cortesias::class, 'control_cortesias_id');
    }

    public function orden()
    {
        if ($this->attributes['origen'] == 1)
            return $this->belongsTo(ordenes::class, 'origen_id')->with(['detalle_orden', 'cajas']);
        else return [];
    }
    public function estadia()
    {
        if ($this->attributes['origen'] == 2)
            return $this->belongsTo(recepciones::class, 'origen_id')->with(['habitaciones', 'tarifas']);
        else return [];
    }
    public function comanda()
    {
        if ($this->attributes['origen'] == 3)
            return $this->belongsTo(comandas::class, 'origen_id')->with('detalles_comanda', 'cajas');
        else return [];
    }
    public function detalle(): Attribute
    {

        if (isset($this->attributes['id']) && $this->attributes['id'] != null)
            switch ($this->attributes['origen']) {
                case 1:
                    return Attribute::make(
                        get: fn () =>  $this->orden
                    );
                    break;
                case 2:
                    return Attribute::make(
                        get: fn () => $this->estadia
                    );
                    break;
                case 3:
                    return Attribute::make(
                        get: fn () => $this->comanda ? $this->comanda->load('usuarios') : null
                    );
                    break;
            }
        else return Attribute::make(
            get: fn () => []
        );
    }
}
