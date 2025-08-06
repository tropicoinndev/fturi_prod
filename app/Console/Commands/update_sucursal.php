<?php

namespace App\Console\Commands;

use App\Models\correlativo_sucursal;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class update_sucursal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'correlativos:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza los correlativos al inicio de cada año';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {

            $year = Carbon::now()->year;
            correlativo_sucursal::query()->update([
                'actual' => 0,
                'year' => $year,
            ]);
            Mail::raw('Notificación de tarea programada', function ($m) use ($year) {
                $m->to('soporte@tropicoinn.com.sv')
                    ->subject("Se reiniciaron los correlativos de las sucursales para el año {$year}");
            });
            $this->info("Los correlativos han sido actualizados exitosamente al año {$year}.");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Ocurrió un error al actualizar los correlativos: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
