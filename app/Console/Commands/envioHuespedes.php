<?php

namespace App\Console\Commands;

use App\Mail\SendHuespedesMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class envioHuespedes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:huesped';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envió de huéspedes a correos de gobierno';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //
        return Mail::to('huespedesenhoteles.d@pnc.gob.sv')
            ->cc(['red@tropicoinn.com.sv', 'auditoriainterna@tropicoinn.com.sv'])
            ->queue(new SendHuespedesMail());
    }
}
