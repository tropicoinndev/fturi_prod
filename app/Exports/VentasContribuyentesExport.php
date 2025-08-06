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

class VentasContribuyentesExport implements FromQuery, WithColumnFormatting, WithHeadings, ShouldQueue, ShouldAutoSize
{
    use Exportable;
    private bool $header;
    private $inicio;
    private $fin;
    private $anuladas;
    public function __construct($inicio, $fin, $anuladas = false, bool $header = false)
    {
        $this->inicio = $inicio;
        $this->fin = $fin;
        $this->anuladas = $anuladas;
        $this->header = $header;
    }

    public function query()
    {
        $data = DB::table('anexo_contribuyentes')->whereBetween('fecha', [$this->inicio, $this->fin]);
        if ($this->anuladas)
            $data = $data->where('emision', '!=', null)
                ->orderBy('anulacion', 'asc')
                ->orderBy('emision', 'asc');
        else
            $data = $data->where('anulacion_estado', null)
                ->orderBy('emision', 'asc');

        $data = $data->select(
            'emision',
            'clase_documento',
            'tipo_documento',
            'numero_resolucion',
            'numero_serie',
            'numero_documento',
            'correlativo_interno',
            'identificacion',
            'nombre',
            'exento',
            'no_sujetas',
            'gravado',
            'iva',
            'cuentas_tercero',
            'debito_cuentas_tercero',
            'total_ventas',
            'dui',
            DB::raw('CASE WHEN exento = 0 THEN 1 ELSE 2 END AS tipo_operacion'), //U
            DB::raw('2 AS tipo_ingreso'), //V
            'anexo'
        );

        if ($this->anuladas)
            $data = $data->addSelect('anulacion');

        return $data;
    }

    public function headings(): array
    {
        return $this->header ? [
            'FECHA DE EMISIÓN', //A
            'CLASE DEL DOCUMENTO', //B
            'TIPO DEL DOCUMENTO', //C
            'NUMERO DE RESOLUCIÓN', //D
            'NUMERO DE SERIE', //E
            'NUMERO DE DOCUMENTO', //F
            'CORRELATIVO INTERNO', //G
            'IDENTIFICACIÓN', //H
            'NOMBRE', //I
            'EXENTO', //J
            'VENTAS NO SUJETAS', //K
            'VENTAS GRAVADAS LOCALES', //L
            'DÉBITO FISCAL', //M
            'VENTAS A CUENTA DE TERCEROS', //N
            'DÉBITO FISCAL POR VENTA A CUENTA DE TERCEROS', //O
            'TOTAL VENTAS', //P
            'DUI DEL CLIENTE', //Q
            'TIPO OPERACIÓN', //U
            'TIPO INGRESO', //V
            'NUMERO DE ANEXO' //R
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
            'H' => NumberFormat::FORMAT_NUMBER,
            'I' => NumberFormat::FORMAT_NUMBER,
            'J' => NumberFormat::FORMAT_NUMBER_00,
            'K' => NumberFormat::FORMAT_NUMBER_00,
            'L' => NumberFormat::FORMAT_NUMBER_00,
            'M' => NumberFormat::FORMAT_NUMBER_00,
            'N' => NumberFormat::FORMAT_NUMBER_00,
            'O' => NumberFormat::FORMAT_NUMBER_00,
            'P' => NumberFormat::FORMAT_NUMBER_00,
            'Q' => NumberFormat::FORMAT_NUMBER,
            'R' => NumberFormat::FORMAT_NUMBER,
            'S' => NumberFormat::FORMAT_NUMBER,
            'T' => NumberFormat::FORMAT_NUMBER
        ];
    }
}
