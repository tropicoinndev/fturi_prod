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

class SujetosExcluidosMigracionExport implements FromQuery, WithColumnFormatting, WithHeadings, ShouldQueue, ShouldAutoSize
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
                'nombre', //A
                'emision', //B
                'codigo_generacion', //C
                'monto', //D
                'renta' //E
            );
    }

    public function headings(): array
    {
        return $this->header ? [
            'NOMBRE / RAZON SOCIAL', //A
            'FECHA DE EMISION', //B
            'NUMERO DE DOCUMENTO', //C
            'MONTO DE LA OPERACION', //D
            'RENTA' //E
        ] : [];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_NUMBER_00,
            'E' => NumberFormat::FORMAT_NUMBER_00
        ];
    }
}
