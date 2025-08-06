<?php

namespace App\Models;


use App\Utils;
use Carbon\Carbon;
use Exception;

class schemaNotaCredito extends schemaBase
{
    const version = 3;
    const tipoDte = "05";
    public $dte_r;
    public function __construct($id, dtes $dte = null)
    {
        parent::__construct($id, self::version, 5);
        $this->dte_r = $dte;
        if ($this->dte != null && $this->dte_r == null) {
            $json = json_decode($this->dte->json);
            if ($json->documentoRelacionado != null && $json->documentoRelacionado[0]->numeroDocumento)
                $this->dte_r = dtes::where('codigo_generacion', $json->documentoRelacionado[0]->numeroDocumento)->first();
            else throw new Exception('No se encontró el código de generación relacionado');
        }

        $json = $this->toArray();
        $this->validar($json);
        $firma = new schemaModel();
        $this->firma = $firma->getFirma($json);
    }



    private function getReceptor()
    {
        try {

            if ($this->comprobante->clientes_id == null)
                throw new Exception("No se puede continuar a generar el DTE Credito Fiscal, porque no tiene un cliente de tipo jurídico asignado asignado");
            $cliente = clientes::find($this->comprobante->clientes_id);
            if ($cliente == null || $cliente->tipo_cliente)
                throw new Exception("No se puede continuar a generar el DTE Credito Fiscal, porque no tiene un cliente de tipo jurídico asignado asignado");

            $nrc = $cliente->detalle->nrc;
            if ($nrc == "" || $nrc == null)
                throw new Exception("No se puede continuar a generar el DTE Credito Fiscal, porque no agregado NRC");

            $nit = $this->getNit($cliente);
            if ($nit == "" || $nit == null)
                throw new Exception("No se puede continuar a generar el DTE Credito Fiscal, porque no tiene una identificacion de tipo 36 - NIT");

            $nombre = $cliente->detalle->juridico;
            $nombreComercial = $cliente->nombre;

            if ($cliente->actividades_economicas_id == null)
                throw new Exception("No se puede continuar a generar el DTE Credito Fiscal, porque no tiene la actividad economica");

            $act = $cliente->actividades->actividad;
            $codAct = $cliente->actividades->codigo;

            $direccion = null;
            if ($cliente->municipios_id == null)
                throw new Exception("No se puede continuar a generar el DTE Credito Fiscal, porque no tiene asignado un municipio");

            $direccion = [
                "departamento" => $cliente->municipiosDepartamentos->departamentos->codigo_mh,
                "municipio" => $cliente->municipiosDepartamentos->codigo_mh,
                "complemento" => str_split($cliente->direccion, 190)[0]
            ];
            return [
                "nit" => $nit,
                "nrc" => $nrc,
                "nombre" => strtoupper($nombre),
                "codActividad" => $codAct,
                "descActividad" => $act,
                "nombreComercial" => $nombreComercial,
                "direccion" => $direccion,
                "telefono" => null,
                "correo" => $cliente->email,
            ];
        } catch (\Throwable $th) {
            throw new Exception('Error al obtener el receptor: ' . $th->getMessage());
        }
    }

    private function getCuerpoDocumento(): array
    {
        //cSpell:ignore Descu, advalorem, descripcion
        try {
            $detalle = $this->comprobante->detalles;
            $body = array();
            foreach ($detalle as $k => $v) {
                $medidas = new schemasUnidadMedidas($v->rubros_id);
                $unitario = floatval(round($v->neto, self::DECIMALES));
                $item = [
                    "numItem" => $k + 1,
                    "tipoItem" => $medidas->tipoItem,
                    "numeroDocumento" => (string) $this->dte_r->codigo_generacion,
                    "codigo" => null,
                    "codTributo" => null,
                    "descripcion" => $v->concepto,
                    "cantidad" => $v->cantidad,
                    "uniMedida" => $medidas->unidadMedida,
                    "precioUni" => $unitario,
                    "montoDescu" => 0,
                    "ventaNoSuj" => 0,
                    "ventaExenta" => floatval(round($v->cantidad * $v->exento, self::DECIMALES)),
                    "ventaGravada" => floatval(round($v->cantidad * $v->gravado, self::DECIMALES)),
                    "tributos" => $this->getTributos($v),
                ];
                array_push($body, $item);
            }
            return $body;
        } catch (\Throwable $th) {
            throw new Exception('Error al obtener el cuerpo del documento: ' . $th->getMessage());
        }
    }
    private function getTributos($v)
    {
        $t = array();
        if ($this->comprobante->iva > 0)
            array_push($t, "20");
        if ($v->cesc > 0)
            array_push($t, "59");

        return count($t) > 0 ? $t : null;
    }

    private function getResumen(): array
    {
        return [
            "totalNoSuj" => 0,
            "totalExenta" => floatval(round($this->comprobante->exento, self::DECIMALES_RESUMEN)),
            "totalGravada" => floatval(round($this->comprobante->gravado, self::DECIMALES_RESUMEN)),
            "subTotalVentas" => floatval(round($this->comprobante->gravado + $this->comprobante->exento, self::DECIMALES_RESUMEN)),
            "descuNoSuj" => 0,
            "descuExenta" => 0,
            "descuGravada" => 0,
            "totalDescu" => 0,
            "tributos" => $this->getTributosResumen(),
            "subTotal" => $this->getSubTotal(),
            'ivaPerci1' => 0,
            "ivaRete1" => floatval(round($this->comprobante->percepcion, self::DECIMALES_RESUMEN)),
            "reteRenta" => 0,
            "montoTotalOperacion" => $this->montoOperacion(),
            "totalLetras" => (new Utils)->toMoney($this->comprobante->total),
            "condicionOperacion" => $this->getCondiciones(),
        ];
    }
    private function getTributosResumen()
    {
        $t = array();
        if ($this->comprobante->cesc == 0 && $this->comprobante->advalorem == 0 && $this->comprobante->iva == 0)
            return null;
        if ($this->comprobante->iva > 0)
            array_push(
                $t,
                [
                    "codigo" => "20",
                    "descripcion" => "Impuesto al Valor Agregado 13%",
                    "valor" => floatval(round($this->comprobante->iva, 2)),
                ]
            );
        if ($this->comprobante->cesc > 0)
            array_push(
                $t,
                [
                    "codigo" => "59",
                    "descripcion" => "Turismo: por alojamiento (5%)",
                    "valor" => floatval(round($this->comprobante->cesc, 2)),
                ]
            );

        return $t;
    }
    private function montoOperacion()
    {
        return floatval(round($this->getSubTotal() + $this->comprobante->cesc  + $this->comprobante->iva - $this->comprobante->percepcion, self::DECIMALES_RESUMEN));
    }
    private function getSubTotal(): float
    {
        return floatval(round($this->comprobante->gravado + $this->comprobante->exento, self::DECIMALES_RESUMEN));
    }

    public function documentoRelacionado(): array
    {

        return [
            [
                "tipoDocumento" => (string) (strlen($this->dte_r->tipo_dte) == 1 ? '0' . $this->dte_r->tipo_dte : $this->dte_r->tipo_dte),
                "tipoGeneracion" => 2,
                "numeroDocumento" => (string) $this->dte_r->codigo_generacion,
                "fechaEmision" => Carbon::parse($this->dte_r->fecha_procesamiento)->format('Y-m-d'),
            ]
        ];
    }
    private function toArray()
    {
        return [
            "identificacion" => $this->getIdentificacion(),
            "documentoRelacionado" => $this->documentoRelacionado(),
            "emisor" => $this->getEmisorNc(),
            "receptor" => $this->getReceptor(),
            "ventaTercero" => null,
            "cuerpoDocumento" => $this->getCuerpoDocumento(),
            "resumen" => $this->getResumen(),
            "extension" => $this->getExtension(),
            "apendice" => null,
        ];
    }

    public function getEmisorNc(): array
    {
        try {

            return [
                "nit" => env('nit', "12170509850014"),
                "nrc" => env('nrc', "90670"),
                "nombre" => env('empresa', "TURISTICAS DE ORIENTE S.A. DE C.V."),
                "codActividad" => "55102",
                "descActividad" => "HOTELES",
                "nombreComercial" => $this->sucursal->sucursal,
                "tipoEstablecimiento" => $this->sucursal->matriz ? "02" : "01",
                "direccion" => [
                    "departamento" => $this->sucursal->municipios->departamentos->codigo_mh,
                    "municipio" => $this->sucursal->municipios->codigo_mh,
                    "complemento" => $this->sucursal->direccion,
                ],
                "telefono" => $this->sucursal->telefono,
                "correo" => $this->sucursal->correo,
            ];
        } catch (\Throwable $th) {
            throw new Exception("Error al generar el emisor: " . $th->getMessage());
        }
    }

    public function getJson()
    {
        try {
            return $this->toArray();
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()]);
        }
    }
    public function getVersion(): int
    {
        return self::version;
    }

    public function getTipoDte(): string
    {
        return self::tipoDte;
    }
}
