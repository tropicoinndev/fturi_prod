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

class SujetosExcluidosExport implements FromQuery, WithColumnFormatting, WithHeadings, ShouldQueue, ShouldAutoSize
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
        return DB::table('anexo_sujetos')
            ->whereBetween('fecha', [$this->inicio, $this->fin])
            ->orderBy('fecha', 'asc')
            ->select(
                'tipo_documento', //A
                'documento', //B
                'nombre', //C
                'emision', //D
                'numero_serie', //E
                'numero_documento', //F
                'monto', //G
                'retencion', //H
                'tipo_operacion', //I
                'clasificacion', //J
                'sector', //K
                'tipo_clasificacion', //L
                'anexo' //M
            );
    }

    public function headings(): array
    {
        return $this->header ? [
            'TIPO DE DOCUMENTO', //A
            'DOCUMENTO', //B
            'NOMBRE / RAZON SOCIAL', //C
            'FECHA DE EMISION', //D
            'NUMERO DE SERIE', //E
            'NUMERO DE DOCUMENTO', //F
            'MONTO DE LA OPERACION', //G
            'MONTO DE LA RETENCION IVA 13%', //H
            'TIPO DE OPERACION', //I
            'CLASIFICACION', //J
            'SECTOR', //K
            'TIPO DE COSTO / GASTO', //L
            'NUMERO DE ANEXO', //M
        ] : [];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'B' => NumberFormat::FORMAT_NUMBER,
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_NUMBER_00,
            'H' => NumberFormat::FORMAT_NUMBER_00,
            'I' => NumberFormat::FORMAT_NUMBER,
            'J' => NumberFormat::FORMAT_NUMBER,
            'K' => NumberFormat::FORMAT_NUMBER,
            'L' => NumberFormat::FORMAT_NUMBER,
            'M' => NumberFormat::FORMAT_NUMBER
        ];
    }
}
