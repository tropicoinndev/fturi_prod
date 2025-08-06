<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class comandasMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;


    public $comandas_creditos;
    public $reporte_activas;

    public function tags()
    {
        return [
            'ComandasActivas'
        ];
    }
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($comandas_creditos, $reporte_activas)
    {
        $this->comandas_creditos = $comandas_creditos;
        $this->reporte_activas = $reporte_activas;
    }

    public function build()
    {
        return $this->subject('Notificaciones FTuri')
            ->view('comandas.creditos_mail');
    }
}
