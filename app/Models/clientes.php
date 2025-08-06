<?php

namespace App\Models;

use App\Models\Enums\niveles_cautela;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class clientes extends Model
{
    use HasFactory;

    protected $table = 'clientes';
    protected $appends = ['cid', 'creacion', 'modificacion', 'cautela', 'periodos'];

    public function cid(): Attribute
    {
        return Attribute::make(
            get: fn() => Crypt::encryptString($this->id)
        );
    }
    public function periodos(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->periodos_creditos_id != null ? Crypt::encryptString($this->periodos_creditos_id) : null
        );
    }
    public function cautela(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->nivel_cautela !== null ? niveles_cautela::from($this->nivel_cautela) : null
        );
    }

    public function creacion(): Attribute
    {
        return Attribute::make(
            get: fn() => Carbon::parse($this->created_at)->diffForHumans()
        );
    }
    public function modificacion(): Attribute
    {
        return Attribute::make(
            get: fn() => Carbon::parse($this->updated_at)->diffForHumans()
        );
    }
    public function descuento()
    {
        return $this->belongsTo(descuentos::class, "descuentos_id");
    }
    public function contactos()
    {
        return $this->hasMany(clientes_contactos::class)->with('contactos');
    }
    public function identificaciones()
    {
        return $this->hasMany(clientes_identificaciones::class)->with('identificaciones');
    }
    public function identificacion()
    {
        return $this->hasOne(clientes_identificaciones::class)->with('identificaciones');
    }
    public function giros()
    {
        return $this->hasMany(clientes_giros::class)->with('giros');
    }
    public function detalle()
    {
        return $this->belongsTo(detalle_contribuyentes::class, "id", 'clientes_id')
            ->where('clientes_id', '>', 0);
    }
    public function municipios()
    {
        return $this->belongsTo(municipios::class, 'municipios_id');
    }
    public function actividades()
    {
        return $this->belongsTo(actividades_economicas::class, 'actividades_economicas_id');
    }
    public function extranjero()
    {
        return $this->belongsTo(paises::class, 'extranjeros_id');
    }

    public function ordenesActivas()
    {
        return $this->hasMany(ordenes::class, 'clientes_id')
            ->where('facturada', false)
            ->where('estado', true)
            ->where('comprobante', true)
            ->whereNotIn('id', function ($q) {
                $q->select('origen_id')->from('detalle_cobros')
                    ->where('origen', 1)->where('estado', true);
            });
    }
    public function recepcionesActivas()
    {
        return $this->hasMany(recepciones::class, 'clientes_id')
            ->where('eliminado', false)
            ->where('facturada', false)
            //->where('estado', true)
            ->where(function ($q) {
                $q->whereDate('fecha_salida', '<=', date("Y-m-d"))
                    ->orWhere('comprobante', true);
            })
            ->whereNotIn('id', function ($q) {
                $q->select('origen_id')->from('detalle_cobros')
                    ->where('origen', 2)->where('estado', true);
            });;
    }
    public function comandasActivas()
    {
        return $this->hasMany(comandas::class, 'clientes_id')
            ->where('comprobante', true)
            ->where('eliminada', false)
            ->where('facturada', false)
            ->where('anulada', false)
            ->where('estado', true)
            ->whereNotIn('id', function ($q) {
                $q->select('origen_id')->from('detalle_cobros')
                    ->where('origen', 3)->where('estado', true);
            });;
    }

    public function anticipos()
    {
        return $this->hasMany(anticipos::class, 'clientes_id')
            ->where("monto", ">", 0)
            ->where("estado", true)
            ->where("anulado", false)
            ->whereNotIn('id', function ($q) {
                $q->from('anticipo_reservacions')->select('anticipos_id');
            })
            ->orderBy('id', 'asc');
    }
    public function anticipos_reservados()
    {
        return $this->hasMany(anticipos::class, 'clientes_id')
            ->where("monto", ">", 0)
            ->where("estado", true)
            ->where("anulado", false)
            ->whereIn('id', function ($q) {
                $q->from('anticipo_reservacions')->select('anticipos_id');
            })
            ->orderBy('id', 'asc');
    }

    public function municipiosDepartamentos()
    {
        return $this->belongsTo(municipios::class, 'municipios_id')->with("departamentos");
    }

    public function solicitantes()
    {
        return $this->hasMany(solicitantes_clientes::class, 'clientes_id')->with('solicitantes');
    }

    public function periodosCreditos()
    {
        return $this->belongsTo(periodos_creditos::class, 'periodos_creditos_id');
    }
}
