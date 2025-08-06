<?php

namespace App\Models;


use App\Utils;

use Exception;

class schemaCreditoFiscal extends schemaBase
{
    const version = 3;
    const tipoDte = "03";
    public function __construct($id)
    {
        parent::__construct($id, self::version, self::version);
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
                "nrc" => $nrc,
                "nit" => $nit,
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
            $noGravadas = 0;
            $ultimoItem = 0;
            foreach ($detalle as $k => $v) {
                $medidas = new schemasUnidadMedidas($v->rubros_id);
                $unitario = floatval(round($v->neto, 4));
                $adv = floatval(round($v->advalorem * $v->cantidad, self::DECIMALES));
                $propina = floatval(round($v->propina * $v->cantidad, self::DECIMALES));
                $noGravadas += floatval(round($adv + $propina, self::DECIMALES));
                $item = [
                    "numItem" => $k + 1,
                    "tipoItem" => $medidas->tipoItem,
                    "numeroDocumento" => null,
                    "cantidad" => $v->cantidad,
                    "codigo" => null,
                    "codTributo" => null,
                    "uniMedida" => $medidas->unidadMedida,
                    "descripcion" => $v->concepto,
                    "precioUni" => $unitario,
                    "montoDescu" => 0,
                    "montoDescu" => 0,
                    "ventaNoSuj" => 0,
                    "ventaExenta" => floatval(round($v->exento > 0 ? $unitario * $v->cantidad : 0, 4)),
                    "ventaGravada" => floatval(round($v->gravado > 0 ? $unitario * $v->cantidad : 0, 4)),
                    "tributos" => $this->getTributos($v),
                    "psv" => floatval(round($v->sugerido, 4)),
                    "noGravado" => 0,

                ];
                array_push($body, $item);
                $ultimoItem = $k + 1;
            }
            //No Gravadas
            if ($noGravadas > 0 && $ultimoItem > 0) {

                $item = [
                    "numItem" => $ultimoItem + 1,
                    "tipoItem" => 2,
                    "numeroDocumento" => null,
                    "cantidad" => 1,
                    "codigo" => null,
                    "codTributo" => null,
                    "uniMedida" => 99,
                    "descripcion" => env('CONCEPTO_NOGRAVADAS', "SERVICIO"),
                    "precioUni" => 0,
                    "montoDescu" => 0,
                    "montoDescu" => 0,
                    "ventaNoSuj" => 0,
                    "ventaExenta" => 0,
                    "ventaGravada" => 0,
                    "tributos" => null,
                    "psv" => 0,
                    "noGravado" => floatval(round($noGravadas, self::DECIMALES)),

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
        if ($this->comprobante->advalorem > 0)
            array_push($t, "C5");
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
            "porcentajeDescuento" => 0, //TODO agregar función get descuento
            "totalDescu" => 0,
            "tributos" => $this->getTributosResumen(),
            "subTotal" => $this->getSubTotal(),
            "ivaRete1" => floatval(round($this->comprobante->percepcion, self::DECIMALES_RESUMEN)),
            "reteRenta" => 0,
            "montoTotalOperacion" => $this->montoOperacion(),
            "totalNoGravado" => floatval(round($this->comprobante->propina + $this->comprobante->advalorem, self::DECIMALES_RESUMEN)),
            "totalPagar" => floatval(round($this->comprobante->total, self::DECIMALES_RESUMEN)),
            "totalLetras" => (new Utils)->toMoney($this->comprobante->total),
            "saldoFavor" => 0,
            "condicionOperacion" => $this->getCondiciones(),
            "pagos" => $this->getPagos(),
            "numPagoElectronico" => null,
            'ivaPerci1' => 0,
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
        if ($this->comprobante->advalorem > 0)
            array_push(
                $t,
                [
                    "codigo" => "C5",
                    "descripcion" => "Impuesto ad- valorem por diferencial de precios de bebidas alcohólicas (8%)",
                    "valor" => floatval(round($this->comprobante->advalorem, 2)),
                ]
            );

        return $t;
    }
    private function montoOperacion()
    {
        return floatval(round($this->getSubTotal() + $this->comprobante->cesc  + $this->comprobante->iva, self::DECIMALES_RESUMEN));
    }
    private function getSubTotal(): float
    {
        return floatval(round($this->comprobante->gravado + $this->comprobante->exento, 2));
    }

    private function toArray()
    {
        return [

            "identificacion" => $this->getIdentificacion(),
            "documentoRelacionado" => null,
            "emisor" => $this->getEmisor(),
            "receptor" => $this->getReceptor(),
            "otrosDocumentos" => null,
            "ventaTercero" => null,
            "cuerpoDocumento" => $this->getCuerpoDocumento(),
            "resumen" => $this->getResumen(),
            "extension" => $this->getExtension(),
            "apendice" => null,

        ];
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
