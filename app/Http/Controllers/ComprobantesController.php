<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNotaCreditoRequest;
use App\Mail\DteMail;
use App\Models\anticipos;
use App\Models\anticipos_cobros;
use App\Models\anulacion_comprobantes;
use App\Models\cajas;
use App\Models\cajas_users;
use App\Models\clientes;
use App\Models\cobros;
use App\Models\comanda_detalles;
use App\Models\comandas;
use App\Models\comprobanteDetalleStructure;
use App\Models\comprobantes;
use App\Models\comprobantes_pagos;
use App\Models\comprobanteStructure;
use App\Models\descuentos;
use App\Models\detalle_comprobantes;
use App\Models\detalle_ordenes;
use App\Models\dteBase;
use App\Models\dtes;
use App\Models\dtesFiles;
use App\Models\forma_pagos;
use App\Models\ordenes;
use App\Models\recepcion_salidas;
use App\Models\recepciones;
use App\Models\registro;
use App\Models\rubro;
use App\Models\servicios;
use App\Models\tipo_comprobantes;
use App\Models\turnos;
use App\Models\User;
use Carbon\Carbon;
use Exception;

use function Pest\Laravel\json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

use stdClass;

class ComprobantesController extends Controller
{
    private $table = 'comprobantes';
    private $url_mh;
    public function __construct()
    {
        $this->getTh($this->table, 'Comprobantes');
        $this->url_mh = env('HOST_API');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view($this->table . '.index', [
            'cajas' => $this->getCajasAccess(),
            'caja' => 0,
        ]);
    }

    public function search(Request $r)
    {
        $r->validate([
            'fecha' => ['required', 'date'],
            'cajas' => ['required', 'string'],
            'busqueda' => ['required', 'string']
        ]);

        $busqueda = trim($r->busqueda);
        $fecha = $r->fecha ?? date('Y-m-d');
        $caja = $r->cajas ? Crypt::decryptString($r->cajas) : 0;
        $comprobantes = comprobantes::whereDate("fecha", '>=',   $fecha);


        if ($busqueda && strlen($busqueda) > 0)
            $comprobantes = $comprobantes->where(function ($q) use ($busqueda) {
                $q->where("titular", 'like', "%" . trim(strtoupper($busqueda)) . "%")
                    ->orWhere("correlativo", 'like', trim(strtoupper($busqueda)) . "%");
            });


        if ($caja != 0) {
            $turnos = turnos::where(function ($q) use ($fecha) {
                $q->whereRaw("? between  apertura and cierre", ['fecha' => $fecha])
                    ->orWhere('fecha', '>=', $fecha);
            })->where('cajas_id', intval($caja))->pluck('id');

            $comprobantes = $comprobantes->whereIn('turnos_id', $turnos);
        }

        $comprobantes = $comprobantes->orderByDesc('correlativo')->orderByDesc('tipo_comprobantes_id')->get();

        $cajas = $this->getCajasAccess();
        return view($this->table . '.index', [
            'cajas' => $cajas,
            'caja' => $caja,
            'fecha' => $fecha,
            'busqueda' => $busqueda,
            'comprobantes' => $comprobantes,
        ]);
    }
    protected function getCajasAccess()
    {
        $cajasAccess = cajas_users::where('users_id', Auth::user()->id)->get();
        return cajas::whereIn('id', $cajasAccess->pluck('cajas_id'))->get();
    }

    public function show(Request $r)
    {
        $comprobante = comprobantes::find(Crypt::decryptString($r->id));
        $registro = registro::where('comprobantes_id', $comprobante->id)->get();
        $formas = forma_pagos::all();

        return view('comprobantes.show', [
            'comprobante' => $comprobante,
            'registros' => $registro,
            'formas' => $formas,
        ]);
    }
    public function detalle(Request $r)
    {
        $comprobante = comprobantes::find(Crypt::decryptString($r->id));
        $registro = registro::where('comprobantes_id', $comprobante->id)->get();
        $formas = forma_pagos::all();

        return view('comprobantes.detalle', [
            'comprobante' => $comprobante,
            'registros' => $registro,
            'formas' => $formas,
        ]);
    }

    public function cobro(Request $r)
    {
        try {
            if (env('disable_facturacion', false))
                return redirect()->back()
                    ->with('type', "danger")
                    ->with('message', "En este momento no es posible facturar. Se informara cuando sea permitido");

            $p = cobros::find(Crypt::decryptString($r->id));
            if ($p->facturado || !$p->estado || $p->anulacion_comprobantes_id)
                return redirect()->back()
                    ->with('type', "danger")
                    ->with('message', "Este cobro ya fue facturado, pertenece a un comprobante anulado, o fue desactivado. Si alguna de las cuentas en este cobro deben facturarse eliminelas del cobro y vuelva a intentar.");
            $this->getValidCobros($p);
            $data = $this->getDataFromCobros($p);
            if ($data->cliente != null && !$data->cliente->estado)
                return redirect()->route('cajas.my')
                    ->with('type', "danger")
                    ->with('message', "El cliente no es valido, este cliente esta desactivado.");

            if ($data->cliente != null && !$data->cliente->tipo_cliente && $data->cliente->detalle == null)
                return redirect()->route('cajas.my')
                    ->with('type', "danger")
                    ->with('message', "El cliente no es valido, no cumple todos los requisitos.");

            $restriccion_pagos = [6002];
            if ($data->cliente != null && $data->cliente->credito)
                $restriccion_pagos = [];

            array_push($restriccion_pagos, 6004);

            $tipo_comprobante = tipo_comprobantes::where('token', $p->tipo_comprobante)->first();
            $correlativo = (new CorrelativosController)->getCorrelativoByToken($tipo_comprobante->token, session('caja')->id);

            if ($correlativo == null)
                return redirect()->back()
                    ->with("message", 'No se encontro un correlativo')
                    ->with('type', "danger");


            $fecha = Carbon::now();
            $efectivo = 0;
            $banco = 0;
            if ($data->cliente != null && $data->cliente?->id > 0) {
                $efectivo =  DB::table('get_efectivo')
                    ->whereMonth('fecha', $fecha->format('m'))
                    ->whereYear('fecha', $fecha->format("Y"))
                    ->where('clientes_id', $data->cliente?->id)
                    ->sum('total_efectivo');
                $banco = DB::table('get_bancos')
                    ->whereMonth('fecha', $fecha->format('m'))
                    ->whereYear('fecha', $fecha->format("Y"))
                    ->where('clientes_id', $data->cliente?->id)
                    ->sum('total_bancos');
            }
            return view('comprobantes.create', [
                'data'              => $data,
                'token'             => $tipo_comprobante->token,
                'correlativo'       => $correlativo,
                'tipo_comprobante'  => $tipo_comprobante,
                'id'                => $r->id,
                'tipo'              => $r->tipo,
                'tipoComprobante'   => $p->tipo_comprobante,
                'p'                 => $p,
                'formas_pagos'      => forma_pagos::whereNotIn('token', $restriccion_pagos)->get(),
                "descuentos"        => descuentos::all(),
                'efectivo'          => $efectivo,
                'banco'             => $banco,
            ]);
        } catch (\Throwable $th) {
            return redirect()->route('cajas.my')
                ->with('type', "danger")
                ->with('message', $th->getMessage());
        }
    }
    private function getValidCobros($p)
    {

        foreach ($p->detalleCobros as $d) {
            switch ($d->origen) {
                case 1:
                    $p = ordenes::find($d->origen_id);
                    if (!$p->estado)
                        return throw new Exception("La orden No. " . $p->id . ",  esta desactivada o esta facturada. Elimine el cobro, e intente configurar uno nuevo, excluyendo esta.");
                    break;
                case 2:
                    $p = recepciones::find($d->origen_id);
                    if (!$p->estado || $p->facturada || $p->eliminado)
                        if (recepcion_salidas::where('recepciones_id', $p->id)->where('estado', true)->where('facturada', false)->count() == 0)
                            throw new Exception("La estadía No. " . $p->id . ", esta desactivada o esta facturada. Elimine el cobro, e intente configurar uno nuevo, excluyendo esta.");
                    break;
                case 3:
                    $p = comandas::find($d->origen_id);
                    if (!$p->estado || $p->facturada || $p->eliminada || $p->anulada)
                        throw new Exception("La comanda No. " . $p->id . ", esta desactivada o esta facturada. Elimine el cobro, e intente configurar uno nuevo, excluyendo esta.");
                    break;
            }
        }
    }
    private function getDataFromCobros($p)
    {
        $cliente = $this->getCliente($p->clientes_id);
        $data = new \stdClass;
        $data->cliente = $cliente;
        $data->titular = $this->getTitular($cliente, $p->titular);
        $totales = new comprobanteStructure();
        $detalle = array();

        foreach ($p->detalleCobros as $dc) {
            $d = $this->getData($dc->origen_id, $dc->origen, $p->tipo_comprobante, $cliente);
            if (property_exists($d, 'detalle'))
                array_push($detalle, $d->detalle);
            if (property_exists($d, 'totales'))
                $totales = $this->getTotales($totales, $d->totales);
        }

        $retencion = env('aplicar_retencion', 100);
        if ($cliente != null && !$cliente->tipo_cliente && $cliente->detalle?->percepcion && $totales->neto >= $retencion) {
            $totales->percepcion = $this->getPercepcion($totales->neto);
            //$totales->percepcion = $totales->total * 0.01;
            $totales->total -= $totales->percepcion;
        }
        $data->totales = $totales;
        $data->detalle = $detalle;
        return $data;
    }
    private function getTotales($total, $data)
    {
        $totales                = $total;
        $totales->iva           += $data->iva;
        $totales->cesc          += $data->cesc;
        $totales->advalorem     += $data->advalorem;
        $totales->propina       += $data->propina;
        $totales->exento        += $data->exento;
        $totales->gravado       += $data->gravado;
        $totales->neto          += $data->neto;
        $totales->percepcion    += $data->percepcion;
        $totales->subtotal      += $data->subtotal;
        $totales->total         += $data->total;
        return $totales;
    }

    private function getData($id, $tipo, $comprobante, $cliente = null): stdClass
    {
        $data = new \stdClass;
        $comprobante_data = null;
        switch ($tipo) {
            case 1:
                $comprobante_data = $this->getComprobanteOrden($id, $cliente, $comprobante);
                break;
            case 2:
                $comprobante_data = $this->getComprobanteEstadias($id, $cliente, $comprobante);
                break;
            case 3:
                $comprobante_data = $this->getComprobanteComandas($id, $cliente, $comprobante);
                break;
        }
        if ($comprobante_data != null) {
            $data->totales = $comprobante_data[1];
            $data->detalle = $comprobante_data[0];
        }

        return $data;
    }

    /* cSpell:ignore cesc, advalorem, recepcion, percepcion, Estadias */
    public function getComprobanteComandas($comanda, $cliente, $comprobante)
    {
        (new PagoAnticipadoController())->turnoComanda($comanda);

        $detalle = [];
        $comanda_detalle = comanda_detalles::where("comandas_id", $comanda)
            ->where('anulado', false)
            ->select(
                'iva',
                'propina',
                'advalorem',
                'precios_id',
                'descuentos_id',
                DB::raw('SUM(cantidad) as cantidad'),
                DB::raw('precio')
            )
            ->groupBy(
                'precios_id',
                'iva',
                'propina',
                'advalorem',
                'precio',
                'descuentos_id',
            )
            ->get();

        $c = new comprobanteStructure();
        foreach ($comanda_detalle as $od) {
            $n = $this->getNetoComandas($od);
            $dc = new comprobanteDetalleStructure($od->cantidad, $n[0], $od->precios->detalle, $od->precios->categorias_precios->rubros_id, $comanda, 3);

            if ($od->descuentos_id != null) {
                $dc->descuentos_id = (float) $od->descuentos_id;
                $dc->porcentaje_descuento = $od->descuentos->porcentaje;
                $dc->descuento = round($od->precio * $od->descuentos->decimales, 2);
            }
            if ($cliente != null && !$cliente->tipo_cliente && $cliente->detalle->exento) {
                $dc->exento = round($dc->neto, 4);
                $dc->advalorem  = (float) $n[1];
                if ($dc->advalorem > 0)
                    $dc->sugerido   = (float) $od->precios->sugerido;
            } else {
                $dc->iva        = (float) $od->iva ? round($dc->neto * env('iva', 0.13), 4) : 0;
                $dc->cesc       = (float) 0;
                $dc->advalorem  = (float) $n[1];
                $dc->gravado    = (float) $this->getPrecioByToken($comprobante, $dc);

                if ($dc->advalorem > 0)
                    $dc->sugerido   = (float) $od->precios->sugerido;
            }
            $dc->propina    = (float) $od->propina ? round($dc->neto * env('propina', 0.1), 4) : 0;

            $dc->descuento       *= $dc->cantidad;
            $dc->subtotal         = (float) $dc->exento + $dc->gravado;
            $dc->total            = (float) $dc->neto + $dc->iva + $dc->cesc + $dc->advalorem + $dc->propina;
            $c->iva        += $dc->iva * $dc->cantidad;
            $c->cesc       += $dc->cesc * $dc->cantidad;
            $c->advalorem  += $dc->advalorem * $dc->cantidad;
            $c->propina    += $dc->propina * $dc->cantidad;
            $c->exento     += $dc->exento * $dc->cantidad;
            $c->gravado    += $dc->gravado * $dc->cantidad;
            $c->neto       += $dc->neto * $dc->cantidad;
            $c->subtotal   = $c->neto + $c->iva + $c->cesc + $c->advalorem + $c->propina;
            $c->total      = $c->subtotal;
            //$dc->detalle    = $od;
            array_push($detalle, $dc);
        }
        /*
        if ($cliente != null && !$cliente->tipo_cliente && $cliente->detalle->percepcion)
            $c->percepcion = $this->getPercepcion($c->neto);
        $c->total -= $c->percepcion;
*/
        return [$detalle, $c];
    }
    private function getNetoComandas($d)
    {
        $a = 1;
        $precio = $d->precio;
        $neto = 0;
        $advalorem = 0;

        if ($d->iva)
            $a += env('iva', 0.13);

        if ($d->propina)
            $a += env('propina', 0.13);

        if ($d->descuentos_id > 0) {
            $descuento = descuentos::find($d->descuentos_id);
            $precio = round($d->precio - ($d->precio * $descuento->decimales), 2);
        }
        $neto = round($precio / $a, 4);
        $advalorem = $this->getAdvaloremComandas($d, $neto);
        return [round((($precio - $advalorem) / $a), 4), $advalorem];
    }

    private function getAdvaloremComandas($d, $neto)
    {
        if ($d->advalorem && $d->precios->sugerido > 0) {

            $sugerido = round($d->precios->sugerido / (1 + env('iva', 0.13)), 4);

            if ($neto > $sugerido)
                return round(($neto - $sugerido) *  env('advalorem', 0.05), 4);
            return 0;
        } else return 0;
    }

    public function getComprobanteEstadias($recepcion, $cliente, $comprobante)
    {
        $detalle = [];
        $totales = new comprobanteStructure();
        $od = recepciones::find($recepcion);
        $precio         = round($od->tarifa ?? $od->tarifas->monto, 4);
        $rubro = rubro::where("token", 12002)->first();
        if (!($rubro && $rubro->id > 0))
            throw new Exception("No se encontró un rubro de tipo 12002 -> Hotel, no se podrán cobrar habitaciones sin este rubro.");

        $concepto = "USO DE HABITACION";
        if ($od?->habitaciones?->glorieta) {
            $concepto = "USO DE GLORIETA";
        }

        $dc = new comprobanteDetalleStructure($od->dias, 0, $concepto, $rubro->id, $recepcion, 2);
        if ($od->descuentos_id != null) {
            $dc->descuentos_id = $od->descuentos_id;
            $dc->porcentaje_descuento = $od->descuentos->porcentaje;
            $dc->descuento = round($precio * $od->descuentos->decimales, 4);
            $precio = $precio - $dc->descuento;
        }
        $impuestos = 1 + env('iva', 0.13);

        if (!$od?->habitaciones?->glorieta)
            $impuestos += +env('cesc', 0.05);

        $dc->neto       = (float) round(($precio / $impuestos), 4);

        if ($cliente != null && !$cliente->tipo_cliente && $cliente->detalle->exento) {
            $dc->exento = round($dc->neto, 4);
            //$dc->cesc       = (float) round($dc->neto * env('cesc', 0.05), 4);
        } else {
            $dc->iva        = (float) round($dc->neto * env('iva', 0.13), 4);
            $dc->gravado    = (float) $this->getPrecioByToken($comprobante, $dc);
            if (!$od?->habitaciones?->glorieta) {
                $dc->cesc       = (float) round($dc->neto * env('cesc', 0.05), 4);
            }
        }
        $dc->descuento       *= $dc->cantidad;
        $dc->subtotal         = (float) round($dc->exento + $dc->gravado, 4);
        $dc->total            = (float) round($dc->neto + $dc->iva + $dc->cesc + $dc->advalorem + $dc->propina, 4);


        $rubro_servicio = rubro::where("token", 12003)->first();
        if (!($rubro_servicio && $rubro_servicio->id > 0))
            throw new Exception("No se encontró un rubro de tipo 12003 -> Servicios, no se podrán cobrar habitaciones sin este rubro es requerido para aplicar cargos.");

        $totales->iva        += round($dc->iva * $dc->cantidad, 4);
        $totales->cesc       += round($dc->cesc * $dc->cantidad, 4);
        $totales->advalorem  += round($dc->advalorem * $dc->cantidad, 4);
        $totales->propina    += round($dc->propina * $dc->cantidad, 4);
        $totales->exento     += round($dc->exento * $dc->cantidad, 4);
        $totales->gravado    += round($dc->gravado * $dc->cantidad, 4);
        $totales->neto       += round($dc->neto * $dc->cantidad, 4);
        $totales->subtotal   = round($totales->neto + $totales->iva + $totales->cesc + $totales->advalorem + $totales->propina, 4);
        $totales->total      = round($totales->subtotal, 4);
        //$dc->detalle    = $od;
        array_push($detalle, $dc);

        //Agregar cargos al comprobante
        $cargos = $od->cargos;
        foreach ($cargos as $cargo) {
            $c = new comprobanteDetalleStructure($cargo->cantidad, $cargo->neto, $cargo->cargos->cargo, $rubro_servicio->id, $recepcion, 2);

            if ($cliente != null && !$cliente->tipo_cliente && $cliente->detalle->exento) {
                $c->exento = round($c->neto, 4);
                $c->cesc       = (float) $cargo->cesc;
                $c->propina       = (float) $cargo->propina;
            } else {
                $c->iva        = (float) $cargo->iva;
                $c->cesc       = (float) $cargo->cesc;
                $c->propina       = (float) $cargo->propina;
                $c->gravado    = (float) $this->getPrecioByToken($comprobante, $c);
            }
            $c->subtotal         = (float) $c->exento + $c->gravado;
            $c->total            = (float) $c->neto + $c->iva + $c->cesc + $c->advalorem + $c->propina;
            $c->rubros_id        = $rubro_servicio->id;
            $c->porcentaje_descuento = 0;


            $totales->iva        += $c->iva * $c->cantidad;
            $totales->cesc       += $c->cesc * $c->cantidad;
            $totales->advalorem  += $c->advalorem * $c->cantidad;
            $totales->propina    += $c->propina * $c->cantidad;
            $totales->exento     += $c->exento * $c->cantidad;
            $totales->gravado    += $c->gravado * $c->cantidad;
            $totales->neto       += $c->neto * $c->cantidad;
            $totales->subtotal   = $totales->neto + $totales->iva + $totales->cesc + $totales->advalorem + $totales->propina;
            $totales->total      = $totales->subtotal + $totales->exento;
            array_push($detalle, $c);
        }
        /*
        if ($cliente != null && !$cliente->tipo_cliente && $cliente->detalle->percepcion)
            $totales->percepcion = $this->getPercepcion($totales->neto);
        $totales->total -= $totales->percepcion;
        */
        return [$detalle, $totales];
    }

    public function getComprobanteOrden($orden, $cliente, $comprobante)
    {
        (new PagoAnticipadoController())->turnoOrden($orden);
        $detalle = [];
        $orden_detalle = detalle_ordenes::where("ordenes_id", $orden)->get();
        /*
        $totales             = new \stdClass;
        $totales->iva        = 0;
        $totales->cesc       = 0;
        $totales->advalorem  = 0;
        $totales->propina    = 0;
        $totales->exento     = 0;
        $totales->gravado    = 0;
        $totales->neto       = 0;
        $totales->percepcion = 0;
        */
        $totales = new comprobanteStructure();
        foreach ($orden_detalle as $od) {
            /*
            $dc             = new \stdClass;
            $dc->cantidad   = $od->cantidad;
            $dc->concepto   = $od->servicios->servicio;
            $dc->iva        = 0;
            $dc->cesc       = 0;
            $dc->advalorem  = 0;
            $dc->propina    = 0;
            $dc->exento     = 0;
            $dc->gravado    = 0;
            $dc->neto       = (float) $od->neto;
            $dc->descuentos_id = null;
            $dc->descuento = 0;
            $dc->porcentaje_descuento = 0;
            $dc->sugerido = 0;
            $dc->rubros_id = $od->servicios->rubros_id ?? null;
            */
            $dc = new comprobanteDetalleStructure($od->cantidad, (float) $od->neto, $od->servicios->servicio, $od->servicios->rubros_id, $orden, 1);

            if ($od->descuentos_id != null) {
                $dc->descuentos_id = $od->descuentos_id;
                $descuento = $this->getDescuentoServicio($od->servicios_id, $od->descuentos_id);
                if ($descuento && count($descuento) > 0) {
                    $dc->porcentaje_descuento = $descuento['porcentaje'];
                    $dc->descuento =  $descuento['monto'];
                }
            }

            if ($cliente != null && !$cliente->tipo_cliente && $cliente->detalle->exento) {
                $dc->exento = round($dc->neto, 4);
                // $dc->cesc       = (float) $od->cesc;
                $dc->advalorem  = (float) $od->advalorem;
                $dc->propina    = (float) $od->propina;
            } else {
                $dc->iva        = (float) $od->iva;
                $dc->gravado    = (float) $this->getPrecioByToken($comprobante, $dc);
                $dc->cesc       = (float) $od->cesc;
                $dc->advalorem  = (float) $od->advalorem;
                $dc->propina    = (float) $od->propina;
            }
            if ($dc->advalorem > 0)
                $dc->sugerido   = (float) $od->servicios->sugerido;

            $dc->descuento       *= $dc->cantidad;
            $dc->subtotal         = (float) $dc->exento + $dc->gravado;
            $dc->total            = (float) $dc->neto + $dc->iva + $dc->cesc + $dc->advalorem + $dc->propina;
            $totales->iva        += $dc->iva * $dc->cantidad;
            $totales->cesc       += $dc->cesc * $dc->cantidad;
            $totales->advalorem  += $dc->advalorem * $dc->cantidad;
            $totales->propina    += $dc->propina * $dc->cantidad;
            $totales->exento     += $dc->exento * $dc->cantidad;
            $totales->gravado    += $dc->gravado * $dc->cantidad;
            $totales->neto       += $dc->neto * $dc->cantidad;
            $totales->subtotal   = $totales->neto + $totales->iva + $totales->cesc + $totales->advalorem + $totales->propina;
            $totales->total      = $totales->subtotal;
            //$dc->detalle    = $od;
            array_push($detalle, $dc);
        }
        /*if ($cliente != null && !$cliente->tipo_cliente && $cliente->detalle?->percepcion) {
            $totales->percepcion = $this->getPercepcion($totales->neto);
            $totales->percepcion = $totales->total * 0.01;
            $totales->total -= $totales->percepcion;
        }*/
        return [$detalle, $totales];
    }

    public function getPercepcion($total)
    {
        return round($total  * env('percepcion'), 4);
    }
    public function getDescuentoServicio($servicio, $descuento)
    {
        $s = servicios::find($servicio);
        $d = descuentos::find($descuento);
        return ['monto' => $s->precio_unitario * $d->decimales, 'porcentaje' => $d->porcentaje];
    }
    public function getPrecioByToken($token, $detalle): float
    {
        switch ($token) {
            case 7001:
                return $detalle->neto;
                break;
            case 7002:
                return round($detalle->neto + $detalle->iva, 4);
                break;
            default:
                return 0;
                break;
        }
        return (float) 0;
    }
    public function getCliente($clientes_id)
    {
        if ($clientes_id > 0) {
            $cliente = clientes::where('id', $clientes_id)->with('detalle')->first();
            return $cliente;
        } else
            return null;
    }
    public function getTitular($cliente, $t = null)
    {
        $titular = null;
        if ($cliente != null) {
            //$titular = $t != null ? $t : '';
            //Cliente Jurídico
            $nombre = "";
            if (!$cliente->tipo_cliente)
                $nombre = ($cliente->detalle->juridico ?? $cliente->nombre);
            else
                //Cliente consumidor final
                $nombre = $cliente->nombre;

            if ($nombre != null && strlen($nombre) > 0)
                $titular = $nombre;
            if ($t != null && strlen($t) > 0)
                $titular = $titular . " / " . $t;
        }
        $titular = $titular ?? "CLIENTES";
        return $titular;
    }

    /**
     * Validación de las cuentas, creación de comprobante
     *
     * @param Request $r
     * @return redirect
     */
    public function store(Request $r)
    {
        try {
            $id = Crypt::decryptString($r->id);
            $p = cobros::find($id);

            //Validación de cuentas
            $this->getValidCobros($p);

            $total = 0;
            $numero_pagos = $r->numero_pagos;
            if ($numero_pagos > 0)
                for ($i = 0; $i < $numero_pagos; $i++)
                    $total += floatval($r["valor-" . $i]);

            $totalAnticipos = $p->anticipos->sum("monto");

            $total += $totalAnticipos;

            //Crear estructura de datos para comprobante
            $data = $this->getDataFromCobros($p);
            if (!(round(floatval($total), 2) === round(floatval($data->totales->total), 2)))
                throw new Exception('El total de pagos ingresados no es igual a el total del comprobante, ingrese de nuevo el total de pagos, e intente generar el comprobante nuevamente.');

            if (!(round(floatval($totalAnticipos), 2) === round(floatval($data->totales->total), 2)))
                for ($i = 0; $i < $numero_pagos; $i++)
                    if (forma_pagos::find($r["forma_pago_" . $i]) == null)
                        throw new Exception('Ocurrió un problema al identificar las formas de pagos, seleccione las formas de pagos correspondientes e intente de nuevo.');

            //Creación de Comprobantes
            $comprobante = $this->createComprobante($data, $p->tipo_comprobante, $r->procedencia ?? null, $r->banco ?? null, $r->observaciones ?? null);

            //Creación de detalle de comprobante
            foreach ($data->detalle as $detalle)
                foreach ($detalle as $v) (new DetalleComprobantesController)->createDetalle($v, $comprobante->id);

            //Creación de registro de pagos según formas de pagos

            if ($numero_pagos > 0)
                for ($i = 0; $i < $numero_pagos; $i++) (new ComprobantesPagosController)->createPagos($r["forma_pago_" . $i], $r["valor-" . $i], $comprobante->id);

            //Creación de registro de pagos de anticipos
            if ($totalAnticipos > 0) {
                $forma_anticipo = forma_pagos::where('token', 6004)->first();
                if ($forma_anticipo != null && isset($forma_anticipo->id)) (new ComprobantesPagosController)->createPagos($forma_anticipo->id, $totalAnticipos, $comprobante->id);
            }
            //Registro de cuentas con comprobante
            foreach ($p->detalleCobros as $detail) (new RegistroController)->createRegistro($detail->origen_id, $comprobante->id, $detail->origen);

            //Completado de cobro
            $p->estado = false;
            $p->facturado = true;
            $p->comprobantes_id = $comprobante->id;
            $p->save();

            anticipos_cobros::where('cobros_id', $p->id)->update(['aplicado' => true, 'turnos_id' => session('turno')->id]);

            if (isset($r->regulada) && intval($r->regulada) == 1) (new OperacionesReguladasController)->store($comprobante);

            //Crear el DTE
            $dteBase = new dteBase($comprobante->id);
            $dteBase->setComprobanteToDte();

            return redirect()->route(
                'comprobantes.resultado',
                [
                    'id' => Crypt::encryptString($comprobante->id),
                    'recibe' => Crypt::encryptString($r->recibe ?? 0),
                ]
            );
        } catch (\Throwable $th) {
            return redirect()->route('cajas.my')
                ->with('type', "danger")
                ->with('message', "Error:" . $th->getMessage());
        }
    }


    public function resultado(Request $r)
    {
        $comprobante_id = Crypt::decryptString($r->id);
        $comprobante = comprobantes::with(['clientes', 'dte'])->find($comprobante_id);
        $pagos = comprobantes_pagos::with('forma_pagos')->where("comprobantes_id", $comprobante->id)->get();

        $recibe = 0;
        $cambio = 0;
        if (isset($r->recibe) && $r->recibe != null) {
            $recibe = Crypt::decryptString($r->recibe);
            $f = forma_pagos::where("token", 6001)->first();
            $efectivo = comprobantes_pagos::where('forma_pagos_id', $f->id)
                ->where('comprobantes_id', $comprobante_id)
                ->select(DB::raw('SUM(monto) as total'))
                ->groupBy('comprobantes_id')
                ->first();
            if ($recibe > 0 && $efectivo != null)
                $cambio = round($recibe - $efectivo->total, 2);
        }

        return view('comprobantes.resultado', [
            'comprobante'   => $comprobante,
            'cambio'        => $cambio,
            'recibe'        => $recibe,
            'pagos'         => $pagos,
        ]);
    }
    private function createComprobante($data, $tipo_comprobante, $procedencia = null, $banco = null, $observaciones = null)
    {

        $tipoComprobante = tipo_comprobantes::where('token', $tipo_comprobante)->first();
        $correlativo = (new CorrelativosController)->getCorrelativoByToken($tipoComprobante->token, session('caja')->id);

        //Errores de clientes
        if ($data->cliente != null && !$data->cliente->estado)
            throw new \ErrorException("El cliente no es valido, esta inactivo");

        if ($data->cliente != null && !$data->cliente->tipo_cliente && $data->cliente->detalle == null)
            throw new \ErrorException("El cliente no es valido, no cumple todos los requisitos.");
        //Crear comprobantes
        $c = new comprobantes;
        $c->fecha = date("Y-m-d");
        $c->titular = $data->titular;
        $c->correlativo = $correlativo->actual + 1;
        $c->iva = $data->totales->iva;
        $c->cesc = $data->totales->cesc;
        $c->advalorem = $data->totales->advalorem;
        $c->neto = $data->totales->neto;
        $c->gravado = $data->totales->gravado;
        $c->exento = $data->totales->exento;
        $c->propina = $data->totales->propina;
        $c->total = $data->totales->total;
        $c->percepcion = $data->totales->percepcion;
        $c->clientes_id = $data->cliente ? $data->cliente->id : null;
        $c->users_id = Auth::user()->id;
        $c->turnos_id = session('turno')->id;
        $c->tipo_comprobantes_id = $tipoComprobante->id;
        $c->sucursales_id = session('caja')->sucursales_id;
        $c->procedencia = $procedencia;
        $c->banco = $banco;
        $c->descripcion = $observaciones;

        if (!$c->save())
            throw new \ErrorException("No se pudo crear el comprobante.");

        (new CorrelativosController)->setCorrelativo($correlativo->id);

        return $c;
    }

    public function clientes(Request $r)
    {
        $cliente = null;
        if (isset($r->clientes_id) && $r->clientes_id > 0) {
            $cliente = clientes::find($r->clientes_id);

            if (!$cliente->estado)
                return redirect()->back()->with('type', 'danger')->with('message', 'No se puede guardar este cliente, esta desactivado, intente de nuevo con otro cliente o active el que selecciono.');
        }
        $cobro = cobros::findOrFail(Crypt::decryptString($r->cobro));
        if ($cliente != null && $cliente->id > 0) {
            $cobro->clientes_id = $cliente->id;
            foreach ($cobro->detalleCobros as $c) {
                $p = null;
                switch ($c->origen) {
                    case 1: //Ordenes
                        $p = ordenes::findOrFail($c->origen_id);
                        break;
                    case 2:
                        $p = recepciones::findOrFail($c->origen_id);
                        break;
                    case 3:
                        $p = comandas::findOrFail($c->origen_id);
                        break;
                }
                if ($p != null) {
                    $p->clientes_id = $cliente->id;
                    $p->save();
                }
            }
        }

        if (isset($r->titular) && $r->titular != null)
            $cobro->titular = $r->titular;
        $cobro->save();
        return redirect()->back()->with('message', 'Se realizaron los cambios correctamente');
    }

    public function descuento(Request $r)
    {

        $cobro = cobros::find(Crypt::decryptString($r->id));
        foreach ($cobro->detalleCobros as $detalle)
            switch ($detalle->origen) {
                case 1: //Ordenes
                    (new DetalleOrdenesController)->setDescuento($detalle->origen_id, $r->descuentos_id);
                    break;
                case 2: //Recepciones
                    $p = recepciones::find($detalle->origen_id);
                    $p->descuentos_id = $r->descuentos_id;
                    $p->save();
                    break;
                case 3: //Comandas
                    (new ComandaDetallesController)->setDescuento($detalle->origen_id, $r->descuentos_id);
                    break;
                default:
                    # code...
                    break;
            }

        return redirect()->back()->with('message', "Se aplicaron los descuentos en los precios que lo permiten.");
    }
    public function reenvioDte(Request $r)
    {
        try {
            $dteBase = new dteBase(Crypt::decryptString($r->id));
            $response = $dteBase->setComprobanteToDte();
            return redirect()->back()->with('message', $response);
        } catch (\Throwable $th) {
            return redirect()->back()->with('type', 'danger')
                ->with('message', 'Ocurrió un erro:' . $th->getMessage());
        }
    }

    public function reenviarMail(Request $r)
    {
        try {
            $this->sendDte(Crypt::decryptString($r->id));
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('type', 'danger')
                ->with('message', 'Ocurrio un erro:' . $th->getMessage());
        }
    }
    public function sendMail(Request $r)
    {
        try {
            $id = Crypt::decryptString($r->id);
            $correo = trim($r->correo);
            if (
                $correo == null ||
                $correo == '' ||
                strlen($correo) <= 5 ||
                !filter_var($correo, FILTER_VALIDATE_EMAIL)
            )
                throw new Exception("Correo no valido, agregue un correo en formato valido Ej. cliente@empresa.com.");
            $this->sendDte($id, $correo);
            return redirect()->back()->with('message', 'Se agrego el correo a la cola de envíos, puede tardar de 1min ~ 5min');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('type', 'danger')
                ->with('message', 'Error: ' . $th->getMessage());
        }
    }

    public function sendDte($id, $correo = null)
    {
        try {

            $dte = dtes::with('comprobante')->find($id);
            if (
                $correo != null &&
                trim($correo) != '' &&
                strlen(trim($correo)) > 5 &&
                filter_var(trim($correo), FILTER_VALIDATE_EMAIL)
            )
                Mail::to(strtolower(trim($correo)))->queue(new DteMail($dte));
            else if (
                $dte->comprobante != null
                && $dte->comprobante->clientes_id > 0
                && $dte->comprobante->clientes->email != null
                && trim($dte->comprobante->clientes->email) != ''
                && strlen(trim($dte->comprobante->clientes->email)) > 5
                && filter_var(trim($dte->comprobante->clientes->email), FILTER_VALIDATE_EMAIL)
            )

                Mail::to(strtolower(trim($dte->comprobante->clientes->email)))->queue(new DteMail($dte));

            elseif (
                $dte->sujeto != null
                && $dte->sujeto->clientes_id > 0
                && $dte->sujeto->clientes->email != null
                && trim($dte->sujeto->clientes->email) != ''
                && strlen(trim($dte->sujeto->clientes->email)) > 5
                && filter_var(trim($dte->sujeto->clientes->email), FILTER_VALIDATE_EMAIL)
            )
                Mail::to(strtolower(trim($dte->sujeto->clientes->email)))->queue(new DteMail($dte));
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function pdfDte(Request $r)
    {
        $dte = dtes::with('comprobante')->find(Crypt::decryptString($r->id));
        return (new dtesFiles($dte))->getStream();
    }



    public function createNotaCredito(Request $r)
    {
        $dte = dtes::findOrFail(Crypt::decryptString($r->id));
        $correlativo = (new CorrelativosController)->getCorrelativoByToken(7003, session('caja')->id);
        return view('comprobantes.create_nota_credito', ['dte' => $dte, 'correlativo' => $correlativo]);
    }

    public function storeNotaCredito(StoreNotaCreditoRequest $r)
    {
        $dtes_id = Crypt::decryptString($r->dtes_id);
        $observaciones = $r->observaciones;


        $dte = dtes::findOrFail($dtes_id);
        try {
            $a = anulacion_comprobantes::where("comprobantes_id", $dte->comprobantes_id)->first();
            if ($a->aceptado)
                throw new Exception('Este proceso ya fue realizado, revise los últimos DTEs generado.');
            $comprobante = comprobantes::find($dte->comprobantes_id);
            $c = $this->createFromComprobanteNC($comprobante, 7003, $observaciones);

            foreach ($comprobante->detalles as $d) (new DetalleComprobantesController)->createDetalle($d, $c->id, true);

            $dteBase = new dteBase($c->id, $dte);
            $dteBase->setComprobanteToDte();

            anulacion_comprobantes::where("comprobantes_id", $comprobante->id)->update(['aceptado' => true]);

            return redirect()->route('comprobantes.result', ['id' => $c->cid]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Error: ' . $th->getMessage());
        }
    }

    public function createFromComprobanteNC(comprobantes $comprobante, $token, $observaciones): comprobantes
    {
        $tipoComprobante = tipo_comprobantes::where('token', $token)->first();
        $correlativo = (new CorrelativosController)->getCorrelativoByToken($tipoComprobante->token, session('caja')->id);

        //Errores de clientes
        if ($comprobante->clientes == null || !$comprobante->clientes->estado)
            throw new \ErrorException("El cliente no es valido, esta inactivo");

        if ($comprobante->clientes == null || $comprobante->clientes->tipo_cliente || $comprobante->clientes->detalle == null)
            throw new \ErrorException("El cliente no es valido, no cumple todos los requisitos para emitir el comprobante.");


        //Crear comprobantes
        $c = new comprobantes;
        $c->fecha       = date("Y-m-d");
        $c->titular     = $comprobante->titular;
        $c->correlativo = $correlativo->actual + 1;
        $c->iva         = $comprobante->iva;
        $c->cesc        = 0;
        $c->advalorem   = 0;
        $c->neto        = $comprobante->neto;
        $c->gravado     = $comprobante->gravado;
        $c->exento      = $comprobante->exento;
        $c->propina     = 0;
        $c->percepcion  = $comprobante->percepcion;
        $c->total       = ($c->neto + $c->iva + $c->cesc) - $c->percepcion;
        $c->clientes_id = $comprobante->clientes->id;
        $c->users_id    = Auth::user()->id;
        $c->turnos_id   = session('turno')->id;
        $c->tipo_comprobantes_id = $tipoComprobante->id;
        $c->descripcion = $observaciones;
        if (!$c->save())
            throw new \ErrorException("No se pudo crear el comprobante.");

        (new CorrelativosController)->setCorrelativo($correlativo->id);
        return $c;
    }


    public function cambioTurnos(Request $r)
    {

        return view('comprobantes.cambio_turnos', [
            'cajas' => cajas::where('estado', true)->get(),
            'comprobantes' => tipo_comprobantes::all(),

        ]);
    }

    public function cambioTurnosSearch(Request $r)
    {

        try {
            $v = Validator::make($r->all(), [
                'caja' => ['required', 'string', function ($att, $v, $f) {
                    try {
                        Crypt::decryptString($v);
                    } catch (\Throwable $th) {
                        $f('Cajas no es valido, no es posible desencriptar');
                    }
                }],
                'comprobante' => ['required', 'string', function ($att, $v, $f) {
                    try {
                        Crypt::decryptString($v);
                    } catch (\Throwable $th) {
                        $f('Cajas no es valido, no es posible desencriptar');
                    }
                }],
                'fechaTurno' => ['required', 'date'],
                'fechaBusqueda' => ['required', 'date'],
                'correlativo' => ['nullable', 'numeric', 'min:1'],
            ]);
            if ($v->fails())
                throw new Exception('Los campos caja, tipo de comprobante, fecha de cambio de turno, y fecha de búsqueda del comprobante son requeridos, también verifique el formato');

            $caja = Crypt::decryptString($r->caja);
            $comprobante = Crypt::decryptString($r->comprobante);

            $turnos = turnos::where('fecha', $r->fechaTurno)->where('cajas_id', $caja)->with(['opcion'])->get();
            $turnos_id = turnos::where('fecha', $r->fechaBusqueda)->where('cajas_id', $caja)->get();

            $list = comprobantes::whereIn('turnos_id', $turnos_id->pluck('id'));

            if (intval($comprobante) > 0)
                $list = $list->where('tipo_comprobantes_id', $comprobante);

            if (isset($r->correlativo) && intval($r->correlativo) > 0)
                $list = $list->where('correlativo', intval($r->correlativo));

            $list = $list->with(['turnosOpcion', 'tipoComprobantes'])->get();
            return response()->json(['list' => $list, 'turnos' => $turnos, 'turnos_id' => $turnos_id]);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()]);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Error: ' . $th->getMessage()]);
        }
    }

    public function cambioTurnosStore(Request $r)
    {
        try {
            $v = Validator::make($r->all(), [
                'comprobantes_id' => ['required', 'string', function ($att, $v, $f) {
                    try {
                        Crypt::decryptString($v);
                    } catch (\Throwable $th) {
                        $f('Cajas no es valido, no es posible desencriptar');
                    }
                }],
                'turno' => ['required', 'string', function ($att, $v, $f) {
                    try {
                        Crypt::decryptString($v);
                    } catch (\Throwable $th) {
                        $f('Cajas no es valido, no es posible desencriptar');
                    }
                }],
            ]);
            if ($v->fails())
                throw new Exception('Campos requeridos');

            $comprobante = comprobantes::find(Crypt::decryptString($r->comprobantes_id));
            $turno = turnos::find(Crypt::decryptString($r->turno));
            if ($comprobante == null)
                throw new Exception('Error al encontrar comprobante');
            if ($turno == null)
                throw new Exception('Error al encontrar turnos');

            $comprobante->turnos_id = $turno->id;
            $comprobante->save();

            return response()->json(['status' => true]);
        } catch (ValidationException $e) {
            return response()->json(['status' => false, 'message' => 'Error: ' . $e->getMessage()]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Error: ' . $th->getMessage()]);
        }
    }


    public function aperturaTurnos(Request $r)
    {

        return view('comprobantes.apertura_turno', [
            'cajas' => cajas::where('estado', true)->get(),
        ]);
    }

    public function aperturaTurnosSearch(Request $r)
    {

        try {
            $v = Validator::make($r->all(), [
                'caja' => ['required', 'string', function ($att, $v, $f) {
                    try {
                        Crypt::decryptString($v);
                    } catch (\Throwable $th) {
                        $f('Cajas no es valido, no es posible desencriptar');
                    }
                }],
                'fecha' => ['required', 'date'],
            ]);
            if ($v->fails())
                throw new Exception('Los campos caja, y fecha son requeridos, también verifique el formato');

            $caja = Crypt::decryptString($r->caja);

            $turnos = turnos::where('estado', false)->where('fecha', $r->fecha)->where('cajas_id', $caja)->with([
                'opcion',
                'uapertura',
                'ucierre'
            ])->get();

            return response()->json(['list' => $turnos,]);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()]);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Error: ' . $th->getMessage()]);
        }
    }

    public function aperturaTurnosStore(Request $r)
    {
        try {
            $v = Validator::make($r->all(), [
                'turno' => ['required', 'string', function ($att, $v, $f) {
                    try {
                        Crypt::decryptString($v);
                    } catch (\Throwable $th) {
                        $f('Cajas no es valido, no es posible desencriptar');
                    }
                }],
                'confirm' => ['required', "accepted"],
            ]);
            if ($v->fails())
                throw new Exception('Campos requeridos');

            $turno = turnos::find(Crypt::decryptString($r->turno));
            if ($turno == null)
                throw new Exception('Error al encontrar turnos');

            if (turnos::where('estado', true)->where('cajas_id', $turno->cajas_id)->count() > 0)
                throw new Exception('Ya hay un turno abierto, no puede haber mas de un turno abierto. Antes debe cerrar el turno');

            if ($turno->estado)
                throw new Exception('El turno aun esta activo');

            $turno->estado = true;
            $turno->cierre_users_id = null;
            $turno->cierre = null;
            $turno->save();

            return redirect()->back()->with('message', 'Se apertura el turno nuevamente');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->with('type', 'danger')
                ->with('message', 'Error: ' . $e->getMessage());
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('type', 'danger')
                ->with('message', 'Error: ' . $th->getMessage());
        }
    }

    public function comprobantesFormaPago(Request $r)
    {
        $r->validate([
            'pago_id' => ['required', 'string'],
            'forma_pagos_id' => ['required', 'string'],
            'confirm' => ['required', 'accepted'],
        ]);
        try {
            $pago_id = Crypt::decryptString($r->pago_id);
            $forma_pagos_id = Crypt::decryptString($r->forma_pagos_id);

            $c = comprobantes_pagos::find($pago_id);
            $c->forma_pagos_id = $forma_pagos_id;
            $c->save();
            return redirect()->back()->with('message', 'Se cambio la forma de pago');
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Error: ' . $th->getMessage());
        }
    }

    public function aplicarAnticipo(Request $r)
    {
        $r->validate([
            'cobros_id' => ['required', 'string'],
            'anticipo' => ['required', 'integer', 'min:1'],
            'monto' => ['required', 'numeric', 'min:0.01'],
            'confrimAnticipo' => ['required', 'accepted'],
        ]);
        try {
            $a = anticipos::find($r->anticipo);
            $c = cobros::find(Crypt::decryptString($r->cobros_id));
            $m = floatval(round($r->monto, 2));
            if ($a == null || !$a->estado || $a->monto == 0)
                throw new Exception('El anticipo agregado no se encontro, o ya se utilizo.');
            if (floatval($a->monto) < $m)
                throw new Exception('El monto que quiere agregar es superior al monto del anticipo. Anticipo: $' . $a->monto);

            if ($c == null)
                throw new Exception('No se encontró el cobro');

            $forma = forma_pagos::where('token', 6004)->first();
            $comprobante = comprobantes::find($c->comprobantes_id);
            $totalAnticipos = comprobantes_pagos::where('forma_pagos_id', $forma->id)->where('comprobantes_id', $comprobante->id)->first();

            if ($totalAnticipos == null)
                throw new Exception('El comprobante no tiene agregado ningún pago en anticipos');

            $montoAnticipos = $c->anticipos->sum("monto");
            $falta = floatval(round($totalAnticipos->monto -  $montoAnticipos, 2));
            if ($m > $falta)
                throw new Exception('El monto que quiere agregar es mayor al pago en anticipos, monto faltante: $' . $falta);

            $cp = new anticipos_cobros;
            $cp->monto = $m;
            $cp->aplicado = true;
            $cp->cobros_id = $c->id;
            $cp->anticipos_id = $a->id;
            $cp->turnos_id = $comprobante->turnos_id;
            $cp->save();
            (new AnticiposController)->aplicar($a->id, $cp->monto);
            return redirect()->back()->with('message', 'Se aplico el anticipo Nº ' . $a->id . ' monto aplicado: $' . $cp->monto);
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Error: ' . $th->getMessage())->with('type', 'danger');
        }
    }

    public function desaplicarAnticipo(Request $r)
    {
        try {
            $id = Crypt::decryptString($r->id);
            $ac = anticipos_cobros::find($id);
            if ($ac == null)
                throw new Exception('No se encontró el pago con ID: ' . $id);

            $a = (new AnticiposController)->devolucion($ac->anticipos_id, $ac->monto);
            $ms = $a != null ? 'Se devolvió el monto $' . $ac->monto . ' al anticipo Nº ' . $a->id : 'ERROR no se devolvió el monto $' . $ac->monto . ' al anticipo Nº ' . $ac->ancitipos_id . ' debe hacerse de forma manual';
            $type = $a != null ? 'success' : 'danger';

            $ac->delete();
            return redirect()->back()->with('message', $ms)->with('type', $type);
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('message', 'Error: ' . $th->getMessage())->with('type', 'danger');
        }
    }

    public function updateRegistro(Request $r)
    {
        try {
            $comprobante = comprobantes::find(Crypt::decryptString($r->comprobante));

            foreach ($comprobante->detalles as $d) {
                if (isset($r['registro_' . $d->id])) {
                    $idr = $r['registro_' . $d->id];
                    $rg = registro::find($idr);
                    $dc = detalle_comprobantes::find($d->id);
                    $dc->registro = $rg->registro;
                    $dc->tipo_registros = $rg->tipo_registros;
                    $dc->save();
                }
            }
            return redirect()->back()->with('message', 'Se guardaron los cambios');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function clearRegistro(Request $r)
    {
        try {
            $dc = detalle_comprobantes::find(Crypt::decryptString($r->id));
            $dc->registro = null;
            $dc->tipo_registros = null;
            $dc->save();

            return redirect()->back()->with('message', 'Se guardaron los cambios');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function activarComprobante(Request $r)
    {
        $r->validate(
            [
                'id' => ['required', 'string'],
                'confirm' => ['required', 'accepted'],
            ]
        );
        try {
            $c = comprobantes::find(Crypt::decryptString($r->id));
            $c->estado = true;
            $c->save();

            return redirect()->back()->with('message', 'Se guardaron los cambios');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
