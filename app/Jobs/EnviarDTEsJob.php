<?php

namespace App\Jobs;

use App\Models\dteBase;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EnviarDTEsJob extends dteBase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $comprobante;
    public $sujeto;
    public $url_mh;

    public function tags()
    {
        return ['EnviarDTEs', 'DTEs'];
    }
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($comprobantes_id = null, $sujeto_excluidos_id = null)
    {
        $this->comprobante = $comprobantes_id;
        $this->sujeto = $sujeto_excluidos_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {

            parent::__construct($this->comprobante, null, $this->sujeto);
            parent::setDte();
        } catch (\Throwable $th) {
            throw $th;
            $this->setLog($th->getMessage());
        }
    }
}
