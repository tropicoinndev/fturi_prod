<?php

namespace App\Mail;

use App\Http\Controllers\RecepcionesController;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SendDataTourMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;


    protected $name;

    public function __construct()
    {
        try {
            $fecha = Carbon::now();
            $primerDiaMesAnterior = $fecha->subMonthNoOverflow()->startOfMonth()->toDateString();
            $ultimoDiaMesAnterior = $fecha->endOfMonth()->toDateString();

            $this->name = $this->huespedeAdultos($primerDiaMesAnterior, $ultimoDiaMesAnterior);
            if (!Storage::disk('dtes')->exists($this->name)) {
                throw new \Exception('El archivo PDF no existe' . $this->name);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function huespedeAdultos($fecha_inicio, $fecha_fin)
    {
        return (new RecepcionesController)->dataTurExcelStore($fecha_inicio, $fecha_fin);
    }

    public function build()
    {
        return $this
            ->subject('Reporte de hospedaje: Hotel Tropico Inn')
            ->view('mail.datatour')
            ->attachFromStorageDisk("dtes", $this->name);
    }
}
