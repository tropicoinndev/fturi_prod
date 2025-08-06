<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storejob_contingenciasRequest;
use App\Http\Requests\Updatejob_contingenciasRequest;
use App\Models\contingencias;
use App\Models\dte_logs;
use App\Models\job_contingencias;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;


class JobContingenciasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storejob_contingenciasRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storejob_contingenciasRequest $r)
    {
        try {

            $contingencia = $this->getContingencia(Crypt::decryptString($r->contingencias_id));
            $p = new job_contingencias;
            $p->inicio = now();
            $p->estado = true;
            $p->contingencias_id = $contingencia->id;
            $p->inicio_users_id = Auth::user()->id;
            $p->save();
            return redirect()
                ->back()
                ->with('message', 'Se inicio a facturar en contingencia, a partir de este momento no se enviaran comprobantes a MH.');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('type', 'danger')
                ->with('message', 'Error al iniciar el modo contingencia: ' . $th->getMessage());
        }
    }


    public function autoCreate()
    {
        try {

            $contingencia = $this->getContingencia();
            $p = new job_contingencias;
            $p->inicio = now();
            $p->estado = true;
            $p->contingencias_id = $contingencia->id;
            $p->save();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function getContingencia($id = null)
    {
        $this->isValid();
        if ($id == null)
            $contingencia = contingencias::where('codigo', 1)->where('estado', true)->first();
        elseif ($id != null && $id > 0)
            $contingencia = contingencias::find($id);
        else
            throw new Exception('Contingencia ID no es valido');
        if ($contingencia == null)
            throw new Exception('No existe el tipo de contingencia con código 1');
        return $contingencia;
    }
    private function isValid()
    {
        $activa = job_contingencias::where('estado', true)->count();
        if ($activa > 0)
            throw new Exception('No se puede crear la contingencia, porque ya hay una contingencia activa.');
    }


    public function desactivar(Request $r)
    {
        try {
            $confirm = boolval($r->confirm);
            if (!$confirm)
                throw new Exception('No se puede cambiar porque no se confirmo, si requiere desactivarlo, confirme antes de enviar la solicitud.');
            $id = Crypt::decryptString($r->id);
            $p = job_contingencias::find($id);
            $p->fin = now();
            $p->estado = false;
            $p->fin_users_id = Auth::user()->id;
            $p->save();
            dte_logs::lastHour()->delete();
            return redirect()
                ->back()
                ->with('message', 'Se desactivo la contingencia con éxito.');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('type', 'danger')
                ->with('message', 'Error al desactivar: ' . $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatejob_contingenciasRequest  $request
     * @param  \App\Models\job_contingencias  $job_contingencias
     * @return \Illuminate\Http\Response
     */
    public function update(Updatejob_contingenciasRequest $request, job_contingencias $job_contingencias)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\job_contingencias  $job_contingencias
     * @return \Illuminate\Http\Response
     */
    public function destroy(job_contingencias $job_contingencias)
    {
        //
    }
}
