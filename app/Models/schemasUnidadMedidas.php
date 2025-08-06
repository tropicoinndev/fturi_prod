<?php

namespace App\Models;

use Exception;

class schemasUnidadMedidas
{
    public $tipoItem;
    public $unidadMedida;
    public function __construct($rubro_id)
    {

        if ($rubro_id && $rubro_id > 0) {
            $rubro = rubro::find($rubro_id);

            if ($rubro == null || $rubro->token == null)
                return $this->default();

            switch ($rubro->token) {
                case 12001:
                case 12004:
                case 12005:
                    $this->default();
                    break;
                case 12002:
                case 12003:
                    $this->tipoItem = 2;
                    $this->unidadMedida = 99;
                    break;
                default:
                    $this->default();
                    break;
            }
        } else return $this->default();
    }
    public function default()
    {
        $this->tipoItem = 1;
        $this->unidadMedida = 59;
    }
}
