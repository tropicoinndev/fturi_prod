<?php

namespace App\Models;

use App\Interfaces\schemaInterface;
use App\Utils;

use Exception;

//cSpell:disable

class schemaConsumidorFinal extends schemaBase implements schemaInterface
{
    const version = 1;
    const tipoDte = "01";
    public $firma;
    public $correlativo;

    public function __construct($id)
    {

        parent::__construct($id, self::version, self::version);

        $json = $this->toArray();
        $this->validar($json);
        $firma = new schemaModel();
        $this->firma = $firma->getFirma($json);
    }



    public function getReceptor()
    {
        try {
            if ($this->comprobante->clientes_id != null) {
                $cliente = clientes::find($this->comprobante->clientes_id);
                if ($cliente == null)
                    return null;
                $nrc = $cliente->tipo_cliente ? null : $cliente->detalle->nrc;
                $nrc = null;
                $nombre = $cliente->tipo_cliente ? $cliente->nombre : $cliente->detalle->juridico;
                $codAct = null;
                $act = null;
                if ($cliente->actividades_economicas_id > 0) {
                    $act = $cliente->actividades->actividad;
                    $codAct = $cliente->actividades->codigo;
                }

                $identificaciones = $cliente->identificaciones;
                $tipoDocumento = null;
                $numDocumento = null;
                if ($identificaciones && count($identificaciones) > 0) {
                    $tipoDocumento = $identificaciones[0]->identificaciones->codigo;
                    $numDocumento = $identificaciones[0]->numero;
                }

                $direccion = null;
                if ($cliente->municipios_id > 0 && $cliente->municipios_id != null) {

                    $complemento = str_split($cliente->direccion, 190)[0];
                    $direccion = [
                        "departamento" => $cliente->municipiosDepartamentos->departamentos->codigo_mh,
                        "municipio" => $cliente->municipiosDepartamentos->codigo_mh,
                        "complemento" => $complemento,
                    ];
                }
                if ($cliente->extranjeros_id > 0) {
                    $direccion = [
                        "departamento" => "00",
                        "municipio" => "00",
                        "complemento" => str_split($cliente->direccion, 190)[0]
                    ];
                }

                return [
                    "tipoDocumento" => $tipoDocumento,
                    "numDocumento" => $numDocumento,
                    "nrc" => $nrc,
                    "nombre" => strtoupper($nombre),
                    "codActividad" => $codAct,
                    "descActividad" => $act,
                    "direccion" => $direccion,
                    "telefono" => null,
                    "correo" => $cliente->email,
                ];
            } else {

                return [
                    "tipoDocumento" => null,
                    "numDocumento" => null,
                    "nrc" => null,
                    "nombre" => strtoupper($this->comprobante->titular),
                    "codActividad" => null,
                    "descActividad" => null,
                    "direccion" => null,
                    "telefono" => null,
                    "correo" => null,
                ];
            }
        } catch (\Throwable $th) {
            throw new Exception('Error al obtener el receptor: ' . $th->getMessage());
        }
    }

    public function getCuerpoDocumento(): array
    {
        //cSpell:ignore Descu, advalorem, descripcion
        try {
            $detalle = $this->comprobante->detalles;
            $body = array();
            $noGravadas = 0;

            foreach ($detalle as $k => $v) {
                $medidas = new schemasUnidadMedidas($v->rubros_id);
                $unitario = floatval(round(($v->gravado > 0 ? $v->gravado : $v->exento), self::DECIMALES));
                $adv = floatval(round($v->advalorem * $v->cantidad, self::DECIMALES));
                $propina = floatval(round($v->propina * $v->cantidad, self::DECIMALES));
                $noGravadas += floatval(round($adv + $propina, self::DECIMALES));
                $gravada = floatval(round($v->gravado > 0 ? $unitario * $v->cantidad : 0, self::DECIMALES));
                $iva = ($gravada / (1 + env('iva'))) *  env('iva');
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
                    "montoDescu" => $v->descuento,
                    "montoDescu" => 0,
                    "ventaNoSuj" => 0,
                    "ventaExenta" => floatval(round($v->exento > 0 ? $unitario * $v->cantidad : 0, self::DECIMALES)),
                    "ventaGravada" => floatval(round($v->gravado > 0 ? $unitario * $v->cantidad : 0, self::DECIMALES)),
                    "tributos" => $this->getTributos($v),
                    "psv" => floatval(round($v->sugerido, self::DECIMALES)),
                    "noGravado" => 0,
                    //"ivaItem" => floatval(round($v->iva * $v->cantidad, self::DECIMALES)),
                    "ivaItem" => floatval(round($iva, self::DECIMALES)),
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
                    "ivaItem" => 0,
                ];
                array_push($body, $item);
            }
            return $body;
        } catch (\Throwable $th) {
            throw new Exception('Error al obtener el cuerpo del documento: ' . $th->getMessage());
        }
    }

    public function getTributos($v)
    {
        $t = array();

        if ($v->advalorem > 0)
            array_push($t, "C5");

        if ($v->cesc > 0)
            array_push($t, "59");

        return count($t) > 0 ? $t : null;
    }

    public function getResumen(): array
    {
        return [
            "totalNoSuj" => 0,
            "totalExenta" => floatval(round($this->comprobante->exento, self::DECIMALES_RESUMEN)),
            "totalGravada" => floatval(round($this->comprobante->gravado, self::DECIMALES_RESUMEN)),
            "subTotalVentas" => $this->getSubTotal(),
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
            "totalIva" => floatval(round($this->comprobante->iva, self::DECIMALES_RESUMEN)),
            "saldoFavor" => 0,
            "condicionOperacion" => $this->getCondiciones(),
            "pagos" => $this->getPagos(),
            "numPagoElectronico" => null,
        ];
    }

    public function getTributosResumen()
    {
        $t = array();
        if ($this->comprobante->cesc == 0 && $this->comprobante->advalorem == 0)
            return null;

        if ($this->comprobante->cesc > 0)
            array_push(
                $t,
                [
                    "codigo" => "59",
                    "descripcion" => "Turismo: por alojamiento (5%)",
                    "valor" => floatval(round($this->comprobante->cesc, self::DECIMALES_RESUMEN)),
                ]
            );
        if ($this->comprobante->advalorem > 0)
            array_push(
                $t,
                [
                    "codigo" => "C5",
                    "descripcion" => "Impuesto ad- valorem por diferencial de precios de bebidas alcohólicas (8%)",
                    "valor" => floatval(round($this->comprobante->advalorem, self::DECIMALES_RESUMEN)),
                ]
            );

        return $t;
    }

    public function montoOperacion()
    {
        return floatval(round($this->getSubTotal() + $this->comprobante->cesc, self::DECIMALES_RESUMEN));
    }

    public function getSubTotal(): float
    {
        return floatval(round($this->comprobante->gravado + $this->comprobante->exento, self::DECIMALES_RESUMEN));
    }


    public function toArray()
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
