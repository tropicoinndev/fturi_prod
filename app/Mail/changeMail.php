<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class changeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;


    private $body;

    public function tags()
    {
        return [
            'CambiosFTuri'
        ];
    }
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($body)
    {
        $this->body = $body;
    }
    public function build()
    {
        return $this->subject('Notificaciones FTuri')
            ->view('mail.change', ['body' => $this->body]);
    }
}
