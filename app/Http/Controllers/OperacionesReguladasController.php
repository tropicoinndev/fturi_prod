<?php

namespace App\Http\Controllers;

use App\Exports\ViewToExcel;
use App\Http\Requests\Storeoperaciones_reguladasRequest;
use App\Http\Requests\Updateoperaciones_reguladasRequest;
use App\Models\clientes;
use App\Models\clientes_personas;
use App\Models\comprobantes;
use App\Models\forma_pagos;
use App\Models\operaciones_reguladas;
use App\Models\personas_naturales;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class OperacionesReguladasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('comprobantes.formularios.index', ['data' => operaciones_reguladas::orderByDesc('id')->paginate(15)]);
    }
    public function search(Request $r)
    {
        $data = operaciones_reguladas::whereIn('comprobantes_id', function ($q) use ($r) {
            $q->from('comprobantes')
                ->select('id')
                ->where(fn($sq) => $sq->where('procedencia', '!=', null)->orWhere('procedencia', '!=', null))
                ->where(DB::raw('UPPER(titular)'), 'like', '%' . strtoupper($r->busqueda) . '%');
        })->orWhere('fecha', 'like', "%" . $r->busqueda . "%")->orderByDesc('id')->paginate(100);
        return view('comprobantes.formularios.index', ['data' => $data, 'busqueda' => $r->busqueda]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $r)
    {
        $p = operaciones_reguladas::find(Crypt::decryptString($r->id));
        $personas = clientes_personas::where('clientes_id', $p->comprobante->clientes_id)->get();
        return view('comprobantes.formularios.operaciones_reguladas', [
            'p' => $p,
            'personas' => $personas,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storeoperaciones_reguladasRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(comprobantes $c)
    {
        try {
            $p = new operaciones_reguladas;
            $p->fecha = $c->fecha;
            $p->cajas_id = $c?->turnos?->cajas_id;
            $p->comprobantes_id = $c->id;
            $p->save();
        } catch (\Throwable $th) {
            Log::error('Error al guardar operación regulada: ' . $th->getMessage(), [
                'comprobante_id' => $c->id ?? null,
                'stack' => $th->getTraceAsString()
            ]);
        }
    }

    public function configurar(Request $r)
    {
        try {
            $id = Crypt::decryptString($r->id);
            $forma = forma_pagos::find(Crypt::decryptString($r->forma_pago));
            $p = operaciones_reguladas::find($id);

            $total = $this->getTotalFormaPago($p->comprobante->clientes_id, $p->fecha, $forma->token);
            $p->monto_sumatoria = $total;
            switch ($forma->token) {
                case 6001:
                    $p->efectivo = $p->comprobante->pagos->where('forma_pagos_id', $forma->id)->first()?->monto;
                    break;
                case 6003:
                    $p->tarjeta = $p->comprobante->pagos->where('forma_pagos_id', $forma->id)->first()?->monto;
                    break;
            }
            $p->forma_pagos_id = $forma->id;
            $p->users_id = Auth::user()->id;
            $p->save();
            return redirect()->back()->with('message', 'Se confirmo la forma de pago del comprobante, puede continuar con el llenado del formulario');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', 'Error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function seccionA(Request $r)
    {
        try {

            if ($r->distinto == 1 && (!isset($r->a_personas_naturales_id) || $r->a_personas_naturales_id == null))
                throw new Exception('Debe seleccionar / agregar una persona natural');

            $p = operaciones_reguladas::find(Crypt::decryptString($r->operacion));
            $p->distinto = $r->distinto == 1;
            $p->seccion_a_persona_id = $r->a_personas_naturales_id ?? null;
            $p->save();
            return redirect()->back()->with('message', 'Se completo la SECCION A: Persona que realiza físicamente la transacción');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', 'Error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function deleteSeccionA(Request $r)
    {
        try {
            $p = operaciones_reguladas::find(Crypt::decryptString($r->id));
            $p->distinto = null;
            $p->seccion_a_persona_id = null;
            $p->save();
            return redirect()->back()->with('message', 'Se elimino la información agregada');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', 'Error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    //cspell:ignore seccion, Storeoperaciones, Updateoperaciones, juridica, operacion
    public function seccionB(Request $r)
    {
        try {

            if ($r->tipo_persona == 1 && (!isset($r->b_personas_naturales_id) || $r->b_personas_naturales_id == null))
                throw new Exception('Debe seleccionar / agregar una persona natural');
            elseif ($r->tipo_persona == 0 && (!isset($r->b_personas_juridica_id) || $r->b_personas_juridica_id == null))
                throw new Exception('Debe seleccionar / agregar una persona jurídica');

            $p = operaciones_reguladas::find(Crypt::decryptString($r->operacion));
            $p->tipo_persona = $r->tipo_persona;

            if ($r->tipo_persona == 1) {
                $p->seccion_b_persona_id =  $r->b_personas_naturales_id;
            }

            //Personas jurídicas deben es la misma información de clientes jurídicos sin complementar.
            if ($r->tipo_persona == 0) {
                $cl = clientes::find($r->b_personas_juridica_id);
                if ($cl->tipo_cliente)
                    throw new Exception('El cliente seleccionado no es un cliente juridico o se desactivo como cliente juridico');
                $p->seccion_b_juridico_id =  $cl->id;
            }
            $p->save();

            return redirect()->back()->with('message', 'Se completo la SECCION B: Persona o Personas a cuyo nombre se realiza la transacción');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', 'Error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function deleteSeccionB(Request $r)
    {
        try {
            $p = operaciones_reguladas::find(Crypt::decryptString($r->id));
            $p->tipo_persona = null;
            $p->seccion_b_persona_id = null;
            $p->seccion_b_juridico_id = null;
            $p->save();
            return redirect()->back()->with('message', 'Se elimino la información agregada');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', 'Error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function parteII(Request $r)
    {
        try {
            $p = operaciones_reguladas::find(Crypt::decryptString($r->operacion));
            $p->clase_servicio = $r->clase_servicio;
            $p->observaciones = $r->observaciones;
            $p->efectivo = $r->efectivo;
            $p->tarjeta = $r->tarjeta;
            $p->cheque = $r->cheque;
            $p->cargo = $r->cargo;
            $p->save();
            if (isset($r->eprocedencia) && $r->eprocedencia && $r->procedencia != null && strlen($r->procedencia) > 3) {
                $comprobante = comprobantes::find($p->comprobantes_id);
                $comprobante->procedencia = $r->procedencia;
                $comprobante->save();
            }
            return redirect()->back()->with('message', 'Se completo la PARTE II - DETALLE DE LA TRANSACCIÓN EN EFECTIVO U OTRO MEDIO');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', 'Error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function getTotalFormaPago($cliente, $fecha, $forma_pago)
    {
        $f = Carbon::parse($fecha);
        return DB::table('get_comprobantes_pagos')
            ->whereMonth('fecha', $f->format('m'))
            ->whereYear('fecha', $f->format('Y'))
            ->where('clientes_id', $cliente)
            ->where('token', $forma_pago)
            ->sum('forma_pago_total');
    }


    public function completar(Request $r)
    {
        try {
            $p = operaciones_reguladas::find(Crypt::decryptString($r->id));
            $p->completado = true;
            $p->save();
            return redirect()->route('operaciones_reguladas.index')->with('message', 'Se completo el formulario de operacion regulada');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', 'Error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function descompletar(Request $r)
    {
        try {
            $p = operaciones_reguladas::find(Crypt::decryptString($r->id));
            $p->completado = false;
            $p->save();
            return redirect()->route('operaciones_reguladas.index')->with('message', 'Se habilito la edición del formulario de operacion regulada');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', 'Error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function formulario(Request $r)
    {
        return view('comprobantes.formularios.preview', [
            'p' => operaciones_reguladas::find(Crypt::decryptString($r->id))
        ]);
    }

    public function revision(Request $r)
    {
        $r->validate([
            'pass' => ['required', 'string'],
            'confirm' => ['required', 'accepted'],
        ]);
        try {
            if (!Hash::check($r->pass, Auth::user()->password))
                throw new Exception('La contraseña ingresada es incorrecta. Verifique y vuelva a intentar');

            $p = operaciones_reguladas::find(Crypt::decryptString($r->id));
            $p->revisado = true;
            $p->users_revisa_id = Auth::user()->id;
            $p->save();
            return redirect()->route('operaciones_reguladas.index')->with('message', 'Se completo la revision del formulario de operacion regulada');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', 'Error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function autorizacion(Request $r)
    {
        $r->validate(
            [
                'opcion' => ['required', 'integer', 'in:1,2'],
                'fecha' => ['nullable', 'date', 'required_if:opcion,1'],
                'pass' => ['required', 'string'],
                'confirm' => ['required', 'accepted'],
                'observaciones' => ['nullable', 'string', 'required_if:opcion,2'],
            ]
        );
        try {
            if (!Hash::check($r->pass, Auth::user()->password))
                throw new Exception('La contraseña ingresada es incorrecta. Verifique y vuelva a intentar');

            $p = operaciones_reguladas::find(Crypt::decryptString($r->id));
            $m = 'Se autorizo el formulario, ya puede imprimirse o descargarse.';
            switch ($r->opcion) {
                case 1:
                    $p->autorizado = true;
                    $p->users_autoriza_id = Auth::user()->id;
                    $p->fecha_envio = $r->fecha;
                    break;
                case 2:
                    $p->autorizado = false;
                    $p->users_autoriza_id = null;
                    $p->ob_autoriza = $r->observaciones;
                    $p->completado = false;
                    $p->revisado = false;
                    $p->users_revisa_id = null;
                    $m = 'Se rechazo el formulario';
                    break;
            }

            $p->save();
            return redirect()
                ->route('operaciones_reguladas.index')
                ->with('message', $m);
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', 'Error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function imprimir(Request $r)
    {
        try {

            $snap = SnappyPdf::loadView('comprobantes.formularios.print', [
                'p' => operaciones_reguladas::find(Crypt::decryptString($r->id))
            ])
                ->setPaper('letter')
                ->setOption('margin-top', '10mm')
                ->setOption('margin-bottom', '10mm')
                ->setOption('margin-left', '10mm')
                ->setOption('margin-right', '10mm');

            return $snap->inline('formulario_operaciones_reguladas.pdf');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', 'Error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function excel(Request $r)
    {
        try {

            $v = view('comprobantes.formularios.excel', ['p' => operaciones_reguladas::find(Crypt::decryptString($r->id))]);
            $rs = Excel::download(new ViewToExcel($v), 'operaciones_reguladas.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            ob_end_clean();
            return $rs;
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', 'Error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
}
