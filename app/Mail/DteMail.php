<?php

namespace App\Mail;

use App\Models\dtes;
use App\Models\dtesFiles;
use App\Utils;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class DteMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $dte;
    private $json;
    private $nameJson;
    private $namePdf;

    public function tags()
    {
        return [
            'DteMail',
            "mail:" . $this->dte->codigo_generacion
        ];
    }
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($dte)
    {
        $this->dte = $dte;

        try {
            $files = new dtesFiles($dte);

            $this->nameJson = $files->getJson();

            $this->namePdf = $files->getPdf();

            if (!Storage::disk('dtes')->exists($this->namePdf)) {
                throw new \Exception('El archivo PDF no existe' . $this->namePdf);
            }
            if (!Storage::disk('json')->exists($this->nameJson)) {
                throw new \Exception('El archivo JSON no existe' . $this->nameJson);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function build()
    {
        return $this->subject('TURÍSTICAS DE ORIENTE S.A. DE C.V. DTE: ' . $this->dte->codigo_generacion)
            ->view('mail.dte', ['dte' => $this->dte])
            ->attachFromStorageDisk("json", $this->nameJson)
            ->attachFromStorageDisk("dtes", $this->namePdf);
    }
}
