<?php

namespace App\Models;

use App\Http\Controllers\ApiMhController;
use App\Http\Controllers\DteContingenciasController;
use App\Http\Controllers\DteLogsController;
use App\Http\Controllers\DtesController;
use App\Mail\DteMail;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class dteApi
{
    //cSpell:ignore codigoGeneracion, idEnvio, schemaCreditoFiscal, codigoMsg, descripcionMsg, recepciondte, contigencias, fesv, dtes
    public $url_mh;
    public $nit;
    public api_mh $api;

    public function __construct()
    {
        $this->url_mh = env('HOST_API');
        $this->nit = env('nit');
        $this->api = (new ApiMhController)->getAuth();
    }

    public function setLog($error)
    {
        Log::error($error);
        throw new Exception($error);
    }

    public function getLote($codigoLote)
    {
        //https://api.dtes.mh.gob.sv/fesv/recepcion/consultadtelote/{codigoLote}
        $url = $this->url_mh . '/fesv/recepcion/consultadtelote/' . $codigoLote;
        //Actualizacion utilizando cliente
        return $this->clienteLotes($url);

        //Primera opcion return $this->apiGet($url);

    }

    public function getDte(dtes $dte)
    {
        $url = $this->url_mh . "/fesv/recepcion/consultadte/";
        $data = [
            'nitEmisor' => (string) $this->nit,
            'tdte' => (string) $dte->tipo,
            'codigoGeneracion' => (string) $dte->codigo_generacion
        ];

        return $this->apiPost($url, $data);
    }
    public function clienteLotes($url, $data = null)
    {
        $cl = new Client();
        $headers = [
            'Authorization' => $this->api->token,
        ];
        try {
            $rs = $cl->request('GET', $url, [
                "headers" => $headers
            ]);
            $data = json_decode($rs->getBody()->getContents(), true);
            return $data;
        } catch (RequestException $e) {
            throw $e;
        }
    }

    public function apiGet($url, $data = null)
    {
        try {
            //var_dump($this->api->token);
            return Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => $this->api->token,
            ])->get($url, [$data]);
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
    public function apiPost($api, $data)
    {
        try {

            return Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => $this->api->token,
            ])->post($api, $data);
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
}
