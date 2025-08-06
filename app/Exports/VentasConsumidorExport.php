<?php

namespace App\Exports;

//cSpell:disable
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class VentasConsumidorExport implements FromQuery, WithColumnFormatting, WithHeadings, ShouldQueue, ShouldAutoSize
{
    use Exportable;
    private bool $header;
    private $inicio;
    private $fin;
    private $anuladas;
    public function __construct($inicio, $fin, bool $header = false)
    {
        $this->inicio   = $inicio;
        $this->fin      = $fin;
        $this->header   = $header;
    }

    public function query()
    {
        return DB::table('anexo_consumidor')
            ->whereBetween('fecha', [$this->inicio, $this->fin])
            ->orderBy('emision', 'asc')
            ->select(
                'emision', //A
                'clase_documento', //B
                'tipo_documento', //C
                'numero_resolucion', //D
                'numero_serie', //E
                'interno_del', //F
                'interno_al', //G
                'numero_documento_del', //H
                'numero_documento_al', //I
                'maquina', //J
                'exento', //K
                'internas_no_sujetas', //L
                'no_sujetas', //M
                'gravado', //N
                'exportaciones_ca', //O
                'exportaciones', //P
                'exportaciones_servicios', //Q
                'ventas_zonas', //R
                'cuentas_terceros', //S
                'total_ventas', //T
                DB::raw('CASE WHEN exento = 0 THEN 1 ELSE 4 END AS tipo_operacion'), //U
                DB::raw('2 AS tipo_ingreso'), //V
                'anexo' //W
            );
    }

    public function headings(): array
    {
        return $this->header ? [
            'FECHA DE EMISIÓN', //A
            'CLASE DEL DOCUMENTO', //B
            'TIPO DEL DOCUMENTO', //C
            'NUMERO DE RESOLUCIÓN', //D
            'SERIE DE DOCUMENTO', //E
            'NUMERO DE CONTROL INTERNO (DEL)', //F
            'NUMERO DE CONTROL INTERNO (AL)', //G
            'NUMERO DE DOCUMENTO (DEL)', //H
            'NUMERO DE DOCUMENTO (AL)', //I
            'No MAQUINA REGISTRADORA', //J
            'VENTAS EXENTAS', //K
            'VENTAS EXENTAS NO SUJETAS A PROPORCIONALIDAD', //L
            'VENTAS NO SUJETAS', //M
            'VENTAS GRAVADAS LOCALES', //N
            'EXPORTACIONES DENTRO DE CA', //O
            'EXPORTACIONES FUERA DE CA', //P
            'EXPORTACION DE SERVICIOS', //Q
            'VENTAS ZONAS FRANCAS Y DPA', //R
            'VENTA A CUENTA DE TERCEROS', //S
            'TOTAL VENTAS', //T
            'TIPO OPERACIÓN', //U
            'TIPO INGRESO', //V
            'NUMERO DE ANEXO' //W
        ] : [];
    }

    public function columnFormats(): array
    {
        return [
            'A' => 'dd/mm/yyyy',
            'B' => NumberFormat::FORMAT_NUMBER,
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_TEXT,
            'H' => NumberFormat::FORMAT_TEXT,
            'I' => NumberFormat::FORMAT_TEXT,
            'J' => NumberFormat::FORMAT_TEXT,
            'K' => NumberFormat::FORMAT_NUMBER_00,
            'L' => NumberFormat::FORMAT_NUMBER_00,
            'M' => NumberFormat::FORMAT_NUMBER_00,
            'N' => NumberFormat::FORMAT_NUMBER_00,
            'O' => NumberFormat::FORMAT_NUMBER_00,
            'P' => NumberFormat::FORMAT_NUMBER_00,
            'Q' => NumberFormat::FORMAT_NUMBER_00,
            'R' => NumberFormat::FORMAT_NUMBER_00,
            'S' => NumberFormat::FORMAT_NUMBER_00,
            'T' => NumberFormat::FORMAT_NUMBER_00,
            'U' => NumberFormat::FORMAT_NUMBER,
            'V' => NumberFormat::FORMAT_NUMBER,
            'W' => NumberFormat::FORMAT_NUMBER,
        ];
    }
}
