<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storemh_contingenciasRequest;
use App\Http\Requests\Updatemh_contingenciasRequest;
use App\Jobs\EnviarDTEsJob;
use App\Models\contingencias;
use App\Models\dte_contingencias;
use App\Models\dte_item_lotes;
use App\Models\dte_lotes;
use App\Models\dtes;
use App\Models\mh_contingencia_items;
use App\Models\mh_contingencias;
use App\Models\schemaConsumidorFinal;
use App\Models\schemaContingencia;
use App\Models\schemaCreditoFiscal;
use App\Models\uuid;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use function Pest\Laravel\json;

class MhContingenciasController extends Controller
{
    public $url_mh;
    public $ambiente;
    public $nit;
    public function __construct()
    {
        $this->url_mh = env('HOST_API');
        $this->ambiente = env('ambiente', '00');
        $this->nit = env('nit');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mes = date('Y-m-01');
        $pendientes = mh_contingencias::where("resuelto", false)->orWhere('mh', false)->get();
        $procesados = mh_contingencias::where("resuelto", true)->where('mh', true)->whereDate('fecha_procesamiento', '>=', $mes)->get();
        return view('dtes.mh_contingencias_index', ['pendientes' => $pendientes, 'procesados' => $procesados]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $r)
    {
        $id = Crypt::decryptString($r->id);
        $p = mh_contingencias::findOrFail($id);
        return view("dtes.mh_contingencias", ['p' => $p]);
    }

    public function setCreateContingencia(array $contingencias): mh_contingencias
    {
        try {
            $c = dte_contingencias::whereIn('id', $contingencias)->with(['dtes'])->orderBy("created_at")->get();
            $dtes = $c->pluck('dtes_id');

            if ($this->getDteValido($dtes) > 0)
                throw new Exception('Uno o mas DTES ya estan en una contingencia, no pueden existir dos contingencias con los mismos DTES, seleccione nuevamente los DTE');
            if (count($c) == 0)
                throw new Exception('No se encontró ninguna contingencia');

            $primero = $c->first();
            $ultimo = $c->last();
            $p = new mh_contingencias;
            $p->fecha_inicio = $primero->fecha_comprobante;
            $p->fecha_fin = $ultimo->fecha_comprobante;
            $p->hora_inicio = Carbon::parse($primero->dtes->comprobante?->created_at ?? $primero->dtes->sujeto?->created_at)->subHour()->format("H:i:s");
            $p->hora_fin = Carbon::parse($ultimo->dtes->comprobante?->created_at ?? $ultimo->dtes->sujeto?->created_at)->addHour()->format("H:i:s");
            $p->tipo_contingencia = (int) intval($primero->contingencias->codigo);
            if ($p->tipo_contingencia != 5)
                $p->motivoContingencia = $primero->contingencias->valor;
            $p->sucursales_id = $primero->dtes->sucursales_id;
            $p->save();
            $this->setItems($c, $p);
            return $p;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    private function setItems($contingencias, mh_contingencias $mh_contingencias)
    {
        foreach ($contingencias as $c) {

            (new MhContingenciaItemsController)->store(
                $c->dtes->tipo_dte,
                $c->dtes->codigo_generacion,
                $c->dtes->id,
                $mh_contingencias->id
            );
        }
    }
    private function getDteValido($dtes)
    {
        return mh_contingencia_items::whereIn('dtes_id', $dtes)->count();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storemh_contingenciasRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storemh_contingenciasRequest $r)
    {
        try {
            $id = Crypt::decryptString($r->id);
            $p =  mh_contingencias::find($id);
            $p->json = null;
            $p->response = null;
            $p->save();

            if ($p == null)
                throw new Exception("No se encontró la contingencia");
            $opcion = $r->opcion;
            switch ($opcion) {
                case 1:
                    $this->generarSchema($p->id);
                    return redirect()->route('dte.enviarLote', ['id' => Crypt::encryptString($p->id)]);
                    break;
                case 2:
                    $this->update($r, $p);
                    return redirect()->back()->with('message', 'Se actualizo la contingencia');
                    break;
            }
        } catch (\Throwable $e) {
            throw $e;
            exit;
            return redirect()
                ->back()
                ->with('type', 'danger')
                ->with('message', 'Error: ' . $e->getMessage());
        }
    }
    public function updateResponse($documento, $response, $error)
    {
        $rp = json_decode($response);
        $p = mh_contingencias::find($documento->contingencia->id);
        $p->codigo_generacion = $documento->codigoGeneracion;
        $p->json = json_encode($documento->getJson());
        $p->response = $response;
        $p->resuelto = !$error;
        $p->mh = !$error;
        if (!$error) {
            $p->fecha_procesamiento = Carbon::createFromFormat('d/m/Y H:i:s', $rp->fechaHora)->format('Y-m-d H:i:s');
            $p->sello_recibido = strval($rp->selloRecibido);
        }
        $p->save();
    }

    public function generarSchema($id)
    {
        try {
            $s = new schemaContingencia($id);
            $data = [
                "nit" => env('nit'),
                "documento" => $s->firma,
            ];
            return $this->setApiMH($s, $data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    private function setApiMH($documento, $data)
    {
        try {

            $api = (new ApiMhController)->getAuth();
            $error = false;

            //Envió a API MH
            $rs_mh = $this->getRequest($api, $data);
            $response = 'Error de conexión';
            if ($rs_mh->status() == 200) {
                $response = $rs_mh->body();
                $rp = json_decode($response);
                if ($rs_mh->successful())
                    if ($rp->selloRecibido == null)
                        $error = true;
            } else $error = true;
            //Actualización de contingencias
            $this->updateResponse($documento, $response, $error);

            /*if ($error)
                throw new Exception('Ocurrio un error, no se pudo procesar la contingencia');*/
        } catch (\Throwable $th) {

            throw $th;
        }
    }
    private function getRequest($api, $data)
    {
        try {
            $url = $this->url_mh . '/fesv/contingencia';
            return Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => $api->token,
            ])->post($url, $data);
        } catch (RequestException  $th) {
            Log::error('Error al realizar la solicitud: ' . $th->getMessage());
            return null;
        } catch (\Throwable $th) {
            Log::error('Error inesperado en getDte: ' . $th->getMessage());
            return null;
        }
    }


    public function lotes(Request $r)
    {
        $p = mh_contingencias::find(Crypt::decryptString($r->id));
        return view('dtes.lotes', ['p' => $p]);
    }

    public function loteStore(Request $r)
    {
        try {
            $dtes = $r->dtes;
            if (!isset($r->dtes) && count($dtes) == 0)
                throw new Exception("No se selecciono ningún DTE");

            $lote = new dte_lotes;
            $lote->codigo_lote = uuid::generate();
            $lote->recibido = false;
            $lote->users_id = Auth::user()->id;
            $lote->save();

            foreach ($dtes as $d) {
                $l = new dte_item_lotes;
                $l->dtes_id = Crypt::decryptString($d);
                $l->dte_lotes_id = $lote->id;
                $l->save();
            }
            switch ($r->opciones) {
                case 1:
                    return $this->setLote($lote);
                    break;
                case 2:
                    return $this->setDtes($lote);
                    break;
            }
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('type', 'danger')
                ->with('message', 'Error: ' . $th->getMessage());
        }
    }
    public function setLote(dte_lotes $lote)
    {
        $dtes = array();
        foreach ($lote->items as $i) {
            switch ($i->dte->tipo_dte) {
                case 1:
                    $dte = $this->generarDteFc($i->dte->comprobantes_id);
                    array_push($dtes, $dte);
                    break;
                case 3:
                    $dte = $this->generarDteCcf($i->dte->comprobantes_id);
                    array_push($dtes, $dte);
                    break;
            }
        }
        $data = [
            "ambiente" => $this->ambiente,
            "idEnvio" => $lote->codigo_lote,
            "version" => 2,
            'nitEmisor' => $this->nit,
            "documentos" => $dtes
        ];

        $this->setApiLotesMH($lote, $data);
    }
    public function setDtes(dte_lotes $lote)
    {
        $ids = "";
        foreach ($lote->items as $i) {

            $tipo = $i->dte->comprobantes_id > 0 ? 1 : ($i->dte->sujeto_excluidos_id > 0 ? 2 : 0);
            $c = $tipo == 1 ? 'comprobante: ' . $i->dte->comprobantes_id : ($tipo == 2 ? 'Sujeto excluido: ' . $i->dte->sujeto_excluidos_id : 'No se encontro el tipo de documento DTE:' . $i->dte->id);
            $ids = $ids . $c;
            if ($tipo == 1) {
                dispatch(new EnviarDTEsJob($i->dte->comprobantes_id));
            } elseif ($tipo == 2) {
                dispatch(new EnviarDTEsJob(null, $i->dte->sujeto_excluidos_id));
            }
        }
        return redirect()->back()->with('message', "Se creo la cuota de envió de todos los comprobantes a DTEs. Para darle seguimiento puede realizando en las herramientas de monitoreo ($ids)");
    }
    public function generarDteFc($comprobante_id)
    {
        try {
            $fc = new schemaConsumidorFinal($comprobante_id);

            return $fc->firma;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function generarDteCcf($comprobante_id)
    {
        try {
            $ccf = new schemaCreditoFiscal($comprobante_id);
            return $ccf->firma;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    private function setApiLotesMH($documento, $data)
    {
        try {
            $api = (new ApiMhController)->getAuth();
            //var_dump($data);
            //Envió a API MH
            $rs_mh = $this->getDte($api, $data);
            var_dump($rs_mh->status());
            $response = 'Error de conexión';
            if ($rs_mh->status() != 404) {
                $response = $rs_mh->body();
                $rp = json_decode($response);

                //Validación de resultado de MH
                if ($rs_mh->successful())
                    if ($rp->codigoMsg == "001") {
                        $documento->recibido = true;
                        $documento->fecha_procesamiento = Carbon::createFromFormat('d/m/Y H:i:s', $rp->fhProcesamiento)->format("Y-m-d H:i:s");
                    }
                var_dump($response);
                $documento->response = $response;
            }
            $documento->save();

            // var_dump($documento);
            //Enviar correos de todos los DTEs

        } catch (\Throwable $th) {
            Log::error('Error en setApiMH: ' . $th->getMessage());
            throw $th;
        }
    }

    private function getDte($api, $data)
    {
        try {
            $url = $this->url_mh . '/fesv/recepcionlote/';

            return Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => $api->token,
            ])->post($url, $data);
        } catch (RequestException  $th) {
            Log::error('Error al realizar la solicitud: ' . $th->getMessage());
            return null;
        } catch (\Throwable $th) {
            Log::error('Error inesperado en getDte: ' . $th->getMessage());
            return null;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\mh_contingencias  $mh_contingencias
     * @return \Illuminate\Http\Response
     */
    public function edit(mh_contingencias $mh_contingencias)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatemh_contingenciasRequest  $request
     * @param  \App\Models\mh_contingencias  $mh_contingencias
     * @return \Illuminate\Http\Response
     */
    public function update(Storemh_contingenciasRequest $r, mh_contingencias $p)
    {
        if ($r->tipo_contingencia != 5) {
            $contingencia = contingencias::where('codigo', $r->tipo_contingencia)->first();
            if ($contingencia == null)
                throw new Exception("No se encontró el tipo de contingencia, es posible que requiera agregarse a la base de datos");
            $p->motivoContingencia = $contingencia->valor;
        } else $p->motivoContingencia = $r->motivo_contingencia;

        $p->fecha_inicio = $r->fecha_inicio;
        $p->fecha_fin = $r->fecha_fin;
        $p->hora_inicio = $r->hora_inicio;
        $p->hora_fin = $r->hora_fin;
        $p->tipo_contingencia = $r->tipo_contingencia;

        $p->save();
        return $p;
    }

    public function destroyItem(Request $r)
    {
        try {
            $item = mh_contingencia_items::find(Crypt::decryptString($r->id));
            $mh = mh_contingencias::find($item->mh_contingencias_id);
            if (!$mh->resuelto) {
                $item->delete();
                return redirect()->back()->with('message', 'Se elimino un DTE de esta contingencia');
            } else
                throw new Exception('No se puede eliminar, porque pertenece a una contingencia resuelta.');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('type', "danger")
                ->with('message', 'Error:' . $th->getMessage());
        }
    }
    public function confirm(Request $r)
    {
        $p = mh_contingencias::find(Crypt::decryptString($r->id));
        if ($p->resulento || $p->mh)
            throw new Exception('No se puede eliminar este registro, porque fue resuelto o entregado a MH');

        return view('confirm', ['p' => $p, 'route' => 'dte.contingencias_delete']);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\mh_contingencias  $mh_contingencias
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {

            $mh = mh_contingencias::find(Crypt::decryptString($r->id));
            $items = mh_contingencia_items::where('mh_contingencias_id', $mh->id)->get();
            dte_contingencias::whereIn('dtes_id', $items->pluck('dtes_id'))->update(['resuelto' => false]);
            mh_contingencia_items::where('mh_contingencias_id', $mh->id)->delete();
            $mh->delete();
            return redirect()->route('contingencias_mh.index')->with('message', 'Se elimino una contingencia sin entregar a MH');
        } catch (\Throwable $th) {
            return redirect()->route('contingencias_mh.index')
                ->with('type', 'danger')
                ->with('message', 'Error' . $th->getMessage());
        }
    }

    public function status(Request $r)
    {
        $p = mh_contingencias::find(Crypt::decryptString($r->id));
        $p->resuelto = true;
        $p->mh = true;
        $p->save();
        return redirect()->back()->with('message', 'Se actualizo el estado de la contingencia a resuelto.');
    }
}
