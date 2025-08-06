<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class ViewToExcel implements FromView, ShouldAutoSize, WithColumnFormatting
{
    public $view;
    public $format;
    public function __construct(View $view, array $format_columns = [])
    {
        $this->view = $view;
        $this->format = $format_columns;
    }
    public function view(): View
    {
        return $this->view;
    }
    public function columnFormats(): array
    {
        return $this->format;
    }
}
