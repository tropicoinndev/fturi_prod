<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PedidosCocina implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $comanda_detalles;
    public $caja;
    public $user_solicita;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($comanda_detalles, $caja, $user_solicita)
    {
        $this->comanda_detalles = $comanda_detalles;
        $this->caja = $caja;
        $this->user_solicita = $user_solicita;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('pedidos.cocina');
    }
}