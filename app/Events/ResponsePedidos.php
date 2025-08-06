<?php

namespace App\Events;

use App\Models\comanda_detalles;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ResponsePedidos implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public comanda_detalles $comanda_detalles;
    public User $user_atiende;
    public int $caja_id;
    public $mensaje;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($caja_id, $comanda_detalles, $user_atiende, $mensaje = "")
    {
        $this->comanda_detalles = $comanda_detalles;
        $this->user_atiende = $user_atiende;
        $this->caja_id = $caja_id;
        $this->mensaje = $mensaje;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('pedidos.response.' . $this->caja_id);
    }
}
