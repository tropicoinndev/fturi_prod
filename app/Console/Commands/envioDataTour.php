<?php

namespace App\Console\Commands;

use App\Mail\SendDataTourMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class envioDataTour extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:datatour';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envió de huéspedes a correos de datatour';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //
        return Mail::to('dataturelsalvador@gmail.com')
            ->cc(['norvinrequeno@tropicoinn.com.sv'])
            ->queue(new SendDataTourMail());
    }
}
