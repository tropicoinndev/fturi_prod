<?php

namespace App\Listeners;
use App\Events\BodegasEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EnviarNotificacionBodegaSalida
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        Notification::send(BodegaSalida::find($event->bodegaSalidaId), new RequisicionCompletadaNotification($event->requisicionesId));
    }
}