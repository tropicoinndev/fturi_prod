<?php

namespace App\Models;

class comprobanteDetalleStructure
{
    public $cantidad;
    public $concepto;
    public $iva;
    public $cesc;
    public $advalorem;
    public $propina;
    public $exento;
    public $gravado;
    public $descuentos_id;
    public $descuento;
    public $sugerido;
    public $porcentaje_descuento;
    public $neto;
    public $subtotal;
    public $total;
    public $rubros_id;
    public $registro;
    public $tipo_registros;
    public function __construct($cantidad, $neto, $concepto, $rubro, $registro, $tipo_registro)
    {
        $this->cantidad             = intval($cantidad);
        $this->concepto             = (string) strtoupper($concepto);
        $this->iva                  = 0;
        $this->cesc                 = 0;
        $this->advalorem            = 0;
        $this->propina              = 0;
        $this->exento               = 0;
        $this->gravado              = 0;
        $this->descuentos_id        = null;
        $this->descuento            = 0;
        $this->sugerido             = 0;
        $this->porcentaje_descuento = 0;
        $this->neto                 = floatval(round($neto, 4));
        $this->total                = 0;
        $this->subtotal             = 0;
        $this->rubros_id            = $rubro;
        $this->registro             = $registro;
        $this->tipo_registros        = $tipo_registro;
    }
}
