<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BodegasEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public string $message;
    public int $tipo;
    public string $link;
    public $bodega_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($bodega_id, string $message= null, int $tipo =1, string $link= "")
    {
        $this->bodega_id = $bodega_id;
        $this->message = $message;
        $this->tipo = $tipo;
        $this->link = $link;
        //pendiente hacer correciones
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('bodegas.event.' .$this->bodega_id);
    }
}
