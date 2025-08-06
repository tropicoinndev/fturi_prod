<?php

namespace App\Models;

use App\Http\Controllers\ApiMhController;
use App\Http\Controllers\DteContingenciasController;
use App\Http\Controllers\DteLogsController;
use App\Http\Controllers\DtesController;
use App\Mail\DteMail;
use Exception;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class dteBase
{
    //cSpell:ignore codigoGeneracion, idEnvio, schemaCreditoFiscal, codigoMsg, descripcionMsg, recepciondte, contigencias, fesv, dtes
    public $url_mh;
    public $comprobante;
    public $sujeto;
    public $dte;
    public $dte_r;
    public function __construct($comprobante_id, dtes $dte_r = null, $sujeto = null)
    {
        $this->url_mh = env('HOST_API');
        if ($sujeto == null && $comprobante_id != null)
            $this->initComprobantes($comprobante_id);
        elseif ($comprobante_id == null && $sujeto != null) {
            $this->initSujetos($sujeto);
        } else return throw new Exception("Debe enviar el ID de un comprobante o un sujeto excluido para inicializar DTE Base");

        $this->dte_r = $dte_r;
    }
    public function initComprobantes($comprobante_id)
    {
        if ($comprobante_id == null || $comprobante_id < 1)
            return throw new Exception("Es requerido enviar el identificador del comprobante");

        $this->comprobante = comprobantes::find($comprobante_id);
        if ($this->comprobante == null || $this->comprobante->id == null)
            return $this->setLog("Error al enviar el DTE, comprobante con identificador " . $comprobante_id . ", no se encontro el comprobante, asegurece que el comprobante existe.");
    }
    public function initSujetos($sujeto_id)
    {
        if ($sujeto_id == null || $sujeto_id < 1)
            return throw new Exception("Es requerido enviar el identificador del sujeto excluido");

        $this->sujeto = sujeto_excluido::find($sujeto_id);
        if ($this->sujeto == null || $this->sujeto->id == null)
            return $this->setLog("Error al enviar el DTE, comprobante con identificador " . $sujeto_id . ", no se encontro el comprobante, asegurece que el comprobante existe.");
    }
    public function setLog($error)
    {
        Log::error($error);
        return throw new Exception($error);
    }

    public function setDte()
    {
        if ($this->comprobante != null && $this->comprobante->id > 0)
            $this->setComprobanteToDte();
        elseif ($this->sujeto != null && $this->sujeto->id > 0)
            $this->generarDteSE();
    }
    public function setComprobanteToDte()
    {
        try {
            switch ($this->comprobante->tipoComprobantes->token) {
                case 7001:
                    return $this->generarDteCcf($this->comprobante->id);
                    break;
                case 7002:
                    return $this->generarDteFc($this->comprobante->id);
                    break;
                case 7003:
                    return $this->generarDteNc($this->comprobante->id, $this->dte_r);
                    break;
            }
        } catch (\Throwable $th) {
            $this->setLog("Error en tipificar Comprobante " . $th->getMessage());
            return throw $th;
        }
    }

    public function generarDteSE()
    {
        try {
            $se = new schemaSujetoExcluido($this->sujeto->id);
            $data = [
                "ambiente" => $se->ambiente,
                "idEnvio" => 1,
                "version" => $se->getVersion(),
                "tipoDte" => $se->getTipoDte(),
                "documento" => $se->firma,
                "codigoGeneracion" => $se->codigoGeneracion
            ];

            return $this->setApiMH($se, $data);
        } catch (\Throwable $th) {
            $this->setLog("Error en generación de schema SE " . $th->getMessage());
            throw $th;
        }
    }

    public function generarDteFc($comprobante_id)
    {
        try {
            $fc = new schemaConsumidorFinal($comprobante_id);
            $data = [
                "ambiente" => $fc->ambiente,
                "idEnvio" => 1,
                "version" => $fc->getVersion(),
                "tipoDte" => $fc->getTipoDte(),
                "documento" => $fc->firma,
                "codigoGeneracion" => $fc->codigoGeneracion
            ];
            return $this->setApiMH($fc, $data);
        } catch (\Throwable $th) {
            $this->setLog("Error en generación de schema FC " . $th->getMessage());
            return throw $th;
        }
    }
    public function generarDteCcf($comprobante_id)
    {
        try {
            $ccf = new schemaCreditoFiscal($comprobante_id);
            $data = [
                "ambiente" => $ccf->ambiente,
                "idEnvio" => 1,
                "version" => $ccf->getVersion(),
                "tipoDte" => $ccf->getTipoDte(),
                "documento" => $ccf->firma,
                "codigoGeneracion" => $ccf->codigoGeneracion
            ];
            return $this->setApiMH($ccf, $data);
        } catch (\Throwable $th) {
            $this->setLog("Error en generación de schema CCF " . $th->getMessage());
            return throw $th;
        }
    }

    public function generarDteNc($comprobante_id, dtes $dte = null)
    {
        try {
            $nc = new schemaNotaCredito($comprobante_id, $dte);
            $data = [
                "ambiente" => $nc->ambiente,
                "idEnvio" => 1,
                "version" => $nc->getVersion(),
                "tipoDte" => $nc->getTipoDte(),
                "documento" => $nc->firma,
                "codigoGeneracion" => $nc->codigoGeneracion
            ];
            return $this->setApiMH($nc, $data);
        } catch (\Throwable $th) {
            throw $th;
            $this->setLog("Error en generación de schema CCF " . $th->getMessage());
        }
    }
    public function setApiMH($documento, $data)
    {
        try {
            $response = null;
            $contingencia = job_contingencias::where('estado', true)->first();
            if ($contingencia != null) {
                $response = [
                    "estado" => "CONTINGENCIA",
                    "codigoMsg" => $contingencia->id,
                    "descripcionMsg" => $contingencia->contigencias->valor,
                ];

                $this->dte = (new DtesController)->store($documento, json_encode($response), true);
                if ($this->dte != null) (new DteContingenciasController)->newAutoContingencia($this->dte->id, $contingencia->contingencias_id);
            } else {
                $api = (new ApiMhController)->getAuth();
                $error = false;
                $enviarCorreo = false;
                $log = false;

                //Envió a API MH
                $rs_mh = $this->getDte($api, $data);
                $response = 'Error de conexión';
                if ($rs_mh->status() != 404) {
                    $response = $rs_mh->body();
                    $rp = json_decode($response);

                    //Validación de resultado de MH
                    if ($rs_mh->successful())
                        if (($rp->codigoMsg == "001" || $rp->codigoMsg == "002") && $rp->estado == "PROCESADO" && $rp->selloRecibido != null)
                            $enviarCorreo = true;
                        else
                            $error = true;
                    else
                        $error = true;
                } else $error = $log = true;
                //Creación y registro de DTE

                $this->dte = (new DtesController)->store($documento, $response, $error);
                if ($log) (new DteLogsController)->store($this->dte, $response);
                if ($enviarCorreo) $this->sendDte();
            }
            return $this->dte->response;
        } catch (\Throwable $th) {
            throw $th;
            //$this->setLog("Error al enviar a la API MH " . $th->getMessage());
        }
    }

    public function getDte($api, $data)
    {
        try {
            $url = $this->url_mh . '/fesv/recepciondte';
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

    public function sendDte()
    {
        try {

            if (
                $this->comprobante != null
                && $this->comprobante->clientes_id > 0
                && $this->comprobante->clientes->email != null
                && trim($this->comprobante->clientes->email) != ''
                && strlen(trim($this->comprobante->clientes->email)) > 5
            )
                $this->sendMail($this->comprobante->clientes->email);
            elseif (
                $this->sujeto != null
                && $this->sujeto->clientes_id > 0
                && $this->sujeto->clientes->email != null
                && trim($this->sujeto->clientes->email) != ''
                && strlen(trim($this->sujeto->clientes->email)) > 5
            )
                $this->sendMail($this->sujeto->clientes->email);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function sendMail($correo)
    {
        try {
            if (filter_var(trim($correo), FILTER_VALIDATE_EMAIL))
                Mail::to(trim(strtolower($correo)))->queue(new DteMail($this->dte));
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
