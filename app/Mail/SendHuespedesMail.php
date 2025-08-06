<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class SendHuespedesMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $fecha;
    protected $data;

    public function __construct()
    {
        $this->fecha = Carbon::now()->format('Y-m-d');
        $this->data = $this->huespedeAdultos();
    }

    private function huespedeAdultos()
    {

        return DB::table('huesped_recepciones')
            ->leftJoin('recepciones', 'huesped_recepciones.recepciones_id', '=', 'recepciones.id')
            ->leftJoin('huespedes', 'huesped_recepciones.huespedes_id', '=', 'huespedes.id')
            ->leftJoin('identificaciones', 'huespedes.identificaciones_id', '=', 'identificaciones.id')
            ->leftJoin('paises', 'huespedes.paises_id', '=', 'paises.id')
            ->leftJoin('habitaciones', 'recepciones.habitaciones_id', '=', 'habitaciones.id')
            ->whereBetween(
                DB::raw("'" . $this->fecha . "'"),
                [
                    DB::raw('recepciones.fecha_ingreso'),
                    DB::raw('recepciones.fecha_salida')
                ]
            )
            ->whereRaw(
                "DATE_PART('year', AGE(?, huespedes.nacimiento)) >= 18",
                [
                    $this->fecha
                ]
            )
            ->select(
                'huespedes.nombre as nombre',
                'huespedes.identificacion as documento',
                'identificaciones.identificacion as identificacion',
                'habitaciones.numero_habitacion as n_h',
                'paises.nacionalidad as p',
                'recepciones.fecha_ingreso as fecha_ingreso',
                'recepciones.fecha_salida as fecha_salida'
            )
            ->get();
    }

    public function build()
    {
        return $this
            ->subject('Reporte de Huespedes')
            ->view('mail.huesped')
            ->with([
                'fecha' => $this->fecha,
                'huespedes' => $this->data,
            ]);
    }
}
