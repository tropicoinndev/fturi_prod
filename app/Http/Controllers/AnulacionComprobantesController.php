<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storeanulacion_comprobantesRequest;
use App\Http\Requests\Updateanulacion_comprobantesRequest;
use App\Models\anticipos_cobros;
use App\Models\anulacion_comprobante_anticipos;
use App\Models\anulacion_comprobantes;
use App\Models\anulacion_registros;
use App\Models\anulaciones;
use App\Models\cobros;
use App\Models\comprobantes;
use App\Models\detalle_cobros;
use App\Models\registro;
use App\Models\tipo_comprobantes;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class AnulacionComprobantesController extends Controller
{
    private $table = 'anulacion_comprobantes';

    public function __construct()
    {
        $this->getTh($this->table, 'Anulacion de comprobantes');
        $this->th['index']['btnAdd'] = false;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     * cSpell:ignore anulacion
     */
    public function index()
    {
        return view('anulacion_comprobantes.index', [
            'th' => $this->th['index'],
            'p' => comprobantes::where('turnos_id', session('turno')->id)->orderBy('tipo_comprobantes_id', 'asc')->get(),
        ]);
    }

    public function list()
    {
        $finicio = date('Y-m-d');
        $ffin = date('Y-m-d');
        return view('anulacion_comprobantes.list', [
            'th' => $this->th['index'],
            'tipo_comprobantes' => tipo_comprobantes::whereIn('token', [7001, 7002])->get(),
            'p' => anulacion_comprobantes::leftJoin('comprobantes', 'comprobantes_id', 'comprobantes.id')
                ->whereBetween('anulacion_comprobantes.fecha', [$finicio, $ffin])
                ->orderBy('anulacion_comprobantes.fecha', 'desc')
                ->paginate(),
            'finicio' => $finicio,
            'ffin' => $ffin,
        ]);
    }
    #cSpell:ignore busqueda, finicio, ffin
    public function list_search(Request $r)
    {
        $finicio = $r->finicio ?? date('Y-m-d');
        $ffin = $r->ffin ?? date('Y-m-d');
        $tipo = '';
        $p = anulacion_comprobantes::leftJoin('comprobantes', 'comprobantes_id', 'comprobantes.id')->where(
            function ($q) use ($r) {
                $q->where(DB::raw('UPPER(comprobantes.titular)'), 'like', '%' . strtoupper($r->busqueda) . '%')
                    ->orWhere('anulacion_comprobantes.correlativo', 'like', '%' . $r->busqueda . '%');
            }
        )
            ->whereBetween('anulacion_comprobantes.fecha', [$finicio, $ffin]);

        if (isset($r->tipo_comprobante) && $r->tipo_comprobante != 0)
            $p = $p->where('comprobantes.tipo_comprobantes_id', $r->tipo_comprobante);


        $p = $p->orderBy('comprobantes.tipo_comprobantes_id')
            ->orderBy('comprobantes.correlativo')
            ->orderBy('anulacion_comprobantes.fecha')
            ->paginate();

        if ($r->tipo == 1)
            return view('anulacion_comprobantes.list', [
                'th' => $this->th['index'],
                'tipo_comprobantes' => tipo_comprobantes::whereIn('token', [7001, 7002])->get(),
                'p' => $p,
                'finicio' => $finicio,
                'ffin' => $ffin,
                'busqueda' => $r->busqueda,
                'tipo_comprobante' => $r->tipo_comprobante,
            ]);
        else {

            if (isset($r->tipo_comprobante) && $r->tipo_comprobante != 0) {
                $t = tipo_comprobantes::find($r->tipo_comprobante);
                $tipo = $t != null ? $t->tipo : 'No se reconoce el tipo de comprobante';
            } else $tipo = 'Todos los comprobantes';
            $view = view(
                'anulacion_comprobantes.print',
                [
                    'p' => $p,
                    'finicio' => $finicio,
                    'ffin' => $ffin,
                    'busqueda' => $r->busqueda,
                    'tipo_comprobante' => $tipo,
                ]
            )->render();

            $pdf = Pdf::loadHTML($view);
            $pdf->setPaper('letter', 'landscape');

            return $pdf->stream();
        }
    }


    public function search(Request $r)
    {
        $finicio = $r->finicio ?? Carbon::now()->subDays(90)->format('Y-m-d');
        $ffin = $r->ffin ?? Carbon::now()->format('Y-m-d');
        return view('anulacion_comprobantes.index', [
            'th' => $this->th['index'],
            'ffin' => $ffin,
            'finicio' => $finicio,
            'buscar' => $r->buscar,
            'p' => comprobantes::where(
                function ($q) use ($r) {
                    $q->where(DB::raw('UPPER(titular)'), 'like', '%' . strtoupper($r->buscar) . '%')
                        ->orWhere('correlativo', 'like', '%' . $r->buscar . '%');
                }
            )
                ->where('fecha', '>=', $finicio)
                ->where('fecha', '<=', $ffin)
                ->orderBy('tipo_comprobantes_id', 'asc')
                ->orderBy('fecha', 'asc')
                ->orderBy('correlativo', 'asc')
                ->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $p = comprobantes::findOrFail(Crypt::decryptString($id));
        return view('anulacion_comprobantes.create', [
            'th' => $this->th['create'],
            'p' => $p,
            'cobro' => cobros::where('comprobantes_id', $p->id)->first(),
            'anulaciones' => anulaciones::where('estado', true)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storeanulacion_comprobantesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storeanulacion_comprobantesRequest $r)
    {
        $c = comprobantes::findOrFail($r->id);
        try {
            $anulaciones_id = Crypt::decryptString($r->anulaciones_id);
            $reg = registro::where('comprobantes_id', $c->id)->get();
            $msg = 'Se activaron las siguientes cuentas:';
            foreach ($reg as $rg) {
                if (anulacion_registros::where('registro', $rg->registro)->where('tipo_registros', $rg->tipo_registros)->where('comprobantes_id', $rg->comprobantes_id)->count()  == 0) {
                    $ar = new anulacion_registros();
                    $ar->comprobantes_id = $rg->comprobantes_id;
                    $ar->registro = $rg->registro;
                    $ar->tipo_registros = $rg->tipo_registros;
                    $ar->save();
                }
                (new RegistroController)->activarCuenta($rg->registro, $rg->tipo_registros);
                switch ($rg->tipo_registros) {
                    case 1:
                        $msg = $msg . ' Orden No. ' . $rg->registro;
                        break;
                    case 2:
                        $msg = $msg . ' Estadía No. ' . $rg->registro;
                        break;
                    case 3:
                        $msg = $msg . ' Comanda No. ' . $rg->registro;
                        break;
                }
            }


            //Anticipos recuperación (Creación de tabla)

            registro::where('comprobantes_id', $c->id)->delete();

            $c->estado = false;
            $c->save();
            $a = new anulacion_comprobantes();
            $a->fecha = date('Y-m-d');
            $a->correlativo = $c->correlativo;
            $a->observacion = $r->observacion;
            $a->users_id = Auth::user()->id;
            $a->cajas_id = session('caja')->id;
            $a->turnos_id = session('turno')->id;
            $a->anulaciones_id = $anulaciones_id;
            $a->comprobantes_id = $c->id;
            $a->save();

            //Activación de anticipos
            $cobro = cobros::where('comprobantes_id', $c->id)->first();

            foreach ($cobro->anticipos as $ac) {
                if ($ac->aplicado) {
                    $aca = new anulacion_comprobante_anticipos;
                    $aca->monto = $ac->monto;
                    $aca->cobros_id = $ac->cobros_id;
                    $aca->anticipos_id = $ac->anticipos_id;
                    $aca->anulacion_comprobantes_id = $a->id;
                    if ($aca->save()) {
                        anticipos_cobros::destroy($ac->id);
                        (new AnticiposController)->devolucion($aca->anticipos_id, $aca->monto);
                    }
                }
            }
            $cobro->anulacion_comprobantes_id = $a->id;
            $cobro->save();
            detalle_cobros::where('cobros_id', $cobro->id)->update(['estado' => false]);

            return redirect()->route('anulacion_comprobantes.index')->with('message', 'Se anulo el comprobante No.' . $a->correlativo . ' ' . $msg);
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Error al anular, envié este mensaje a informatica@tropicoinn.com.sv, error: ' . $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\anulacion_comprobantes  $anulacion_comprobantes
     * @return \Illuminate\Http\Response
     */
    public function show(anulacion_comprobantes $anulacion_comprobantes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\anulacion_comprobantes  $anulacion_comprobantes
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $r) {}

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updateanulacion_comprobantesRequest  $request
     * @param  \App\Models\anulacion_comprobantes  $anulacion_comprobantes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $r)
    {
        try {
            $this->setTipo($r);
            return redirect()->back()->with('message', 'Se cambio el tipo de anulacion');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', 'danger')
                ->with('message', 'Ocurrió un error:' . $th->getMessage());
        }
    }

    public function setTipo($r)
    {
        $anulacion = Crypt::decryptString($r->anulacion);
        $tipoAnulacion = Crypt::decryptString($r->tipo_anulacion);
        $p = anulacion_comprobantes::find($anulacion);
        $p->anulaciones_id = $tipoAnulacion;
        $p->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\anulacion_comprobantes  $anulacion_comprobantes
     * @return \Illuminate\Http\Response
     */
    public function destroy(anulacion_comprobantes $anulacion_comprobantes)
    {
        //
    }
}
