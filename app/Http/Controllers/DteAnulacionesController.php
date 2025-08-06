<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storedte_anulacionesRequest;
use App\Http\Requests\Updatedte_anulacionesRequest;
use App\Mail\DteMail;
use App\Models\anulacion_comprobantes;
use App\Models\anulaciones;
use App\Models\comprobantes;
use App\Models\dte_anulaciones;
use App\Models\dteBase;
use App\Models\dtes;
use App\Models\empleados;
use App\Models\schemaAnulacion;
use App\Models\solicitantes;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class DteAnulacionesController extends Controller
{
    public $url_mh;
    public function __construct()
    {
        $this->url_mh = env('HOST_API');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $anulaciones = anulacion_comprobantes::where("aceptado", false)->get();
        return view('dtes_anulaciones.index', ['anulaciones' => $anulaciones]);
    }

    public function search(Request $r)
    {
        $anulaciones = anulacion_comprobantes::where("correlativo", trim($r->busqueda))->get();
        return view('dtes_anulaciones.index', ['anulaciones' => $anulaciones, 'busqueda' => trim($r->busqueda)]);
    }

    public function config(Request $r)
    {
        try {
            return $this->getConfig($r, 'dtes_anulaciones.config');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('type', 'danger')->with('message', 'Error: ' . $th->getMessage());
        }
    }

    public function getConfig($r, $view)
    {

        $p = anulacion_comprobantes::findOrFail(Crypt::decryptString($r->id));

        $dte = dtes::where('comprobantes_id', $p->comprobantes->id)->first();

        if ($dte == null) {
            throw new Exception("Este comprobante no tiene un DTE");
        }
        $anulacion = dte_anulaciones::where('dtes_id', $dte->id)->first();
        if ($anulacion && !$anulacion->error)
            return redirect()->route('dte_anulaciones.show', ['id' => Crypt::encryptString($anulacion->id)]);

        $solicitantes = [];
        if ($dte->comprobante->clientes_id != null && count($dte->comprobante->clientes->solicitantes) > 0)
            $solicitantes = $dte->comprobante->clientes->solicitantes;

        $empleados = [];

        if (count($p->users->empleado) > 0) {
            $empleados = $p->users->empleado;
        } else {
            $empleados = empleados::all();
        }
        $valid = $this->esInvalidable($dte);
        $tipoAnulaciones = anulaciones::where('estado', true)->get();
        return view($view, [
            'p' => $p,
            'd' => $dte,
            'solicitantes' => $solicitantes,
            'empleados' => $empleados,
            'tipoAnulaciones' => $tipoAnulaciones,
            'valid' => $valid,
        ]);
    }

    public function esInvalidable($dte): bool
    {

        switch ($dte->tipo_dte) {
            case 3:
                return $this->getTimeValid($dte->fecha_procesamiento, intval(env('dias_ccf', 1)), intval(env('minutos_invalidacion', 60)));
                break;
            case 1:
                return $this->getTimeValid($dte->fecha_procesamiento, intval(env('dias_fc', 90)), intval(env('minutos_invalidacion', 60)));
                break;
            default:
                throw new Exception('Comprobante no catalogado');
                break;
        }
    }
    public function getTimeValid($procesamiento, $dias, $minutos): bool
    {
        if ($procesamiento == null)
            return false;
        $procesado = Carbon::createFromFormat("Y-m-d H:i:s", $procesamiento);
        $tiempo = $procesado->copy()->addDays($dias);
        $ahora = Carbon::now();
        $dif = $tiempo->diffInMinutes($ahora);
        return $dif > $minutos;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storedte_anulacionesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storedte_anulacionesRequest $r)
    {
        try {
            $anulaciones = $this->setStore($r);
            return redirect()->route('dte_anulaciones.show', ['id' => Crypt::encryptString($anulaciones->id)]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function setStore($r)
    {
        $dte = dtes::findOrFail(Crypt::decryptString($r->id));
        $anulacion = dte_anulaciones::where('dtes_id', $dte->id)->first();
        if ($anulacion && !$anulacion->error)
            return redirect()->route('dte_anulaciones.show', ['id' => Crypt::encryptString($anulacion->id)]);

        $anulacion_comprobantes = anulacion_comprobantes::where('comprobantes_id', $dte->comprobantes_id)->first();
        $codigoGeneracionR = null;

        $solicitante = solicitantes::find($r->solicitante);
        $responsable = empleados::find($r->responsable);

        if ($solicitante == null)
            throw new Exception("No se encontró el solicitante");
        if ($responsable == null)
            throw new Exception("No se encontró el responsable");

        if ($anulacion_comprobantes->anulaciones->codigo != 2) {
            $codigoGeneracionR = $r->codigoGeneracionR;
            $relacionado = dtes::where('codigo_generacion', $codigoGeneracionR)->first();
            if ($relacionado == null)
                throw new Exception("No se encontró ningún DTE con ese codigo de generación.");

            $codigoGeneracionR = $relacionado->codigo_generacion;
        }

        $api = (new ApiMhController)->getAuth();
        $schema = new schemaAnulacion($dte->id, $solicitante, $responsable, $codigoGeneracionR);

        $error = false;
        $enviarCorreo = false;


        $data = [
            'ambiente' => $schema->ambiente,
            'idEnvio' => 1,
            'version' => $schema->getVersion(),
            'documento' => $schema->firma
        ];

        $rs = $this->setApiMH($api, $data);
        $response = 'Error de conexión';
        if ($rs->status() != 404) {
            $response = $rs->body();
            $rp = json_decode($response);

            //Validación de resultado de MH
            if ($rs->successful())
                if ($rp->codigoMsg == "001" && $rp->estado == "PROCESADO" && $rp->selloRecibido != null)
                    $enviarCorreo = true;
                else
                    $error = true;
            else
                $error = true;
        } else $error = true;
        //Creación y registro de DTE
        $anulaciones = $this->newAnulacion($schema, $response, $anulacion_comprobantes, $error);

        if ($enviarCorreo) $this->sendDte($dte);

        return $anulaciones;
    }

    public function sendDte($dte)
    {
        try {

            if (
                $dte->comprobante != null
                && $dte->comprobante->clientes_id > 0
                && $dte->comprobante->clientes->email != null
                && trim($dte->comprobante->clientes->email) != ''
                && strlen(trim($dte->comprobante->clientes->email)) > 5
            )
                if (filter_var(trim($dte->comprobante->clientes->email), FILTER_VALIDATE_EMAIL))
                    Mail::to($dte->comprobante->clientes->email)->queue(new DteMail($dte));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function newAnulacion(schemaAnulacion $documento, $response, anulacion_comprobantes $anulacion_comprobantes, $error)
    {
        try {
            $rs = json_decode($response);

            $p = $this->getAnulacion($documento->dte->id);
            $p->codigo_generacion = $documento->codigoGeneracion;
            $p->codigo_generacion_r = $documento->codigoGeneracionR;
            $p->tipo_dte = $documento->dte->tipo;
            $p->error = $error;
            $p->anulacion_comprobantes_id = $anulacion_comprobantes->id;
            $p->dtes_id = $documento->dte->id;
            $p->users_id = Auth::user()->id;
            $p->response = $response;
            $p->json = json_encode($documento->getJson());
            if (!$error) {
                $p->fecha_procesamiento = $rs->fhProcesamiento;
                $p->sello_recibido = $rs->selloRecibido;

                //Actualización de anulacion.
                $anulacion_comprobantes->aceptado = true;
                $anulacion_comprobantes->response = $response;
                $anulacion_comprobantes->save();
            }
            $p->save();
            return $p;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    private function getAnulacion($dte_id)
    {
        $p = dte_anulaciones::where('dtes_id', $dte_id)->first();
        if ($p != null && $p->id > 0)
            return $p;
        else
            return new dte_anulaciones;
    }

    public function setApiMH($api, $data)
    {
        try {
            $url = $this->url_mh . '/fesv/anulardte/';
            //var_dump($url);
            return Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => $api->token,
            ])->post($url, $data);
        } catch (RequestException  $th) {
            throw $th;
            $this->setLog('Error al realizar la solicitud: ' . $th->getMessage());
            return null;
        } catch (\Throwable $th) {
            throw $th;
            $this->setLog('Error inesperado en getDte: ' . $th->getMessage());
            return null;
        }
    }


    public function show(Request $r)
    {
        $p = dte_anulaciones::findOrFail(Crypt::decryptString($r->id));
        return view('dtes_anulaciones.show', ['p' => $p]);
    }

    public function historia(Request $r)
    {
        $p = dte_anulaciones::paginate(10);
        return view('dtes_anulaciones.historia', ['p' => $p]);
    }
    public function historiaSearch(Request $r)
    {
        $b = $r->busqueda;
        $p = dte_anulaciones::where("json", "like", '%' . strtoupper($b) . '%')
            ->orWhere('codigo_generacion', 'like', '%' . $b . '%')
            ->paginate();
        return view('dtes_anulaciones.historia', ['p' => $p]);
    }


    public function resuelta(Request $r)
    {
        $p = anulacion_comprobantes::findOrFail(Crypt::decryptString($r->id));
        $p->aceptado = true;
        $p->eliminada = true;
        $p->save();
        return redirect()->route('dte_anulaciones.historia')->with('message', 'Se cerro la anulacion con éxito.');
    }
    public function comprobanteDelete(Request $r)
    {
        $p = anulacion_comprobantes::findOrFail(Crypt::decryptString($r->id));
        return view("dtes_anulaciones.confirm", ['p' => $p]);
    }

    public function comprobanteDestroy(Request $r)
    {
        $r->validate(
            [
                'id' => ['required', 'string'],
                'confirm' => ['required', 'accepted'],
            ]
        );
        try {
            $this->destroy($r);
            return redirect()->route('dte_anulaciones.historia')->with('message', 'Se elimino el comprobante con éxito.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('type', 'danger')->with('message', 'Error: ' . $th->getMessage());
        }
    }

    public function destroy($r)
    {
        $p = anulacion_comprobantes::findOrFail(Crypt::decryptString($r->id));
        $c = comprobantes::find($p->comprobantes_id);
        $c->eliminado = true;
        $c->save();

        $p->aceptado = true;
        $p->save();
    }
}
