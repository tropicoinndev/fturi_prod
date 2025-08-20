<?php

namespace App\Http\Controllers;

use App\Events\CajasEvent;
use App\Exports\VentasRubrosAdvalorem;
use App\Http\Requests\ReportesPanelRequest;
use App\Http\Requests\StorecajasRequest;
use App\Http\Requests\UpdatecajasRequest;
use App\Models\abonos;
use App\Models\anticipos;
use App\Models\anulacion_comprobantes;
use App\Models\anulaciones_detalle_comanda;
use App\Models\bodega_cajas;
use App\Models\bodegas;
use App\Models\caja_precios;

use App\Models\caja_turnos;
use App\Models\cajas;
use App\Models\cajas_comprobantes;
use App\Models\cajas_users;
use App\Models\categorias_precios;
use App\Models\comanda_detalles;
use App\Models\comanda_existencias;
use App\Models\comandas;
use App\Models\comprobantes;
use App\Models\detalle_comprobantes;
use App\Models\dtes;
use App\Models\Enums\user_token;
use App\Models\forma_pagos;
use App\Models\opcion_turnos;
use App\Models\ordenes;
use App\Models\precios;
use App\Models\recepcion_salidas;
#Agregar
use App\Models\recepciones;
use App\Models\registro;
use App\Models\rubro;
use App\Models\sucursales;
use App\Models\tipo_comprobantes;
use App\Models\turnos;
use App\Models\User;
use App\Models\vventas_rubros;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDF;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class CajasController extends Controller
{
    private $table = 'cajas';

    public function __construct()
    {
        $this->getTh($this->table, 'Cajas');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->table . '.index', [
            'th' => $this->th['index'],
            'p' => cajas::orderBy('id', 'DESC')->paginate(15),
            'cajas' => cajas_users::where('users_id', Auth::user()->id)->get(),
            'table' => $this->table,
            'data' => [
                'sucursales' => sucursales::orderBy('id', 'DESC')->get()
            ],
        ]);
    }

    #MOVIL
    public function logoutCajasApp(Request $r)
    {
        if (session('caja') != null)
            session()->forget('caja');
        if (session('turno') != null)
            session()->forget('turno');

        return redirect()->route('cajas.login.app');
    }

    public function loginCajasApp()
    {
        return view('app.cajas_login', [
            'p' => cajas::orderBy('id', 'DESC')->paginate(15),
            'cajas' => cajas_users::with('cajas')->where('users_id', Auth::user()->id)->get(),
        ]);
    }
    #---

    public function menu()
    {
        return view('cajas.menu');
    }
    /***api listar precios disponibles */
    public function list_precios(Request $r)
    {
        $caja = Crypt::decryptString($r->caja);
        return response()->json(['precios' => $this->getPrecioCaja($caja)]);
    }
    /**funcion que trae cajaPrecio */
    private function getPrecioCaja($caja)
    {
        return caja_precios::where('cajas_id', '=', $caja)->with('precios')->get();
    }
    /** buscar precio */
    public function apiSearchPrecio(Request $r)
    {
        return response()->json(
            [
                'listp' => $this->getPrecioCaja(Crypt::decryptString($r->id))
            ]
        );
    }
    /**esta funcion es donde se agrega la precio a caja  */
    public function store_apiPrecio(Request $r)
    {

        $messege = "";
        $type = true;
        try {
            $caja = Crypt::decryptString($r->caja);
            $type = $this->setPrecioCajas($caja, $r->precio);
            $messege = $type ? "Precio agregado a esta caja" : "Error al guardar";
        } catch (\Throwable $th) {
            $messege = "Error: accion no realizada, actualice e intente de nuevo";
            $type = false;
        }
        return response()->json(
            [
                "message"       => $messege,
                "type"          => $type ? 'success' : 'danger',
                "precios"    => $this->getPrecioCaja($caja)
            ]
        );
    }
    /**esta funcion guarda la caja creada para el precio */
    private function setPrecioCajas($caja, $precio)
    {
        /**pendiente terminar asignar precios a caja */
        try {
            $inCajaPrecio = caja_precios::where('cajas_id', $caja)->where('precios_id', $precio)->count();

            if ($inCajaPrecio >= 1) {
                caja_precios::where('cajas_id', $caja)->where('precios_id', $precio)->delete();
            } else {
                $p = new caja_precios;
                $p->precios_id = $precio;
                $p->cajas_id = $caja;

                $p->save();
            }
            return true;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    /**listar cajas */
    public function index_api()
    {
        return response()->json(['list' => $this->getList()]);
    }

    private function getList()
    {
        return cajas::orderBy('caja', 'ASC')->get();
    }

    /***api list users */
    public function list_usuarios(Request $r)
    {
        $caja = Crypt::decryptString($r->caja);
        return response()->json(['users' => $this->getUsuarioCaja($caja)]);
    }

    public function apiSearchUsuario(Request $r)
    {
        return response()->json(
            [
                'list' => $this->getUsuarioCaja(Crypt::decryptString($r->id))
            ]
        );
    }
    /***api list opcion_turnos */
    public function list_opcionTurnos(Request $r)
    {
        $caja = Crypt::decryptString($r->caja);
        return response()->json(['opcion_turnos' => $this->getTurnoCaja($caja)]);
    }

    public function apiSearchTurnos(Request $r)
    {
        return response()->json(
            [
                'listf' => $this->getTurnoCaja(Crypt::decryptString($r->id))
            ]
        );
    }
    /**obtengo los turnos en caja  */
    private function getTurnoCaja($caja)
    {
        return caja_turnos::where('cajas_id', '=', $caja)->with('opcion_turnos')->get();
    }
    /** esta funcion habilita opcion turnos a caja */
    public function store_apiTurnos(Request $r)
    {

        $messege = "";
        $type = true;
        try {
            $caja = Crypt::decryptString($r->caja);
            $type = $this->setTurnoCaja($caja, $r->opcion_turno);
            $messege = $type ? "Opcion turno agregado a esta caja" : "Error al guardar";
        } catch (\Throwable $th) {
            $messege = "Error: accion no realizada, actualice e intente de nuevo";
            $type = false;
        }
        return response()->json(
            [
                "message"       => $messege,
                "type"          => $type ? 'success' : 'danger',
                "opcion_turnos"    => $this->getTurnoCaja($caja)
            ]
        );
    }
    /***aqui secrea la opcion de turno en caja */
    private function setTurnoCaja($caja, $opcion_turno)
    {

        try {
            $inTurno_caja = caja_turnos::where('cajas_id', $caja)->where('opcion_turnos_id', $opcion_turno)->count();

            if ($inTurno_caja >= 1) {
                caja_turnos::where('cajas_id', $caja)->where('opcion_turnos_id', $opcion_turno)->delete();
            } else {
                $p = new caja_turnos;
                $p->cajas_id = $caja;
                $p->opcion_turnos_id = $opcion_turno;
                $p->estado = true;
                $p->save();
            }
            return true;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    /**esta funcion es donde se agrega el usuario a caja  */
    public function store_apiUsuario(Request $r)
    {

        $messege = "";
        $type = true;
        try {
            $caja = Crypt::decryptString($r->caja);
            $type = $this->setUsuarioCaja($caja, $r->user);
            $messege = $type ? "Usuario agregado a esta caja" : "Error al guardar";
        } catch (\Throwable $th) {
            $messege = "Error: accion no realizada, actualice e intente de nuevo";
            $type = false;
        }
        return response()->json(
            [
                "message"       => $messege,
                "type"          => $type ? 'success' : 'danger',
                "users"    => $this->getUsuarioCaja($caja)
            ]
        );
    }
    private function getUsuarioCaja($caja)
    {
        return cajas_users::where('cajas_id', '=', $caja)->with('users')->get();
        //return cajas_users::with('cajas')->where('cajas_id', $caja)->get();
    }
    private function setUsuarioCaja($caja, $user)
    {

        try {
            $inCaja = cajas_users::where('cajas_id', $caja)->where('users_id', $user)->count();

            if ($inCaja >= 1) {
                cajas_users::where('cajas_id', $caja)->where('users_id', $user)->delete();
            } else {
                $p = new cajas_users;
                $p->cajas_id = $caja;
                $p->users_id = $user;
                $p->pin = Hash::make('1234');
                $p->save();
            }
            return true;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }



    public function panelCajas()
    {
        return view($this->table . '.panelCajas', [
            'th' => $this->th['index'],
            'p' => cajas::orderBy('id', 'DESC')->paginate(15),
            'table' => $this->table,
            'data' => [
                'sucursales' => sucursales::orderBy('sucursal', 'ASC')->get()
            ],
        ]);
    }

    public function search(Request $request)
    {
        if (isset($request->txtBusqueda)) {
            $p = cajas::where('caja', 'ilike', '%' . $request->txtBusqueda . '%')
                ->paginate();

            return view($this->table . '.index', [
                'th' => $this->th['index'],
                'p' => $p,
                'txtBusqueda' => $request->txtBusqueda,
                'data' => [
                    'sucursales' => sucursales::orderBy('sucursal', 'ASC')->get()
                ],
            ]);
        } else {
            return to_route($this->table . '.index');
        }
    }
    public function apiSearch(Request $r)
    {
        return response()->json([
            'cajas_users' => cajas_users::where('pin', 'like', '%' . $r->txtPin . '%')->get()
        ]);
    }
    public function getCajas()
    {
        return response()->json([
            'cajas_users' => DB::table('cajas_users')->get()->pin
        ]);
    }
    public function apiPin(Request $request)
    { //pendiente refactorizar esta funcion no hace lo que deberia aun  $Pin = $request->txtPin;


        $Pin = $request->txtPin;
        $storedCaja = DB::table('cajas_users')->get()->pin;
        $Caja =  DB::table('cajas')->get()->caja;
        if (($Pin === $storedCaja) && ($Caja == "tropiclub")) {
            return response()->json(['message' => 'pin correcto'], 200);
        } else {
            return response()->json(['message' => 'Pin incorrecto'], 400);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->table . '.create', [
            'th' => $this->th['create'],
            'table' => $this->table,
            'data' => [
                'sucursales' => sucursales::orderBy('sucursal', 'ASC')->get()
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorecajasRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorecajasRequest $r)
    {
        try {
            $p = new cajas;
            $p->caja = $r->caja;
            $p->color_fondo = $r->color_fondo;
            $p->color_texto = $r->color_texto;
            $p->codigo_punto_venta = $r->codigo_punto_venta;
            $p->sucursales_id = $r->sucursales_id;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro guardado correctamente: ' . $p->caja)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\cajas  $cajas
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $caja = cajas::findOrFail(Crypt::decryptString($id));
        return view($this->table . '.show', [
            'th' => $this->th['show'] =  [
                'title'     => $caja->caja,
                'table'     => $this->table,
                'bread'     => $this->table . '.show'
            ],
            'p'             => $caja,
            'bodegas'       => bodegas::all(),
            'table'         => $this->table,
            'cajas'         => cajas::where('id', '<>', $caja->id)->orderBy('caja', 'DESC')->get(),
            'cajas_users'   => cajas_users::where('cajas_id', '=',  $caja->id)->get(),
            'cajaTurnos'    => caja_turnos::where('cajas_id', '=',  $caja->id)->get(),
            'cajaPrecios'   => caja_precios::where('cajas_id', '=',  $caja->id)->get(),
            'bodega_cajas'  => bodega_cajas::where('cajas_id', $caja->id)->with('bodegas')->get(),
            'cajas_comprobantes'  => cajas_comprobantes::where('cajas_id', $caja->id)->with('cajas', 'cajas_origen')->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\cajas  $cajas
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            return view($this->table . '.edit', [
                'th' => $this->th['edit'],
                'p' => cajas::findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,
                'data' => [
                    'sucursales' => sucursales::orderBy('sucursal', 'ASC')->get()
                ],
            ]);
        } catch (\Throwable $th) {
            return to_route($this->table . '.index')
                ->with('message', 'Error al encontrar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatecajasRequest  $request
     * @param  \App\Models\cajas  $cajas
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatecajasRequest $r)
    {
        try {
            $p = cajas::findOrFail($r->id);

            $p->caja = $r->caja;
            $p->color_fondo = $r->color_fondo;
            $p->color_texto = $r->color_texto;
            $p->codigo_punto_venta = $r->codigo_punto_venta;
            $p->ip = $r->ip;
            $p->sucursales_id = $r->sucursales_id;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro actualizado correctamente: ' . $p->caja)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return to_route($this->table . '.index')
                ->with('message', 'Error al actualizar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function confirm($id)
    {
        try {
            return view('confirm', [
                'th' => $this->th['confirm'],
                'p' => cajas::findOrFail(Crypt::decryptString($id))
            ]);
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\cajas  $cajas
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            if (!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table . '.index')
                    ->with('message', 'Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type', 'danger');

            #cajas::destroy(Crypt::decryptString($r->id));
            $p = cajas::findOrFail(Crypt::decryptString($r->id));
            $p->delete();

            return to_route($this->table . '.index')
                ->with('message', 'Registro eliminado con exito: ' . $p->caja);
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

    public function status($id)
    {
        try {
            $p = cajas::findOrFail(Crypt::decryptString($id));
            $p->estado = !$p->estado;
            $p->save();
            if ($p->estado == false)
                broadcast(new CajasEvent($p->id, "Es requerido cerrar la session en la caja, la caja fue deshabilitada.", 2, "/cajas/logout"));
            return redirect()->back()
                ->with('message', 'Estado modificado correctamente: ' . $p->caja)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al cambiar estado: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function alertas(Request $r)
    {
        try {
            $id = Crypt::decryptString($r->id);
            $message = $r->message ? strtoupper($r->message) : "";
            $tipo = intval($r->tipo);
            $url = $r->tipo == 2 ? trim($r->url) : "";
            if ($id == null)
                throw new Exception('No se encontró la caja');
            if ($tipo == null || $tipo < 1 || $tipo > 2)
                throw new Exception('El tipo de alerta no es valido' . $tipo);
            if ($tipo == 2 && $url == null)
                throw new Exception('Debe agregar una URL valida para este tipo de alerta.');

            broadcast(new CajasEvent($id, $message, $r->tipo, $url));
            return redirect()->back()->with('message', 'Se envió la alerta.');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('type', 'danger')
                ->with('message', 'Error: ' . $th->getMessage());
        }
    }
    function isValidRoute($path)
    {
        foreach (Route::getRoutes() as $route) {
            if ($route->uri() === ltrim($path, '/')) {
                return true;
            }
        }
        return false;
    }
    ///**funcion para cambiar estados desde cajas comprobantes desde el show cajas */
    public function statusComprobanteCajas($id)
    {
        try {

            $cajaComprobante = cajas_comprobantes::with('cajas_origen')->findOrFail(Crypt::decryptString($id));
            $cajaOrigen = $cajaComprobante->cajas_origen;
            $cajaOrigen->estado = !$cajaOrigen->estado;
            $cajaOrigen->save();
            return back()
                ->with('message', 'Estado modificado correctamente: ' . $cajaOrigen->caja)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route('cajas.index')
                ->with('message', 'Error al cambiar estado: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function destroy_api(Request $r)
    {
        $m = "Se elimino una caja";
        $t = true;
        try {
            cajas::destroy($r->id);
        } catch (\Throwable $th) {
            $t = false;
            $m = "Error: " . $th->getMessage();
        }
        return response()->json([
            'list' => $this->getList(),
            'message' => $m,
            'type' => $t ? 'success' : 'danger',
        ]);
    }

    #1
    public function my(Request $r)
    {
        if (!session('caja'))
            return redirect()->route("cajas.login");
        $correlativos_ccf = (new CorrelativosController)->getCorrelativoByToken("7001", session('caja')->id);
        $correlativos_f = (new CorrelativosController)->getCorrelativoByToken("7002", session('caja')->id);
        $ccf = $correlativos_ccf != null && $correlativos_ccf->id != null ? $correlativos_ccf->actual + 1 : 0;
        $f = $correlativos_f != null && $correlativos_f->id != null ? $correlativos_f->actual + 1 : 0;

        $orden = ordenes::where('comprobante', true)
            ->where('estado', true)
            ->where('anulada', false)
            ->whereIn('cajas_id', session('solicitudes'))
            ->with(['clientes', 'cajas'])
            ->get();

        $comandas = comandas::where(function ($q) {
            $q->where('comprobante', true)
                ->orWhere('fecha', '<', date('Y-m-d'));
        })
            ->where('estado', true)
            ->where('facturada', false)
            ->where('eliminada', false)
            ->where('anulada', false)
            ->where('tipo_comanda', 1)
            ->whereIn('cajas_id', session('solicitudes'))
            ->with(['clientes', 'cajas', 'cortesia'])
            ->get();
        if (session('caja')->hospedaje)
            $recepciones = recepciones::where('comprobante', true)
                ->where('estado', true)
                ->where('facturada', false)
                ->where('eliminado', false)
                ->with(['clientes', 'habitaciones', 'pospago'])
                ->get();
        else $recepciones = [];
        return view('cajas.panel', [
            'ordenes' => $orden,
            'comandas' => $comandas,
            'recepciones' => $recepciones,
            'correlativo_ccf' => $ccf,
            'correlativo_f' => $f,
            'ccf' => $correlativos_ccf
        ]);
    }

    public function comandas(Request $r)
    {
        if (!session('caja'))
            return redirect()->route("cajas.login");
        $correlativos_ccf = (new CorrelativosController)->getCorrelativoByToken("7001", session('caja')->id);
        $correlativos_f = (new CorrelativosController)->getCorrelativoByToken("7002", session('caja')->id);
        $ccf = $correlativos_ccf != null && $correlativos_ccf->id != null ? $correlativos_ccf->actual + 1 : 0;
        $f = $correlativos_f != null && $correlativos_f->id != null ? $correlativos_f->actual + 1 : 0;



        $comandas = comandas::where(function ($q) {
            $q->where('comprobante', true)
                ->orWhere('fecha', '<', date('Y-m-d'));
        })
            ->where('estado', true)
            ->where('facturada', false)
            ->where('eliminada', false)
            ->where('anulada', false)
            ->where('tipo_comanda', 3)
            ->whereIn('cajas_id', session('solicitudes'))
            ->with(['clientes', 'cajas', 'cortesia'])
            ->get();
        return view('cajas.comandas', [
            'comandas' => $comandas,
            'correlativo_ccf' => $ccf,
            'correlativo_f' => $f,
            'ccf' => $correlativos_ccf
        ]);
    }
    public function hospedajePospago()
    {
        if (!session('caja'))
            return redirect()->route("cajas.login");
        $recepciones = recepciones::where('comprobante', true)
            ->whereIn(
                'id',
                recepcion_salidas::where('estado', true)
                    ->where('facturada', false)
                    ->pluck('recepciones_id')
            )
            ->where('facturada', false)
            ->where('eliminado', false)
            ->with(['clientes', 'habitaciones', 'pospago'])
            ->get();


        return view('cajas.pospago', [
            'recepciones' => $recepciones,
        ]);
    }
    #2
    public function login()
    {
        if (session('caja') != null)
            return redirect()->route('cajas.my');

        $ucajas = cajas_users::where('users_id', Auth::user()->id)->get();
        return view('cajas.login', ['cajas' => $ucajas]);
    }
    #3
    public function auth(Request $r)
    {
        try {

            $ucaja = cajas_users::findOrFail(Crypt::decryptString($r->caja));

            if (!Hash::check($r->pin, $ucaja->pin))
                return redirect()->back()
                    ->with("message", 'PIN incorrecto, intente de nuevo.')
                    ->with("type", 'danger');
            $caja = cajas::with("Sucursales")->find($ucaja->cajas_id);
            if (!$caja->estado)
                return redirect()->back()
                    ->with("message", 'La caja esta deshabilitada en este momento, intente ingresar mas tarde. (Si el problema continua consulte a ' . env('MAIL_SOPORTE', 'soporte@tropicoinn.com.sv') . ')')
                    ->with("type", 'danger');
            $turno = turnos::where('cajas_id', $ucaja->cajas_id)->where('estado', true)->first();

            if ($turno == null) {
                $turno = $this->createTurno($ucaja->cajas_id);
                if ($turno == null)
                    return throw new Exception('No hay un turno para abrir en este momento. Si aun hay un turno habilitado para hoy, puede realizar la apertura con al menos ' . env('hora_turno', 1) . ' hora(s) de anticipación segun lo configurado.');
            }

            $cajas_cobros = $caja->cajas_comprobantes->pluck('origen_cajas_id');
            $cajas_cobros[] = $caja->id;
            session(['caja' => $caja, 'turno' => $turno, 'solicitudes' => $cajas_cobros]);
            return redirect()->route('cajas.menu');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('type', 'danger')
                ->with('message', $th->getMessage());
        }
    }

    private function createTurno($caja)
    {
        $opcion = $this->getOpcionTurno($caja);
        //return 'Buscando un turno';
        if ($opcion == null)
            return throw new Exception('Esta caja no tiene un turno abierto, No es posible abrir un turno a esta hora para esta caja.');

        try {
            $turno = new turnos;
            $turno->fecha = date('Y-m-d');
            $turno->apertura = date('Y-m-d H:i:s');
            $turno->apertura_users_id = Auth::user()->id;
            $turno->opcion_turnos_id = $opcion->id;
            $turno->cajas_id = $caja;
            $turno->save();
            return $turno;
        } catch (\Throwable $th) {
            return null;
        }
    }

    private function getOpcionTurno($caja)
    {
        $hora = new DateTime();
        $opcion_turno = $this->getOpcionByHour($caja, $hora->format('Y-m-d H:i:s'));

        if ($opcion_turno == null) {
            $h = env('hora_turno', 1);
            $hora->modify('+' . $h . ' hour');
            //return throw new Exception('Hora: ' . $hora->format('H:i:s'));
            $opcion_turno = $this->getOpcionByHour($caja, $hora->format('Y-m-d H:i:s'));
        }
        if ($opcion_turno == null)
            return throw new Exception('No es posible abrir un turno en este momento. No hay un turno habilitado o es muy temprano, es posible abir un nuevo turno ' . $h . ' hora(s) antes. Si debe haber un turno en este horario por favor reporte este problema a informatica@tropicoinn.com.sv');

        return $opcion_turno;
    }
    private function getOpcionByHour($caja, $hora)
    {
        return DB::table('get_opcion_turnos as opcion_turnos')
            ->leftJoin('caja_turnos', 'opcion_turnos.id', 'caja_turnos.opcion_turnos_id')
            ->where('f_apertura', "<=", $hora)
            ->where('f_cierre', ">=", $hora)
            ->where('opcion_turnos.estado', true)
            ->where('cajas_id', $caja)
            ->whereNotIn('opcion_turnos.id', function ($q) use ($caja) {
                $q->select('opcion_turnos_id')
                    ->from('turnos')
                    ->where('fecha', date('Y-m-d'))
                    ->where('cajas_id', $caja);
            })
            ->select(['opcion_turnos.*'])
            ->first();
    }
    public function cierreTurno(Request $r)
    {
        try {
            if (!session('caja') || !session('turno'))
                return redirect()->route("cajas.login");
            $turno = session('turno');

            if ($this->isValidCierre())
                return redirect()->route('cajas.cuentas')->with('message', 'No se puede cerrar el turno hasta facturar las cuentas o asignarlas al cliente correspondiente');
            $data = $this->getDataCierre($turno->id, true);

            if ($data != null) {
                return view('cajas.cierre', $data);
            } else return redirect()->back()->with('message', 'El turno ya esta cerrado. Cierre la sesion de la caja, y vuelva a abrir. Para actualizar el turno')
                ->with('type', 'danger');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('type', 'danger')
                ->with('message', "No se puede realizar el cierre, Observaciones: " . $th->getMessage());
        }
    }

    public function getDataCierre($turno_id, $verify = false)
    {

        $turno = turnos::where('id', $turno_id);
        if ($verify)
            $turno = $turno->where('estado', true);
        $turno = $turno
            ->with('uapertura')
            ->with('opcion')
            ->first();

        if ($turno != null) {
            $anulaciones = anulacion_comprobantes::leftJoin('comprobantes', 'anulacion_comprobantes.comprobantes_id', 'comprobantes.id')
                ->select("anulacion_comprobantes.*")
                ->where('anulacion_comprobantes.turnos_id', $turno->id)
                ->where('comprobantes.eliminado', false)
                ->get();
            $comprobante = comprobantes::with(["clientes", "users", "pagos", 'anulacion', 'dte'])
                ->where('turnos_id', $turno->id)
                ->where('eliminado', false);
            if (count($anulaciones) > 0)
                $comprobante = $comprobante->orWhereIn('id', $anulaciones->pluck('comprobantes_id'));

            $comprobante = $comprobante->orderBy('correlativo')->get();
            $anticipos = anticipos::with(['clientes', 'aplicado'])
                ->where('turnos_id', $turno->id)
                ->orderBy('id')
                ->get();
            $abonos = abonos::with(['clientes'])
                ->where('estado', true)
                ->where('eliminado', false)
                ->where('turnos_id', $turno->id)
                ->orderBy('id')
                ->get();
            $formas = forma_pagos::all();

            return [
                "comprobantes" => $comprobante,
                "forma_pagos" => $formas,
                "nforma" => $formas->count() ?? 0,
                "caja" => cajas::find($turno->cajas_id),
                "turno" => $turno,
                'tipo_comprobante' => tipo_comprobantes::where('token', '!=', 7005)->get(),
                'anticipos' => $anticipos,
                'abonos' => $abonos,
                #'dtes'=>dtes::whereIn('comprobantes_id',$comprobante->pluck('id'))->get(),
            ];
        }
        return null;
    }
    public function isValidCierre()
    {
        $c = session('caja');
        $turno = session('turno');
        $recepcion = 0;
        $hora = date('Y-m-d ' . env('hora_recepciones', '17:00:00'));
        if (env('caja_recepcion', 1) == $c->id && date('Y-m-d H:i:s') >= $hora)
            $recepcion = DB::table('getcierrerecepciones')->where('sucursales_id', env('sucursal_recepcion', 1))->count();

        if (env('caja_tropiclub', 2) == $c->id && date('Y-m-d H:i:s') >= $hora)
            $recepcion = DB::table('getcierrerecepciones')->where('sucursales_id', env('sucursal_tropiclub', 2))->count();

        $comandas = DB::table('getcierrecomandas')->where('turnos_id', $turno->id)
            ->where('cajas_id', $c->id)
            ->count();
        $ordenes = DB::table('getcierreordenes')->where('turnos_id', $turno->id)->count();

        return ($recepcion > 0 || $comandas > 0 || $ordenes > 0);
    }

    public function cuentasActivas(Request $r)
    {
        $c = session('caja');
        $turno = session('turno');
        $recepcion = [];
        $hora = date('Y-m-d ' . env('hora_recepciones', '17:00:00'));
        if (env('caja_recepcion', 1) == $c->id && date('Y-m-d H:i:s') >= $hora)
            $recepcion = recepciones::whereIn('id', function ($q) {
                $q->from('getcierrerecepciones')->select('id')
                    ->where('sucursales_id', env('sucursal_recepcion', 1));
            })->get();

        if (env('caja_tropiclub', 2) == $c->id && date('Y-m-d H:i:s') >= $hora)
            $recepcion = recepciones::whereIn('id', function ($q) {
                $q->from('getcierrerecepciones')->select('id')
                    ->where('sucursales_id', env('sucursal_tropiclub', 2));
            })->get();


        $comandas = comandas::whereIn(
            "id",
            function ($q) use ($c, $turno) {
                $q->from('getcierrecomandas')->select('id')->where('turnos_id', $turno->id)
                    ->where('cajas_id', $c->id);
            }
        )->get();

        $ordenes = ordenes::whereIn(
            "id",
            function ($q) use ($c, $turno) {
                $q->from('getcierreordenes')->select('id')->where('turnos_id', $turno->id);
            }
        )->get();

        return view('cajas.cuentas', [
            'recepcion' => $recepcion,
            'comandas' => $comandas,
            'ordenes' => $ordenes,
        ]);
    }
    public function cierreTurnoStore(Request $r)
    {
        try {
            if ($this->isValidCierre())
                return redirect()->route('cajas.cuentas')->with('message', 'No se puede cerrar el turno hasta facturar las cuentas o asignarlas al cliente correspondiente');

            if (!session('caja') || !session('turno'))
                return redirect()->route("cajas.login");
            $turno = session('turno');
            $turnos = turnos::where('estado', true)
                ->where('id', $turno->id)
                ->with('uapertura')
                ->with('opcion')
                ->first();
            if ($turnos != null) {
                $turnos->cierre = date("Y-m-d h:i:s");
                $turnos->cierre_users_id = Auth::user()->id;
                $turnos->estado = false;
                $turnos->save();
                $this->logout($r);
                broadcast(new CajasEvent($turnos->cajas_id, "Es requerido cerrar la session en la caja, por cambio de turno.", 2, "/cajas/logout"));

                return redirect()->route('cajas.cierre_container', ["id" => Crypt::encryptString($turnos->id)]);
            } else return redirect()->back()
                ->with('message', 'El turno ya esta cerrado. Cierre la sesión de la caja, y vuelva a abrir. Para actualizar el turno')
                ->with('type', 'danger');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('type', 'danger')
                ->with('message', "No se puede realizar el cierre, Observaciones: " . $th->getMessage());
        }
    }
    public function logout(Request $r)
    {
        if (session('caja') != null)
            session()->forget('caja');
        if (session('turno') != null)
            session()->forget('turno');

        return redirect()->route('cajas.login');
    }

    public function impresion(Request $r)
    {
        return view('cajas.container_cierre', ['url' => route('cajas.cierre_print', ["id" => $r->id])]);
    }

    protected function getPDF(): DomPDF
    {
        $pdf = PDF::getFacadeRoot();
        $dompdf = $pdf->getDomPDF();
        $dompdf->setHttpContext(stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE
            ],
        ]));
        return $pdf;
    }
    public function pdf(Request $r)
    {

        $id = Crypt::decryptString($r->id);
        $data = $this->getDataCierre($id);

        if ($data != null) {
            $pdf = $this->getPDF();
            $pdf->loadView('cajas.cierre_print', $data);
            $pdf->setPaper('letter', 'landscape');

            return $pdf->stream();
        } else
            return redirect()->back()->with('message', 'No se encontraron datos para mostrar');
    }

    public function turnos_reporte(Request $r)
    {
        if (!session('caja') || !session('turno'))
            return redirect()->route("cajas.login");

        if (isset($r->fecha_inicio) || isset($r->fecha_fin)) {
            $v = $r->validate([
                'fecha_inicio' => ['required', 'date'],
                'fecha_fin' => ['required', 'date']
            ]);
            if (!$v) return redirect()->back()
                ->with('message', 'Las fechas no son validas')
                ->with('type', 'danger');
        }
        if (isset($r->accion)) {
            $accion = Crypt::decryptString($r->accion);

            if ($accion == 2 && isset($r->fecha_inicio) && isset($r->fecha_fin)) {
                return $this->getReporte($r->fecha_inicio, $r->fecha_fin);
            }
        }
        $fecha_inicio = $r->fecha_inicio ?? date("Y-m-d");
        $fecha_fin = $r->fecha_fin ?? date("Y-m-d");

        $turnos = turnos::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->where('cajas_id', session('caja')->id)->get();
        return view('cajas.reporte_turnos', compact('fecha_inicio', 'fecha_fin', 'turnos'));
    }

    public function getReporte($fecha_inicio, $fecha_fin)
    {
        if (!session('caja') || !session('turno'))
            return redirect()->route("cajas.login");
        return view('cajas.container_cierre', ['url' => route('cajas.turnos_reporte_pdf', ['fecha_inicio' => Crypt::encryptString($fecha_inicio), 'fecha_fin' => Crypt::encryptString($fecha_fin), 'caja' => Crypt::encryptString(session('caja')->id)])]);
    }
    public function getReportePDF(Request $r)
    {
        set_time_limit(120);
        $fecha_inicio = Crypt::decryptString($r->fecha_inicio);
        $fecha_fin = Crypt::decryptString($r->fecha_fin);
        $caja = cajas::find(Crypt::decryptString($r->caja));

        $turnos = turnos::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->where('cajas_id', $caja->id)->get();
        $todosTunos = $turnos->pluck('id');
        $anulaciones = anulacion_comprobantes::leftJoin('comprobantes', 'anulacion_comprobantes.comprobantes_id', 'comprobantes.id')
            ->select("anulacion_comprobantes.*")
            ->whereIn('anulacion_comprobantes.turnos_id', $todosTunos)
            ->where('comprobantes.eliminado', false)
            ->get();
        $comprobantes = comprobantes::with("clientes")
            ->with("users")
            ->with("pagos")
            ->whereIn('turnos_id', $todosTunos)
            ->where('eliminado', false);
        if (count($anulaciones) > 0)
            $comprobantes = $comprobantes->orWhereIn('id', $anulaciones->pluck('comprobantes_id'));
        $comprobantes = $comprobantes->orderBy('correlativo')->get();

        $anticipos = anticipos::with(['clientes', 'aplicado'])
            ->whereIn('turnos_id', $todosTunos)
            ->orderBy('id')
            ->get();
        $abonos = abonos::with(['clientes'])
            ->where('estado', true)
            ->where('eliminado', false)
            ->whereIn('turnos_id', $todosTunos)
            ->orderBy('id')
            ->get();
        $forma_pagos = forma_pagos::all();

        $tipo_comprobante = tipo_comprobantes::where('token', '!=', 7005)->get();
        $nforma = $forma_pagos->count() ?? 0;
        $pdf = $this->getPDF();
        //return $pdf->loadView(
        /*$view = view(
            "cajas.cierre_print_fecha",
            compact(
                'turnos',
                'comprobantes',
                'forma_pagos',
                'caja',
                'tipo_comprobante',
                'nforma',
                'fecha_inicio',
                'fecha_fin',
                'anticipos',
                'abonos'
            )
        )->render();*/
        $snap = SnappyPdf::loadView(
            "cajas.cierre_print_fecha",
            compact(
                'turnos',
                'comprobantes',
                'forma_pagos',
                'caja',
                'tipo_comprobante',
                'nforma',
                'fecha_inicio',
                'fecha_fin',
                'anticipos',
                'abonos',
                'anulaciones'
            )
        )
            ->setPaper('letter', 'landscape') // Tamaño carta y orientación horizontal
            ->setOption('margin-top', '10mm')
            ->setOption('margin-bottom', '10mm')
            ->setOption('margin-left', '10mm')
            ->setOption('margin-right', '10mm');
        return $snap->inline('reporte_rubros.pdf');
        //->setPaper('letter', 'landscape')->stream();
    }


    public function venta_reportes(Request $r)
    {
        if (!session('caja') || !session('turno'))
            return redirect()->route("cajas.login");

        if (isset($r->fecha_inicio) || isset($r->fecha_fin)) {
            $v = $r->validate([
                'fecha_inicio' => ['required', 'date'],
                'fecha_fin' => ['required', 'date']
            ]);
            if (!$v) return redirect()->back()
                ->with('message', 'Las fechas no son validas')
                ->with('type', 'danger');
        }
        if (isset($r->accion)) {
            $accion = Crypt::decryptString($r->accion);

            if ($accion == 2 && isset($r->fecha_inicio) && isset($r->fecha_fin)) {
                return $this->getReporteVentaPDF($r->fecha_inicio, $r->fecha_fin, $r->turnos);
            }
        }
        $caja = cajas::find(session('caja')->id);
        $fecha_inicio = $r->fecha_inicio ?? date("Y-m-d");
        $fecha_fin = $r->fecha_fin ?? date("Y-m-d");

        $turnos = turnos::whereBetween('fecha', [$fecha_inicio, $fecha_fin])
            ->where('cajas_id', session('caja')->id);
        if (isset($r->turnos) && count($r->turnos))
            $turnos = $turnos->whereIn('opcion_turnos_id', $r->turnos);

        $turnos = $turnos->get();
        return view('cajas.reporte_venta', [
            "turnos" => $caja->turnos,
            "list" => $turnos,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'turno_selected' => $r->turnos ?? [],
        ]);
    }

    //cSpell:ignore uapertura, ucierre, categorias
    public function venta_pdf(Request $r)
    {

        $id = Crypt::decryptString($r->id);

        $turnos = turnos::with('uapertura')
            ->with('ucierre')
            ->with('opcion')
            ->find($id);

        $comandas = comanda_detalles::leftJoin('precios', "comanda_detalles.precios_id", 'precios.id')
            ->with("comandas")
            ->with("user_comanda")
            ->where('turnos_id', $id)
            ->select(['comanda_detalles.*', 'precios.detalle', 'categorias_precios_id'])
            ->get();

        $categorias = categorias_precios::whereIn('id', $comandas->pluck('categorias_precios_id'))
            ->orderBy('categoria')->get();

        $resumen = comanda_detalles::leftJoin('precios', 'comanda_detalles.precios_id', '=', 'precios.id')
            ->where('turnos_id', $id)
            ->groupBy('precios.id')
            ->select(
                [
                    'precios.detalle',
                    DB::raw('SUM(comanda_detalles.cantidad) as cantidad'),
                    DB::raw('SUM(comanda_detalles.precio * comanda_detalles.cantidad) as total_precio')
                ]
            )
            ->get();
        $existencias = comanda_existencias::whereIn('comanda_detalles_id', $comandas->pluck('id'))
            ->with(['existencias', 'productos'])
            ->select([DB::raw('SUM(cantidad) as cantidad'), 'productos_id', 'existencias_id'])
            ->groupBy('productos_id', 'existencias_id')
            ->get();
        $anulaciones = anulaciones_detalle_comanda::whereIn('comanda_detalles_id', $comandas->pluck('id'))
            ->with(['comanda_detalles', 'users'])
            ->get();
        $caja = cajas::find($turnos->cajas_id);
        $pdf = $this->getPDF();
        $pdf->loadView(
            'cajas.venta_print',
            [
                "comandas" => $comandas,
                "caja" => $caja,
                "turno" => $turnos,
                'categorias' => $categorias,
                'resumen' => $resumen,
                'existencias' => $existencias,
                'anulaciones' => $anulaciones,
            ]
        );
        $pdf->setPaper('letter', 'landscape');

        return $pdf->stream();
    }


    public function getReporteVentaPDF($fecha_inicio, $fecha_fin, $turnosArr = [])
    {
        $turnos = turnos::with('uapertura')
            ->with('ucierre')
            ->with('opcion')
            ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
            ->where('cajas_id', session('caja')->id);
        if (isset($turnosArr) && count($turnosArr) > 0)
            $turnos = $turnos->whereIn('opcion_turnos_id', $turnosArr);
        $turnos = $turnos->get();

        $comandas = comanda_detalles::leftJoin('precios', "comanda_detalles.precios_id", 'precios.id')
            ->with("comandas")
            ->with("user_comanda")
            ->whereIn('turnos_id', $turnos->pluck('id'))
            ->select(['comanda_detalles.*', 'precios.detalle', 'categorias_precios_id'])
            ->get();

        $categorias = categorias_precios::whereIn('id', $comandas->pluck('categorias_precios_id'))
            ->orderBy('categoria')->get();

        $resumen = comanda_detalles::leftJoin('precios', 'comanda_detalles.precios_id', '=', 'precios.id')
            ->whereIn('turnos_id', $turnos->pluck('id'))
            ->groupBy('precios.id')
            ->select(
                [
                    'precios.detalle',
                    DB::raw('SUM(comanda_detalles.cantidad) as cantidad'),
                    DB::raw('SUM(comanda_detalles.precio * comanda_detalles.cantidad) as total_precio')
                ]
            )
            ->get();
        $existencias = comanda_existencias::whereIn('comanda_detalles_id', $comandas->pluck('id'))
            ->with(['existencias', 'productos'])
            ->select([DB::raw('SUM(cantidad) as cantidad'), 'productos_id', 'existencias_id'])
            ->groupBy('productos_id', 'existencias_id')
            ->get();
        $caja = cajas::find(session('caja')->id);
        $pdf = $this->getPDF();
        $pdf->loadView(
            'cajas.venta_turnos_print',
            [
                "comandas" => $comandas,
                "caja" => $caja,
                "turno" => $turnos,
                'categorias' => $categorias,
                'resumen' => $resumen,
                'existencias' => $existencias,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
            ]
        );
        $pdf->setPaper('letter', 'landscape');

        return $pdf->stream();
    }

    public function hospedaje(Request $r)
    {
        try {
            $caja = cajas::find(Crypt::decryptString($r->id));
            $caja->hospedaje = !$caja->hospedaje;
            $caja->save();
            return redirect()->back()->with('message', 'Se cambio la opcion hospedaje');
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Ocurrió un error: ' . $th->getMessage());
        }
    }

    public function authApp(Request $r)
    {
        try {
            $ucaja = cajas_users::where('cajas_id', Crypt::decryptString($r->caja))->first();

            if (!Hash::check($r->pin, $ucaja->pin))
                return redirect()->back()
                    ->with("message", 'PIN incorrecto, intente de nuevo.')
                    ->with("type", 'danger');

            $turno = turnos::where('cajas_id', $ucaja->cajas_id)->where('estado', true)->first();

            if ($turno == null) {
                $turno = $this->createTurno($ucaja->cajas_id);
                if ($turno == null)
                    return throw new Exception('No hay un turno para abrir en este momento. Si aun hay un turno habilitado para hoy, puede realizar la apertura con al menos ' . env('hora_turno', 1) . ' hora(s) de anticipación segun lo configurado.');
            }
            $caja = cajas::with("Sucursales")->find($ucaja->cajas_id);
            $cajas_cobros = $caja->cajas_comprobantes->pluck('origen_cajas_id');
            $cajas_cobros[] = $caja->id;
            session(['caja' => $caja, 'turno' => $turno, 'solicitudes' => $cajas_cobros]);

            return redirect()->route('app.comandas');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('type', 'danger')
                ->with('message', $th->getMessage());
        }
    }
    //**pendiente refactorizar panel reportes turnos, ventas, cocina, bar */
    public function panelReportes()
    {
        $data = $this->getPanelData();

        return view('report.panel_reportes.panel', $data);
    }
    public function panelReportesSubMenu()
    {
        return view('report.panel_reportes.sub_menu.enlaces');
    }
    private function getPanelData()
    {
        $fecha = date("Y-m-d");
        $cajaId = null;
        $cajas = $this->CajasUsuario();

        $turnos = turnos::whereIn('cajas_id', $cajas->pluck('cajas_id'))->whereDate('fecha', $fecha)->get();
        $t_ids = $turnos->pluck('id');
        $comandas = $this->comandasVendidas($t_ids);
        $ordenes = $this->ordenesVendidas($t_ids);
        $detalleOrden = $ordenes->flatMap(fn($orden) => $orden->detalle_orden);


        return [
            "cajas" => $cajas,
            "cajaId" => $cajaId,
            "inicio" => $fecha,
            "fin" => $fecha,
            "turnos" => $turnos,
            "opcion" => $this->opcionesTurnos(),
            "comandas" => $comandas,
            "ordenes" => $detalleOrden,
        ];
    }
    private function obtenerTurnos(Request $r, $inicio, $fin)
    {
        return turnos::whereBetween('fecha', [$inicio, $fin])
            ->when($r->cajas_id != 0, function ($query) use ($r) {
                return $query->where('cajas_id', $r->cajas_id);
            })
            ->when(!empty($r->turnos) && count($r->turnos) > 0, function ($query) use ($r) {
                return $query->whereIn('opcion_turnos_id', $r->turnos);
            })
            ->get();
    }
    private function cajasUsuario()
    {
        return cajas_users::where('users_id', Auth::user()->id)->get();
    }
    private function opcionesTurnos()
    {
        return  opcion_turnos::all();
    }
    private function comandasVendidas($t)
    {
        return comanda_detalles::leftJoin('precios', "comanda_detalles.precios_id", 'precios.id')
            ->with(['comandas', 'user_comanda'])
            ->whereIn('turnos_id', $t)
            ->select(['comanda_detalles.*', 'precios.detalle', 'categorias_precios_id'])
            ->get();
    }
    private function ordenesVendidas($t)
    {
        return ordenes::with('detalle_orden')->WhereNotNull('turnos_id')->whereIn('turnos_id', $t)->get();
    }
    public function reportesTurnosByCajas(ReportesPanelRequest $r)
    {
        try {
            $opcion = $r->opcion ?? 1;
            $caja = cajas::find($r->cajas_id);
            $cajaId = $caja->id ?? null;
            $inicio = $r->inicio ?? date("Y-m-d");
            $fin = $r->fin ?? date("Y-m-d");

            $turnos = $this->obtenerTurnos($r, $inicio, $fin);

            if ($turnos->isNotEmpty()) {
                $todosTurnos = $turnos->pluck('id');
                $comprobantes = comprobantes::with(['clientes', 'users', 'pagos'])
                    ->whereIn('turnos_id', $todosTurnos)
                    ->where('eliminado', false)
                    ->get();
                $anticipos = anticipos::with(['clientes', 'aplicado'])
                    ->where('estado', true)
                    ->whereIn('turnos_id', $todosTurnos)
                    ->orderBy('id')
                    ->get();
                $abonos = abonos::with(['clientes'])
                    ->where('estado', true)
                    ->where('eliminado', false)
                    ->whereIn('turnos_id', $todosTurnos)
                    ->orderBy('id')
                    ->get();
            } else throw new Exception('No se encontraron datos, revise los parametros e intente de nuevo');

            $forma_pagos = forma_pagos::all();
            $nforma = $forma_pagos->count() ?? 0;
            switch ($opcion) {
                case 1:
                    return view('report.panel_reportes.panel', [
                        'turnos' => $turnos,
                        'cajas' => $this->CajasUsuario(),
                        'cajaId' => $cajaId,
                        'inicio' => $inicio,
                        'fin' => $fin,
                        'turno_selected' => $r->turnos ?? [],
                        'opcion' => $this->opcionesTurnos(),
                    ]);
                    break;
                case 2:
                    $pdf = $this->getPDF();
                    $pdf->loadView(
                        'report.panel_reportes.panel_print',
                        [
                            'turnos' => $turnos,
                            'cajas' => $this->CajasUsuario(),
                            'inicio' => $inicio,
                            'fin' => $fin,
                            'comprobantes' => $comprobantes,
                            'forma_pagos' => $forma_pagos,
                            'cajaId' => $cajaId,
                            'tipo_comprobante' => tipo_comprobantes::where('token', '!=', 7005)->get(),
                            'nforma' => $nforma,
                            'anticipos' => $anticipos,
                            'abonos' => $abonos
                        ]
                    );
                    $pdf->setPaper('letter', 'landscape');
                    return $pdf->stream();
                    break;
            }
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('type', 'danger')
                ->with('message', $th->getMessage());
            return throw $th;
        }
    }
    //reporte de ventas seleccionando cajas y turnos
    public function panelVentaReporte()
    {
        $data = $this->getPanelData();

        return view('report.panel_reportes.venta', $data);
    }
    public function reportesVentasByCajas(ReportesPanelRequest $r)
    {

        try {
            $opcion = $r->opcion ?? 1;
            $caja = cajas::find($r->cajas_id);
            $cajaId = $caja->id ?? null;

            $inicio = $r->inicio ?? date("Y-m-d");
            $fin = $r->fin ?? date("Y-m-d");
            $cajas = $this->CajasUsuario();

            $turnos = $this->obtenerTurnos($r, $inicio, $fin);
            $t_ids = $turnos->pluck('id');
            $comandas = $this->comandasVendidas($t_ids);

            $categorias = categorias_precios::whereIn('id', $comandas->pluck('categorias_precios_id'))
                ->orderBy('categoria')->get();

            $resumen = comanda_detalles::leftJoin('precios', 'comanda_detalles.precios_id', '=', 'precios.id')
                ->whereIn('turnos_id', $turnos->pluck('id'))
                ->groupBy('precios.id')
                ->select(
                    [
                        'precios.detalle',
                        DB::raw('SUM(comanda_detalles.cantidad) as cantidad'),
                        DB::raw('SUM(comanda_detalles.precio * comanda_detalles.cantidad) as total_precio')
                    ]
                )
                ->get();
            $existencias = comanda_existencias::whereIn('comanda_detalles_id', $comandas->pluck('id'))
                ->with(['existencias', 'productos'])
                ->select([DB::raw('SUM(cantidad) as cantidad'), 'productos_id', 'existencias_id'])
                ->groupBy('productos_id', 'existencias_id')
                ->get();

            $ordenes = $this->ordenesVendidas($t_ids);
            $detalleOrden = $ordenes->flatMap(fn($orden) => $orden->detalle_orden);

            switch ($opcion) {
                case 1:
                    return view('report.panel_reportes.venta', [
                        'turnos' => $turnos,
                        'cajas' => $cajas,
                        'cajaId' => $cajaId,
                        'inicio' => $inicio,
                        'fin' => $fin,
                        'turno_selected' => $r->turnos ?? [],
                        'opcion' => $this->opcionesTurnos(),
                        'ordenes' => $detalleOrden,
                        "comandas" => $comandas,

                    ]);
                    break;
                case 2:
                    $pdf = $this->getPDF();
                    $pdf->loadView(
                        'report.panel_reportes.venta_print',
                        [
                            'turnos' => $turnos,
                            'cajas' => $cajas,
                            'inicio' => $inicio,
                            'fin' => $fin,
                            'comandas' => $comandas,
                            "caja" => $caja,
                            'cajaId' => $cajaId,
                            "turno" => $turnos,
                            'categorias' => $categorias,
                            'resumen' => $resumen,
                            'existencias' => $existencias,
                            'ordenes' => $detalleOrden,

                        ]
                    );
                    $pdf->setPaper('letter', 'landscape');
                    return $pdf->stream();
                    break;
            }
        } catch (\Throwable $th) {
            return throw $th;
        }
    }

    public function ventasRubrosForm(Request $r)
    {

        return view('report.panel_reportes.ventaRubrosForm', [
            'cajas' => $this->getUserCajas(),
            'rubros' => $this->getRubros(),
        ]);
    }

    public function getUserCajas($caja = null)
    {

        if ($caja == null) {
            $userCajas = cajas_users::where('users_id', Auth::user()->id)->get();
            return cajas::whereIn('id', $userCajas->pluck('cajas_id'))->get();
        } else
            return cajas::whereIn('id', $caja)->get();
    }
    public function getRubros($rubros = null)
    {

        if ($rubros == null) {

            return rubro::all();
        } else
            return rubro::whereIn('id', $rubros)->get();
    }

    public function ventaRubrosAcciones(Request $r)
    {
        $r->validate([
            'cajas_id' => ['required', 'array'],
            'inicio'   => ['required', 'date'],
            'fin'      => ['required', 'date', 'after_or_equal:inicio'],
            'rubros_id' => ['required', 'array'],
            'opcion'   => ['required', 'integer', 'min:1', 'max:3']
        ], [
            'cajas_id.required' => 'El campo "cajas_id" es obligatorio.',
            'cajas_id.array'    => 'El campo cajas debe ser un array.',
            'inicio.required'   => 'La fecha de inicio es obligatoria.',
            'inicio.date'       => 'El fecha de inicio debe ser una fecha válida.',
            'fin.required'      => 'La fecha de final es obligatoria.',
            'fin.date'          => 'La fecha final debe ser una fecha válida.',
            'fin.after'         => 'La fecha final debe ser posterior a la fecha de inicio.',
            'rubros_id.required' => 'El campo rubros es obligatorio.',
            'rubros_id.array'   => 'El campo rubros debe ser un array.',
            'opcion.required'   => 'El campo opcion es obligatorio.',
            'opcion.integer'    => 'El campo opcion debe ser un número entero.',
            'opcion.min'        => 'El campo opcion debe ser al menos :min.',
            'opcion.max'        => 'El campo opcion no puede ser mayor que :max.',
        ]);

        try {
            #Rubros
            $rubros = $r->rubros_id;
            $selectedRubros = [];
            if (is_array($rubros) && count($rubros) > 0) {
                foreach ($rubros as $v) {
                    $c = Crypt::decryptString($v);
                    if ($c == 0) {
                        $selectedRubros = null;
                        break;
                    }
                    array_push($selectedRubros, $c); // [1,2,3..]
                }
            }

            #Cajas
            $cajas = $r->cajas_id;
            $selectedCajas = [];
            if (is_array($cajas) && count($cajas) > 0) {
                foreach ($cajas as $v) {
                    $c = Crypt::decryptString($v);
                    if ($c == 0) {
                        $selectedCajas = null;
                        break;
                    }
                    array_push($selectedCajas, $c);
                }
            }

            $data = $this->getDataRubros(
                $selectedCajas,
                $r->inicio,
                $r->fin,
                $selectedRubros
            );
            $caja = $this->getUserCajas($selectedCajas);
            $rubro = $this->getRubros($selectedRubros);

            switch ($r->opcion) {
                case 1: #Vista previa
                    return view('report.panel_reportes.ventaRubrosPreview', [
                        'data' => $data,
                        'caja' => $caja,
                        'rubro' => $rubro
                    ]);
                    break;
                case 2: #PDF
                    /*return view('report.panel_reportes.ventaRubrosPrint3', [
                        'inicio' => $r->inicio, #Se usa para mostrarlo en el reporte imprimible
                        'fin'   => $r->fin,   #Se usa para mostrarlo en el reporte imprimible
                        'data'  => $data,
                        'caja'  => $caja,
                        'rubro' => $rubro
                    ]);*/

                    #Snappy
                    $snap = SnappyPdf::loadView('report.panel_reportes.ventaRubrosPrint', [
                        'inicio' => $r->inicio, #Se usa para mostrarlo en el reporte imprimible
                        'fin'   => $r->fin,   #Se usa para mostrarlo en el reporte imprimible
                        'data'  => $data,
                        'caja'  => $caja,
                        'rubro' => $rubro,
                    ])
                        ->setPaper('letter', 'landscape');
                    /*->setOption('margin-top', '10mm')
                        ->setOption('margin-bottom', '10mm')
                        ->setOption('margin-left', '10mm')
                        ->setOption('margin-right', '10mm');*/

                    return $snap->inline('reporte_ventas_por_rubro.pdf');
                    //->setPaper('letter', 'landscape')->stream();
                    break;

                case 3:
                    $fecha_inicio = Carbon::parse($r->inicio)->format('Y-m-d');
                    $fecha_fin = Carbon::parse($r->fin)->format('Y-m-d');
                    $name = 'detalle_advalorem' . $fecha_inicio . '_al_' . $fecha_fin . '.xlsx';
                    return Excel::download(new VentasRubrosAdvalorem($fecha_inicio, $fecha_fin, $selectedRubros, $selectedCajas), $name);
                    break;
                default:
                    throw new Exception('No se encontro la opcion seleccionada');
                    break;
            }
        } catch (\Throwable $th) {
            return throw $th;
            return redirect()->back()->with('message', 'Error: ' . $th->getMessage())->with('type', 'danger');
        }
    }

    private function getDataRubros($cajas = null, $inicio, $fin, $rubros = null)
    {

        $data = vventas_rubros::whereBetween('fecha', [$inicio, $fin]);
        if ($cajas != null && is_array($cajas) && count($cajas)) {
            $data = $data->whereIn('cajas_id', $cajas);
        }
        if ($rubros != null && is_array($rubros) &&  count($rubros)) {
            $data = $data->whereIn('rubros_id', $rubros);
        }
        return $data->get();
    }
    #---Reporte de ventas por rubro---
    public function ventaRubros(Request $r)
    {
        try {
            $opcion  = $r->opcion ?? 1;

            $tipoComprobantes  = tipo_comprobantes::get();
            $tipoComprobanteId = $r->tipo_comprobantes_id ?? null;

            $cajas = cajas::all();
            $caja = cajas::find($r->cajas_id) ?? null;

            $rubros = rubro::get();
            $rubro  = rubro::find($r->rubros_id) ?? null;

            $inicio = $r->inicio ?? date("Y-m-d");
            $fin    = $r->fin ?? date("Y-m-d");

            $turnos = $this->obtenerTurnos($r, $inicio, $fin);

            $opcion_turnos = opcion_turnos::all();

            #---Se filtrará según si se selecciona un tipo de comprobante o todos---
            if ($tipoComprobanteId <= 0 || $tipoComprobanteId === null)
                $tc = tipo_comprobantes::pluck('id');
            else
                $tc = tipo_comprobantes::where('token', $tipoComprobanteId)->pluck('id');
            #-----

            $anulaciones = anulacion_comprobantes::leftJoin('comprobantes', 'anulacion_comprobantes.comprobantes_id', 'comprobantes.id')
                ->whereIn('anulacion_comprobantes.turnos_id', $turnos->pluck('id'))
                ->where('comprobantes.eliminado', false)
                ->pluck('comprobantes.id');

            $rc_comprobantes = registro::query()
                ->leftJoin('comprobantes', 'registros.comprobantes_id', '=', 'comprobantes.id')
                ->leftJoin('tipo_comprobantes', 'comprobantes.tipo_comprobantes_id', '=', 'tipo_comprobantes.id')
                ->leftJoin('detalle_comprobantes', 'detalle_comprobantes.comprobantes_id', '=', 'comprobantes.id')
                ->leftJoin('rubros', 'detalle_comprobantes.rubros_id', '=', 'rubros.id')
                ->leftJoin('turnos', 'comprobantes.turnos_id', '=', 'turnos.id')
                ->leftJoin('ordenes', function ($j) {
                    $j->on('registros.registro', '=', 'ordenes.id')
                        ->where('registros.tipo_registros', 1);
                })
                ->leftJoin('comandas', function ($j) {
                    $j->on('registros.registro', '=', 'comandas.id')
                        ->where('registros.tipo_registros', 3);
                })
                ->select([
                    DB::raw('(SELECT caja FROM cajas WHERE id = turnos.cajas_id) as caja_nombre'),
                    'registros.registro as comprobante',
                    'comprobantes.fecha as c_fecha',
                    'detalle_comprobantes.concepto',
                    'detalle_comprobantes.cantidad as cantidad',
                    'detalle_comprobantes.neto as neto',
                    DB::raw('detalle_comprobantes.neto * detalle_comprobantes.cantidad as venta'),
                    'detalle_comprobantes.propina as propina',
                    'detalle_comprobantes.iva as iva',
                    'detalle_comprobantes.cesc as cesc',
                    'detalle_comprobantes.total as total', //
                    'comprobantes.correlativo as c_correlativo',
                    'tipo_comprobantes.tipo as tipo_c',
                    'tipo_comprobantes.token as tipo_comprobante_token',
                    'rubros.token as token_rubro',
                    'rubros.rubro as nombre_rubro',
                    'comandas.id as comanda',
                    'ordenes.id as orden',
                ])
                ->when($rubro, fn($query) => $query->where('detalle_comprobantes.rubros_id', $rubro->id))
                ->when($turnos->isNotEmpty(), fn($query) => $query->whereIn('comprobantes.turnos_id', $turnos->pluck('id')))
                ->whereIn('comprobantes.tipo_comprobantes_id', $tc)
                ->whereNotIn('comprobantes.id', $anulaciones)
                ->whereIn('tipo_registros', [1, 3])
                ->get();

            switch ($opcion) {
                case 1:
                    return view('report.panel_reportes.ventaRubros', [
                        'turnos' => $turnos,
                        'tipoComprobantes' => $tipoComprobantes,
                        'tipoComprobanteId' => $tipoComprobanteId,
                        'cajas' => $cajas,
                        'caja'  => $caja,
                        'inicio' => $inicio,
                        'fin'   => $fin,
                        'turno_selected' => $r->turnos ?? [],
                        'opcion' => $opcion_turnos,
                        'rubros' => $rubros,
                        'rubro' => $rubro,
                        'registros' => $rc_comprobantes,
                    ]);
                    break;
                case 2:
                    return view('report.panel_reportes.ventaRubrosPrint', [
                        'turnos' => $turnos,
                        'cajas' => $cajas,
                        'inicio' => $inicio,
                        'fin'   => $fin,
                        'turno_selected' => $r->turnos[0] ?? [],
                        'registros' => $rc_comprobantes,
                        'orientacionPagina' => 2,
                    ]);

                    #---
                    /*$pdf = $this->getPDF();

                    $pdf->loadView('report.panel_reportes.ventaRubrosPrint', [
                        'turnos' => $turnos,
                        'cajas' => $cajas,

                        'inicio' => $inicio,
                        'fin'   => $fin,
                        'turno_selected' => $r->turnos[0] ?? [],
                        'detalleComprobantes' => $detalleComprobantes,
                    ]);

                    $pdf->setPaper('letter', 'landscape');
                    return $pdf->stream();*/
                    break;
            }
        } catch (Throwable $th) {
            return throw $th;
        }
    }


    public function comandasActivas(Request $r)
    {
        return view('report.panel_reportes.comandasActivasForm', [
            'cajas' => $this->getUserCajas(),
        ]);
    }

    public function comandasActivasAcciones(Request $r)
    {

        $r->validate([
            'cajas_id' => ['required', 'array'],
            'inicio'   => ['required', 'date'],
            'fin'      => ['required', 'date', 'after_or_equal:inicio'],
            'opcion'   => ['required', 'integer', 'min:1', 'max:2']
        ], [
            'cajas_id.required' => 'El campo "cajas_id" es obligatorio.',
            'cajas_id.array'    => 'El campo cajas debe ser un array.',
            'inicio.required'   => 'La fecha de inicio es obligatoria.',
            'inicio.date'       => 'El fecha de inicio debe ser una fecha válida.',
            'fin.required'      => 'La fecha de final es obligatoria.',
            'fin.date'          => 'La fecha final debe ser una fecha válida.',
            'fin.after'         => 'La fecha final debe ser posterior a la fecha de inicio.',
            'opcion.required'   => 'El campo opcion es obligatorio.',
            'opcion.integer'    => 'El campo opcion debe ser un número entero.',
            'opcion.min'        => 'El campo opcion debe ser al menos :min.',
            'opcion.max'        => 'El campo opcion no puede ser mayor que :max.',
        ]);

        try {
            #Cajas
            $cajas = $r->cajas_id;
            $selectedCajas = [];
            if (is_array($cajas) && count($cajas) > 0) {
                foreach ($cajas as $v) {
                    $c = Crypt::decryptString($v);
                    if ($c == 0) {
                        $selectedCajas = null;
                        break;
                    }
                    array_push($selectedCajas, $c);
                }
            }

            $caja = $this->getUserCajas($selectedCajas);
            if ($selectedCajas == null || (is_array($selectedCajas) && count($selectedCajas)))
                $selectedCajas = $caja->pluck('id');

            $data = $this->getDataComandasActivas(
                $selectedCajas,
                $r->inicio,
                $r->fin
            );


            switch ($r->opcion) {
                case 1: #Vista previa
                    return view('report.panel_reportes.comandasActivasPreview', [
                        'data' => $data,
                        'caja' => $caja,

                    ]);
                    break;
                case 2: #PDF
                    $snap = SnappyPdf::loadView(
                        'report.panel_reportes.comandasActivasPrint',
                        [
                            'inicio' => $r->inicio,
                            'fin'   => $r->fin,
                            'data'  => $data,
                            'caja'  => $caja,
                        ]
                    )
                        ->setPaper('letter')
                        ->setOption('margin-top', '10mm')
                        ->setOption('margin-bottom', '10mm')
                        ->setOption('margin-left', '10mm')
                        ->setOption('margin-right', '10mm');
                    return $snap->inline('reporte_turnos_agrupado.pdf');
                    break;
                default:
                    throw new Exception('No se encontro la opcion seleccionada');
                    break;
            }
        } catch (\Throwable $th) {
            return throw $th;
            return redirect()->back()->with('message', 'Error: ' . $th->getMessage())->with('type', 'danger');
        }
    }

    public function getDataComandasActivas($cajas = null, $fecha_inicio, $fecha_fin)
    {
        return comandas::WhereBetween('fecha', [$fecha_inicio, $fecha_fin])
            ->where('estado', true)
            ->where('facturada', false)
            ->where('eliminada', false)
            ->where('anulada', false)
            ->whereIn('cajas_id', $cajas)
            ->with(['clientes', 'cajas', 'cortesia'])
            ->get();
    }


    public function ventasHabitaciones()
    {
        return view('report.ventas_habitaciones.form', [
            'vendedores' => $this->getUserVentas(),
            'sucursales' => sucursales::all(),
        ]);
    }

    public function ventasHabitacionesAcciones(Request $r)
    {

        $r->validate([

            'inicio'   => ['required', 'date'],
            'fin'      => ['required', 'date', 'after_or_equal:inicio'],
            'opcion'   => ['required', 'integer', 'min:1', 'max:2'],
            'sucursal' => ['required', 'string'],
            'vendedores' => ['required', 'array'],
            'resumen' =>  ['nullable', 'integer'],
        ], [
            'vendedores.required'   => 'El campo vendedor/a es obligatorio.',
            'vendedores.array'      => 'El campo vendedor/a es obligatorio seleccione uno o mas',
            'inicio.required'       => 'La fecha de inicio es obligatoria.',
            'inicio.date'           => 'El fecha de inicio debe ser una fecha válida.',
            'fin.required'          => 'La fecha de final es obligatoria.',
            'fin.date'              => 'La fecha final debe ser una fecha válida.',
            'fin.after'             => 'La fecha final debe ser posterior a la fecha de inicio.',
            'opcion.required'       => 'El campo opcion es obligatorio.',
            'opcion.integer'        => 'El campo opcion debe ser un número entero.',
            'sucursal.required'   => 'El campo sucursal es obligatorio.',
            'sucursal.string'     => 'El campo sucursal es obligatorio.',
        ]);

        try {
            #Cajas
            $vendedores = $r->vendedores;
            $sucursal = Crypt::decryptString($r->sucursal);
            $selectedVendedores = [];
            if (is_array($vendedores) && count($vendedores) > 0) {
                foreach ($vendedores as $v) {
                    $c = Crypt::decryptString($v);
                    if ($c == 0) {
                        $selectedVendedores = null;
                        break;
                    }
                    array_push($selectedVendedores, $c);
                }
            }

            $vendedoresList = $this->getUserVentas($selectedVendedores);
            if ($selectedVendedores == null || (is_array($selectedVendedores) && count($selectedVendedores)))
                $selectedVendedores = $vendedoresList->pluck('id');

            $data = $this->getDataVentasHabitaciones(
                $r->inicio,
                $r->fin,
                $sucursal == 0 ? null : $sucursal,
                $selectedVendedores
            );
            if ($sucursal != null && $sucursal > 0)
                $sucursales = sucursales::where('id', $sucursal)->get();
            else
                $sucursales =  sucursales::all();

            switch ($r->opcion) {
                case 1: #Vista previa
                    if (isset($r->resumen) && $r->resumen == 1) {
                        return view('report.ventas_habitaciones.resumenPreview', [
                            'inicio' => $r->inicio,
                            'fin'   => $r->fin,
                            'data' => $data,
                            'vendedores' => $vendedoresList,
                            'sucursales' => $sucursales
                        ]);
                    } else
                        return view('report.ventas_habitaciones.detallePreview', [
                            'inicio' => $r->inicio,
                            'fin'   => $r->fin,
                            'data' => $data,
                            'vendedores' => $vendedoresList,
                            'sucursales' => $sucursales
                        ]);
                    break;
                case 2: #PDF
                    if (isset($r->resumen) && $r->resumen == 1) {
                        $snap = SnappyPdf::loadView(
                            'report.ventas_habitaciones.printResumen',
                            [
                                'inicio' => $r->inicio,
                                'fin'   => $r->fin,
                                'data'  => $data,
                                'vendedores' => $vendedoresList,
                                'sucursales' => $sucursales
                            ]
                        )
                            ->setPaper('letter')
                            ->setOption('margin-top', '10mm')
                            ->setOption('margin-bottom', '10mm')
                            ->setOption('margin-left', '10mm')
                            ->setOption('margin-right', '10mm');
                        return $snap->inline('reporte_venta_habitaciones.pdf');
                    } else {
                        $snap = SnappyPdf::loadView(
                            'report.ventas_habitaciones.printDetalle',
                            [
                                'inicio' => $r->inicio,
                                'fin'   => $r->fin,
                                'data'  => $data,
                                'vendedores' => $vendedoresList,
                                'sucursales' => $sucursales
                            ]
                        )
                            ->setPaper('letter')
                            ->setOption('margin-top', '10mm')
                            ->setOption('margin-bottom', '10mm')
                            ->setOption('margin-left', '10mm')
                            ->setOption('margin-right', '10mm');
                        return $snap->inline('reporte_venta_habitaciones.pdf');
                    }
                    break;
                default:
                    throw new Exception('No se encontro la opcion seleccionada');
                    break;
            }
        } catch (\Throwable $th) {
            return throw $th;
            return redirect()->back()->with('message', 'Error: ' . $th->getMessage())->with('type', 'danger');
        }
    }

    public function getDataVentasHabitaciones($fecha_inicio, $fecha_fin, $sucursales = null, $vendedores = null)
    {
        $data = DB::table('ventas_habitaciones')
            ->WhereBetween('fecha', [$fecha_inicio, $fecha_fin]);

        if ($sucursales != null)
            $data = $data->where('sucursales_id', $sucursales);
        if ($vendedores != null)
            $data = $data->where(fn($q) => $q->whereIn('reserva_user_id', $vendedores)->orWhereIn('recepcion_user_id', $vendedores));
        return $data->get();
    }
    public function getUserVentas($v = null)
    {
        if ($v != null)
            return User::whereIn('id', $v)->get();

        return User::where('token', user_token::VENDEDOR)->get();
    }


    public function cajasTurnosForm(Request $r)
    {

        return view('cajas.reportes.turnos_diarios.form', [
            'cajas' => $this->getUserCajas(),

        ]);
    }

    public function getTurnoSection($turno)
    {
        if ($turno == null)
            return opcion_turnos::all();
        else
            return opcion_turnos::whereIn('id', $turno)->get();
    }
    public function cajasTurnosAcciones(Request $r)
    {

        $cajas = $r->cajas_id;

        $selectedCajas = [];
        if (is_array($cajas) && count($cajas) > 0) {
            foreach ($cajas as $v) {
                $c = Crypt::decryptString($v);
                if ($c == 0) {
                    $selectedCajas = null;
                    break;
                }
                array_push($selectedCajas, $c);
            }
        }

        $caja = $this->getUserCajas($selectedCajas);
        $dia = boolval($r->dia);
        $data = $this->getDataCierreUnion($caja, $r->fecha, $dia);
        switch ($r->opcion) {
            case 1:
                return view('cajas.reportes.turnos_diarios.preview', $data);
                break;
            case 2:
                $pdf = $this->getPDF();
                $pdf->loadView(
                    'cajas.reportes.turnos_diarios.print',
                    $data
                );
                $pdf->setPaper('letter', 'landscape');
                return $pdf->stream();
                break;
        }
    }
    public function getDataCierreUnion($caja, $fecha, $dia = false)
    {


        $turno = turnos::whereIn('cajas_id', $caja->pluck('id'));
        if (!$dia)
            $turno = $turno->whereDate("fecha", $fecha);

        if ($dia)
            $turno = $turno->whereDate('apertura', '<=', $fecha)
                ->whereDate('cierre', '>=', $fecha);


        $turno = $turno
            ->with('uapertura')
            ->with('opcion')
            ->get();


        if ($turno != null) {
            $anulaciones = anulacion_comprobantes::leftJoin('comprobantes', 'anulacion_comprobantes.comprobantes_id', 'comprobantes.id')
                ->whereIn('anulacion_comprobantes.turnos_id', $turno->pluck("id"))
                ->where('comprobantes.eliminado', false)
                ->get();
            $comprobante = comprobantes::with(["clientes", "users", "pagos", 'anulacion', 'dte'])
                ->whereIn('turnos_id', $turno->pluck("id"))
                ->where('eliminado', false);

            if (count($anulaciones) > 0)
                $comprobante = $comprobante->orWhereIn('id', $anulaciones->pluck('comprobantes_id'));

            $comprobante = $comprobante->orderBy('correlativo')->get();
            $anticipos = anticipos::with(['clientes', 'aplicado'])
                ->whereIn('turnos_id', $turno->pluck("id"))
                ->orderBy('id')
                ->get();
            $abonos = abonos::with(['clientes'])
                ->where('estado', true)
                ->where('eliminado', false)
                ->whereIn('turnos_id', $turno->pluck("id"))
                ->orderBy('id')
                ->get();

            $formas = forma_pagos::all();

            return [
                "comprobantesAll" => $comprobante,
                "forma_pagos" => $formas,
                "nforma" => $formas->count() ?? 0,
                "turnos" => $turno,
                'tipo_comprobante' => tipo_comprobantes::where('token', '!=', 7005)->get(),
                'anticiposAll' => $anticipos,
                'abonosAll' => $abonos,
                'dia' => $dia,
                'fecha' => $fecha

                #'dtes'=>dtes::whereIn('comprobantes_id',$comprobante->pluck('id'))->get(),
            ];
        }
        return null;
    }

    public function buscarCuentas(Request $r)
    {
        return view('cajas.cuentas_busqueda');
    }
    public function buscarCuentasResultado(Request $r)
    {
        try {
            $cuenta = null;
            switch ($r->tipo_cuenta) {
                case 1:
                    $cuenta = ordenes::find($r->id);
                    break;
                case 2:
                    $cuenta = recepciones::find($r->id);
                    break;
                case 3:
                    $cuenta = comandas::find($r->id);
                    break;
            }
            $registro = null;
            if ($cuenta != null) {
                $registro = registro::where('tipo_registros', $r->tipo_cuenta)->where('registro', $r->id)->first();
            } else throw new Exception('No se encontro ninguna cuenta con ese identificador, revise los parametros de bisqueda');

            return view('cajas.cuenta_resultado', ['p' => $cuenta, 'r' => $registro, 'tipo' => $r->tipo_cuenta]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Error: ' . $th->getMessage())->with('type', 'danger');
        }
    }

    public function estadoCuentas(Request $r)
    {
        try {
            $tipo = Crypt::decryptString($r->tipo);
            $id = Crypt::decryptString($r->id);
            $registro = registro::where('tipo_registros', $tipo)->where('registro', $id)->first();
            if (!isset($registro) || $registro->id == null  || $registro->count() == 0) {
                throw new Exception('No se encontro ninguna factura con los datos proporcionados');
            }
            (new RegistroController)->desactivarCuenta($id, $tipo);
            return redirect()->route('cajas.buscar_cuentas')->with('message', 'Se actualizo el estado de la cuenta');
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Error: ' . $th->getMessage())->with('type', 'danger');
        }
    }
}
