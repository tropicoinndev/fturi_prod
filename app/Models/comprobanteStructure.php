<?php

namespace App\Models;

class comprobanteStructure
{
    public $iva;
    public $cesc;
    public $advalorem;
    public $propina;
    public $exento;
    public $gravado;
    public $neto;
    public $percepcion;
    public $subtotal;
    public $total;
    public function __construct()
    {
        $this->iva        = 0;
        $this->cesc       = 0;
        $this->advalorem  = 0;
        $this->propina    = 0;
        $this->exento     = 0;
        $this->gravado    = 0;
        $this->neto       = 0;
        $this->percepcion = 0;
    }
}
