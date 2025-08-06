<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Facades\Http;
//cSpell:disable

class schemaModel
{
    private $url;
    private $privatePass;
    private $nit;
    private $estado;

    public function __construct()
    {
        $this->url = env('HOST_FIRMADOR') . "/firmardocumento/";
        $this->privatePass = env('PASSWORD_PRIV');
        $this->nit = env('nit', '12170509850014');
        $this->estado = true;
    }

    public function getFirma($json)
    {
        try {
            $data = [
                'nit' => $this->nit,
                'activo' => $this->estado,
                'passwordPri' => $this->privatePass,
                'dteJson' => $json,
            ];

            $rs = Http::withHeaders(['Content-Type' => 'application/JSON'])
                ->post($this->url, $data);
            if ($rs->successful())
                return $rs["body"];
            else throw new Exception($rs . " " . $this->url);
        } catch (\Throwable $th) {
            throw new Exception("Error al firmar: " . $this->url . " Error:" . $th->getMessage());
        }
    }
}
