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

class VentasRubrosAdvalorem implements FromQuery, WithColumnFormatting, WithHeadings, ShouldQueue, ShouldAutoSize
{
    use Exportable;
    private bool $header;
    private $inicio;
    private $fin;
    private $rubros;
    private $cajas;

    public function __construct($inicio, $fin, $rubros = null, $cajas = null, bool $header = true)
    {
        $this->inicio = $inicio;
        $this->fin = $fin;
        $this->rubros = $rubros;
        $this->cajas = $cajas;
        $this->header = $header;
    }
    public function query()
    {
        $data = DB::table('ventas_rubros')->whereBetween('fecha', [$this->inicio, $this->fin]);
        if ($this->rubros != null && is_array($this->rubros))
            $data = $data->whereIn('rubros_id', $this->rubros);
        if ($this->cajas != null && is_array($this->cajas))
            $data = $data->whereIn('cajas_id', $this->cajas);
        return $data->select(
            'fecha',
            'concepto',
            'cantidad',
            'neto',
            DB::raw('(cantidad * total) as venta'),
            DB::raw('(cantidad * advalorem) as advalorem'),
            'numero_control'
        )->orderBy('fecha');
    }

    public function headings(): array
    {
        return $this->header ? [
            'FECHA', //A
            'DETALLE', //B
            'CANTIDAD', //C
            'NETO', //D
            'VENTA', //E
            'AD-VALOREM', //F
            'FACTURA MH', //G
        ] : [];
    }

    public function columnFormats(): array
    {
        return [
            'A' => 'dd/mm/yyyy',
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_NUMBER,
            'D' => NumberFormat::FORMAT_NUMBER_00,
            'E' => NumberFormat::FORMAT_NUMBER_00,
            'F' => NumberFormat::FORMAT_NUMBER_00,
            'G' => NumberFormat::FORMAT_TEXT
        ];
    }
}
