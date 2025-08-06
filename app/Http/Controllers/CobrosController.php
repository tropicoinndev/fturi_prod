<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorecobrosRequest;
use App\Http\Requests\UpdatecobrosRequest;
use App\Models\anticipo_reservacion;
use App\Models\anticipos;
use App\Models\anticipos_cobros;
use App\Models\clientes;
use App\Models\cobros;
use App\Models\comandas;
use App\Models\detalle_cobros;
use App\Models\evento_cuentas;
use App\Models\ordenes;
use App\Models\recepciones;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class CobrosController extends Controller
{
    private $table = 'cobros';

    public function __construct()
    {
        $this->getTh($this->table, 'Cobros');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('cobros.index', [
            'p' => cobros::where('estado', true)
                ->where('facturado', false)
                ->where('cajas_id', session('caja')->id)
                ->get(),
        ]);
    }
    public function search(Request $r)
    {
        return view('cobros.index', [
            'p' => cobros::where('estado', true)
                ->where('facturado', false)
                ->where('cajas_id', session('caja')->id)
                ->where(function ($q) use ($r) {
                    $q->where(DB::raw('UPPER(titular)'), 'ilike', '%' . strtoupper($r->buscar) . '%')->orWhereIn('clientes_id', function ($sq) use ($r) {
                        $sq->from('clientes')
                            ->select('id')
                            ->where('nombre', 'ilike', '%' . strtoupper($r->buscar) . '%');
                    });
                })
                ->get(),
            'buscar' => $r->buscar,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $r)
    {
        if (!isset($r->id)) {
            $origen = Crypt::decryptString($r->origen);
            $origen_id = Crypt::decryptString($r->origen_id);
            $tipo_comprobante = Crypt::decryptString($r->tipo_comprobante);
            if ($origen <= 0 || $origen_id <= 0 || $tipo_comprobante <= 0) {
                return redirect()->back()->with('message', 'Faltan datos para realizar el cobro.')->with('type', 'danger');
            }

            $dc = $this->existeCobro($origen, $origen_id);

            if (!isset($dc->id)) {
                $data = $this->getCobro($origen, $origen_id, $tipo_comprobante);
            } else {
                $data = cobros::find($dc->cobros_id);
            }
        } else {
            $data = cobros::find(Crypt::decryptString($r->id));
        }

        return view('cobros.create', ['p' => $data]);
    }

    public function createHab(Request $r)
    {
        $origen = Crypt::decryptString($r->origen);
        $tipo_comprobante = Crypt::decryptString($r->tipo_comprobante);
        $origenes = $r->origen_id;
        $cobro = null;
        $origen_id = null;
        if ($tipo_comprobante <= 0) {
            return redirect()->back()->with('message', 'Faltan datos para realizar el cobro.')->with('type', 'danger');
        }
        if ($origenes == null || count($origenes) <= 0) {
            return redirect()->back()->with('type', 'danger')->with('message', 'Debe seleccionar una o mas cuentas para agregar al cobro.');
        }
        foreach ($origenes as $o) {
            $origen_id = Crypt::decryptString($o);
            $dc = $this->existeCobro($origen, $origen_id);
            if (isset($dc->cobros_id)) {
                $cobro = $dc->cobros_id;
                break;
            }
        }
        if ($cobro == null) {
            $data = $this->getCobro($origen, $origen_id, $tipo_comprobante);
        } else {
            $data = cobros::find($cobro);
        }
        foreach ($origenes as $n) {
            if (Crypt::decryptString($n) != $origen_id) {
                $this->createDetalle($origen, Crypt::decryptString($n), $data->id);
            }
        }

        return view('cobros.create', ['p' => $data]);
    }
    private function existeCobro($origen, $origen_id)
    {
        return detalle_cobros::where('origen', $origen)->where('origen_id', $origen_id)->where('estado', true)->first();
    }
    private function getCobro($origen, $origen_id, $tipo_comprobante)
    {
        try {
            $p = null;
            $cl = null;
            switch ($origen) {
                case 1:
                    $p = ordenes::find($origen_id);
                    break;
                case 2:
                    $p = recepciones::find($origen_id);
                    break;
                case 3:
                    $p = comandas::find($origen_id);
                    break;
            }
            if ($p->clientes_id > 0) {
                $cl = clientes::find($p->clientes_id);
            }
            if ($tipo_comprobante == 7001) {
                $tipo_comprobante = $p->clientes_id > 0 && !$cl->tipo_cliente ? 7001 : 7002;
            }

            return $this->createOrigen($origen, $origen_id, $tipo_comprobante, $cl != null ? $cl->id : null, $p->titular ?? null);
        } catch (\Throwable $th) {
            return throw $th;
        }
    }

    private function createOrigen($origen, $origen_id, $comprobante, $cliente_id, $titular = null)
    {
        try {
            $p = $this->createCobro($comprobante, $cliente_id, session('caja')->id, $titular);
            $this->createDetalle($origen, $origen_id, $p->id);

            return $p;
        } catch (\Throwable $th) {
            return throw $th;
        }
    }
    public function createCobro($comprobante, $cliente_id, $caja, $titular = null)
    {
        try {
            $p = new cobros();
            $p->titular = $titular;
            $p->fecha = date('Y-m-d');
            $p->clientes_id = $cliente_id;
            $p->tipo_comprobante = $comprobante;
            $p->users_id = Auth::user()->id;
            $p->cajas_id = $caja;
            $p->save();
            return $p;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function createDetalle($origen, $origen_id, $cobro)
    {
        if (detalle_cobros::where('origen', $origen)->where('origen_id', $origen_id)->where('estado', true)->count() == 0) {
            $dc = new detalle_cobros();
            $dc->origen = $origen;
            $dc->origen_id = $origen_id;
            $dc->cobros_id = $cobro;
            $dc->save();

            $ar = anticipo_reservacion::where('tipo_reservacion', $dc->origen)
                ->where('reservacion_id', $dc->origen_id)
                ->get();

            if ($ar->count() > 0) {
                foreach ($ar as $r) {
                    $a = anticipos::find($r->anticipos_id);
                    if ($a->estado) {
                        $ac = new anticipos_cobros();
                        $ac->monto = $a->monto;
                        $ac->anticipos_id = $a->id;
                        $ac->cobros_id = $cobro;
                        $ac->save();

                        $a->monto -= $ac->monto;
                        $a->estado = false;
                        $a->save();
                    }
                    anticipo_reservacion::destroy($r->id);
                }
            }
        }
    }
    /* public function isValidOrigen($origen, $origen_id)
    {
        switch ($origen) {
            case 1:
                $p = ordenes::find($origen_id);

                if (!isset($p->id)) return false;

                return $p->estado && !$p->comprobante;
                break;
            case 2:
                $p = recepciones::find($origen_id);

                if (!isset($p->id)) return false;

                return $p->estado && !$p->facturada && !$p->eliminado;
                break;
            default:
                return false;
                break;
        }
    }*/
    private function desencriptarAnticipos($anticipos)
    {
        return collect($anticipos)->map(function ($id) {
            return Crypt::decryptString($id);
        });
    }

    //***funcion para asignar anticipos que posee un evento y asiganrlo solo alas cuentas de eventos */
    public function asignar($cobro, $evento, $anticipos, $monto)
    {
        try {
            // Desencriptar los IDs de anticipos
            $anticiposID = $this->desencriptarAnticipos($anticipos);

            // Convertir el monto a flotante
            $total = floatval($monto);
            // Obtener los anticipos reservados para el evento y tipo de reservación especificado
            $anticiposReservado = anticipo_reservacion::where('tipo_reservacion', 4)
                ->where('reservacion_id', $evento)
                 ->whereIn('anticipos_id', $anticiposID)
                ->get();


            // Validar si se encontraron anticipos reservados
            if ($anticiposReservado->isEmpty()) {
                return redirect()->back()->with('message', 'No se encontraron anticipos para estas cuentas.')->with('type', 'danger');
            }

            // Asignar anticipos
            foreach ($anticiposReservado as $reservacion) {
                $anticipo = anticipos::find($reservacion->anticipos_id);

                // Validar si el anticipo existe y está activo
                if ($anticipo && $anticipo->estado) {
                    // Determinar el monto a asignar
                    $disponible = min($anticipo->monto, $total);
                    $montoAsignar = $disponible;

                    // Crear el registro de anticipo cobrado
                    $anticipoCobro = new anticipos_cobros();
                    $anticipoCobro->monto = $montoAsignar;
                    $anticipoCobro->anticipos_id = $anticipo->id;
                    $anticipoCobro->cobros_id = $cobro;
                    $anticipoCobro->turnos_id = session('turno')->id;
                    $anticipoCobro->save();

                    // Actualizar el monto del anticipo
                    $anticipo->monto -= $montoAsignar;
                    if ($anticipo->monto <= 0) {
                        $anticipo->estado = false;
                    }
                    $anticipo->save();

                    // Eliminar la reservación de anticipo
                    $reservacion->delete();

                    // Reducir el monto total
                    $total -= $montoAsignar;

                    // Salir del bucle si se ha asignado todo el monto
                    if ($total <= 0) {
                        break;
                    }
                }
            }

            return $anticipoCobro;
        } catch (\Throwable $th) {
            // Manejo de excepción
            return redirect()->back()->with('message', 'Ocurrió un error: ' . $th->getMessage())->with('type', 'danger');
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorecobrosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $r)
    {

        $p = cobros::findOrFail(Crypt::decryptString($r->cobro));
        try {
            if (isset($r->ordenes)) {
                foreach ($r->ordenes as $o) {
                    $this->createDetalle(1, Crypt::decryptString($o), $p->id);
                };
            }
            if (isset($r->estadias)) {
                foreach ($r->estadias as $e) {
                    $this->createDetalle(2, Crypt::decryptString($e), $p->id);
                };
            }
            if (isset($r->comandas)) {
                foreach ($r->comandas as $c) {
                    $this->createDetalle(3, Crypt::decryptString($c), $p->id);
                };
            }

            if ($r->opcion == 1) {
                return redirect()->back();
            } elseif ($r->opcion == 2) {


                return redirect()->route('comprobantes.cobro', ['id' => Crypt::encryptString($p->id)]);
            }
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrio un problema al agregar, error: ' . $th->getMessage());
        }
    }

    public function anticipos(Request $r)
    {
        try {
            $cobro = cobros::findOrFail(Crypt::decryptString($r->cobro));
            $anticipo = anticipos::findOrFail(Crypt::decryptString($r->anticipo));
            $monto = (float) $r->monto;

            if ($anticipo->anulado) {
                return response()->json(['message' => 'Este anticipo fue anulado', 'type' => 'danger']);
            }
            if (!$anticipo->estado || $anticipo->anulado) {
                return response()->json(['message' => 'Este anticipo fue aplicado', 'type' => 'danger']);
            }
            if ($anticipo->monto < $monto) {
                return response()->json(['message' => 'El monto del anticipo es menor al monto que agrega. O ya fue aplicado', 'type' => 'danger']);
            }
            if ($anticipo->fecha_aplicacion > date('Y-m-d')) {
                return response()->json(['message' => 'El anticipo aun no puede aplicarse, debe aplicarse el dia ' . $anticipo->fecha_aplicacion . ' o porterior a esta fecha.', 'type' => 'danger']);
            }
            if (!$cobro->estado) {
                return response()->json(['message' => 'El cobro ya no esta disponible.', 'type' => 'danger']);
            }
            if ($cobro->facturado) {
                return response()->json(['message' => 'El cobro ya ha sido facturado.', 'type' => 'danger']);
            }

            $p = new anticipos_cobros();
            $p->monto = $monto;
            $p->cobros_id = $cobro->id;
            $p->anticipos_id = $anticipo->id;
            $p->save();
            $anticipo->monto -= $p->monto;
            if ($anticipo->monto == 0) {
                $anticipo->estado = false;
            }
            $anticipo->save();

            return response()->json(['message' => 'Se agrego el anticipo a este cobro', 'anticipos' => $cobro->anticipos, 'activos' => $cobro->clientes->anticipos]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Ocurrio un error al agregar el anticipo, error: ' . $th->getMessage(), 'type' => 'danger']);
        }
    }

    public function anticiposDestroy(Request $r)
    {
        try {
            $p = anticipos_cobros::find(Crypt::decryptString($r->id));
            if ($p->aplicado) {
                return redirect()->route('cajas.my')->with('message', 'Esta intentando borrar un anticipo aplicado, el comprobante que intenta modificar ya fue completado')->with('type', 'danger');
            }
            $cobro = cobros::find($p->cobros_id);
            (new AnticiposController())->devolucion($p->anticipos_id, $p->monto);
            $p->delete();
            return response()->json(['message' => 'Se agrego el anticipo a este cobro', 'anticipos' => $cobro->anticipos, 'activos' => $cobro->clientes->anticipos]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Ocurrio un error al agregar el anticipo, error: ' . $th->getMessage(), 'type' => 'danger']);
        }
    }
    public function anticiposReservaDestroy(Request $r)
    {
        try {
            $cobro = cobros::find(Crypt::decryptString($r->cobro));
            //Devolución de monto
            anticipo_reservacion::where('anticipos_id', Crypt::decryptString($r->id))->delete();
            return response()->json([
                'message' => 'Se elimino la reserva del anticipo, ya puede ser aplicado.',
                'anticipos' => $cobro->anticipos,
                'activos' => $cobro->clientes->anticipos,
                'reserva' => $cobro->clientes->anticipos_reservados,
            ]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Ocurrio un error al agregar el anticipo, error: ' . $th->getMessage(), 'type' => 'danger']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\cobros  $cobros
     * @return \Illuminate\Http\Response
     */
    public function comprobante(Request $r)
    {
        try {
            $p = cobros::find(Crypt::decryptString($r->id));
            $tipo = Crypt::decryptString($r->tipo);

            if ($tipo == 7001)
                if ($p->detalleCobros->where('origen', 2)->count() > 0 && !$p->clientes->tipo_cliente && !$p->clientes->ccf)
                    throw new Exception('No se puede cambiar el tipo de comprobante, porque tiene una recepción y este comprobante no permite CCF');


            $p->tipo_comprobante = $tipo;
            $p->save();
            return redirect()->back()->with('message', 'Se cambio el tipo de comprobante de este cobro.');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('type', 'danger')
                ->with('message', 'Ocurrio un error al cambiar el tipo de comprobante. Error: ' . $th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\cobros  $cobros
     * @return \Illuminate\Http\Response
     */
    public function edit(cobros $cobros)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatecobrosRequest  $request
     * @param  \App\Models\cobros  $cobros
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatecobrosRequest $request, cobros $cobros)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\cobros  $cobros
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            $p = cobros::find(Crypt::decryptString($r->id));

            foreach ($p->anticipos as $ac) {
                (new AnticiposController())->devolucion($ac->anticipos_id, $ac->monto);
            }

            detalle_cobros::where('cobros_id', $p->id)->delete();
            anticipos_cobros::where('cobros_id', $p->id)->delete();
            $p->delete();
            return redirect()->route('cobros.index')->with('message', 'Se elimino el registro.');
        } catch (\Throwable $th) {
            return redirect()
                ->route('cobros.index')
                ->with('type', 'danger')
                ->with('message', 'Ocurrio un error al eliminar. Eliminar:' . $th->getMessage());
        }
    }
    public function confirm(Request $r)
    {
        return view('confirm', [
            'th' => $this->th['confirm'],
            'p' => cobros::findOrFail(Crypt::decryptString($r->id)),
        ]);
    }
}
