<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class eventos extends Model
{
    use HasFactory;
    protected $table = 'eventos';
    protected $appends = ['cid', 'creacion', 'actualizado', 'total_evento','formateada','montofacturado','final'];
    public function cid(): Attribute
    {
        return Attribute::make(get: fn() => Crypt::encryptString($this->id));
    }
    public function creacion(): Attribute
    {
        return Attribute::make(get: fn() => Carbon::parse($this->created_at)->diffForHumans());
    }
    public function actualizado(): Attribute
    {
        return Attribute::make(get: fn() => Carbon::parse($this->updated_at)->diffForHumans());
    }
    public function formateada(): Attribute
    {
        return Attribute::make(get: fn() => Carbon::parse($this->fecha)->isoFormat('dddd, D [de] MMMM [de] YYYY'));
    }
    public function final(): Attribute
    {
        return Attribute::make(get: fn() => Carbon::parse($this->fecha_fin)->isoFormat('dddd, D [de] MMMM [de] YYYY'));
    }
    public function clientes()
    {
        return $this->belongsTo(clientes::class, 'clientes_id')->with('contactos');
    }
    public function sonidos()
    {
        return $this->belongsTo(sonidos::class, 'sonidos_id');
    }
    public function tipo_eventos()
    {
        return $this->belongsTo(tipo_eventos::class, 'tipo_eventos_id');
    }
    public function forma_pagos()
    {
        return $this->belongsTo(forma_pagos::class, 'forma_pagos_id');
    }
    public function montajes()
    {
        return $this->belongsTo(montajes::class, 'montajes_id');
    }
    public function usuarios()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
    public function autorizacion()
    {
        return $this->belongsTo(User::class, 'autoriza_users_id');
    }
    public function negacion()
    {
        return $this->belongsTo(User::class, 'negacion_users_id');
    }
    public function getAnticipos()
    {
        return $this->hasMany(anticipo_reservacion::class, 'reservacion_id')
            ->where('tipo_reservacion', 4)->with('anticipos')
            ->whereIn('anticipos_id', function ($query) {
                $query->select('id')
                    ->from('anticipos')
                    ->where('estado', true)
                    ->where('anulado', false);
            })
            ->withSum('anticipos', 'monto')
            ->withCount('anticipos');
    }
    public function cuentas()
    {
        return $this->hasMany(evento_cuentas::class, 'eventos_id')->where('estado', true);
    }

    public function detalle_montaje()
    {
        return $this->hasMany(detalle_montaje_eventos::class, 'eventos_id');
    }
    public function totalEvento(): Attribute
    {
        return Attribute::make(
            get: fn () =>  $this->cuentas->sum('monto')

        );
    }

    public function salones()
    {
        return $this->hasMany(eventos_salones::class)->with('salones');
    }
    public function galerias_evento()
    {
        return $this->hasMany(eventos_galerias::class)->with('galerias')->withCount('galerias');
    }

    public function ordenesEvento($detalle) {
        return ordenes::whereIn('id', function ($q) use ($detalle) {
        $q->select('origen_id')
            ->from('evento_cuentas')
            ->where('origen', 1)
            ->where('eventos_id', $detalle)
            ->where('estado', true);
        });

    }
    public function comandasEvento($detalle) {
        return comandas::whereIn('id', function ($q) use ($detalle){
            $q->select('origen_id')
            ->from('evento_cuentas')
            ->where('origen',3)
            ->where('eventos_id',$detalle)
            ->where('estado',true);
        });
    }
    public function salonesEvento($detalle,$fecha,$inicio,$finalizacion)
    {

        $salones =salones::whereNotIn('id', function ($q) {
            $q->select('salones_id')
            ->from('eventos_salones');
        })
        ->orWhere(function ($s) use ($detalle, $fecha, $inicio, $finalizacion) {
            $s->whereIn('id', function ($e) use ($detalle, $fecha, $inicio, $finalizacion) {
                $e->select('salones_id')
                ->from('eventos_salones')
                ->where('eventos_id', $detalle)
                    ->leftJoin('eventos', 'eventos.id', '=', 'eventos_salones.eventos_id')
                    ->where('eventos.id',$detalle)
                    ->whereDate('eventos.fecha', $fecha)
                    ->whereTime('eventos.inicio', '=', $inicio)
                    ->whereTime('eventos.finalizacion', '=', $finalizacion);
            });
        })
        ->get();

        return $salones;
    }
    public function  salonesEventoAnterior($eventoAnterior)
    {
        $salonesEventoAnterior = salones::whereIn('id', function ($d) use ($eventoAnterior) {
                $d->select('salones_id')
                ->from('eventos_salones')
                ->where('eventos_id', $eventoAnterior);
            })->get();

            return $salonesEventoAnterior;
    }
    public function cuentaOrdenes($id)
    {
    return ordenes::whereIn('id', function ($q) use ($id) {
            $q->select('origen_id')
            ->from('evento_cuentas')
            ->where('origen', 1)
            ->where('eventos_id', $id)
                ->where('estado', true);
        })
        ->whereNotIn('id', function ($q) {
            $q->select('origen_id')
                ->from('detalle_cobros')
                ->where('origen', 1)
                ->where('estado', true);

        });
    }
    public function cuentaComandas($id)
    {
        return comandas::whereIn('id', function ($q) use ($id) {
            $q->select('origen_id')
            ->from('evento_cuentas')
            ->where('origen', 3)
            ->where('eventos_id', $id)
                ->where('estado', true);
        })->whereNotIn('id', function ($co) {
            $co->select('origen_id')->from('detalle_cobros')
            ->where('origen', 3)
            ->where('estado', true);
        });
    }
    public function ordenesTest()
    {
        return ordenes::whereIn('id', function ($query) {
            $query->select('origen_id')
            ->from('evento_cuentas')
            ->where('origen', 1)
            ->where('eventos_id', $this->id)
                ->where('estado', true);
        });
    }
    public function comandasTest()
    {
        return comandas::whereIn('id', function ($query) {
            $query->select('origen_id')
            ->from('evento_cuentas')
            ->where('origen', 3)
            ->where('eventos_id', $this->id)
                ->where('estado', true);
        });
    }
    public function reservaciones()
    {
        return reservaciones::whereIn('id', function ($query) {
            $query->select('origen_id')
            ->from('evento_cuentas')
            ->where('origen', 2)
            ->where('eventos_id', $this->id)
                ->where('estado', true);
        });
    }
    public function comandaFacturada()
    {
        return $this->hasMany(evento_cuentas::class, 'eventos_id')
        ->where('origen', 3)
        ->where('estado', true)
        ->whereIn('origen_id', function ($q) {
            $q->select('id')
                ->from('comandas')
                ->whereIn('id', function ($co) {
                    $co->select('origen_id')
                    ->from('detalle_cobros')
                    ->where('origen', 3)
                    ->where('estado', true);
                });
        });
    }
    public function ordenFacturada()
    {
        return $this->hasMany(evento_cuentas::class, 'eventos_id')
            ->where('origen', 1)
            ->where('estado', true)
            ->whereIn('origen_id', function ($q) {
                $q->select('id')
                    ->from('ordenes')
                    ->whereIn('id', function ($co) {
                        $co->select('origen_id')
                            ->from('detalle_cobros')
                            ->where('origen', 1)
                            ->where('estado', true);
                    });
            });
    }
    public function montofacturado(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->comandaFacturada()->sum('monto') + $this->ordenFacturada()->sum('monto')
        );
    }

}

