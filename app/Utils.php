<?php

namespace App;
//cSpell:ignore dompdf, DomPDF, Barryvdh
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDF;
use ParseError;
use stdClass;

class Utils
{
    private $unidades = [
        '',
        'UNO ',
        'DOS ',
        'TRES ',
        'CUATRO ',
        'CINCO ',
        'SEIS ',
        'SIETE ',
        'OCHO ',
        'NUEVE ',
        'DIEZ ',
        'ONCE ',
        'DOCE ',
        'TRECE ',
        'CATORCE ',
        'QUINCE ',
        'DIECISÉIS ',
        'DIECISIETE ',
        'DIECIOCHO ',
        'DIECINUEVE ',
        'VEINTE ',
    ];

    /**
     * @var array
     */
    private $decenas = [
        'VEINTI',
        'TREINTA ',
        'CUARENTA ',
        'CINCUENTA ',
        'SESENTA ',
        'SETENTA ',
        'OCHENTA ',
        'NOVENTA ',
        'CIEN ',
    ];

    /**
     * @var array
     */
    private $centenas = [
        'CIENTO ',
        'DOSCIENTOS ',
        'TRESCIENTOS ',
        'CUATROCIENTOS ',
        'QUINIENTOS ',
        'SEISCIENTOS ',
        'SETECIENTOS ',
        'OCHOCIENTOS ',
        'NOVECIENTOS ',
    ];

    /**
     * @var array
     */
    private $acentosExcepciones = [
        'VEINTIDOS'  => 'VEINTIDÓS ',
        'VEINTITRES' => 'VEINTITRÉS ',
        'VEINTISEIS' => 'VEINTISÉIS ',
    ];

    /**
     * @var string
     */
    public $conector = 'CON';

    /**
     * @var bool
     */
    public $apocope = false;

    /**
     * Formatea y convierte un número a letras.
     *
     * @param int|float $number
     * @param int       $decimals
     *
     * @return string
     */
    public function toWords($number, $decimals = 2)
    {
        $this->checkApocope();

        $number = number_format($number, $decimals, '.', '');

        $splitNumber = explode('.', $number);

        $splitNumber[0] = $this->wholeNumber($splitNumber[0]);

        if (!empty($splitNumber[1])) {
            $splitNumber[1] = $this->convertNumber($splitNumber[1]);
        }

        return $this->glue($splitNumber);
    }

    /**
     * Formatea y convierte un número a letras en formato moneda.
     *
     * @param int|float $number
     * @param int       $decimals
     * @param string    $currency
     * @param string    $cents
     *
     * @return string
     */
    public function toMoney($number, $decimals = 2, $currency = 'DOLARES (USD)', $cents = 'CENTAVOS')
    {
        $this->checkApocope();

        $number = number_format($number, $decimals, '.', '');

        $splitNumber = explode('.', $number);

        $splitNumber[0] = $this->wholeNumber($splitNumber[0]) . ' ' . mb_strtoupper($currency, 'UTF-8');

        if (!empty($splitNumber[1])) {
            $splitNumber[1] = $this->convertNumber($splitNumber[1]);
        }

        if (!empty($splitNumber[1])) {
            $splitNumber[1] .= ' ' . mb_strtoupper($cents, 'UTF-8');
        }

        return $this->glue($splitNumber);
    }

    /**
     * Formatea y convierte un número a letras en formato libre.
     *
     * @param int|float $number
     * @param int       $decimals
     * @param string    $whole_str
     * @param string    $decimal_str
     *
     * @return string
     */
    public function toString($number, $decimals = 2, $whole_str = '', $decimal_str = '')
    {
        return $this->toMoney($number, $decimals, $whole_str, $decimal_str);
    }

    /**
     * Formatea y convierte un número a letras en formato facturación electrónica.
     *
     * @param int|float $number
     * @param int       $decimals
     * @param string    $currency
     *
     * @return string
     */
    public function toInvoice($number, $decimals = 2, $currency = '')
    {
        $this->checkApocope();

        $number = number_format($number, $decimals, '.', '');

        $splitNumber = explode('.', $number);

        $splitNumber[0] = $this->wholeNumber($splitNumber[0]);

        if (!empty($splitNumber[1])) {
            $splitNumber[1] .= '/100 ';
        } else {
            $splitNumber[1] = '00/100 ';
        }

        return $this->glue($splitNumber) . mb_strtoupper($currency, 'UTF-8');
    }

    /**
     * Valida si debe aplicarse apócope de uno.
     *
     * @return void
     */
    private function checkApocope()
    {
        if ($this->apocope === true) {
            $this->unidades[1] = 'UN ';
        }
    }

    /**
     * Formatea la parte entera del número a convertir.
     *
     * @param string $number
     *
     * @return string
     */
    private function wholeNumber($number)
    {
        if ($number == '0') {
            $number = 'CERO ';
        } else {
            $number = $this->convertNumber($number);
        }

        return $number;
    }

    /**
     * Concatena las partes formateadas del número convertido.
     *
     * @param array $splitNumber
     *
     * @return string
     */
    private function glue($splitNumber)
    {
        return implode(' ' . mb_strtoupper($this->conector, 'UTF-8') . ' ', array_filter($splitNumber));
    }

    /**
     * Convierte número a letras.
     *
     * @param string $number
     *
     * @return string
     */
    private function convertNumber($number)
    {
        $converted = '';

        if (($number < 0) || ($number > 999999999)) {
            throw new ParseError('Wrong parameter number');
        }

        $numberStrFill = str_pad($number, 9, '0', STR_PAD_LEFT);
        $millones = substr($numberStrFill, 0, 3);
        $miles = substr($numberStrFill, 3, 3);
        $cientos = substr($numberStrFill, 6);

        if (intval($millones) > 0) {
            if ($millones == '001') {
                $converted .= 'UN MILLÓN ';
            } elseif (intval($millones) > 0) {
                $converted .= sprintf('%sMILLONES ', $this->convertGroup($millones));
            }
        }

        if (intval($miles) > 0) {
            if ($miles == '001') {
                $converted .= 'MIL ';
            } elseif (intval($miles) > 0) {
                $converted .= sprintf('%sMIL ', $this->convertGroup($miles));
            }
        }

        if (intval($cientos) > 0) {
            if ($cientos == '001') {
                $this->apocope === true ? $converted .= 'UN ' : $converted .= 'UNO ';
            } elseif (intval($cientos) > 0) {
                $converted .= sprintf('%s ', $this->convertGroup($cientos));
            }
        }

        return trim($converted);
    }

    /**
     * @param string $n
     *
     * @return string
     */
    private function convertGroup($n)
    {
        $output = '';

        if ($n == '100') {
            $output = 'CIEN ';
        } elseif ($n[0] !== '0') {
            $output = $this->centenas[$n[0] - 1];
        }

        $k = intval(substr($n, 1));

        if ($k <= 20) {
            $unidades = $this->unidades[$k];
        } else {
            if (($k > 30) && ($n[2] !== '0')) {
                $unidades = sprintf('%sY %s', $this->decenas[intval($n[1]) - 2], $this->unidades[intval($n[2])]);
            } else {
                $unidades = sprintf('%s%s', $this->decenas[intval($n[1]) - 2], $this->unidades[intval($n[2])]);
            }
        }

        $output .= array_key_exists(trim($unidades), $this->acentosExcepciones) ?
            $this->acentosExcepciones[trim($unidades)] : $unidades;

        return $output;
    }

    public static function getPDF(): DomPDF
    {
        $pdf = Pdf::getFacadeRoot();
        $dompdf = $pdf->getDomPDF();
        $dompdf->setHttpContext(stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE
            ],
        ]));
        return $pdf;
    }

    public function getCalPrecio($precio, $iva = false, $cesc = false, $propina = false, $decimales = 4)
    {
        $obj  = new stdClass;
        $obj->neto = 0;
        $obj->iva = 0;
        $obj->cesc = 0;
        $obj->propina = 0;

        $agregados = 1;
        if ($iva)
            $agregados += env('iva', 0.13);
        if ($cesc)
            $agregados += env('cesc', 0.05);
        if ($propina)
            $agregados += env('propina', 0.1);

        $obj->neto = round($precio / $agregados, $decimales);
        if ($iva)
            $obj->iva = round($obj->neto * env('iva', 0.13), $decimales);
        if ($cesc)
            $obj->cesc = round($obj->neto *  env('cesc', 0.05), $decimales);
        if ($propina)
            $obj->propina = round($obj->neto *  env('propina', 0.1), $decimales);

        return $obj;
    }


    public function tipoDocumento($i)
    {
        $arr = [
            '01' => 'FACTURA',
            '03' => 'CRÉDITO FISCAL',
            '04' => 'NOTA DE REMISIÓN',
            '05' => 'NOTA DE CRÉDITO',
            '06' => 'NOTA DE DÉBITO',
            '07' => 'COMPROBANTE DE RETENCIÓN',
            '08' => 'COMPROBANTE DE LIQUIDACIÓN',
            '09' => 'DOCUMENTO CONTABLE DE LIQUIDACIÓN',
            '14' => 'FACTURA DE SUJETO EXCLUIDO',
        ];
        return $arr[$i] ?? '---';
    }
    public function tipoGeneracion($i)
    {
        $arr = [
            '1' => 'FÍSICO',
            '2' => 'ELECTRÓNICO',
        ];
        return $arr[$i] ?? '---';
    }
    public function modeloFacturacion($i)
    {
        $arr = [
            '1' => 'FACTURACIÓN PREVIO',
            '2' => 'FACTURACIÓN DIFERIDO',
        ];
        return $arr[$i] ?? '---';
    }
    public function tipoTransmision($i)
    {
        $arr = [
            '1' => 'TRANSMISIÓN NORMAL',
            '2' => 'TRANSMISIÓN POR CONTINGENCIA',
        ];
        return $arr[$i] ?? '---';
    }

    public function unidades($u = null)
    {
        $unidades =
            [
                59 => "Unidad",
                99 => "Otra",
                1 => "Metro",
                2 => "Yarda",
                3 => "Vara",
                4 => "Pie",
                5 => "Pulgada",
                6 => "Milímetro",
                8 => "Milla cuadrada",
                9 => "Kilómetro cuadrado",
                10 => "Hectárea",
                11 => "Manzana",
                12 => "Acre",
                13 => "Metro cuadrado",
                14 => "Yarda cuadrada",
                15 => "Vara cuadrada",
                16 => "Pie cuadrado",
                17 => "Pulgada cuadrada",
                18 => "Metro cúbico",
                19 => "Yarda cúbica",
                20 => "Barril",
                21 => "Pie cúbico",
                22 => "Galón",
                23 => "Litro",
                24 => "Botella",
                25 => "Pulgada cúbica",
                26 => "Mililitro",
                27 => "Onza fluida",
                29 => "Tonelada métrica",
                30 => "Tonelada",
                31 => "Quintal métrico",
                32 => "Quintal",
                33 => "Arroba",
                34 => "Kilogramo",
                35 => "Libra troy",
                36 => "Libra",
                37 => "Onza troy",
                38 => "Onza",
                39 => "Gramo",
                40 => "Miligramo",
                42 => "Megawatt",
                43 => "Kilowatt",
                44 => "Watt",
                45 => "Megavoltio-amperio",
                46 => "Kilovoltio-amperio",
                47 => "Voltio-amperio",
                49 => "Gigawatt-hora",
                50 => "Megawatt-hora",
                51 => "Kilowatt-hora",
                52 => "Watt-hora",
                53 => "Kilovoltio",
                54 => "Voltio",
                55 => "Millar",
                56 => "Medio millar",
                57 => "Ciento",
                58 => "Docena",
            ];
        if ($u != null)
            return $unidades[$u];
        return $unidades;
    }

    public function  tipoItem()
    {
        return [
            1 => "Bienes",
            2 => "Servicios",
            3 => "Ambos",
        ];
    }
}
