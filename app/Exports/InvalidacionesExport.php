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

class InvalidacionesExport implements FromQuery, WithColumnFormatting, WithHeadings, ShouldQueue, ShouldAutoSize
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
        return DB::table('anexo_invalidaciones')
            ->whereBetween('anulacion', [$this->inicio, $this->fin])
            ->orderBy('anulacion', 'asc')
            ->select(
                'numero_resolucion', //A
                'clase_documento', //B
                'desde', //C
                'hasta', //D
                'tipo_documento', //E
                'tipo_detalle', //F
                'serie', //G
                'desdec', //H
                'hastac', //I
                'codigo_generacion' //J
            );
    }

    public function headings(): array
    {
        return $this->header ? [
            'NUMERO DE RESOLUCIÓN', //A
            'CLASE DEL DOCUMENTO', //B
            'DESDE', //C
            'HASTA', //D
            'TIPO DE DOCUMENTO', //F
            'TIPO DE DETALLE', //G
            'SERIE', //H
            'DESDE', //I
            'HASTA', //J
            'CODIGO DE GENERACION' //H
        ] : [];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_NUMBER,
            'C' => NumberFormat::FORMAT_NUMBER,
            'D' => NumberFormat::FORMAT_NUMBER,
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_TEXT,
            'H' => NumberFormat::FORMAT_NUMBER,
            'I' => NumberFormat::FORMAT_NUMBER,
            'J' => NumberFormat::FORMAT_TEXT
        ];
    }
}
