<?php

namespace App\Http\Controllers;

use App\Events\CajasEvent;
use App\Events\PedidosBar;
use App\Events\PedidosCocina;
use App\Events\ResponsePedidos;
use App\Http\Requests\mesasRequest;
use App\Http\Requests\ReportesPanelRequest;
use App\Http\Requests\StorecomandasRequest;
use App\Http\Requests\UpdatecomandasRequest;
use App\Mail\comandasMail;
use App\Models\anulaciones_detalle_comanda;
use App\Models\bodega_cajas;
use App\Models\bodegas;
use App\Models\cajas;
use App\Models\cajas_users;
use App\Models\clientes;
use App\Models\comanda_detalles;
use App\Models\comandas;
use App\Models\cortesias;
use App\Models\eventos;
use App\Models\opcion_turnos;
use App\Models\sucursales;
use App\Models\turnos;
use App\Models\User;
use App\Utils;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;
use Predis\Response\Status;

class ComandasController extends Controller
{
    private $table = 'comandas';

    public function __construct()
    {
        $this->getTh($this->table, 'Comandas');
    }
    /**
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $r)
    {
        try {
            $comandas = $this->getComandas();

            $comandas_detalle = null;
            $cortesias = null;
            $id = null;

            if (isset($r->id)) {
                $id = (int) Crypt::decryptString($r->id);
            } else {
                if ($comandas->count() > 0) {
                    $id = $comandas->first()->id;
                }
            }
            $comanda = null;
            if ($id != null) {
                $comanda = comandas::with(['clientes', 'usuarios'])->find($id);
                if (!$comanda->estado || $comanda->comprobante || $comanda->eliminada || $comanda->facturada || $comanda->anulada) {
                    return redirect()->route('comandas.index')->with('message', 'Esta comanda no esta disponible.')->with('type', 'danger');
                }

                $comandas_detalle = $comanda->detalles_comanda;
                if ($comanda->tipo_comanda == 3)
                    $cortesias = cortesias::where('origen', 3)->where('origen_id', $comanda->id)->with('titular')->first();
            }

            return view('comandas.index', [
                "comanda"           => $comanda,
                'comandas'          => $comandas,
                'cortesia'          => $cortesias,
                'comandas_detalle'  => $comandas_detalle,
                'bodegas'           => bodega_cajas::where("cajas_id", session('caja')->id)->with("bodegas")->get(),
            ]);
        } catch (\Throwable $th) {
            return 'Ocurrió un error al cargar esta comanda,(Tome una captura a esta pantalla y envié a informatica@tropicoinn.com.sv) error: ' . $th->getMessage();
        }
    }

    public function getComandas()
    {
        return comandas::with(['clientes', 'usuarios'])
            ->where('comprobante', false)
            ->where('estado', true)
            ->where('facturada', false)
            ->where('eliminada', false)
            ->where('cajas_id', session('caja')->id)
            ->where('turnos_id', session('turno')->id)
            ->orderBy('mesa', 'asc')
            ->get();
    }

    #---MOVIL---
    public function getComandasApp()
    {
        return view('app.comandas', [
            'comandas' => $this->getComandas(),
        ]);
    }
    public function validarSiComandaExiste(Request $r)
    { #Evento @change hace uso de este método
        try {
            #Se usa la misma validación en ambos métodos
            #Si el método retorna 'true', es porque ya existe una mesa con ese numero
            if ($this->validarRequestDeCrearComanda(intval($r->mesa)))
                throw new Exception('Ocupada');

            return response()->json(['status' => true, 'message' => 'Disponible']);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
    private function validarRequestDeCrearComanda($mesa)
    {
        #DECLARACIÓN DE VARIABLES
        $cajasId  = session('caja')->id;
        $turnosId = session('turno')->id;

        #VALIDACIÓN DE PARÁMETROS
        if ($mesa <= 0 || $mesa == null)
            throw new Exception('Ingrese un número válido');

        if ($cajasId <= 0 || $cajasId == null)
            throw new Exception('No se encontró el parámetro: caja');

        if ($turnosId <= 0 || $turnosId == null)
            throw new Exception('No se encontró el parámetro: turno');

        #LÓGICA
        #---Verificar si ya existe una mesa con el mismo numero
        $existeMesa = comandas::where('cajas_id', $cajasId)
            ->where('turnos_id', $turnosId)
            ->where('mesa', $mesa)
            ->where('estado', true)
            ->where('facturada', false)
            ->where('anulada', false)
            ->where('eliminada', false)
            ->first();

        return ($existeMesa != null) ? true : false; #Existe: true - No Existe: false
    }
    public function storeApp(Request $r)
    { #Evento @click hace uso de este metodo
        try {
            #DECLARACION DE VARIABLES
            $mesa = intval($r->mesa);

            #Si el metodo retorna 'true', es porque ya existe una mesa con ese numero
            if ($this->validarRequestDeCrearComanda($mesa))
                throw new Exception('Ocupada');

            #En caso que no exista una mesa, se procede a crearla
            $comandaCreada = $this->nuevaComanda($mesa);
            if ($comandaCreada == null)
                throw new Exception('No se pudo crear la mesa: ' . $mesa);

            return response()->json(['status' => true, 'comandaId' => Crypt::encryptString($comandaCreada->id), 'message' => 'Mesa creada correctamente.']);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
    public function getComandasProductosApp(Request $r)
    { #Recibe como parametro el id de la comanda para seleccionar todos los productos agregados a la misma
        $id      = null;
        $comanda = null;

        if (isset($r->id)) {
            $id = (int) (Crypt::decryptString($r->id));

            $comanda = comandas::with(['clientes', 'usuarios'])->find($id);
        }

        return view('app.comandas_productos', [
            'comanda' => $comanda,
            'bodegas' => bodega_cajas::where("cajas_id", session('caja')->id)->with("bodegas")->get(),
            'sucursal' => session('caja'),
        ]);
    }
    public function getComandaDetalleApp(Request $r)
    {
        return comanda_detalles::with(['comandas', 'precios', 'descuentos', 'user_comanda', 'user_acepta'])
            ->where('comandas_id', '=', Crypt::decryptString($r->id))
            ->orderBy('id', 'DESC')
            ->get();
    }
    public function comprobanteApp(Request $r)
    {
        try {
            $comandaId = Crypt::decryptString($r->id);
            if ($comandaId <= 0 || $comandaId == null)
                throw new Exception('No se encotró el parámetro: id');

            $comanda = comandas::find($comandaId);
            if (count($comanda->detalles_comanda) == 0)
                return throw new Exception('Esta comanda debe tener uno o más productos para poder solicitar el comprobante.');

            $comanda->comprobante = true;
            $comanda->save();

            broadcast(
                new CajasEvent(
                    session('caja')->id,
                    Auth::user()->name . ' solicita el comprobante de la comanda Nº. ' . $comanda->id . ' de la mesa # ' . $comanda->mesa,
                    1,
                    route('cobros.create', [
                        'origen' => Crypt::encryptString(3),
                        'origen_id' => Crypt::encryptString($comanda->id),
                        'tipo_comprobante' => Crypt::encryptString(7002),
                    ]),
                ),
            );

            return response()->json([
                'status' => true,
                'message' => 'Se solicitó el comprobante de la comanda Nº. ' . $comanda->id . ' en la mesa #' . $comanda->mesa,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
    #---MOVIL---

    public function productos(Request $r)
    {
        $txtBusqueda = $r->input('txtBusqueda');

        $productos = DB::table('getexistenciasbyproducto')
            ->where('producto_nombre', 'ilike', '%' . $txtBusqueda . '%')
            ->get();

        if (count($productos) > 0) {
            return response()->json([['productos' => $productos]]);
        } else {
            return response()->json([
                'message' => 'NO SE HA ENCONTRADO EL PRODUCTO',
            ]);
        }
    }
    public function getDetalle(Request $r)
    {
        try {
            $comanda = comandas::find(Crypt::decryptString($r->id));

            return response()->json([
                'comanda_detalle' => comanda_detalles::where('comandas_id', $comanda->id)
                    ->with('precios')
                    ->get(),
                'comanda' => $comanda,
            ]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Ocurrió un error al cargar el detalle de la comanda' . $th->getMessage()]);
        }
    }
    public function getComandaDetalle(Request $request)
    {
        try {
            $comandaId = $request->input('comandaId');
            $comandaDetalles = comanda_detalles::with(['comandas', 'precios', 'descuentos', 'user_comanda', 'user_acepta'])
                ->where('comandas_id', '=', $comandaId)
                ->orderBy('id', 'DESC')
                ->get();
            return response()->json(['comandaDetalles' => $comandaDetalles]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function comprobante($id)
    {
        try {
            $comanda = comandas::find(Crypt::decryptString($id));
            if (count($comanda->detalles_comanda) == 0) {
                return redirect()->back()->with('type', 'danger')->with('message', 'Esta comanda debe tener uno o mas productos para poder solicitar el comprobante.');
            }
            $comanda->comprobante = true;
            $comanda->save();
            broadcast(
                new CajasEvent(
                    session('caja')->id,
                    Auth::user()->name . ' solicita el comprobante de la comanda No. ' . $comanda->id . ' de la mesa #' . $comanda->mesa,
                    1,
                    route('cobros.create', [
                        'origen' => Crypt::encryptString(3),
                        'origen_id' => Crypt::encryptString($comanda->id),
                        'tipo_comprobante' => Crypt::encryptString(7002),
                    ]),
                ),
            );
            return redirect()
                ->route('comandas.index')
                ->with('message', 'Se solicito el comprobante de la comanda No. ' . $comanda->id . ' en la mesa #' . $comanda->mesa);
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('type', 'danger')
                ->with('message', 'Ocurrió un error al solicitar el comprobante, actualice y vuelva a intentar. Error: ' . $th->getMessage());
        }
    }
    public function bloquear($id)
    {
        try {
            $comanda = comandas::find(Crypt::decryptString($id));
            if (count($comanda->detalles_comanda) == 0) {
                return redirect()->back()->with('type', 'danger')->with('message', 'Esta comanda debe tener uno o mas productos para poder bloquearse.');
            }
            if ($comanda->comprobante == true) {
                return redirect()->back()->with('message', 'Esta comanda ya esta bloqueada')->with('type', 'danger');
            }
            $comanda->comprobante = true;
            $comanda->save();
            (new EventoCuentasController())->montoCuentas($comanda->id, $comanda->sum_comanda, 3);
            return redirect()->back()->with('message', 'Se bloqueo la comanda Nº ' . $comanda->id)->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('type', 'danger')
                ->with('message', $th->getMessage());
        }
    }
    public function desbloquear(Request $r)
    {
        $comanda = comandas::find(Crypt::decryptString($r->id));
        $comanda->comprobante = false;
        $comanda->save();

        return redirect()
            ->back()
            ->with('message', 'Se desbloqueo la comanda Nº' . $comanda->id . ' de la mesa #' . $comanda->mesa);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorecomandasRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(mesasRequest $r)
    {
        $ruta = 'comandas.index';
        //Validación de request


        try {

            //Conversion de datos

            $mesa  = intval($r->mesa);
            if (!$mesa || $mesa <= 0)
                return throw new Exception('message', 'El numero de mesa debe ser mayor a 0');

            /**
             * Refactorización de codigo, se creo la función para mayor reusabilidad
             */
            $this->getValidarComanda($mesa);



            //Creación de comanda
            $p = $this->nuevaComanda($mesa);

            return to_route($ruta, [
                'id' => Crypt::encryptString($p->id),
            ]);
        } catch (\Throwable $th) {
            return to_route($this->table . '.index')
                ->with('message',  $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     *
     * Validación de existencia de mesas
     *
     * @param int $caja
     * @param int $turno
     * @param int $mesa
     * @return comandas
     */
    public function getValidarComanda($mesa)
    {
        try {
            $mesaValida = $this->getComandaInterface($mesa)->count();

            if ($mesaValida > 0)
                throw new Exception('Ya existe una comanda con este numero de mesa.');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getComandaInterface($mesa)
    {
        try {
            $caja  = intval(session('caja')->id);
            $turno = intval(session('turno')->id);

            return comandas::where('cajas_id', $caja)
                ->where('turnos_id', $turno)
                ->where('mesa', $mesa)
                ->where('estado', true)
                ->where('facturada', false)
                ->where('anulada', false)
                ->where('eliminada', false)
                ->where('comprobante', false);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function nuevaComanda($mesa, $tipo_comanda = null, $cliente = null, $titular = null)
    {
        try {

            $p = new comandas();
            $p->fecha = date('Y-m-d');
            $p->turnos_id = session('turno')->id;
            $p->cajas_id = session('caja')->id;
            $p->users_id = Auth::id();
            $p->mesa = $mesa;
            $p->tipo_comanda = $tipo_comanda !== null ? $tipo_comanda : 1;
            //En el caso de crear una comanda con cliente deben enviarse tres parámetros
            if ($cliente != null)
                $p->clientes_id = $cliente;
            if ($titular != null)
                $p->titular = $titular;
            $p->save();

            return $p;
        } catch (\Throwable $th) {
            throw new Exception('Error al crear una nueva comanda detalle [' . $th->getMessage() . ']');
        }
    }

    //***funcion para confirmar creacion de comanda desde evento */
    public function comandaEvento(Request $r)
    {
        try {
            $eventoId = Crypt::decryptString($r->input('eventos_id'));
            $e_id = (int) $eventoId;
            $e = eventos::find($e_id);
            if (!$e->id || $e->id <= 0)
                return redirect()->back()
                    ->with('message', 'Debe ingresar un numero de mesa valido ')
                    ->with('type', 'danger');
            $mesa_comanda = (int) $r->input('mesa_comanda');
            if (!$mesa_comanda || $mesa_comanda <= 0)
                return redirect()->back()
                    ->with('message', 'Debe ingresar un numero de mesa valido ')
                    ->with('type', 'danger');

            $p = new comandas();
            $p->fecha = date('Y-m-d');
            $p->cajas_id = session('caja')->id;
            $p->tipo_comanda = 5;
            $p->users_id = Auth::id();
            $p->mesa = $mesa_comanda;
            $p->clientes_id = $e->clientes_id ?? null;
            $p->titular = $e->titular ?? null;
            $p->save();
            (new EventoCuentasController())->eventoCuentas($p->id, $eventoId, 3);
            return redirect()->route('eventos.comandasbyEvento', [
                'id' => Crypt::encryptString($p->id),
                'eventoId' => $eventoId
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\comandas  $comandas
     * @return \Illuminate\Http\Response
     */
    public function show(comandas $comandas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\comandas  $comandas
     * @return \Illuminate\Http\Response
     */
    public function edit(comandas $comandas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatecomandasRequest  $request
     * @param  \App\Models\comandas  $comandas
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatecomandasRequest $request, comandas $comandas)
    {
        //
    }

    public function confirm(Request $r)
    {
        $comanda = comandas::findOrFail(Crypt::decryptString($r->id));
        if (
            comanda_detalles::where('comandas_id', $comanda->id)
            ->where('anulado', false)
            ->count() > 0
        ) {
            return redirect()->back()->with('message', 'No se puede eliminar esta comanda, porque contiene productos.');
        }
        return view('confirm', [
            'th' => $this->th['confirm'],
            'p' => $comanda,
        ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\comandas  $comandas
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            $id = Crypt::decryptString($r->id);
            if (comanda_detalles::where('comandas_id', $id)->where('anulado', false)->count() > 0) {
                return redirect()->back()->with('message', 'No se puede eliminar esta comanda, porque contiene productos.');
            }
            $p = comandas::find($id);
            $p->eliminada = true;
            $p->save();
            return redirect()
                ->route('comandas.index')
                ->with('message', 'Se elimino correctamente la comanda #' . $p->id);
        } catch (\Throwable $th) {
            return redirect()
                ->route('comandas.index')
                ->with('message', 'Ocurrio un error al eliminar ' . $th->getMessage());
        }
    }

    public function clientes(Request $r)
    {
        $p = comandas::find(Crypt::decryptString($r->comanda));

        //return $p;

        if ($r->asignacion == 1) {
            $c = clientes::find($r->clientes_id);
            if (!$c->estado) {
                return redirect()->back()->with('message', 'Este cliente esta desactivado, debe agregar un cliente activo.')->with('type', 'danger');
            }
            $p->clientes_id = $c->id;
        } elseif ($r->asignacion == 2) {
            $p->titular = $r->titular;
        }
        $p->save();

        return redirect()
            ->back()
            ->with('message', 'Se agrego el ' . ($r->asignacion == 1 ? 'cliente.' : 'titular.'));
    }

    public function tipoComanda(Request $r)
    {
        $comanda = comandas::findOrFail(Crypt::decryptString($r->id));

        try {
            $tipo = Crypt::decryptString($r->tipo);
            //Validación por cache en navegador
            if (!$comanda->estado || $comanda->facturada || $comanda->eliminada || $comanda->anulada)
                return throw new Exception('La cuenta no puede cambiarse, porque esta desactivada');

            //Borrado de cortesía
            if ($comanda->tipo_comanda == 3 && $tipo != 3)
                cortesias::where('origen', 3)->where('origen_id', $comanda->id)->delete();

            //Cambio del tipo de comandas
            $comanda->tipo_comanda = $tipo;
            $comanda->save();

            return redirect()->back()->with('message', 'Se cambio el tipo de comanda');
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Error:' . $th->getMessage())->with('type', 'danger');
        }
    }

    public function precio(Request $r)
    {
        try {
            $r->validate(['id' => ['required', 'string'], 'precio' => ['required', 'numeric'], 'propina' => ['nullable', 'boolean'],]);
            $id = Crypt::decryptString($r->id);
            $precio = $r->precio;

            $comanda_detalle = comanda_detalles::findOrFail($id);
            $comanda_detalle->precio = $precio;
            if (isset($r->propina))
                $comanda_detalle->propina = $r->propina;
            $comanda_detalle->save();

            return response()->json(['status' => true]);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->errors(), 'status' => false]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Ocurrió un error: ' . $th->getMessage()]);
        }
    }

    public function produccion(Request $r)
    {
        try {
            $comanda_detalles = $r->detalle;
            $count = comanda_detalles::whereIn('id', $r->detalle)->whereNull('solicitud')->whereNull('user_solicita_id')->count();
            if ($count == 0)
                return throw new Exception('Todos los productos seleccionados ya fueron solicitados.');

            if (Auth::user() == null || Auth::user()->id == null)
                return throw new Exception('Se perdio su sesión, vuelva a iniciar sesión.');

            $detalleCocina = array();
            $detalleBar = array();
            $detalle = array();
            foreach ($comanda_detalles as $d) {
                $p = comanda_detalles::find($d);

                if ($p->solicitud == null) {
                    $p->solicitud = now();
                    $p->user_solicita_id = Auth::user()->id;
                    $p->save();
                    array_push($detalle, $p->id);
                    if ($p->precios->categorias_precios->rubros != null)
                        switch ($p->precios->categorias_precios->rubros->token) {
                            case 12001:
                                array_push($detalleCocina, $p->id);
                                break;
                            case 12004:
                                array_push($detalleBar, $p->id);
                                break;
                        }
                }
            }
            if (count($detalleCocina) > 0)
                event(new PedidosCocina($detalleCocina, session('caja'), Auth::user()->name));

            if (count($detalleBar) > 0)
                event(new PedidosBar($detalleBar, session('caja'), Auth::user()->name));

            $list = array();
            if (count($detalle) > 0)
                $list = comanda_detalles::where('anulado', false)->whereIn('id', $detalle)->with(["precios", "lotes"])->get();

            return response()->json(['message' => "Se envio la solicitud del pedido.", 'status' => true, 'list' =>  $list]);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'status' => false]);
        }
    }

    public function getAllProduccionCocina(Request $r)
    {
        return response()->json(['list' => $this->getPedidos([12001])]);
    }

    public function getAllProduccionBar(Request $r)
    {
        return response()->json(['list' => $this->getPedidos([12004])]);
    }

    public function getPedidos(array $token)
    {

        $cajas = session('solicitudes');
        return comanda_detalles::leftJoin('precios', 'precios.id', 'comanda_detalles.precios_id')
            ->leftJoin('categorias_precios', 'categorias_precios.id', 'precios.categorias_precios_id')
            ->leftJoin('rubros', 'rubros.id', 'categorias_precios.rubros_id')
            ->leftJoin('comandas', 'comandas.id', 'comanda_detalles.comandas_id')
            ->whereIn('rubros.token', $token)
            ->whereNotNull('comanda_detalles.solicitud')
            ->whereNull('comanda_detalles.entregado')
            ->where('comandas.estado', true)
            ->where('comandas.anulada', false)
            ->where('comandas.eliminada', false)
            ->where('comanda_detalles.cancelado', false)
            ->where('comanda_detalles.anulado', false)
            ->whereIn('comandas.cajas_id', $cajas)
            ->orderBy('comanda_detalles.solicitud', 'DESC')
            ->with(['dprecio', 'comandawtcaja', 'user_solicita', 'user_asignado'])
            ->select(["comanda_detalles.*"])
            ->get();
    }
    public function panelProduccionCocina()
    {
        try {
            $users = cajas_users::where('cajas_id', session('caja')->id)
                ->whereHas('users', function ($query) {
                    $query->where('token', 3002);
                })->with('users')->get();
            return view('comandas.produccion', [
                'comanda_detalles' => $this->getPedidos([12001]),
                'empleados' => $users,
                'broadcast' => ['route' => "pedidos.cocina", 'listen' => "PedidosCocina"],
                'title' => 'SOLICITUDES A COCINA',
                'routeData' => route('comandas.produccion_cocina')
            ]);
        } catch (\Throwable $th) {
            return 'Ocurrió un error al cargar este panel de produccion, (Tome una captura a esta pantalla y envíe a informatica@tropicoinn.com.sv) error: ' . $th->getMessage();
        }
    }
    public function panelProduccionBar()
    {
        try {
            $users = cajas_users::where('cajas_id', session('caja')->id)
                ->whereHas('users', function ($query) {
                    $query->where('token', 3002);
                })->with('users')->get();
            return view('comandas.produccion', [
                'comanda_detalles' => $this->getPedidos([12004]),
                'empleados' => $users,
                'broadcast' => ['route' => "pedidos.bar", 'listen' => "PedidosBar"],
                'title' => 'SOLICITUDES A BAR',
                'routeData' => route('comandas.produccion_bar')
            ]);
        } catch (\Throwable $th) {
            return 'Ocurrió un error al cargar este panel de produccion, (Tome una captura a esta pantalla y envíe a informatica@tropicoinn.com.sv) error: ' . $th->getMessage();
        }
    }
    public function produccionTimer(Request $r)
    {
        try {
            $r->validate([
                'id' => ['required', 'string'],
                'empleado' => ['required', 'string'],
                'espera' => ['required', 'numeric'],
            ]);
            $id = Crypt::decryptString($r->id);
            $user = Crypt::decryptString($r->empleado);
            $time = Carbon::createFromTime(0, 0, 0);
            $espera = intval($r->espera);
            $producto = comanda_detalles::find($id);
            $empleado = User::find($user);
            if (!$espera || $espera < 0)
                throw new Exception('El tiempo asignado no es valido.');

            //Bloque de validación para mantenimientos asíncronos y evitar errores de cache por cambios no actualizados.
            if ($producto->cancelado)
                throw new Exception('Esta solicitud ya fue cancelada por otro usuario, actualice la pagina para que desaparezca.');

            if ($producto->espera != null)
                throw new Exception('Este pedido ya tiene un tiempo asignado: ' . $producto->espera);

            if ($producto->anulado)
                throw new Exception('Este pedido fue anulado, no se puede agregar tiempo');

            if ($empleado == null || $empleado->id == null)
                throw new Exception('Debe seleccionar el empleado asignado a este pedido');
            //Fin bloque de validaciones asíncronas


            $producto->users_acepta_id = Auth::user()->id; //Usuario que selecciona y acepta el pedido.
            $producto->users_asigna_id = $empleado->id; //Usuario asignado.
            $producto->espera = $time->addMinutes($r->espera)->format('H:i:s');
            $producto->aceptacion = Carbon::now();
            $producto->prioridad = $r->prioridad;
            $producto->save();

            $comanda = $producto->comandas;
            $producto->precios;
            $mensaje = "Se acepto el pedido " . strtoupper($producto->precios->detalle ?? 'sin nombre') . " de la mesa #" . $comanda->mesa . ". Aceptado por: " . strtoupper(Auth::user()->user);
            broadcast(new ResponsePedidos($comanda->cajas_id, $producto, Auth::user(),  $mensaje));


            return response()->json(['producto' => comanda_detalles::where('id', $producto->id)->with(['dprecio', 'comandawtcaja', 'user_solicita', 'user_asignado'])->get()]);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()]);
        }
    }

    public function produccionCompleto(Request $r)
    {
        try {
            $r->validate(['id' => ['required', 'string', 'max:256']]);
            $id = Crypt::decryptString($r->id);
            $detalle = comanda_detalles::find($id);
            if ($detalle->entregado != null || $detalle->cancelado || $detalle->anulado)
                return response()->json(['producto' => $detalle]);
            $time = date("Y-m-d H:i:s");
            $detalle->aceptacion = $detalle->aceptacion ?? $time;
            $detalle->entregado = $time;
            $detalle->users_acepta_id = $detalle->users_acepta_id ?? Auth::user()->id;
            $detalle->users_asigna_id = $detalle->users_asigna_id ?? Auth::user()->id;
            $detalle->save();

            $comanda = $detalle->comandas;
            $detalle->precios;
            $mensaje = "Pedido completo listo para entrega: " . strtoupper($detalle->precios->detalle ?? 'sin nombre') . " de la mesa #" . $comanda->mesa . ". Completado por: " . strtoupper(Auth::user()->user);
            broadcast(new ResponsePedidos($comanda->cajas_id, $detalle, Auth::user(), $mensaje));

            return response()->json(['producto' => comanda_detalles::where('id', $detalle->id)->with(['dprecio', 'comandawtcaja',])->first(), 'message' => $mensaje]);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()]);
        }
    }
    public function produccionNegado(Request $r)
    {
        try {
            $r->validate(['id' => ['required', 'string', 'max:256'], 'observacion' => ['required', 'string', 'max:255']]);
            $id = Crypt::decryptString($r->id);
            $detalle = comanda_detalles::find($id);
            if ($detalle->entregado != null || $detalle->cancelado)
                return response()->json(['producto' => $detalle]);
            $time = date("Y-m-d H:i:s");
            $detalle->aceptacion = $time;
            $detalle->observacion_negacion = $r->observacion;
            $detalle->cancelado = true;
            $detalle->users_acepta_id = Auth::user()->id;
            $detalle->save();

            $comanda = $detalle->comandas;
            $detalle->precios;
            $mensaje = "Pedido negado: " . strtoupper($detalle->precios->detalle ?? 'sin nombre') . " de la mesa #" . $comanda->mesa . ". Negado por: " . strtoupper(Auth::user()->user) . ", Observación: " . $detalle->observacion_negacion;
            broadcast(new ResponsePedidos($comanda->cajas_id, $detalle, Auth::user(), $mensaje));
            return response()->json(['producto' => $detalle, 'message' => $mensaje]);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()]);
        }
    }

    public function setMinutosExtra(Request $r)
    {
        try {

            $id = (int) Crypt::decryptString($r->id);
            $minutos = $r->minutos;
            if (!isset($id) || $id <= 0)
                throw new Exception('El identificador del producto seleccionado no es valido, recargue la pagina e intente de nuevo.');
            if (!isset($minutos) || $minutos <= 0)
                throw new Exception('Los minutos a agregar deben ser mayor a cero.');



            $p = comanda_detalles::find($id);
            if ($p->cancelado)
                throw new Exception('Esta solicitud ya fue cancelada por otro usuario, actualice la pagina para que desaparezca.');

            if ($p->espera == null)
                throw new Exception('Este pedido aun no tiene un tiempo de espera asignado');

            if ($p->entregado != null)
                throw new Exception('Este pedido ya fue entregado, no se puede agregar tiempo');

            if ($p->anulado)
                throw new Exception('Este pedido fue anulado, no se puede agregar tiempo');
            if ($p->incremento_tiempo != null)
                $time = Carbon::parse($p->incremento_tiempo)->addMinutes($minutos);
            else
                $time = Carbon::createFromTime(0, 0, 0)->addMinutes($minutos);

            $p->incremento_tiempo = $time->format('H:i:s');
            $p->save();

            $comanda = $p->comandas;
            $mensaje = "Se agregaron $p->incremento_tiempo minutos extras a: " . strtoupper($p->precios->detalle ?? 'sin nombre') . " de la mesa #" . $comanda->mesa . ". Por el usuario: " . strtoupper(Auth::user()->user);
            broadcast(new ResponsePedidos($comanda->cajas_id, $p, Auth::user(), $mensaje));

            return response()->json(['producto' => comanda_detalles::where('id', $p->id)->with(['dprecio', 'comandawtcaja', 'user_solicita', 'user_asignado'])->first()]);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()]);
        }
    }

    public function viewReporteCocina()
    {
        $fecha = date("Y-m-d");

        return view('report.produccion.pedidos', [
            'data' => $this->getPedidosData([12001], $fecha, $fecha),
            'title' => "Reporte de pedidos a cocina",
            'search' => route('comandas.produccion.search_reporte_cocina'),
            'inicio' => $fecha,
            'fin' => $fecha,
        ]);
    }
    public function viewReporteBar()
    {
        $fecha = date("Y-m-d");
        return view('report.produccion.pedidos', [
            'title' => "Reporte de pedidos a bar",
            'data' => $this->getPedidosData([12004], $fecha, $fecha),
            'search' => route('comandas.produccion.search_reporte_bar'),
            'inicio' => $fecha,
            'fin' => $fecha,
        ]);
    }
    public function searchReporteCocina(Request $r)
    {
        try {
            $r->validate([
                'inicio' => ['required', 'date'],
                'fin' => ['required', 'date']
            ]);
            $accion = Crypt::decryptString($r->accion);
            $data = [
                'title' => "Reporte de pedidos a cocina",
                "data" => $this->getPedidosData([12001], $r->inicio, $r->fin),
                'search' => route('comandas.produccion.search_reporte_cocina'),
                'inicio' => $r->inicio,
                'fin' => $r->fin,
                'caja' => session('caja')->caja
            ];
            if ($accion == 1)
                return view(
                    'report.produccion.pedidos',
                    $data
                );
            elseif ($accion == 2) {
                $pdf = (new Utils)->getPDF();
                $pdf->loadView('report.produccion.pedidos_pdf', $data);
                $pdf->setPaper('letter', 'landscape');

                return $pdf->stream();
            }
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function searchReporteBar(Request $r)
    {
        try {
            $r->validate([
                'inicio' => ['required', 'date'],
                'fin' => ['required', 'date']
            ]);
            $accion = Crypt::decryptString($r->accion);
            $data = [
                'title' => "Reporte de pedidos a bar",
                "data" => $this->getPedidosData([12004], $r->inicio, $r->fin),
                'search' => route('comandas.produccion.search_reporte_bar'),
                'inicio' => $r->inicio,
                'fin' => $r->fin,
                'caja' => session('caja')->caja
            ];
            if ($accion == 1)
                return view(
                    'report.produccion.pedidos',
                    $data
                );
            elseif ($accion == 2) {
                $pdf = (new Utils)->getPDF();
                $pdf->loadView('report.produccion.pedidos_pdf', $data);
                $pdf->setPaper('letter', 'landscape');

                return $pdf->stream();
            }
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    private function getPedidosData(array $token, $inicio = null, $fin = null)
    {
        if ($inicio == null)
            $inicio = date("Y-m-d");
        if ($fin == null)
            $fin = date("Y-m-d");
        $cajas = session('solicitudes');
        return comanda_detalles::leftJoin('precios', 'precios.id', 'comanda_detalles.precios_id')
            ->leftJoin('categorias_precios', 'categorias_precios.id', 'precios.categorias_precios_id')
            ->leftJoin('rubros', 'rubros.id', 'categorias_precios.rubros_id')
            ->leftJoin('comandas', 'comandas.id', 'comanda_detalles.comandas_id')
            ->whereIn('rubros.token', $token)
            ->whereIn('comandas.cajas_id', $cajas)
            ->whereDate('comanda_detalles.solicitud', ">=", $inicio)
            ->whereDate('comanda_detalles.solicitud', "<=", $fin)
            ->with(['dprecio', 'comandawtcaja', 'user_solicita', 'user_asignado', 'user_acepta'])
            ->select(["comanda_detalles.*"])
            ->orderBy('comanda_detalles.solicitud')
            ->get();
    }

    public function lock()
    {
        return view('app.lock');
    }
    private function getDataPedidos($tipoPedido)
    {
        $fecha = date("Y-m-d");
        $cajas = cajas_users::where('users_id', auth()->id())->get();
        $turnos = turnos::whereIn('cajas_id', $cajas->pluck('cajas_id'))->get();
        $cajasT = cajas_users::where('users_id', auth()->id())->whereIn('cajas_id', $turnos->pluck('cajas_id'))->get();
        $opcion_turnos = opcion_turnos::all();

        return [
            'fecha' => $fecha,
            'cajas' => $cajasT,
            'turnos' => $turnos,
            'opcion' => $opcion_turnos,
            'data' => $this->getPedidosDataTurnoCaja([$tipoPedido], $fecha, $fecha, null, []),
            'inicio' => $fecha,
            'fin' => $fecha,
        ];
    }

    public function produccionCocinaByCajas()
    {
        $datos = $this->getDataPedidos(12001);
        $datos['title'] = "Reporte de pedidos a cocina";
        $datos['search'] = route('cajas.getPedidoCocinaCaja');

        return view('report.panel_reportes.cocina', $datos);
    }
    public function produccionBarByCajas()
    {
        $datos = $this->getDataPedidos(12004);
        $datos['title'] = "Reporte de pedidos a bar";
        $datos['search'] = route('cajas.getPedidoBarCaja');

        return view('report.panel_reportes.cocina', $datos);
    }
    //para filtar por caja y turno cuando existan
    private function getPedidosDataTurnoCaja(array $token, $inicio = null, $fin = null, $comandasId = null, $cancelado = null, $caja = null,  $turnos = null)
    {
        $inicio = $inicio ?? date("Y-m-d");
        $fin = $fin ?? date("Y-m-d");
        return comanda_detalles::leftJoin('precios', 'precios.id', 'comanda_detalles.precios_id')
            ->leftJoin('categorias_precios', 'categorias_precios.id', 'precios.categorias_precios_id')
            ->leftJoin('rubros', 'rubros.id', 'categorias_precios.rubros_id')
            ->leftJoin('comandas', 'comandas.id', 'comanda_detalles.comandas_id')
            ->whereIn('rubros.token', $token)
            ->when($caja !== null, function ($query) use ($caja) {
                return $query->where('comandas.cajas_id', $caja);
            })
            ->when(!empty($turnos), function ($query) use ($turnos) {
                return $query->whereIn('comandas.turnos_id', $turnos);
            })
            ->whereDate('comanda_detalles.solicitud', ">=", $inicio)
            ->whereDate('comanda_detalles.solicitud', "<=", $fin)
            ->with(['dprecio', 'comandawtcaja', 'user_solicita', 'user_asignado', 'user_acepta'])
            ->when($comandasId, function ($query) use ($comandasId) {
                return $query->where('comanda_detalles.comandas_id', $comandasId);
            })
            ->when($cancelado !== null, function ($query) use ($cancelado) {
                return $query->where('comanda_detalles.cancelado', $cancelado);
            })
            ->select(["comanda_detalles.*"])
            ->orderBy('comanda_detalles.solicitud')
            ->get();
    }
    public function getPedidoByCajas(ReportesPanelRequest $r, $rubrosToken, $reporteTitle, $view, $route)
    {
        try {
            $opcion = $r->opcion ?? 1;
            $caja = cajas::find($r->cajas_id);
            $cajaId = $caja->id ?? null;
            $inicio = $r->inicio ?? date("Y-m-d");
            $fin = $r->fin ?? date("Y-m-d");
            $comandasId = $r->comandas_id ?? false;
            $cancelado = $r->cancelado ?? false;

            // Obtener las cajas asignadas al usuario actual
            $cajas = cajas_users::where('users_id', auth()->id())->get();

            // Obtener los turnos dentro del rango de fechas seleccionado
            $turnos = turnos::whereBetween('fecha', [$inicio, $fin])
                ->when($r->cajas_id != 0, function ($query) use ($r) {
                    return $query->where('cajas_id', $r->cajas_id);
                })
                ->when(!empty($r->turnos) && count($r->turnos) > 0, function ($query) use ($r) {
                    return $query->whereIn('opcion_turnos_id', $r->turnos);
                })
                ->get();
            $turnos = turnos::whereIn('cajas_id', $cajas->pluck('cajas_id'))->whereBetween('fecha', [$inicio, $fin])->get();
            $cajasT = cajas_users::where('users_id', auth()->id())->whereIn('cajas_id', $turnos->pluck('cajas_id'))->get();



            // Obtener los datos de los pedidos según los rubros, fechas, caja y turnos
            $data = $this->getPedidosDataTurnoCaja($rubrosToken, $inicio, $fin, $comandasId, $cancelado, $caja->id ?? null, $turnos->pluck('id') ?? null);

            // Preparar datos para la vista
            $opcion_turnos = opcion_turnos::all();
            $dataView = [
                'title' => $reporteTitle,
                'search' => route($route),
                'turnos' => $turnos,
                'data' => $data,
                'cajas' => $cajasT,
                'cajaId' => $cajaId,
                'caja' => $caja->caja ?? $cajaId,
                'inicio' => $inicio,
                'fin' => $fin,
                'comandasId' => $comandasId,
                'cancelado' => $cancelado,
                'turno_selected' => $r->turnos ?? [],
                'opcion' => $opcion_turnos,
            ];

            if ($opcion == 1) {
                return view($view, $dataView);
            } elseif ($opcion == 2) {

                $pdf = (new Utils)->getPDF();
                $pdf->loadView($view . '_pdf', $dataView);
                $pdf->setPaper('letter', 'landscape');
                return $pdf->stream();
            }
        } catch (\Throwable $th) {
            return throw $th;
        }
    }

    public function getPedidoCocinaByCajas(ReportesPanelRequest $r)
    {
        return $this->getPedidoByCajas($r, [12001], "Reporte de pedidos a cocina", 'report.panel_reportes.cocina', 'cajas.getPedidoCocinaCaja');
    }

    public function getPedidoBarByCajas(ReportesPanelRequest $r)
    {
        return $this->getPedidoByCajas($r, [12004], "Reporte de pedidos a bar", 'report.panel_reportes.cocina', 'cajas.getPedidoBarCaja');
    }

    public function reporteAnulaciones(Request $r)
    {
        return view('comandas.reportes.anulaciones.form', ['cajas' => $this->getUserCajas()]);
    }
    public function getUserCajas($caja = null)
    {
        if ($caja == null) {
            $userCajas = cajas_users::where('users_id', Auth::user()->id)->get();
            return cajas::whereIn('id', $userCajas->pluck('cajas_id'))->get();
        } else
            return cajas::whereIn('id', $caja)->get();
    }

    public function reporteAnulacionesAcciones(Request $r)
    {
        try {
            //code...

            $arrayCajas = $this->getArrayCajas($r->cajas_id);
            $cajas = $this->getUserCajas($arrayCajas);

            $data = anulaciones_detalle_comanda::whereBetween("created_at", [$r->inicio, $r->fin])->get();

            if ($data == null)
                throw new Exception("No se encontraron datos, verifique los parametros de busqueda ingresados");

            switch ($r->opcion) {
                case 1:

                    return view("comandas.reportes.anulaciones.preview", [
                        'cajas' => $cajas,
                        'data' => $data,
                        'inicio' => Carbon::parse($r->inicio),
                        'fin' => Carbon::parse($r->fin),
                    ]);
                    break;
                case 2:
                    $snap = SnappyPdf::loadView('comandas.reportes.anulaciones.print', [
                        'cajas' => $cajas,
                        'data' => $data,
                        'inicio' => Carbon::parse($r->inicio),
                        'fin' => Carbon::parse($r->fin),
                    ])
                        ->setPaper('letter')
                        ->setOption('margin-top', '10mm')
                        ->setOption('margin-bottom', '10mm')
                        ->setOption('margin-left', '10mm')
                        ->setOption('margin-right', '10mm');

                    return $snap->inline('reporte_de_anulaciones_comandas.pdf');
                    break;
                default:
                    # code...
                    break;
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Error: ' . $th->getMessage())->with('type', 'warning');
        }
    }
    /**
     * @param array $cajas_ids | Encriptados
     */
    public function getArrayCajas($cajas_ids)
    {
        $cajas = $cajas_ids;
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
        return $selectedCajas;
    }

    public function addCredito()
    {
        $comandas_credito = null;
        $comandas_reportes = null;

        $mover_credito = DB::table('comandas_activas_turno')
            ->where('clientes_id', '>', 0)
            ->where('credito', true)
            ->get();
        if (count($mover_credito) > 0) {
            $comandas_credito = comandas::whereIn('id', $mover_credito->pluck('id'))
                ->with(['clientes', 'cajas'])
                ->orderBy('clientes_id')
                ->get();

            comandas::whereIn('id', $mover_credito->pluck('id'))
                ->update(['tipo_comanda' => 3, 'comprobante' => true]);
        }

        $reportar = DB::table('comandas_activas_turno')
            ->whereNull('clientes_id')
            ->orWhere('credito', false)
            ->get();
        if (count($reportar) > 0) {
            $comandas_reportes = comandas::whereIn('id', $reportar->pluck('id'))
                ->with(['clientes', 'cajas'])
                ->get();
        }
        if (
            $comandas_credito != null ||
            $comandas_reportes != null
        )
            Mail::to('soporte@tropicoinn.com.sv')->cc(['auditoriainterna@tropicoinn.com.sv'])->queue(new comandasMail($comandas_credito, $comandas_reportes));
    }

    public function setCredito(Request $r)
    {
        $r->validate([
            'id' => ['required'],
            'confirm' => ['required', 'accepted']
        ]);
        try {
            $p = comandas::find(Crypt::decryptString($r->id));
            $p->tipo_comanda = 3;
            $p->save();
            return redirect()
                ->back()
                ->with('message', 'Comanda ' . $p->id . ' movida a credito');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }


    public function reporteCreditos(Request $r)
    {
        return view('comandas.reportes.creditos.form', ['cajas' => $this->getUserCajas()]);
    }


    public function reporteCreditosAcciones(Request $r)
    {
        try {
            $arrayCajas = $this->getArrayCajas($r->cajas_id);
            $cajas = $this->getUserCajas($arrayCajas);

            $data = comandas::where('estado', true)
                ->where('facturada', false)
                ->where('eliminada', false)
                ->where('tipo_comanda', 3)
                ->whereIn('cajas_id', $cajas->pluck('id'))
                ->orderBy("fecha")
                ->get();

            if ($data == null)
                throw new Exception("No se encontraron datos");

            switch ($r->opcion) {
                case 1:

                    return view("comandas.reportes.creditos.preview", [
                        'cajas' => $cajas,
                        'data' => $data,
                    ]);
                    break;
                case 2:
                    $snap = SnappyPdf::loadView('comandas.reportes.creditos.print', [
                        'cajas' => $cajas,
                        'data' => $data,
                        'inicio' => Carbon::parse($r->inicio),
                        'fin' => Carbon::parse($r->fin),
                    ])
                        ->setPaper('letter')
                        ->setOrientation('landscape')
                        ->setOption('margin-top', '10mm')
                        ->setOption('margin-bottom', '10mm')
                        ->setOption('margin-left', '10mm')
                        ->setOption('margin-right', '10mm');

                    return $snap->inline('reporte_de_comandas_creditos.pdf');
                    break;
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Error: ' . $th->getMessage())->with('type', 'warning');
        }
    }
}
