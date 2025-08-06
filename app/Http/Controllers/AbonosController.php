<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreabonosRequest;
use App\Http\Requests\UpdateabonosRequest;
use App\Models\abonos;
use App\Models\abonos_detalles;
use App\Models\forma_pagos;
use App\Utils;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;

class AbonosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('abonos.index', ['data' => abonos::where('turnos_id', session('turno')->id)->paginate()]);
    }

    public function search(Request $r)
    {
        $r->validate([
            'buscar' => ['required', 'numeric', 'min:1']
        ]);
        try {

            if (!isset($r->buscar)) throw new Exception('Debe escribir un numero valido');
            return view('abonos.index', ['data' => abonos::where('id', $r->buscar)->where('cajas_id')->paginate()]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Error: ' . $th->getMessage())->with('type', 'danger');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('abonos.create', ['forma_pagos' => forma_pagos::whereNotIn('token', [6004, 6002])->get()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreabonosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreabonosRequest $r)
    {
        try {
            $comprobantes = $this->getComprobantesInterface($r->clientes_id);
            if ($comprobantes->count() == 0)
                throw new Exception("No hay comprobantes en la forma de pago credito para este cliente.");
            $p = new abonos;
            $p->fecha = date('Y-m-d');
            $p->users_id = Auth::user()->id;
            $p->clientes_id = $r->clientes_id;
            $p->caja_users_id = Auth::user()->id;
            $p->turnos_id = session('turno')->id;
            $p->forma_pagos_id = Crypt::decryptString($r->forma_pagos);
            $p->save();
            return redirect()->route('abonos_detalles.create', ['id' => Crypt::encryptString($p->id)]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Ocurrio un problema al agregar ' . $th->getMessage())->with('type', 'danger');
        }
    }

    public function getComprobantesInterface($cliente)
    {
        return DB::table('getcomprobantescreditos')->where('clientes_id', $cliente);
    }
    public function confirm($id)
    {
        try {
            $th['table'] = 'abonos';
            return view('confirm', [
                'th' => $th,
                'p' => abonos::findOrFail(Crypt::decryptString($id)),
            ]);
        } catch (\Throwable $t) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

    public function print(Request $r)
    {
        $p = abonos::find(Crypt::decryptString($r->id));


        $pdf = $this->getPDF();
        $pdf->loadView(
            'abonos.print',
            [
                'p' => $p,
                'letras' => (new Utils)->toMoney($p->monto)
            ]
        );
        //return $view;
        $pdf->setPaper('letter');
        return $pdf->stream();
    }

    protected function getPDF(): DomPDFPDF
    {
        $pdf = PDF::getFacadeRoot();
        $dompdf = $pdf->getDomPDF();
        $dompdf->setHttpContext(stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE
            ],
        ]));
        return $pdf;
    }
    public function impresion(Request $r)
    {
        return view('abonos.container_print', ['url' => route('abonos.print', ["id" => $r->id])]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\abonos  $abonos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r, abonos $abonos)
    {
        try {
            $p = $abonos::findOrFail(Crypt::decryptString($r->id));
            if (abonos_detalles::where('abonos_id', $p->id)->where('estado', true)->count() > 0)
                throw new Exception('No se puede eliminar este ingreso a caja, porque tiene comprobantes asignados');
            $p->delete();
            return redirect()->route('abonos.index')->with('message', 'Se elimino un registro');
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Ocurrio un problema al eliminar: ' . $th->getMessage())->with('type', 'danger');
        }
    }
}
