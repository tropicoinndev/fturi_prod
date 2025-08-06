<?php

namespace App\Http\Controllers;

use App\Exports\ViewToExcel;
use App\Http\Requests\Storeajustes_inventarioRequest;
use App\Http\Requests\Updateajustes_inventarioRequest;
use App\Models\ajustes_existencias;
use App\Models\ajustes_inventario;
use App\Models\bodega_cajas;
use App\Models\bodega_users;
use App\Models\bodegas;
use App\Models\cajas;

#Add
use App\Models\existencias;
use App\Models\lotes;
use App\Models\requisicion_detalles;
use App\Models\User;
use App\Utils;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use function PHPSTORM_META\type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

use Throwable;

class AjustesInventarioController extends Controller
{
    private $table = 'ajustes_inventarios';

    public function __construct()
    {
        $this->getTh($this->table, 'Historial Ajustes de inventario');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { #Dashboard
        return view($this->table . '.index', [
            'p' => ajustes_inventario::with(['userSolicitante', 'userRealiza', 'userAutoriza'])
                ->orderBy('id', 'desc')
                ->get(),
        ]);
    }

    public function solicitados()
    {
        return view($this->table . '.solicitados', [
            'p' => ajustes_inventario::with(['userSolicitante', 'userRealiza', 'userAutoriza'])
                ->where('solicitado', false)
                ->where('autorizado', false)
                ->orderBy('id', 'desc')
                ->paginate(15),
        ]);
    }

    public function negados()
    {
        return view($this->table . '.negados', [
            'p' => ajustes_inventario::with(['userSolicitante', 'userRealiza', 'userAutoriza'])
                ->where('solicitado', false)
                ->where('autorizado', false)
                ->orderBy('id', 'desc')
                ->paginate(15),
        ]);
    }

    public function autorizar()
    {
        $p = ajustes_inventario::with(['userSolicitante', 'userRealiza', 'userAutoriza', 'ajustesExistencias'=>function($q){
            $q->with(['existencias'=>function($qq){
                $qq->with(['productosExistencias','bodegas']);
            }]);
        }])
            ->where('solicitado', true)
            ->where('autorizado', false)
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view($this->table.'.autorizar',[
            'p'=>$p,
        ]);
    }

    public function historial()
    {
        return view($this->table . '.historial', [
            'p' => ajustes_inventario::with(['userSolicitante', 'userRealiza', 'userAutoriza'])
                ->where('solicitado', false)
                ->where('autorizado', true)
                ->orderBy('updated_at', 'desc')
                ->paginate(20),
        ]);
    }

    public function search(Request $r)
    {
        return view($this->table . '.index', [
            'th'   => $this->th['index'],
            'table' => $this->table,
            'p'    => ajustes_inventario::where('observacion', 'ilike', '%' . $r->txtBusqueda . '%')
                #->whereDate('fecha_proceso', '=', $r->txtBusqueda)
                ->paginate(15),
            'txtBusqueda' => $r->txtBusqueda,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $usuarios = User::whereIn('id', function ($u) {
            $u->select('users_id')
                ->from('bodega_users');
        })
        ->orderBy('name','asc')
        ->get();

        return view($this->table.'.create',[
            'usuarios'=>$usuarios,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storeajustes_inventarioRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $r)
    {
        try {
            $ajusteInv = new ajustes_inventario();
            $ajusteInv->observacion          = $r->observacion;
            $ajusteInv->fecha_proceso        = date('Y-m-d');
            $ajusteInv->solicitante_users_id = intval(Crypt::decryptString($r->solicitanteUsersId));
            $ajusteInv->realiza_users_id     = auth()->user()->id;
            $ajusteInv->autoriza_users_id    = null;
            $ajusteInv->estado               = true;
            $ajusteInv->solicitado           = false;
            $ajusteInv->autorizado           = false;
            $ajusteInv->save();

            return to_route('solicitud.detalle', [
                'id' => $ajusteInv->cid,
            ]);
        } catch (Throwable $th) {
            return redirect()->back()
                ->with('type', 'danger')
                ->with('message', 'Error al crear la solicitud: ' . $th->getMessage());
        }
    }
    public function detalle($id)
    {
        $usuarios = User::whereIn('id', function ($u) {
            $u->select('users_id')
                ->from('bodega_users');
        })
            ->orderBy('name', 'asc')
            ->get();

        $ajusteInv = ajustes_inventario::with(['userSolicitante', 'userRealiza', 'userAutoriza'])->find(Crypt::decryptString($id));

        return view($this->table . '.detalle', [
            'ajusteInv' => $ajusteInv,
            'ajusteExi' => ajustes_existencias::with(['existencias'=>function($q){
                    $q->with(['productosExistencias','bodegas']);
                }, 'userRealiza', 'ajusInventario'])->where('ajustes_inventarios_id', $ajusteInv->id)->orderBy('id', 'desc')->get(),
            'usuarios' => $usuarios,
            'existencias' => existencias::with(['bodegas', 'productosExistencias', 'requisicionExistencias'])
                ->where('estado', true)
                ->orderBy('id', 'desc')
                ->get(),
                
        ]);
    }
    public function autorizarDetalle($id)
    {
        $ajusteInv = ajustes_inventario::with(['userSolicitante', 'userRealiza', 'userAutoriza'])->find(Crypt::decryptString($id));

        return view($this->table . '.autorizarDetalle', [
            'ajusteInv' => $ajusteInv,
        ]);
    }
    public function saveSolicitud(Request $r)
    {
        try {
            #---Asignacion de datos POST a variables---
            $loteId             = intval($r->loteId);
            $cantExistencias    = intval($r->cantExistencias);
            $accion             = intval(Crypt::decryptString($r->accion));
            $ajusteInventarioId = intval(Crypt::decryptString($r->ajusteInventarioId));

            if ($loteId <= 0 || $loteId === null)
                throw new Exception('No se encontró el parámetro: numero lote.');
            if ($cantExistencias <= 0 || $cantExistencias === null)
                throw new Exception('No se encontró el parámetro: cantidad existencias.');
            if ($accion <= 0 || $accion === null)
                throw new Exception('No se encontró el parámetro: accion.');
            #------------------------------------------

            $ajusteExi = new ajustes_existencias();
            $ajusteExi->existencias_id = $loteId; #1
            $ajusteExi->accion         = $accion; #3
            $ajusteExi->cantidad       = $cantExistencias; #2
            $ajusteExi->users_id       = auth()->user()->id;
            $ajusteExi->estado         = true;
            $ajusteExi->ajustes_inventarios_id = $ajusteInventarioId;
            $ajusteExi->save();

            return response()->json([
                'status' => true,
                'message' => 'Se guardo su solicitud con exito.',
                'ajusteExi' => ajustes_existencias::with(['existencias'=>function($q){
                    $q->with(['productosExistencias','bodegas']);
                }, 'userRealiza', 'ajusInventario'])->where('ajustes_inventarios_id', $ajusteInventarioId)->orderBy('id', 'desc')->get(),
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $th->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ajustes_inventario  $ajustes_inventario
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $p = ajustes_existencias::with(['existencias', 'userRealiza', 'ajusInventario'])->where('ajustes_inventarios_id', Crypt::decryptString($id))->first();

        return view($this->table . '.show', [
            'p' => $p,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ajustes_inventario  $ajustes_inventario
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ajusteInv = ajustes_inventario::with(['userSolicitante', 'userRealiza', 'userAutoriza'])->find(Crypt::decryptString($id));
        $ajusteExi = ajustes_existencias::with(['existencias', 'userRealiza', 'ajusInventario'])->where('ajustes_inventarios_id', $ajusteInv->id)->first();

        return view($this->table . '.edit', [
            'ajusteInv' => $ajusteInv,
            'ajusteExi' => $ajusteExi,
            'usuarios' => User::orderBy('name', 'asc')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updateajustes_inventarioRequest  $request
     * @param  \App\Models\ajustes_inventario  $ajustes_inventario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $r)
    {
        try {
            $ajusteExiId = intval($r->ajusteExiId);
            $accion      = intval(Crypt::decryptString($r->accion));
            $catidad     = intval($r->cantidad);

            $ajusteExi = ajustes_existencias::find($ajusteExiId);
            $ajusteExi->accion = $accion;
            $ajusteExi->cantidad = $catidad;
            $ajusteExi->save();

            return redirect()->back()
                ->with('message', 'Lote #' . $ajusteExi->id . ' editado con exito.')
                ->with('type', 'success');
        } catch (Throwable $th) {
            return redirect()->back()
                ->with('message', 'Error al editar el lote: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function completarSolicitud(Request $r)
    {
        try {
            $ajusteInv = ajustes_inventario::find(Crypt::decryptString($r->ajusteInvId));

            $ajusteInv->solicitado = true;
            $ajusteInv->save();

            return redirect()->back()
                ->with('message', 'Solicitud completada con exito.')
                ->with('type', 'success');
        } catch (Throwable $th) {
            return redirect()->back()
                ->with('message', 'Error al completar la solicitud: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function findLote(Request $r)
    {
        try {
            #---Asignacion de datos POST a variables---
            $ajusteInv = intval(Crypt::decryptString($r->ajusteInv));
            $numLote = intval($r->numLote);

            if ($ajusteInv <= 0 || $ajusteInv === null)
                throw new Exception('No se encontró el parámetro: ajuste inventario id.');
            if ($numLote <= 0 || $numLote === null)
                throw new Exception('No se encontró el parámetro: numero lote.');
            #------------------------------------------

            #---Evitar agregar el mismo lote a la solicitud---
            $ajusteExi = ajustes_existencias::where('existencias_id', $numLote)->where('ajustes_inventarios_id', $ajusteInv)->first();

            if ($ajusteExi)
                throw new Exception('Este número de lote #' . $ajusteExi->existencias_id . ' ya fue agregado, por favor elija otro.');
            #-------------------------------------------------

            #---Encontrar lote solicitado---
            $lote = existencias::with(['bodegas', 'productosExistencias', 'requisicionExistencias'])->find($numLote);

            if (!$lote)
                throw new Exception('No fue posible encontrar el lote solicitado, intentelo de nuevo.');
            #-------------------------------

            return response()->json([
                'status' => true,
                'p' => $lote,
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $th->getMessage(),
            ]);
        }
    }

    /*public function updateLote(Request $r){
        try{
            #---Asignacion de datos POST a variables---
            $accion             = Crypt::decryptString($r->accion);
            $numLote            = intval($r->numLote);
            $cantExistencias    = intval($r->cantExistencias);
            $observacion        = $r->observacion;
            $solicitanteUsersId = Crypt::decryptString($r->solicitanteUsersId);
            $autorizaUsersId    = Crypt::decryptString($r->autorizaUsersId);

            if($accion === null)
                throw new Exception('No se encontró el parámetro: opcion.');
            if($numLote <= 0 || $numLote === null)
                throw new Exception('No se encontró el parámetro: numero lote.');
            if($cantExistencias <= 0 || $cantExistencias === null)
                throw new Exception('No se encontró el parámetro: cantidad existencias.');
            if($solicitanteUsersId <= 0 || $solicitanteUsersId === null)
                throw new Exception('No se encontró el parámetro: usuario solicitante.');
            if($autorizaUsersId <= 0 || $autorizaUsersId === null)
                throw new Exception('No se encontró el parámetro: usuario autoriza.');
            #------------------------------------------

            #---Encontrar registro solicitado---
            $lote = existencias::with(['bodegas','productosExistencias','requisicionExistencias'])->find($numLote);

            if(!$lote)
                throw new Exception('No fue posible encontrar el lote solicitado, intentelo de nuevo.');
            #-----------------------------------

            #---Logica: aumento o descarte de existencias---
            if($accion == 1){#Aumentar existencias
                $lote->existencia += $cantExistencias;
                $msj = 'aumentado';
            }
            else if($accion == 2){#Descartar existencias
                if($cantExistencias > $lote->existencia)
                    throw new Exception('La cantidad a descartar es mayor a la existencia actual.');

                $lote->existencia -= $cantExistencias;

                if($lote->existencia == 0)
                    $lote->estado = false;

                $msj = 'descartado';
            }
            else{
                throw new Exception('El parámetro: opcion no está disponible.');
            }
            #-----------------------------------------------

            if($lote->save()){
                #Insercion de datos
                $ajusteInv = new ajustes_inventario();
                $ajusteInv->observacion          = $observacion;
                $ajusteInv->fecha_proceso        = date('Y-m-d');
                $ajusteInv->solicitante_users_id = $solicitanteUsersId;
                $ajusteInv->realiza_users_id     = auth()->user()->id;
                $ajusteInv->autoriza_users_id    = $autorizaUsersId;
                $ajusteInv->estado               = true;
                $ajusteInv->solicitado           = false;
                $ajusteInv->autorizado           = false;
                $ajusteInv->save();

                $ajusteExi = new ajustes_existencias();
                $ajusteExi->existencias_id = $lote->id;
                $ajusteExi->accion         = $accion;
                $ajusteExi->cantidad       = number_format($cantExistencias,2);
                $ajusteExi->users_id       = auth()->user()->id;
                $ajusteExi->estado         = true;
                $ajusteExi->ajustes_inventarios_id = $ajusteInv->id;
                $ajusteExi->save();

                return response()->json([
                    'status' =>true,
                    'message'=>'Se ha '.$msj.' en '.$cantExistencias.' la existencia del lote #'.$lote->id,
                    'p'=>$lote,
                ]);
            }
            else{
                throw new Exception('No se fue posible ajustar la existencia de este lote.');
            }
        }
        catch(Throwable $th){
            return response()->json([
                'status' =>false,
                'message'=>'Error: '.$th->getMessage(),
            ]);
        }
    }*/

    public function editSolicitud(Request $r)
    {
        try {
            #---Asignacion de datos POST a variables---
            $loteId             = intval($r->loteId);
            $cantExistencias    = intval($r->cantExistencias);
            $accion             = intval($r->accion);
            $observacion        = $r->observacion;
            $solicitanteUsersId = intval(Crypt::decryptString($r->solicitanteUsersId));

            if ($loteId <= 0 || $loteId === null)
                throw new Exception('No se encontró el parámetro: numero lote.');
            if ($cantExistencias <= 0 || $cantExistencias === null)
                throw new Exception('No se encontró el parámetro: cantidad existencias.');
            if ($accion <= 0 || $accion === null)
                throw new Exception('No se encontró el parámetro: accion.');
            if ($solicitanteUsersId <= 0 || $solicitanteUsersId === null)
                throw new Exception('No se encontró el parámetro: usuario solicitante.');
            #------------------------------------------

            #---Encontrar registros---
            $ajusteExi = ajustes_existencias::with(['existencias', 'userRealiza', 'ajusInventario'])
                ->where('existencias_id', $loteId)
                ->first();

            if (!$ajusteExi)
                throw new Exception('No fué posible encontrar el ajuste de existencia.');

            $ajusteInv = ajustes_inventario::find($ajusteExi->ajustes_inventarios_id);

            if (!$ajusteInv)
                throw new Exception('No fué posible encontrar el ajuste de inventario.');
            #-------------------------

            $ajusteInv->observacion          = $observacion;
            $ajusteInv->fecha_proceso        = date('Y-m-d');
            $ajusteInv->solicitante_users_id = $solicitanteUsersId;
            $ajusteInv->realiza_users_id     = Auth::user()->id;
            $ajusteInv->autoriza_users_id    = null;
            $ajusteInv->estado               = true;
            $ajusteInv->solicitado           = true;
            $ajusteInv->autorizado           = false;
            $ajusteInv->save();

            $ajusteExi->existencias_id = $loteId;
            $ajusteExi->accion         = $accion;
            $ajusteExi->cantidad       = $cantExistencias;
            $ajusteExi->users_id       = auth()->user()->id;
            $ajusteExi->estado         = true;
            $ajusteExi->ajustes_inventarios_id = $ajusteInv->id;
            $ajusteExi->save();

            return response()->json([
                'status' => true,
                'message' => 'Se editó su solicitud con exito.',
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $th->getMessage(),
            ]);
        }
    }

    public function authAction(Request $r)
    {
        try {
            $opcion = intval($r->opcion); #Opcion: 1 = Autorizar, 2 = Negar

            $r->validate([
                'password' => ['required', 'string'],
                'confirm' => ['required', 'in:1'],
            ]);

            if (!Hash::check($r->password, Auth::user()->password))
                return redirect()->back()->with('type', 'danger')->with('message', 'Contraseña incorrecta.');

            #---Encontrar ajuste de inventario---
            $ajusteInv = ajustes_inventario::find(Crypt::decryptString($r->ajusteInventarioId)); #Retorna un objeto

            if (!$ajusteInv)
                return redirect()->back()->with('type', 'danger')->with('message', 'No fué posible encontrar el registro solicitado.');

            #---Encontrar todos los ajustes de existencias, segun el id ajuste de inventario
            $ajusteExi = ajustes_existencias::where('ajustes_inventarios_id', $ajusteInv->id)->get(); #Retorna una coleccion
            #-----------------------------------

            if ($opcion === 1) { #Autorizar
                foreach ($ajusteExi as $ae) {
                    $lote = existencias::with(['bodegas', 'productosExistencias', 'requisicionExistencias'])->find($ae->existencias_id);

                    #---Logica: aumento o descarte de existencias---
                    if ($ae->accion === 1) { #Aumentar existencias
                        $lote->estado = true;
                        $lote->existencia += $ae->cantidad;
                    } else if ($ae->accion === 2) { #Descartar existencias
                        if ($ae->cantidad > $lote->existencia)
                            throw new Exception('La cantidad a descartar es mayor a la existencia actual.');

                        $lote->existencia -= $ae->cantidad;

                        if ($lote->existencia === 0)
                            $lote->estado = false;
                    } else {
                        throw new Exception('El parámetro: opcion no está disponible.');
                    }

                    $lote->save();
                    #-----------------------------------------------
                }
                $msj = 'Ajuste de inventario realizado con exito.';
            } else {
                $msj = 'Ajuste de inventario negado.';
            }

            #Esto siempre debe de ocurrir, ya sea si se autorizó o se negó un ajuste
            $ajusteInv->observacion = $ajusteInv->observacion . ' -> ' . $r->observacion;
            $ajusteInv->autoriza_users_id = Auth::user()->id;
            $ajusteInv->solicitado = false;
            $ajusteInv->autorizado = $opcion === 1 ? true : false;
            $ajusteInv->save();

            return to_route($this->table . '.historial')->with('type', 'success')->with('message', $msj);
        } catch (Throwable $th) {
            return redirect()->back()->with('type', 'danger')->with('message', 'Error: ' . $th->getMessage());
        }
    }

    public function reporte()
    {
        return view($this->table . '.reporte', [
            'bodegas' => bodegas::orderBy('bodega', 'asc')->get(),
            'usuarios' => User::orderBy('name', 'asc')->get(),
        ]);
    }

    public function reporteOpcion(Request $r)
    {
        $bodegaId     = $r->bodegaId;
        $usuarioId    = $r->usuarioId;
        $fechaProceso = $r->fechaProceso;
        $opcion       = intval(Crypt::decryptString($r->opcion));

        $ajusteInv = ajustes_inventario::where('solicitante_users_id', $usuarioId)
            ->where('fecha_proceso', $fechaProceso)
            ->first(); #Retorna un solo objeto

        if (!$ajusteInv)
            return redirect()->back()->with('type', 'danger')->with('message', 'No fue posible encontrar el ajuste de inventario solicitado.');

        /*$ajusteExi = DB::table('ajustes_existencias')
            ->leftJoin('ajustes_inventarios','ajustes_existencias.ajustes_inventarios_id','=','ajustes_inventarios.id')
            ->leftJoin('existencias','ajustes_existencias.id','=','existencias.id')
            ->leftJoin('bodegas','existencias.bodegas_id','=','bodegas.id')
            ->leftJoin('productos','existencias.productos_id','=','productos.id')
            ->leftJoin('users as user_solicitante','ajustes_inventarios.solicitante_users_id','=','user_solicitante.id')
            ->leftJoin('users as user_realiza','ajustes_inventarios.realiza_users_id','=','user_realiza.id')
            ->leftJoin('users as user_autoriza','ajustes_inventarios.autoriza_users_id','=','user_autoriza.id')
            ->select(
                'ajustes_inventarios.*',
                'ajustes_existencias.*',
                'existencias.*',
                'bodegas.*',
                'productos.*',
                'user_solicitante.name as user_solicitante',
                'user_realiza.name as user_realiza',
                'user_autoriza.name as user_autoriza',
            )
            ->where('ajustes_existencias.ajustes_inventarios_id', $ajusteInv->id)
            ->where('existencias.bodegas_id', $bodegaId)
            ->whereDate('fecha_proceso',$fechaProceso)
            ->get();*/

        $ajusteExi = DB::table('getreportehistorialajuste')
            ->where('ajustes_inventarios_id', $ajusteInv->id)
            ->where('user_solicitante_id', $usuarioId)
            ->where('bodegas_id', $bodegaId)
            ->where('fecha_proceso', $fechaProceso)
            ->get();

        switch ($opcion) {
            case 1: #Buscar
                return view($this->table . '.reporte', [
                    'usuarios' => User::orderBy('name', 'asc')->get(),
                    'usuarioId' => $usuarioId,
                    'bodegas' => bodegas::orderBy('bodega', 'asc')->get(),
                    'bodegaId' => $bodegaId,
                    'fechaProceso' => $fechaProceso,
                    'data' => $ajusteExi,
                ]);
                break;
            case 2: #PDF
                $pdf = Utils::getPdf();

                $pdf->loadView($this->table . '.reporte_print', [
                    'data' => $ajusteExi,
                ]);

                $pdf->setPaper('letter', 'landscape');
                return $pdf->stream();
                break;
            case 3: #Excel
                $view = view($this->table . '.reporte_excel', [
                    'data' => $ajusteExi,
                ]);

                $fileName = 'reporte_ajustes_inventarios_' . date('Y-m-d_H:i:s') . '.xlsx';

                $rs = Excel::download(new ViewToExcel($view), $fileName, \Maatwebsite\Excel\Excel::XLSX);
                ob_end_clean();

                return $rs;
                break;
            default:
                return to_route($this->table . '.reporte')
                    ->with('type', 'danger')
                    ->with('message', 'Opcion no encontrada.');
        }
    }

    public function imprimirActa($id)
    {
        $ajusteInv = ajustes_inventario::with(['userSolicitante', 'userRealiza', 'userAutoriza'])
            ->find(Crypt::decryptString($id)); #Retorna un solo objeto

        $ajusteExi = ajustes_existencias::with(['existencias' => function ($q) {
            $q->with(['bodegas', 'productosExistencias', 'requisicionExistencias']);
        }, 'userRealiza', 'ajusInventario'])
            ->where('ajustes_inventarios_id', $ajusteInv->id)
            ->get(); #Retorna una coleccion de objetos

        /*$existencias = existencias::with(['bodegas','productosExistencias','requisicionExistencias'])->find($ajusteExi->existencias->id);

        $bc = bodega_cajas::with(['bodegas'])->where('bodegas_id',$existencias->bodegas_id)->first();
        $suc = cajas::with(['Sucursales'])->find($bc->cajas_id);*/
        #---

        $pdf = Utils::getPdf();

        $pdf->loadView($this->table . '.print_acta', [
            'ajusteInv' => $ajusteInv,
            'ajusteExi' => $ajusteExi,
            #'existencias'=>$existencias,
            #'sucursal'=>$suc->sucursales->sucursal,
        ]);

        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream();
    }

    public function reporteExistenciasByBodega()
    {
        return view($this->table . '.existencia_bodega', [
            'bodegas' => bodegas::orderBy('bodega', 'asc')->get(),
        ]);
    }
    public function reporteExistenciasByProducto()
    {
        return view($this->table . '.reporte_existencias', [
            'bodegas' => bodegas::orderBy('bodega', 'asc')->get(),
        ]);
    }
    public function reportExisByBodegaSearch(Request $r)
    {

        $productoId = $r->productoId ?? null;
        $bodegaId   = $r->bodegaId;
        $opcion     = intval(Crypt::decryptString($r->opcion));

        switch ($opcion) {
            case 1: #Buscar
                $existencias = existencias::with(['productosExistencias', 'bodegasExistencias', 'requisicionExistencias'])
                    ->where('bodegas_id', $bodegaId)
                    ->where('estado', true)
                    ->orderBy('id', 'desc');

                if (!isset($productoId)) {
                    $existencias = $existencias->get();
                    $view = $this->table . '.existencia_bodega';
                } else {
                    $existencias = $existencias->where('productos_id', $productoId)->get();
                    $view = $this->table . '.reporte_existencias';
                }

                $reporteExistencias = [];

                foreach ($existencias as $existencia) {
                    $requisicionDetalle = requisicion_detalles::find($existencia->requisicion_detalles_id);

                    $reporteExistencias[$existencia->id] = [
                        'existencia' => $existencia,
                        'requisicion_detalles_id' => $requisicionDetalle,
                    ];
                }

                return view($view, [
                    'bodegas' => bodegas::orderBy('bodega', 'asc')->get(),
                    'bodegaId' => $bodegaId,
                    'reporteExistencias' => $reporteExistencias,
                ]);
                break;
            case 2: #Reporte PDF en Frame
                return $this->getReporteBodegaExistenciaPDF($r);
                break;
            default:
                return to_route($this->table . '.reporte')
                    ->with('type', 'danger')
                    ->with('message', 'Opcion no encontrada.');
        }
    }

    private function getReporteBodegaExistenciaPDF($r)
    {

        $usuarioLogueado = Auth::user();

        $bodega = bodega_users::with(['relacionUsuarios', 'relacionBodegas'])
            ->where('users_id', $usuarioLogueado->id)
            ->orderBy('id', 'DESC')
            ->get();
        $bodegaId = $r->bodegaId;
        $producto = $r->productoId;
        $bodega = bodegas::find($r->bodega);
        $existencias = $this->getExistencias($bodegaId, $producto);


        $detalles = requisicion_detalles::with(['relacionProductos', 'relacionUsuarios', 'relacionLotes', 'relacionExistencias', 'relacionRequisiciones'])
            ->orderBy('id', 'DESC')
            ->get();
        $reporteExistenciasBodega = [];
        foreach ($existencias as $existencia) {
            $requisicionDetalle = requisicion_detalles::find($existencia->requisicion_detalles_id);

            $reporteExistenciasBodega[$existencia->id] = [
                'existencia' => $existencia,
                'requisicion_detalles_id' => $requisicionDetalle,
                'detalles' => $detalles->where('requisiciones_id', $existencia->requisicion_detalles_id)->all(),
            ];
        }

        return Pdf::loadView('ajustes_inventarios.existencia_bodega_print', compact('bodegaId', 'reporteExistenciasBodega', 'bodega'))
            ->setPaper('letter', 'landscape')
            ->stream();
    }
    private function getExistencias($bodega, $producto)
    {
        return existencias::when($producto != 0, function ($query) use ($producto) {
            return $query->where('productos_id', $producto);
        })
            ->when(!empty($bodega) && $bodega > 0, function ($query) use ($bodega) {
                return $query->where('bodegas_id', $bodega);
            })
            ->get();
    }

    public function editSolicitante(Request $r)
    {
        try {
            #---Asignacion de datos POST a variables---
            $ajusteInventarioId    = intval(Crypt::decryptString($r->ajusteInventarioId));
            $solicitanteUsersId    = intval(Crypt::decryptString($r->solicitanteUsersId));
            $newSolicitanteUsersId = intval(Crypt::decryptString($r->newSolicitanteUsersId));

            if ($ajusteInventarioId <= 0 || $ajusteInventarioId === null)
                throw new Exception('No se encontró el parámetro: ajustes inventario id.');
            if ($solicitanteUsersId <= 0 || $solicitanteUsersId === null)
                throw new Exception('No se encontró el parámetro: solicitante user id.');
            if ($newSolicitanteUsersId <= 0 || $newSolicitanteUsersId === null)
                throw new Exception('No se encontró el parámetro: new solicitante user id.');
            #------------------------------------------

            #---Encontrar registros solicitados---
            $ai = ajustes_inventario::where('id', $ajusteInventarioId)
                ->where('solicitante_users_id', $solicitanteUsersId)
                ->where('estado', true)
                ->where('autorizado', false)
                ->first();

            if (!$ai)
                throw new Exception('No fue posible encontrar el registro solicitado.');
            #-------------------------------------

            $ai->solicitante_users_id = $newSolicitanteUsersId;
            $ai->save();

            return redirect()->back()
                ->with('message', 'Solicitante editado correctamente: ' . $ai->userSolicitante->name)
                ->with('type', 'success');
        } catch (Throwable $th) {
            return redirect()->back()
                ->with('message', 'Error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function confirm($id)
    {
        try {
            return view('confirm', [
                'th' => $this->th['confirm'],
                'p' => ajustes_existencias::findOrFail($id)
            ]);
        } catch (Throwable $th) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $th->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
    public function destroy(Request $r)
    {
        try {
            $p = ajustes_existencias::where('estado', true)->find(Crypt::decryptString($r->id));
            $p->delete();

            return to_route($this->table . '.solicitados')
                ->with('message', 'Ajuste de Existencia #' . $p->id . ' Eliminada con exito.')
                ->with('type', 'success');
        } catch (Throwable $th) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $th->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

    public function redisenoDte(){
        $pdf = Utils::getPdf();

        $data = [
            ['id'=>1], ['id'=>2], ['id'=>3], ['id'=>4], ['id'=>5],
            ['id'=>6], ['id'=>7], ['id'=>8], ['id'=>9], ['id'=>10],
            ['id'=>11],['id'=>12],['id'=>13],['id'=>14],['id'=>15],
            ['id'=>16],['id'=>17],['id'=>18],['id'=>19],['id'=>20],
            ['id'=>21],['id'=>22],['id'=>23],['id'=>24],['id'=>25],
            ['id'=>30],['id'=>31],['id'=>32],['id'=>33],['id'=>34],
            ['id'=>35],['id'=>36],['id'=>37],['id'=>38],['id'=>39],
            ['id'=>40],['id'=>41],['id'=>42],['id'=>43],['id'=>44],
            ['id'=>45],['id'=>46],['id'=>47],['id'=>48],['id'=>49],
            ['id'=>50],['id'=>51],['id'=>52],['id'=>53],['id'=>54],
            ['id'=>55],['id'=>56],['id'=>57],['id'=>58],['id'=>59],
            ['id'=>60],['id'=>61],['id'=>62],['id'=>63],['id'=>64],
            ['id'=>65],['id'=>66],['id'=>67],['id'=>68],['id'=>68],
            ['id'=>69],['id'=>70],
        ];

        $pdf->loadView('mail.fefc_newVersion',[
            'data'=>$data,
        ]);

        $pdf->setPaper('letter','portrait');
        return $pdf->stream();
    }
}
