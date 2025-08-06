<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storedte_lotesRequest;
use App\Http\Requests\Updatedte_lotesRequest;
use App\Models\dte_item_lotes;
use App\Models\dte_lotes;
use App\Models\dteApi;
use App\Models\dtes;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;


class DteLotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lotes = dte_lotes::limit(15)->orderByDesc("id")->get();
        return view('dtes.lotes_index', ['lotes' => $lotes]);
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
     * @param  \App\Http\Requests\Storedte_lotesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storedte_lotesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\dte_lotes  $dte_lotes
     * @return \Illuminate\Http\Response
     */
    public function show(Request $r)
    {
        $p = dte_lotes::findOrFail(Crypt::decryptString($r->id));

        return view('dtes.lotes_show', ['p' => $p]);
    }

    public function getProcesados(Request $r)
    {
        $p = dte_lotes::findOrFail(Crypt::decryptString($r->id));
        $json = json_decode($p->response);
        $codigoLote = $json?->codigoLote;
        if ($codigoLote == null)
            throw new Exception('No se encontro el codigoLote');


        $dteApi = new dteApi();
        //$dteApi->getLote($p->codigo_lote);
        $lote = $dteApi->getLote($codigoLote);
        return $lote;
        //return var_dump($lote["procesados"]);
        if ($lote != null && count($lote["procesados"]) > 0) {
            $errores = "No se encontraron los siguientes DTEs:";
            $actualizados = "Se actualizaron los siguientes DTEs:";
            $sinactualizar = "No se actualizaron los siguientes DTEs:";
            $err = false;
            foreach ($lote["procesados"] as $l) {
                if (isset($l["codigoGeneracion"])) {
                    $codigoGeneracionDte = $l["codigoGeneracion"];
                    $rs = json_encode($l);
                    $dte = dtes::where('codigo_generacion', $codigoGeneracionDte)->first();
                    if ($dte == null) {
                        $err = true;
                        $errores = $errores . " CG: " . $codigoGeneracionDte;
                    } else {
                        if ($dte->error) {
                            if ($l["estado"] == "PROCESADO") {
                                $dte->sello_recibido = $l["selloRecibido"];
                                $dte->estado = $l["estado"];
                                $dte->observaciones = json_encode($l["observaciones"]);
                                $dte->fecha_procesamiento = $l["fhProcesamiento"];
                                $dte->error = false;
                                $dte->response = $rs;
                                $dte->save();
                                $actualizados = $actualizados . " CG: " . $dte->codigo_generacion;
                            } else {
                                $sinactualizar = $sinactualizar . " GC: " . $dte->codigo_generacion . " --Estado MH:" . $l["estado"];
                            }
                        } else {
                            $sinactualizar = $sinactualizar . " GC: " . $dte->codigo_generacion . " --Actualmente no se encuentra con error.";
                        }
                    }
                }
            }
            return response()->json(['errores' => $err ? $errores : null, 'actualizados' => $actualizados, "sinActualizar" => $sinactualizar]);
        } else
            return $lote;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\dte_lotes  $dte_lotes
     * @return \Illuminate\Http\Response
     */
    public function edit(dte_lotes $dte_lotes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatedte_lotesRequest  $request
     * @param  \App\Models\dte_lotes  $dte_lotes
     * @return \Illuminate\Http\Response
     */
    public function update(Updatedte_lotesRequest $request, dte_lotes $dte_lotes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\dte_lotes  $dte_lotes
     * @return \Illuminate\Http\Response
     */
    public function destroy(dte_lotes $dte_lotes)
    {
        //
    }
}
