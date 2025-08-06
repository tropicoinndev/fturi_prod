<?php

namespace App\Models;

use App\Utils;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Exception;
use Illuminate\Support\Facades\Storage;


class dtesFiles
{
    public $dte;
    public $json;
    public function __construct(dtes $dte)
    {
        $this->dte = $dte;
        $this->json = json_decode($this->dte->json);
    }

    public function create()
    {
        $this->getJson();
        $this->getPdf();
    }

    public function getJson()
    {
        try {
            $filename = $this->dte->codigo_generacion . '.json';
            $dataJson = json_decode($this->dte->json);
            $dataJson->firmaElectronica = $this->dte->firma;
            $dataJson->selloRecibido = $this->dte->sello_recibido;
            $json = json_encode($dataJson, JSON_PRETTY_PRINT);
            Storage::disk('json')->put($filename, $json);
            return $filename;
        } catch (\Throwable $th) {
            throw new Exception("Error al crear el JSON. Detalles:" . $th->getMessage());
        }
    }
    public function getPdf()
    {
        try {
            $filename = $this->dte->codigo_generacion . '.pdf';
            $pdf = (new Utils)->getPDF();
            $data = ['dte' => $this->dte, 'json' => $this->json, 'qr' => $this->getQR(), 'rqr' => $this->getQRInvalido()];

            $path = $this->getViewPath();
            $pdf->loadView($path, $data);
            $content = $pdf->output();
            Storage::disk('dtes')->put($filename, $content);
            return $filename;
        } catch (\Throwable $th) {
            throw new Exception("Error al crear el PDF. Detalles:" . $th->getMessage());
        }
    }

    public function getStream()
    {
        $pdf = (new Utils)->getPDF();
        $data = ['dte' => $this->dte, 'json' => $this->json, 'qr' => $this->getQR(), 'rqr' => $this->getQRInvalido()];

        $path = $this->getViewPath();
        $pdf->loadView($path, $data);
        //return view($path, $data);
        return $pdf->stream();
    }

    public function getViewPath(): string
    {
        $path = "";
        if ($this->dte->comprobantes_id != null)
            switch ($this->dte->comprobante->tipoComprobantes->token) {
                case 7001:
                    $path = 'mail.feCcf';
                    break;
                case 7002:
                    $path = 'mail.fefc';
                    break;
                case 7003:
                    $path = 'mail.fenc';
                    break;
            }
        elseif ($this->dte->sujeto_excluidos_id != null)
            $path = 'mail.fese';
        else
            throw new Exception('No se encontró el tipo de comprobante (detesFiles');
        return $path;
    }

    private function getQR()
    {

        $url = "https://admin.factura.gob.sv/consultaPublica?ambiente=" . $this->json->identificacion->ambiente . "&codGen=" . $this->json->identificacion->codigoGeneracion . "&fechaEmi=" . $this->json->identificacion->fecEmi;

        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($url)
            ->size(300)
            ->margin(10)
            ->build();

        return 'data:image/png;base64,' . base64_encode($result->getString());
    }
    private function getQRInvalido()
    {

        if ($this->dte->invalidado == null || strlen($this->dte->invalidado->codigo_generacion_r) < 30)
            return null;

        $relacionado = dtes::where('codigo_generacion', $this->dte->invalidado->codigo_generacion_r)->first();
        $json = json_decode($relacionado->json);
        $url = "https://admin.factura.gob.sv/consultaPublica?ambiente=" . $json->identificacion->ambiente . "&codGen=" . $json->identificacion->codigoGeneracion . "&fechaEmi=" . $json->identificacion->fecEmi;

        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($url)
            ->size(300)
            ->margin(10)
            ->build();

        return 'data:image/png;base64,' . base64_encode($result->getString());
    }
}
