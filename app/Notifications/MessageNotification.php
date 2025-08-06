<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class MessageNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public string $message;
    public int $tipo;
    public string $link;
    public function __construct(string $message = null, int $tipo = 1, string $link = null)
    {
        $this->message = $message;
        $this->tipo = $tipo;
        $this->link = $link;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast'];
    }

    public function toBroadcast($notifiable):BroadcastMessage
    {
        return new BroadcastMessage([
            'message' => $this->message,
            'tipo' => $this->tipo,
            'link' => $this->link
        ]);
    }
}