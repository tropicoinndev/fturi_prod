<?php

namespace App\Http\Controllers;


use App\Models\api_mh;
use App\Models\comprobantes;
use App\Models\contingencias;
use App\Models\dte_contingencias;
use App\Models\dteBase;
use App\Models\dtes;
use App\Models\job_contingencias;
use App\Models\schemaConsumidorFinal;
use App\Models\schemaCreditoFiscal;
use App\Models\sucursales;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Horizon\Contracts\JobRepository;

#Add
use App\Http\Requests\StorecontingenciasRequest;
use App\Http\Requests\UpdatecontingenciasRequest;
use App\Models\anticipos;
use App\Models\anticipos_visual;
use App\Models\cajas;
use App\Models\dteApi;
use App\Models\schemaModel;
use App\Models\tipo_comprobantes;
use App\Models\turnos;
use App\Utils;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DtesController extends Controller
{
    //cSpell:ignore busqueda, codigo, generacion, finalizacion, Dtes, Credito
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dte = dtes::whereDate("fecha_procesamiento", date("Y-m-d"))
            ->where("error", false)
            ->get();
        $dteError = dtes::where("error", true)
            ->get();
        $errores = $dteError->count();
        $procesados = $dte->count();
        $api = api_mh::where("finalizacion", '>', now())
            ->first();
        $sucursales = sucursales::all();
        $contingencias = contingencias::where('estado', true)->get();

        $comprobantes = comprobantes::leftJoin('dtes', 'dtes.comprobantes_id', 'comprobantes.id')
            ->whereNull('dtes.id')
            ->where('comprobantes.eliminado', false)
            ->select(['comprobantes.*'])
            ->get();
        $comprobantesCount = $comprobantes->count();


        $contingencia = job_contingencias::where("estado", true)
            ->first();

        return view('dtes.index', [
            'dtes' => $dte,
            'dteError' => $dteError,
            'errores' => $errores,
            'api' => $api,
            'sucursales' => $sucursales,
            'procesados' => $procesados,

            'comprobantes' => $comprobantes,
            'comprobantesCount' => $comprobantesCount,
            'contingencia' => $contingencia,
            'contingencias' => $contingencias,
        ]);
    }

    public function documento($id)
    {

        $p = dtes::findOrFail(Crypt::decryptString($id));
        return view("dtes.documentos", ['p' => $p]);
    }

    public function procesados()
    {
        $fecha = date("Y-m-d");
        $dte = dtes::whereDate("fecha_procesamiento", ">=", $fecha)->where("error", false)->orderByDesc('id')->get();
        return view('dtes.procesados', [
            'dtes' => $dte,
            'fecha' => $fecha,
        ]);
    }

    public function comprobantes(Request $r)
    {
        $busqueda = trim($r->busqueda);
        $codigo_generacion = trim($r->codigo_generacion);

        $tipo = $r->tipo_comprobante ?? null;

        if (isset($r->busqueda))
            $fecha = $r->fecha ?? null;
        else $fecha = $r->fecha ?? date('Y-m-d');
        $caja = $r->cajas ? Crypt::decryptString($r->cajas) : 0;
        $comprobantes = comprobantes::where('id', '>', 0);

        if ($fecha != null)
            $comprobantes = $comprobantes->whereDate("fecha", ">=", $fecha);

        if ($tipo != null)
            $comprobantes = $comprobantes->where("tipo_comprobantes_id", $tipo);

        //Buscar correlativos o titular
        if ($busqueda && strlen($busqueda) > 0)
            $comprobantes = $comprobantes->where(function ($q) use ($busqueda) {
                $q->where(DB::raw('UPPER(titular)'), 'like', "%" . strtoupper(trim($busqueda)) . "%")
                    ->orWhere("correlativo", 'like', trim(strtoupper($busqueda)) . "%");
            });

        //Buscar codigo generacion
        if ($codigo_generacion && strlen($codigo_generacion) > 0) {
            $dtes = dtes::where('codigo_generacion', 'like', trim(strtoupper($codigo_generacion)) . "%")
                ->get();
            $comprobantes = $comprobantes->orWhereIn('id', $dtes->pluck('comprobantes_id'));
        }
        //Buscar selección de cajas por medio de turnos.
        if ($caja != 0 && $fecha != null) {
            $turnos = turnos::where(function ($q) use ($fecha) {
                $q->whereRaw("? between  apertura and cierre", ['fecha' => $fecha])
                    ->orWhere('fecha', '>=', $fecha);
            })->where('cajas_id', intval($caja))->pluck('id');
            $comprobantes = $comprobantes->whereIn('turnos_id', $turnos);
        }

        $comprobantes = $comprobantes->orderByDesc('correlativo')->orderByDesc('tipo_comprobantes_id')->paginate(20000);

        $cajas = cajas::all();
        return view('dtes.comprobantes', [
            'comprobantes' => $comprobantes,
            'fecha' => isset($r->busqueda) ? $fecha : null,
            'busqueda' => $busqueda,
            'cajas' => $cajas,
            'caja' => $caja,
            'tipo' => $tipo,
            'codigo_generacion' => $codigo_generacion,
            'tipo_comprobantes' => tipo_comprobantes::whereIn('token', [7001, 7002, 7003])->get()
        ]);
    }
    public function procesadosSearch(Request $r)
    {

        $busqueda = trim($r->busqueda);
        $fecha = $r->fecha ?? date('Y-m-d');
        $dte = dtes::leftJoin("comprobantes", 'comprobantes.id', 'dtes.comprobantes_id')
            ->whereDate("fecha_procesamiento", ">=", $fecha)
            ->where(function ($q) use ($busqueda) {
                $q->where('codigo_generacion', 'like', '%' . $busqueda . '%')
                    ->orWhere(DB::raw('UPPER(comprobantes.titular)'), 'like', '%' . strtoupper($busqueda) . '%');
            })->where("error", false)
            ->select('dtes.*')
            ->orderByDesc('dtes.id')
            ->get();
        return view('dtes.procesados', [
            'dtes' => $dte,
            'fecha' => $fecha,
            'busqueda' => $busqueda,
        ]);
    }

    public function fallidos()
    {

        $dte = dtes::where("error", true)->orderByDesc('id')->get();
        return view('dtes.fallidos', [
            'dtes' => $dte,

        ]);
    }
    public function fallidosSearch(Request $r)
    {
        $busqueda = strtoupper(trim($r->busqueda));
        $dte = dtes::leftJoin("comprobantes", 'comprobantes.id', 'dtes.comprobantes_id')
            ->where(function ($q) use ($busqueda) {
                $q->where('codigo_generacion', 'like', '%' . $busqueda . '%')
                    ->orWhere(DB::raw('UPPER(comprobantes.titular)'), 'like', '%' . $busqueda . '%');
            })->where("error", true)
            ->orderByDesc('dtes.id')
            ->select('dtes.*')
            ->get();

        return view('dtes.fallidos', [
            'dtes' => $dte,
            'busqueda' => $busqueda,
        ]);
    }
    public function getProcesados(Request $r)
    {
        $dte = dtes::findOrFail(Crypt::decryptString($r->id));
        $api = new dteApi();
        $dt = $api->getDte($dte);
        $json = json_decode($dt);
        $status = $dt->status();
        $rs = $dt->body();

        return view("dtes.consulta", ['rs' => $rs, 'dte' => $dte, 'json' => $json, 'status' => $status]);
    }

    public function anticiposVisual(Request $r)
    {
        return view("dtes.anticipos.consulta");
    }

    public function anticiposVisualSearch(Request $r)
    {
        $anticipos = explode(';', $r->anticipos);
        $data = null;
        if (($r->anticipos != null || $r->anticipos != '') && $anticipos != null && is_array($anticipos) && count($anticipos) > 0) {
            $data = anticipos_visual::whereIn('cveanticipo', $anticipos)->get();
        } else if (isset($r->titular) && strlen($r->titular) > 1) {
            $data = anticipos_visual::where('cuenta', 'like', strtoupper($r->titular) . '%')->orWhere('cveanticipo', intval($r->titular))->get();
        }
        return view("dtes.anticipos.consulta", ['data' => $data, 'titular' => $r->titular, 'anticipos' => $r->anticipos]);
    }

    public function anticiposVisualDesactivar(Request $r)
    {
        try {

            $anticipos = $r->anticipos;
            $m = 'No se realizaron cambios, no se selecciono ningún anticipo.';
            if (isset($anticipos) && is_array($anticipos) && count($anticipos) > 0) {
                $anticiposArray = array();
                foreach ($anticipos as $a) {
                    array_push($anticiposArray, Crypt::decryptString($a));
                }
                if (is_array($anticiposArray) && count($anticiposArray) > 0) {
                    anticipos_visual::whereIn('cveanticipo', $anticiposArray)->update(['activo' => false]);
                    $m = 'Se desactivaron los anticipos según el detalle: ' . implode(' | ', $anticiposArray);
                }
            }
            return redirect()->route('anticiposVisual.index')->with('message', $m);
        } catch (\Throwable $th) {
            return redirect()->route('anticiposVisual.index')->with('message', 'Error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param $dte, $response, $error
     * @return dtes
     */
    public function store($dte, $response, $error): dtes
    {
        try {
            $rp = json_decode($response);
            if (isset($dte->comprobante) && $dte->comprobante != null && $dte->comprobante->id && $dte->comprobante->id > 0) {
                $p = $this->existeDte($dte->comprobante->id);
            } elseif (isset($dte->sujeto) && $dte->sujeto != null && $dte->sujeto->id && $dte->sujeto->id > 0) {
                $p = $this->existeDteSE($dte->sujeto->id);
            }

            if ($p && $p->id != null && !$p->error) {
                dte_contingencias::where("dtes_id", $p->id)->update(['resuelto' => true]);
                return $p;
            }
            $p->correlativo = $dte->correlativo;
            $p->response = $response;
            $p->json = json_encode($dte->getJson());
            $p->firma = $dte->firma;

            $p->codigo_generacion = $dte->codigoGeneracion;
            $p->sello_recibido = $error ? null : $rp->selloRecibido;
            $p->estado = $error ? null : $rp->estado;
            $p->observaciones = $error ? null : json_encode($rp->observaciones);
            $p->fecha_procesamiento = $error ? null : $rp->fhProcesamiento;
            $p->error = $error;
            $p->contingencias_id = null;

            if (Auth::check())
                $p->users_id = Auth::user()->id;
            else
                $p->users_id = isset($dte->comprobante)  && $dte->comprobante != null ? $dte->comprobante->users_id : $dte->sujeto->users_id;

            $p->comprobantes_id = isset($dte->comprobante)  && $dte->comprobante->id && $dte->comprobante->id > 0 ? $dte->comprobante->id : null;
            $p->sujeto_excluidos_id = isset($dte->sujeto) && $dte->sujeto->id && $dte->sujeto->id > 0 ? $dte->sujeto->id : null;

            $p->sucursales_id = $dte->sucursal->id;
            $p->tipo_dte = $dte->getTipoDte();
            $p->save();
            //Log::info("Creado DTE " . $p->id . " Sello: " . $p->sello_recibido, ['creaciónDte']);
            return $p;
        } catch (\Throwable $th) {
            throw $th;
        }
    }



    public function setDteConsumidor(schemaConsumidorFinal $dte, $response, $error): dtes
    {
        return $this->store($dte, $response, $error);
    }
    public function setDteCreditoFiscal(schemaCreditoFiscal $dte, $response, $error): dtes
    {
        return $this->store($dte, $response, $error);
    }


    private function existeDte($comprobantes_id)
    {
        $dte = dtes::where("comprobantes_id", $comprobantes_id)->first();
        if ($dte == null)
            return new dtes;

        return $dte;
    }
    public function existeDteSE($id)
    {
        $dte = dtes::where("sujeto_excluidos_id", $id)->first();
        if ($dte == null)
            return new dtes;
        return $dte;
    }

    public function nuevoCorrelativo(Request $r)
    {
        try {
            $id = Crypt::decryptString($r->id);
            $dte = dtes::findOrFail($id);
            $dte->correlativo = "";
            $dte->codigo_generacion = "";
            if ($dte->save()) {
                $dteBase = new dteBase($dte->comprobantes_id);
                $dteBase->setComprobanteToDte();
                return redirect()->back()->with('message', 'Se genero el comprobante con un nuevo correlativo, Revise los resultados');
            } else
                throw new Exception('No se pudo borrar el correlativo');
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Ocurrió un error:' . $th->getMessage());
        }
    }

    #Contingencias
    public function contingenciasIndex()
    {
        try {
            return view('dtes.contingencias', [
                'p' => contingencias::orderBy('id', 'desc')->get(),
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Ocurrió un error: ' . $th->getMessage());
        }
    }

    public function contingenciasStore(StorecontingenciasRequest $r)
    {
        try {
            $p = new contingencias();
            $p->codigo = $r->codigo;
            $p->valor  = $r->valor;
            $p->estado = true;
            $p->save();

            return to_route('dte.contingenciasIndex')
                ->with('message', 'Registro guardado correctamente: ' . $p->valor)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function contingenciasEdit($id)
    {
        try {
            return view('dtes.contingenciasEdit', [
                'p' => contingencias::find(Crypt::decryptString($id)),
            ]);
        } catch (\Throwable $th) {
            return to_route('dte.contingenciasIndex')
                ->with('message', 'Error al encontrar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function contingenciasUpdate(UpdatecontingenciasRequest $r)
    {
        try {
            $p = contingencias::find(Crypt::decryptString($r->id));

            $p->codigo = $r->codigo;
            $p->valor  = $r->valor;
            $p->save();

            return to_route('dte.contingenciasIndex')
                ->with('message', 'Registro guardado correctamente: ' . $p->valor)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return to_route('dte.contingenciasIndex')
                ->with('message', 'Error al editar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function contingenciasConfirm($id)
    {
        try {
            return view('confirmContingencias', [
                'p' => $id,
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function contingenciasDelete(Request $r)
    {
        try {
            if (!isset($r->id) || empty(trim($r->id))) {
                return to_route('dte.contingenciasIndex')
                    ->with('message', 'Ocurrior un error, el identificador de registro no cumple con los requerimientos necesarios.')
                    ->with('type', 'danger');
            }

            $p = contingencias::find(Crypt::decryptString($r->id));
            $p->delete();

            return to_route('dte.contingenciasIndex')
                ->with('message', 'Registro eliminado con exito: ' . $p->valor)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', 'Error al eliminar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function contingenciasStatus($id)
    {
        try {
            $p = contingencias::find(Crypt::decryptString($id));
            $p->estado = !$p->estado;
            $p->save();

            return to_route('dte.contingenciasIndex')
                ->with('message', 'Se ha modificado el estado: ' . $p->valor)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return to_route('dte.contingenciasIndex')
                ->with('message', 'Error al cambiar estado: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function contingenciasSearch(Request $r)
    {
        return view('dtes.contingencias', [
            'p'          => contingencias::where('valor', 'ilike', '%' . $r->txtBusqueda . '%')->paginate(15),
            'txtBusqueda' => $r->txtBusqueda,
        ]);
    }

    public function resultado(Request $r)
    {
        $dte = dtes::find(Crypt::decryptString($r->id));
        $json = json_decode($dte->json);
        return view('dtes.resultado', ['dte' => $dte, 'json' => $json]);
    }

    public function update(Request $r)
    {
        try {
            $rs = Crypt::decryptString($r->rs);
            $id = Crypt::decryptString($r->id);

            $p = dtes::findOrFail($id);

            $d = json_decode($rs);

            if ($d?->estado == "PROCESADO") {
                $p->sello_recibido = $d->selloRecibido;
                $p->estado = $d->estado;
                $p->observaciones = json_encode($d->observaciones);
                $p->fecha_procesamiento = $d->fhProcesamiento;
                $p->error = false;
                $p->save();
            }
            return redirect()->route('dte.documento', ['id' => $p->cid])->with('message', 'Se actualizo el comprobante');
        } catch (\Throwable $th) {
            return redirect()->back()->with('type', 'danger')->with('message', 'Error: ' . $th->getMessage());
        }
    }

    public function firmadorStatus()
    {
        $r = "No disponible, revise los permisos o el contenedor";
        $status = false;
        try {
            $url = env('HOST_FIRMADOR') . '/firmardocumento/status';
            $http = Http::get($url);
            if ($http->successful()) {
                $r = $http->body();
                $status = true;
            }
            return response()->json([
                'status' => $status,
                'response' => $r,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => $status,
                'response' => $r . " (" . $th->getMessage() . ')',
            ]);
        }
    }

    public function testFirma(Request $r)
    {
        $firma = new schemaModel();
        return response()->json(['firmador' => $firma->getFirma(['mensaje' => 'Hola Mundo'])]);
    }

    public function complemento(Request $r)
    {
        try {

            $id = Crypt::decryptString($r->id);

            $p = dtes::findOrFail($id);
            $p->complemento = $r->complemento;
            $p->save();

            return redirect()->back()->with('message', 'Se actualizo el DTE');
        } catch (\Throwable $th) {
            return redirect()->back()->with('type', 'danger')->with('message', 'Error: ' . $th->getMessage());
        }
    }

    public function observaciones(Request $r)
    {
        $fecha = $r->fecha ?? date("Y-m-d");
        $dte = dtes::whereDate("fecha_procesamiento", ">=", $fecha)->whereRaw('LENGTH(observaciones) > 2')->orderByDesc('id')->get();
        return view('dtes.observaciones', [
            'dtes' => $dte,
            'fecha' => $fecha,
        ]);
    }
}
