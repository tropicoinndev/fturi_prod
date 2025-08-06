<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CajasEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public string $message;
    public int $tipo;
    public string $link;
    public $caja_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($caja_id, string $message = null, int $tipo = 1, string $link = "")
    {
        $this->caja_id = $caja_id;
        $this->message = $message;
        $this->tipo = $tipo;
        $this->link = $link ?? "";
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('cajas.event.' . $this->caja_id);
    }
}
