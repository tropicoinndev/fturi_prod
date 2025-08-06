<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;

use Illuminate\Queue\SerializesModels;

class ReporteHuespedesMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $fecha;
    protected $data;

    public function __construct($fecha, $data)
    {
        $this->fecha = $fecha;
        $this->data = $data;
    }

    public function build()
    {
        return $this
            ->subject('Reporte de Huespedes')
            ->view('mail.huesped')
            ->with([
                'fecha' => $this->fecha,
                'huespedes' => $this->data,
            ]);
    }
}
