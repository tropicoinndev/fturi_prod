<?php

namespace App\Exports;

//cSpell:disable
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class viewExport implements FromView, ShouldAutoSize
{
    public $view;
    public function __construct(View $view)
    {
        $this->view = $view;
    }
    public function view(): View
    {
        return $this->view;
    }
}
