<?php

namespace App\Models;

use App\Http\Controllers\CorrelativoSucursalController;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use JsonSchema\Constraints\Constraint;
use JsonSchema\Validator;

class schemaBase
{
    private $version;
    private $tipoDte;
    const DECIMALES = 4;
    const DECIMALES_RESUMEN = 2;
    public $ambiente;
    public $comprobante;
    public $codigoGeneracion;
    public $firma;
    public $sucursal;
    public $caja;
    public $correlativo;
    public $dte;
    public $contingencia;
    public $motivoContingencia;
    private $tipoModelo; // 1- Facturación previo 2- Facturación diferido.
    private $tipoOperacion; // 1- Facturación normal 2- Facturación contingencia.
    //cSpell:ignore dtes, codigo, generacion, turisticas, direccion, telefono, identificacion
    public function __construct(int $id, int $version, int $tipo_dte)
    {
        $this->version = $version;
        $this->tipoDte = $tipo_dte;
        $this->contingencia = null;
        $this->tipoModelo = 1;
        $this->tipoOperacion = 1;
        $this->dte = dtes::where("comprobantes_id", $id)->first();
        $this->comprobante = comprobantes::with(['turnosCajas', 'detalles'])->find($id);
        if ($this->comprobante == null)
            throw new Exception('No se pudo encontrar el comprobante con identificador: ' . $id);

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



        $this->ambiente = env('ambiente', '00');
        if ($this->codigoGeneracion == null || strlen($this->codigoGeneracion) < 30)
            $this->codigoGeneracion  = uuid::generate();
        $this->sucursal = $this->getSucursal();
        $this->caja = $this->comprobante->turnosCajas->cajasSucursales;
    }
    public function getIdentificacion(): array
    {
        //cSpell:ignore Operacion, Contin
        try {
            return [
                "version" => $this->version,
                "ambiente" => $this->ambiente,
                "tipoDte" => "0" . $this->tipoDte,
                "numeroControl" => $this->numeroControl(),
                "codigoGeneracion" => $this->codigoGeneracion,
                "tipoModelo" => $this->tipoModelo,
                "tipoOperacion" => $this->tipoOperacion,
                "tipoContingencia" => $this->contingencia,
                "motivoContin" => $this->motivoContingencia,
                "fecEmi" => Carbon::parse($this->comprobante->fecha)->format('Y-m-d'), // Carbon::parse(now())->format('Y-m-d'), //Carbon::parse($this->comprobante->fecha)->format('Y-m-d'),
                "horEmi" => Carbon::parse($this->comprobante->created_at)->format('H:i:s'),
                "tipoMoneda" => "USD",
            ];
        } catch (\Throwable $th) {
            throw new Exception("Error al generar la identificación del DTE: " . $th->getMessage());
        }
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
                "nombreComercial" => $this->sucursal->sucursal,
                "tipoEstablecimiento" => $this->sucursal->matriz ? "02" : "01",
                "direccion" => [
                    "departamento" => $this->sucursal->municipios->departamentos->codigo_mh,
                    "municipio" => $this->sucursal->municipios->codigo_mh,
                    "complemento" => $this->sucursal->direccion,
                ],
                "telefono" => $this->sucursal->telefono,
                "correo" => $this->sucursal->correo,
                "codEstableMH" => $this->sucursal->codigo_establecimiento,
                "codEstable" => null,
                "codPuntoVentaMH" => $this->comprobante->turnosCajas->cajasSucursales->codigo_punto_venta,
                "codPuntoVenta" => null,
            ];
        } catch (\Throwable $th) {
            throw new Exception("Error al generar el emisor: " . $th->getMessage());
        }
    }

    public function getSucursal(): sucursales
    {
        try {
            $id = $this->comprobante->turnosCajas->cajasSucursales->sucursales->id;
            return sucursales::find($id);
        } catch (\Throwable $th) {
            throw new Exception('Error al obtener la sucursal: ' . $th->getMessage());
        }
    }

    public function validar($j)
    {
        $schemaPath = public_path("schemas/dte_" . $this->tipoDte . ".json");
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

    public function numeroControl()
    {
        if ($this->correlativo && strlen($this->correlativo) > 30)
            return $this->correlativo;

        $correlativo = (new CorrelativoSucursalController)->getCorrelativo($this->sucursal->id, $this->tipoDte);

        $this->correlativo = "DTE-0" . $this->tipoDte . "-" . $this->sucursal->codigo_establecimiento . $this->caja->codigo_punto_venta . '-' . $this->formatCorrelativo($correlativo);
        return $this->correlativo;
    }
    public function formatCorrelativo($correlativo): string
    {
        return str_pad(strval($correlativo), 15, '0', STR_PAD_LEFT);
    }
    public function getCondiciones()
    {
        //cSpell:ignore credito
        $pagos = $this->comprobante->pagos;
        if ($pagos && count($pagos) > 0) {
            $credito = false;
            $contado = false;
            foreach ($pagos as $p) {
                $f = $p->forma_pagos;
                if ($f->token == 6002)
                    $credito = true;
                else
                    $contado = true;

                if ($credito && $contado) return 3;
            }
            if ($credito) return 2;
        }
        return 1;
    }

    public function getPagos()
    {
        //cSpell:ignore credito, dpagos
        $dpagos = array();
        $pagos = $this->comprobante->pagos;
        if ($pagos && count($pagos) > 0)
            foreach ($pagos as $p) {
                if ($p->forma_pagos->token != 6002)
                    array_push($dpagos, [
                        "codigo" => $this->getFormaPagoCod($p->forma_pagos->token),
                        "montoPago" => floatval(round($p->monto, self::DECIMALES_RESUMEN)),
                        "referencia" => null,
                        "plazo" => null,
                        "periodo" => null,
                    ]);
            }


        return count($dpagos) > 0 ? $dpagos : null;
    }

    public function getFormaPagoCod($token): string
    {
        switch ($token) {
            case 6001:
                return "01";
                break;
            case 6003:
                return "03";
                break;
            case 6005:
                return "04";
                break;
            default:
                return "01";
                break;
        }
    }

    public function getExtension()
    {
        try {

            $empleado = $this->comprobante?->users?->empleadoOne;
            if ($this->comprobante?->regestadia != null && $this->comprobante?->regestadia?->tipo_registros == 2) {
                $recepcion = $this->comprobante?->regestadia?->estadia;
                $huesped = $recepcion?->huesped?->huesped;
                if (
                    isset($huesped)
                    && isset($empleado)
                    && $huesped != null
                    && $huesped->id > 0
                    && $huesped->nombre != null
                    && $empleado != null
                    && $empleado->id > 0
                    && $empleado->nombre_completo != null
                ) {
                    $observaciones = "Registro de huesped Nº" . $recepcion->id;
                    $descripcion = $this->comprobante->descripcion != null ? " - " . $this->comprobante->descripcion : "";
                    $observaciones = $observaciones . $descripcion;
                    return [
                        'nombEntrega' => $empleado->nombre_completo,
                        'docuEntrega' => $empleado->numero_documento,
                        'nombRecibe' => $huesped->nombre,
                        'docuRecibe' => $huesped->identificacion,
                        'observaciones' =>  $observaciones,
                        'placaVehiculo' => null,
                    ];
                }
            } elseif (
                $this->comprobante->descripcion != null
                && $empleado->id > 0
                && $empleado->nombre_completo != null
            ) {
                return [
                    'nombEntrega' => $empleado->nombre_completo,
                    'docuEntrega' => $empleado->numero_documento,
                    'nombRecibe' => $this->comprobante?->clientes?->nombre ?? null,
                    'docuRecibe' => null,
                    'observaciones' =>  $this->comprobante->descripcion,
                    'placaVehiculo' => null,
                ];
            } else
                return null;
        } catch (\Throwable $th) {
            return throw $th;
        }
    }
    public function getNit(clientes $cliente)
    {
        if ($cliente == null)
            throw new Exception("No se pudo encontrar el cliente.");

        $nit = clientes_identificaciones::leftJoin("identificaciones", 'clientes_identificaciones.identificaciones_id', "identificaciones.id")
            ->where('clientes_id', $cliente->id)
            ->where('identificaciones.codigo', "36")
            ->first();

        if ($nit == null || $nit->id == null)
            throw new Exception("No se pudo encontrar el NIT es requerido.");

        return str_replace("-", "", $nit->numero);
    }
}
