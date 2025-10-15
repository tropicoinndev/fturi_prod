<?php

namespace App\Models;


use Carbon\Carbon;
use Exception;
use JsonSchema\Constraints\Constraint;
use JsonSchema\Validator;
use stdClass;

//cSpell:disable

class schemaAnulacion
{
    const version = 2;
    public $firma;
    public $ambiente;
    public $codigoGeneracion;
    public $codigoGeneracionR;
    public $correlativo;
    public $anulacion;
    public $sucursal;
    public $comprobante;
    public $dte;
    public $anulacionComprobantes;
    public $solicitante;
    public $responsable;
    #---Agregados---
    public $fechaEvento;
    public $horaEvento;

    public function __construct($id, solicitantes $solicitante, empleados $responsable, $codigoGeneracionR = null, $fechaEvento, $horaEvento)
    {
        $this->solicitante = $solicitante;
        $this->responsable = $responsable;
        $this->codigoGeneracionR = trim($codigoGeneracionR);
        #---Agregados---
        $this->fechaEvento = $fechaEvento;
        $this->horaEvento = $horaEvento;
        #---
        $this->dte = dtes::find($id);
        $this->comprobante = $this->dte->comprobante;
        $this->anulacion = dte_anulaciones::where('dtes_id', $this->dte->id)->first();
        $this->anulacionComprobantes = anulacion_comprobantes::where('comprobantes_id', $this->comprobante->id)->first();


        $this->sucursal = sucursales::find($this->dte->sucursales_id);
        $this->codigoGeneracion = $this->anulacion->codigo_generacion ?? null;

        $this->codigoGeneracionR = $this->codigoGeneracionR ?? ($this->anulacion->codigo_generacion_r ?? null);


        $this->ambiente = env('ambiente', '00');
        if ($this->codigoGeneracion == null || strlen($this->codigoGeneracion) < 30)
            $this->codigoGeneracion  = uuid::generate();

        $json = $this->toArray();
        $this->validar($json);
        $firma = new schemaModel();
        $this->firma = $firma->getFirma($json);
    }

    public function getIdentificacion(): array
    {
        //cSpell:ignore Operacion, Contin
        try {
            return [
                "version" => self::version,
                "ambiente" => $this->ambiente,
                "codigoGeneracion" => $this->codigoGeneracion,
                /*"fecAnula" => Carbon::parse(now())->format('Y-m-d'),Originales
                "horAnula" => Carbon::parse(now())->format('H:i:s'),Originales*/
                "fecAnula"=>Carbon::parse($this->fechaEvento)->format('Y-m-d'),//Agregados
                "horAnula"=>Carbon::parse($this->horaEvento)->format('H:i:s'),//Agregados
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
                "nombre" => env('empresa', "TURISTICAS DE ORIENTE S.A. DE C.V."),
                "tipoEstablecimiento" => $this->sucursal->matriz ? "02" : "01",
                "nomEstablecimiento" => $this->sucursal->sucursal,
                "codEstableMH" => $this->sucursal->codigo_establecimiento,
                "codEstable" => null,
                "codPuntoVentaMH" => $this->comprobante->turnosCajas->cajasSucursales->codigo_punto_venta,
                "codPuntoVenta" => null,
                "telefono" => $this->sucursal->telefono,
                "correo" => $this->sucursal->correo,
            ];
        } catch (\Throwable $th) {
            throw new Exception("Error al generar el emisor: " . $th->getMessage());
        }
    }
    public function getDocumento(): array
    {
        try {
            $titular = $this->titular();
            $json = json_decode($this->dte->json);
            $fecha = $json?->identificacion?->fecEmi ?? $this->dte->fecha_procesamiento;
            return [
                "tipoDte" => $this->dte->tipo,
                "codigoGeneracion" => $this->dte->codigo_generacion,
                "selloRecibido" => $this->dte->sello_recibido,
                "numeroControl" => $this->dte->correlativo,
                "fecEmi" => Carbon::parse($fecha)->format("Y-m-d"),
                "montoIva" => round($this->comprobante->iva, 2),
                "codigoGeneracionR" => $this->anulacionComprobantes->anulaciones->codigo != 2 ? $this->codigoGeneracionR : null,
                "tipoDocumento" => $titular->tipoDocumento,
                "numDocumento" => $titular->numDocumento,
                "nombre" => $titular->nombre
            ];
        } catch (\Throwable $th) {
            throw new Exception("Error al generar el emisor: " . $th->getMessage());
        }
    }

    public function titular(): stdClass
    {
        $titular = new stdClass;
        $titular->tipoDocumento = null;
        $titular->numDocumento = null;
        $titular->nombre = null;

        if ($this->comprobante->clientes_id == null)
            throw new Exception("No se puede continuar a anular el comprobante, es requerido tener un cliente: tipo de documento, numero documento, nombre");
        $cliente = clientes::find($this->comprobante->clientes_id);
        if ($cliente == null)
            throw new Exception("No se encontro el cliente con el ID " . $this->comprobante->clientes_id . "No se puede continuar a anular el comprobante, es requerido tener un cliente: tipo de documento, numero documento, nombre");


        $id = $cliente->identificaciones;
        if (count($id) == 0)
            throw new Exception("Es requerido que el cliente tenga un tipo de identificacion: CAT-22: 36 - NIT, 13 - DUI, 02 - Carnet de residente, 03 - PASAPORTE, 37 - OTRO");

        $titular->tipoDocumento = $id[0]->identificaciones->codigo;
        $titular->numDocumento = $id[0]->numero;
        $titular->nombre = $cliente->nombre;

        return $titular;
    }


    public function getMotivo()
    {
        $tipo = intval($this->anulacionComprobantes->anulaciones->codigo);
        return
            [
                "tipoAnulacion" => $tipo,
                "motivoAnulacion" => $tipo == 3 ? $this->anulacionComprobantes->anulaciones->anulacion : null,
                "nombreResponsable" => $this->responsable->nombre_completo,
                "tipDocResponsable" => $this->responsable->identificaciones->codigo,
                "numDocResponsable" => $this->responsable->numero_documento,
                "nombreSolicita" => $this->solicitante->nombre_completo,
                "tipDocSolicita" => $this->solicitante->identificaciones->codigo,
                "numDocSolicita" => $this->solicitante->numero_documento
            ];
    }
    public function toArray()
    {
        return [
            "identificacion" => $this->getIdentificacion(),
            "emisor" => $this->getEmisor(),
            "documento" => $this->getDocumento(),
            "motivo" => $this->getMotivo(),

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


    public function validar($j)
    {
        $schema = json_decode(file_get_contents("schemas/anulacion-schema-v2.json"));
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
}
