<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storedte_logsRequest;
use App\Http\Requests\Updatedte_logsRequest;
use App\Models\dte_logs;
use App\Models\dtes;
use App\Models\job_contingencias;
use Carbon\Carbon;

class DteLogsController extends Controller
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

    public function store(dtes $dte, $rs = null)
    {
        try {
            $p = new dte_logs;
            $p->hora = now();
            $p->response = $rs;
            $p->comprobantes_id = $dte->comprobantes_id;
            $p->dtes_id = $dte->id;
            $p->save();

            $this->needContingencia();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function needContingencia()
    {
        try {
            if (dte_logs::lastHour()->count() >= 3) {
                dte_logs::lastHour()->delete();
                (new JobContingenciasController)->autoCreate();
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\dte_logs  $dte_logs
     * @return \Illuminate\Http\Response
     */
    public function show(dte_logs $dte_logs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\dte_logs  $dte_logs
     * @return \Illuminate\Http\Response
     */
    public function edit(dte_logs $dte_logs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatedte_logsRequest  $request
     * @param  \App\Models\dte_logs  $dte_logs
     * @return \Illuminate\Http\Response
     */
    public function update(Updatedte_logsRequest $request, dte_logs $dte_logs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\dte_logs  $dte_logs
     * @return \Illuminate\Http\Response
     */
    public function destroy(dte_logs $dte_logs)
    {
        //
    }
}
