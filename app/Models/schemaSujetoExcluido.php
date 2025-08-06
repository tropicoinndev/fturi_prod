<?php

namespace App\Models;

use App\Http\Controllers\CorrelativoSucursalController;
use App\Utils;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use JsonSchema\Constraints\Constraint;
use JsonSchema\Validator;

//cSpell:disable

class schemaSujetoExcluido
{
    const version = 1;
    const tipoDte = "14";
    public $ambiente;
    public $firma;
    public $correlativo;
    public $sujeto;
    public $sucursal;
    public $caja;
    public $dte;
    public $codigoGeneracion;
    public $contingencia;
    public $motivoContingencia;
    private $tipoModelo; // 1- Facturación previo 2- Facturación diferido.
    private $tipoOperacion; // 1- Facturación normal 2- Facturación contingencia.
    public $totalCompra;
    public $totalRenta;
    public $totalDescuento;
    public function __construct($id)
    {
        $this->ambiente = env('ambiente');
        $this->tipoModelo = 1;
        $this->tipoOperacion = 1;

        $this->sujeto = sujeto_excluido::find($id);
        if ($this->sujeto == null)
            throw new Exception('No se logro encontrar el sujeto excluido');


        $this->caja = $this->sujeto->cajas;
        if ($this->caja == null)
            throw new Exception('No se encontro la caja donde se realizo este DTE');

        $this->sucursal = $this->caja->Sucursales;
        if ($this->sucursal == null)
            throw new Exception('No se encontro la sucursal donde se realizo este DTE');


        $this->dte = dtes::where("sujeto_excluidos_id", $id)->first();

        if ($this->dte != null && $this->dte->error == false)
            throw new Exception("Este comprobante ya fue procesado sin errores, con el codigo de generación: " . $this->dte->codigo_generacion . " y sello de recepción: " . $this->dte->sello_recibido);
        elseif ($this->dte != null && $this->dte->error == true) {
            $this->correlativo = $this->dte->correlativo;
            $this->codigoGeneracion = $this->dte->codigo_generacion;

            $item = $this->dte->contingencia;
            if ($item && $item != null) {
                $this->tipoModelo = 2;
                $this->tipoOperacion = 2;
                $this->contingencia = $item->mh_contingencia->tipo_contingencia;
                $this->motivoContingencia = $item->mh_contingencia->motivoContingencia;
            }
        }
        if ($this->codigoGeneracion == null || strlen($this->codigoGeneracion) < 30)
            $this->codigoGeneracion  = uuid::generate();

        $json = $this->toArray();
        $this->validar($json);
        $firma = new schemaModel();
        $this->firma = $firma->getFirma($json);
    }



    public function getReceptor()
    {
        try {
            if ($this->sujeto->clientes_id != null) {
                $cliente = clientes::find($this->sujeto->clientes_id);
                if ($cliente == null || !$cliente->estado || !$cliente->tipo_cliente)
                    throw new Exception('No se puede crear este sujeto exlcuido, porque no se agrego un cliente valido');

                $nombre = $cliente->nombre;
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
                if ($cliente->municipios_id > 0) {

                    $direccion = [
                        "departamento" => $cliente->municipiosDepartamentos->departamentos->codigo_mh,
                        "municipio" => $cliente->municipiosDepartamentos->codigo_mh,
                        "complemento" => str_split($cliente->direccion, 190)[0]
                    ];
                }

                return [
                    "tipoDocumento" => $tipoDocumento,
                    "numDocumento" => $numDocumento,
                    "nombre" => strtoupper($nombre),
                    "codActividad" => $codAct,
                    "descActividad" => $act,
                    "direccion" => $direccion,
                    "telefono" => null,
                    "correo" => $cliente->email,
                ];
            }
            return null;
        } catch (\Throwable $th) {
            throw new Exception('Error al obtener el receptor: ' . $th->getMessage());
        }
    }

    public function getCuerpoDocumento(): array
    {
        //cSpell:ignore Descu, advalorem, descripcion
        try {
            $detalle = $this->sujeto->detalles;
            $this->totalCompra = 0;
            $this->totalRenta = 0;
            $this->totalDescuento;
            $body = array();
            foreach ($detalle as $k => $v) {
                $compra = floatval(round(($v->cantidad * $v->precio_unitario) - $v->descuento, 2));
                $this->totalDescuento += $v->descuento;
                $this->totalCompra += $compra;
                $this->totalRenta += floatval(round($v->renta, 2));
                $item = [
                    "numItem" => $k + 1,
                    "tipoItem" => $v->tipo_item,
                    "cantidad" => intval($v->cantidad),
                    "codigo" => null,

                    "uniMedida" => $v->unidad_medida,
                    "descripcion" => $v->descripcion,
                    "precioUni" => floatval(round($v->precio_unitario, 2)),
                    "montoDescu" => floatval(round($v->descuento ?? 0, 2)),
                    "compra" => $compra,
                ];
                array_push($body, $item);
            }
            return $body;
        } catch (\Throwable $th) {
            throw new Exception('Error al obtener el cuerpo del documento: ' . $th->getMessage());
        }
    }



    public function getResumen(): array
    {

        return [
            "totalCompra" => round($this->totalCompra, 2),
            "descu" => round($this->totalDescuento, 2),
            "totalDescu" => round($this->totalDescuento, 2),
            "subTotal" => round($this->totalCompra, 2),
            "ivaRete1" => 0,
            "reteRenta" => round($this->totalRenta, 2),
            "totalPagar" => round($this->getTotal(), 2),
            "totalLetras" => (new Utils)->toMoney($this->getTotal()),
            "condicionOperacion" => 1,
            "pagos" => null,
            "observaciones" => null,
        ];
    }

    public function getTotal(): float
    {
        return floatval(round($this->totalCompra - $this->totalRenta, 2));
    }

    public function toArray()
    {
        return [

            "identificacion" => $this->getIdentificacion(),
            "emisor" => $this->getEmisor(),
            "sujetoExcluido" => $this->getReceptor(),
            "cuerpoDocumento" => $this->getCuerpoDocumento(),
            "resumen" => $this->getResumen(),
            "apendice" => null,

        ];
    }
    public function getIdentificacion(): array
    {
        //cSpell:ignore Operacion, Contin
        try {
            return [
                "version" => self::version,
                "ambiente" => $this->ambiente,
                "tipoDte" => self::tipoDte,
                "numeroControl" => $this->numeroControl(),
                "codigoGeneracion" => $this->codigoGeneracion,
                "tipoModelo" => $this->tipoModelo,
                "tipoOperacion" => $this->tipoOperacion,
                "tipoContingencia" => $this->contingencia,
                "motivoContin" => $this->motivoContingencia,
                "fecEmi" => Carbon::parse($this->sujeto->fecha)->format('Y-m-d'),
                "horEmi" => Carbon::parse($this->sujeto->created_at)->format('H:i:s'),
                "tipoMoneda" => "USD",
            ];
        } catch (\Throwable $th) {
            throw new Exception("Error al generar la identificación del DTE: " . $th->getMessage());
        }
    }
    public function numeroControl()
    {
        if ($this->correlativo && strlen($this->correlativo) > 30)
            return $this->correlativo;

        $correlativo = (new CorrelativoSucursalController)->getCorrelativo($this->sucursal->id, self::tipoDte);

        $this->correlativo = "DTE-" . self::tipoDte . "-" . $this->sucursal->codigo_establecimiento . $this->caja->codigo_punto_venta . '-' . $this->formatCorrelativo($correlativo);


        return $this->correlativo;
    }
    public function formatCorrelativo($correlativo): string
    {
        return str_pad(strval($correlativo), 15, '0', STR_PAD_LEFT);
    }

    public function getEmisor(): array
    {
        try {

            return [
                "nit" => env('nit', "12170509850014"),
                "nrc" => env('nrc', "90670"),
                "nombre" => env('empresa', "TURISTICAS DE ORIENTE S.A. DE C.V."),
                "codActividad" => "55102",
                "descActividad" => "HOTELES",
                "direccion" => [
                    "departamento" => $this->sucursal->municipios->departamentos->codigo_mh,
                    "municipio" => $this->sucursal->municipios->codigo_mh,
                    "complemento" => $this->sucursal->direccion,
                ],
                "telefono" => $this->sucursal->telefono,
                "correo" => $this->sucursal->correo,
                "codEstableMH" => $this->sucursal->codigo_establecimiento,
                "codEstable" => null,
                "codPuntoVentaMH" => $this->caja->codigo_punto_venta,
                "codPuntoVenta" => null,
            ];
        } catch (\Throwable $th) {
            throw new Exception("Error al generar el emisor: " . $th->getMessage());
        }
    }
    public function validar($j)
    {
        $schemaPath = public_path("schemas/dte_" . self::tipoDte . ".json");
        if (!file_exists($schemaPath)) {
            Log::error('El archivo de esquema no existe en la ruta especificada', ['path' => $schemaPath]);
            return;
        }
        $schema = json_decode(file_get_contents($schemaPath));
        $val = new Validator;
        $json = json_encode($j);
        $json = json_decode($json);
        if (json_last_error() !== JSON_ERROR_NONE)
            throw new Exception('Json no valido');

        $val->validate($json, $schema, Constraint::CHECK_MODE_APPLY_DEFAULTS);

        if (!$val->isValid()) {
            $errors = "";
            foreach ($val->getErrors() as $error)
                $errors =  $errors . '[property: ' . $error['property'] . ' · message: ' . $error['message'] . ' value: ' . (isset($error['value']) ? $error['value'] : null) . ']';


            throw new Exception("Error al procesar el JSON: " . $errors);
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
