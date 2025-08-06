<?php

namespace App\Console\Commands;

use App\Http\Controllers\ComandasController;
use Illuminate\Console\Command;

class comandasCreditos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'comandas:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Movimiento de comandas a crédito, y envió de avisos.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //
        return (new ComandasController)->addCredito();
    }
}
