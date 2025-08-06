<?php

namespace App\Models;


use Carbon\Carbon;
use Exception;
use JsonSchema\Constraints\Constraint;
use JsonSchema\Validator;

//cSpell:disable

class schemaContingencia
{
    const version = 3;
    public $firma;
    public $ambiente;
    public $codigoGeneracion;
    public $correlativo;
    public $contingencia;
    public $sucursal;
    public $items;

    public function __construct($id)
    {

        $this->contingencia = mh_contingencias::find($id);

        if ($this->contingencia === null)
            throw new Exception('No se pudo encontrar la contingencia con identificador: ' . $id);

        $this->items = $this->contingencia->items ?? [];
        $this->sucursal = sucursales::find($this->contingencia->sucursales_id);
        $this->codigoGeneracion = $this->contingencia->codigo_generacion ?? null;
        if (empty($this->items))
            throw new Exception('No se encontraron comprobantes para agregar a la contingencia, deben haber uno o mas');

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
                "fTransmision" => Carbon::parse(now())->format('Y-m-d'),
                "hTransmision" => Carbon::parse(now())->format('H:i:s'),
            ];
        } catch (\Throwable $th) {
            throw new Exception("Error al generar la identificación del DTE: " . $th->getMessage());
        }
    }
    private function getDetalleDte(): array
    {
        $arr = array();
        foreach ($this->items as $i => $item)
            array_push($arr, [
                'noItem' => $i + 1,
                'codigoGeneracion' => $item->codigo_generacion,
                'tipoDoc' => strlen($item->tipo_doc) == 1 ? '0' . $item->tipo_doc : '' . $item->tipo_doc
            ]);
        return $arr;
    }
    private function getMotivo()
    {
        return [
            "fInicio" => $this->contingencia->fecha_inicio,
            "fFin" => $this->contingencia->fecha_fin,
            "hInicio" => $this->contingencia->hora_inicio,
            "hFin" => $this->contingencia->hora_fin,
            "tipoContingencia" => $this->contingencia->tipo_contingencia,
            "motivoContingencia" => $this->contingencia->tipo_contingencia == 5 ? $this->contingencia->motivoContingencia : null
        ];
    }
    public function toArray()
    {
        return [
            "identificacion" => $this->getIdentificacion(),
            "emisor" => $this->getEmisor(),
            "detalleDTE" => $this->getDetalleDte(),
            "motivo" => $this->getMotivo(),
        ];
    }

    public function getEmisor(): array
    {
        try {

            return [
                "nit" => (string) env('nit', "12170509850014"),
                "nombre" => (string) env('empresa', "TURISTICAS DE ORIENTE S.A. DE C.V."),
                "nombreResponsable" => (string) env("responsable"),
                "tipoDocResponsable" => (string) env("tipo_doc_responsable", "13"),
                "numeroDocResponsable" => (string) env("doc_responsable"),
                "tipoEstablecimiento" => (string) ($this->sucursal->matriz ? "02" : "01"),
                'codPuntoVenta' => null,
                'codEstableMH' => null,
                "telefono" => (string) $this->sucursal->telefono,
                "correo" => (string) $this->sucursal->correo,
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

    public function validar($j)
    {
        $schema = json_decode(file_get_contents("schemas/contingencia-schema-v3.json"));
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
