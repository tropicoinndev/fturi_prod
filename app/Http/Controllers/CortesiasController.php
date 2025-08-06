<?php

namespace App\Http\Controllers;

use Ably\Utils\Crypto;
use App\Exports\ViewToExcel;
use App\Http\Requests\StorecortesiasRequest;
use App\Http\Requests\UpdatecortesiasRequest;
use App\Models\cajas;
use App\Models\clientes;
use App\Models\comandas;
use App\Models\control_cortesias;
use App\Models\cortesias;
use App\Models\ordenes;
use App\Models\recepciones;
use App\Models\tipo_comprobantes;
use App\Models\tipo_cortesia;
use App\Models\User;
use App\Utils;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class CortesiasController extends Controller
{

    private $table = 'cortesias';

    public function __construct()
    {
        $this->getTh($this->table, 'Cortesias');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->table . '.index', [
            'th' => $this->th['index'],
            'p' => cortesias::orderBy('id', 'asc')
                ->where('autorizado', false)
                ->get(),
            'table' => $this->table,
            'data' => [
                'usuarios' => User::orderBy('id', 'DESC')->get(),
            ],
        ]);
    }
    public function autorizar()
    {
        return view('cortesias.autorizar', [
            'p' => cortesias::orderBy('id', 'asc')
                ->where('autorizado', false)
                ->where('autoriza_users_id', null)
                ->get(),
        ]);
    }

    public function detalle(Request $r)
    {
        return view('cortesias.detalle', [
            'p' => cortesias::find(Crypt::decryptString($r->id)),
        ]);
    }
    /** busqueda de cortesias */
    public function search(Request $r)
    {
        return view($this->table . '.index', [
            'th' => $this->th['index'],
            'p' => cortesias::where('id', 'ilike', '%' . $r->txtBusqueda . '%')
                ->orWhere('monto', 'ilike', '%' . $r->txtBusqueda . '%')
                ->orWhere('observacion', 'ilike', '%' . $r->txtBusqueda . '%')
                ->paginate(15),
            'txtBusqueda' => $r->txtBusqueda,
            'table' => $this->table,
            'data' => [
                'usuarios' => User::orderBy('id', 'ASC')->get(),
            ],
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->table . '.create', [
            'th' => $this->th['create'],
            'table' => $this->table,
            'data' => [
                'usuarios' => User::orderBy('id', 'asc')->get(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorecortesiasRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorecortesiasRequest $r)
    {
        $origen = Crypt::decryptString($r->origen);
        $id = Crypt::decryptString($r->id);
        $ruta = $origen == 1 ? 'ordenes.index' : ($origen == 2 ? 'recepciones.index' : ($origen == 3 ? 'comandas.index' : 'cajas.menu'));
        try {
            if (cortesias::where('origen', $origen)->where('origen_id', $id)->count() > 0)
                return throw new Exception('Esta cuenta ya fue agregada a cortesias..');

            //Validar y traer el objeto de la cuenta
            $p = $this->getCuenta($origen, $id);
            //Monto total de la cuenta
            $monto = $this->getMonto($origen, $p);

            $titular = control_cortesias::find(Crypt::decryptString($r->titular));
            $id = $origen == 1 ? $p->orden : $p->id;

            if ($titular == null || !$titular->estado)
                return throw new Exception('El titular seleccionado esta desactivado');

            //Control de monto en el titular
            $faltante = $titular->monto - $titular->consumo;
            if ($faltante < $monto)
                return throw new Exception('La cuenta sobre pasa el monto total asignado para este mes ' . date('m/Y') . ' sobrepasa por: $' . $monto - $faltante);

            //Creación de cortesía.
            $data = new cortesias();
            $data->fecha = date('Y-m-d');
            $data->monto = $monto;
            $data->origen = $origen;
            $data->origen_id = $id;
            $data->control_cortesias_id = $titular->id;
            $data->save();

            (new RegistroController)->desactivarCuenta($id, $origen);
            //Cambiar el tipo de comanda a cortesía
            if ($origen == 3) {
                $p->tipo_comanda = 3;
                $p->save();
            }
            return redirect()
                ->route($ruta)
                ->with('message', 'Se agrego a cortesía la cuenta')
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->route($ruta)
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function correccion(Request $r)
    {
        //cSpell:ignore cortesia,correccion
        $cortesia = cortesias::findOrFail(Crypt::decryptString($r->id));

        try {
            $cortesia->autorizado = false;
            $cortesia->autoriza_users_id = null;
            $cortesia->save();
            $origen = $cortesia->origen;
            (new RegistroController)->desactivarCuenta($cortesia->origen_id, $cortesia->origen);
            //Cambiar el tipo de comanda a cortesía
            if ($origen == 3) {
                $p = comandas::find($cortesia->origen_id);
                $p->tipo_comanda = 3;
                $p->save();
            }
            $ruta = $origen == 1 ? 'ordenes.index' : ($origen == 2 ? 'recepciones.index' : ($origen == 3 ? 'comandas.index' : 'cajas.menu'));
            return redirect()
                ->route($ruta)
                ->with('message', 'Se agrego a cortesía la cuenta')
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('type', 'danger')
                ->with('message', 'Ocurrió un error: ' . $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\cortesias  $cortesias
     * @return \Illuminate\Http\Response
     */
    public function show(cortesias $cortesias)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\cortesias  $cortesias
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            return view($this->table . '.edit', [
                'th' => $this->th['edit'],
                'p' => cortesias::findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,
                'data' => [
                    'usuarios' => User::orderBy('id', 'asc')->get(),
                ],
            ]);
        } catch (\Throwable $th) {
            return to_route($this->table . '.index')
                ->with('message', 'Error al contrar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatecortesiasRequest  $request
     * @param  \App\Models\cortesias  $cortesias
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatecortesiasRequest $request)
    {
        try {
            $p = cortesias::findOrFail($request->id);
            $p->fecha = now();
            $p->monto = $request->monto;
            $p->origen = $request->origen;
            $p->origen_id = $request->origen_id;
            $p->facturada = isset($request->facturada) ? $request->facturada : false;
            $p->autorizado = isset($request->autorizado) ? $request->autorizado : false;
            $p->observacion = $request->observacion;
            $p->autoriza_users_id = isset($request->autoriza_users_id) ? $request->autoriza_users_id : null;
            $p->save();

            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Registro guardado correctamente: ' . $p->origen)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    /**fuction for confirm */
    public function confirm($id)
    {
        try {
            return view('confirm', [
                'th' => $this->th['confirm'],
                'p' => cortesias::findOrFail(Crypt::decryptString($id)),
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrio un error (' . $th->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\cortesias  $cortesias
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            if (!isset($r->id) || empty(trim($r->id))) {
                return redirect()
                    ->route($this->table . '. index')
                    ->with('message', 'Ocurrior un error, el identificador de registro no cumple con los requerimientos necesarios.')
                    ->with('type', 'danger');
            }
            $p = cortesias::findOrFail(Crypt::decryptString($r->id));
            $p->delete();
            return to_route($this->table . '.index')->with('message', 'Registro eliminado con exito: ' . $p->origen);
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrio un error (' . $th->getMessage() . ')')
                ->with('type', 'danger');
        }
    }


    public function aplicar(Request $r)
    {
        try {
            if (cortesias::where('origen', Crypt::decryptString($r->origen))->where('origen_id', Crypt::decryptString($r->id))->count() > 0)
                return throw new Exception('Esta cuenta ya fue agregada a cortesias..');
            return view('cortesias.aplicar', [
                'p' => $this->getCuenta(Crypt::decryptString($r->origen), Crypt::decryptString($r->id)),
                'origen' => Crypt::decryptString($r->origen),
                'tipoCortesia' => tipo_cortesia::where('estado', true)->orderBy('tipo')->with('titulares')->get(),
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Ocurrió un error al aplicar la cortesía: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function getCuenta($origen, $id)
    {
        switch ($origen) {
            case 1:
                $p = ordenes::find($id);
                if (!$p->estado) return throw new Exception('La orden esta inhabilitada, por favor verifique si ya fue aplicada por otra persona.');
                return $p;
                break;
            case 2:
                $p = recepciones::find($id);
                if (!$p->estado || $p->facturada || $p->eliminado) return throw new Exception('La comanda esta inhabilitada, por favor verifique si ya fue aplicada por otra persona.');
                return $p;
                break;
            case 3:
                $p = comandas::find($id);
                if (!$p->estado || $p->facturada || $p->eliminada || $p->anulada) return throw new Exception('La comanda esta inhabilitada, por favor verifique si ya fue aplicada por otra persona.');
                return $p;
                break;
        }
    }
    private function getMonto($origen, $p)
    {
        switch ($origen) {
            case 1:
                return round($p->getSumDetalleOrden(), 2);
                break;
            case 2:
                return round($p->tarifas->precio * $p->dias, 2);
                break;
            case 3:
                return round($p->detalles_comanda->sum('total'), 2);
                break;
            default:
                return 0;
                break;
        }
    }
    //cSpell:ignore opcion, accion, observacion
    public function authAccion(Request $r)
    {
        try {
            $r->validate([
                'password' => ['required', 'string'],
                'observacion' => ['nullable', 'string', 'max:200'],
                'confirm' => ['required', 'in:1'],
                'opcion' => ['required', 'string'],
                'id' => ['required', 'string']
            ]);
            if (!Hash::check($r->password, Auth::user()->password))
                return throw new Exception('Contraseña incorrecta');
            $p = cortesias::findOrFail(Crypt::decryptString($r->id));
            $p->observacion = $r->observacion;
            $p->autoriza_users_id = Auth::user()->id;
            $p->autorizado = true;
            $p->estado = intval(Crypt::decryptString($r->opcion)) == 1;
            $p->save();
            if (!$p->estado)
                $this->negacionCuenta($p);
            return redirect()->route('cortesias.autorizar')->with('message', 'Se realizo la accion con éxito');
        } catch (\Throwable $th) {
            return redirect()->back()->with('type', 'danger')
                ->with('message', 'Error al realizar la accion, detalles del error: ' . $th->getMessage());
        }
    }

    private function negacionCuenta($p)
    {
        if ($p) {
            $c = comandas::find($p->origen_id);
            $c->comprobante = false;
            $c->facturada = false;
            $c->estado = true;
            $c->save();
        }
    }

    public function comprobante(Request $r)
    {
        return view('cortesias.comprobante', [
            'cuentas' => cortesias::where('facturada', false)
                ->where('estado', true)
                ->where('autoriza_users_id', '>', 0)
                ->with(['titular'])
                ->orderBy('origen')
                ->get(),
            'cajas' => cajas::where('estado', true)->get(),
            'tipo_comprobantes' => tipo_comprobantes::whereIn('token', [7001, 7002])->get(),
        ]);
    }

    public function precios(Request $r)
    {
        return view('cortesias.precios', [
            'cuentas' => cortesias::where('facturada', false)
                ->where('estado', true)
                ->where('autoriza_users_id', '>', 0)
                ->with(['titular'])
                ->orderBy('origen')
                ->get(),
            'tipos_cortesias' => tipo_cortesia::where('estado', true)->get(),
        ]);
    }
    public function cobro(Request $r)
    {
        $r->validate([
            "clientes_id" => ['required', 'numeric'],
            'cajas_id' => ['required', 'string'],
            'comprobante' => ['required', 'string'],
            'cuentas' => ['required', 'array'],
        ]);
        try {
            $cliente = clientes::find($r->clientes_id);
            if ($cliente == null || !$cliente->estado)
                return throw new Exception('El cliente seleccionado es invalido o esta desactivado, intente con otro cliente, o revise a detalle el problema con este cliente.');
            $caja = cajas::find(Crypt::decryptString($r->cajas_id));
            if ($caja == null || !$caja->estado)
                return throw new Exception('La caja seleccionado es invalida o esta desactivada, intente con otra caja, o revise a detalle el problema con esta caja.');
            $comprobante = Crypt::decryptString($r->comprobante);
            $detalle = array();
            foreach ($r->cuentas as $v) {
                $c = cortesias::find($v);
                if (!$c->autorizado || !$c->estado || $c->facturada || $c->autoriza_users_id == null)
                    return throw new Exception('La cortesia con identificador: ' . $c->id . ' no esta autorizada, activa, o esta facturada.');
                array_push($detalle, $c);
            }
            $cobro = (new CobrosController)->createCobro($comprobante, $cliente->id, $caja->id);

            foreach ($detalle as $d) {
                (new CobrosController)->createDetalle($d->origen, $d->origen_id, $cobro->id);
                (new RegistroController)->activarCuenta($d->origen_id, $d->origen);
                $d->estado = false;
                $d->save();
            }

            return redirect()->back()
                ->with('message', 'Se creo el cobro con éxito' .  '  #' . $cobro->id);
        } catch (\Throwable $th) {
            return redirect()->back()->with('type', 'danger')
                ->with('message', 'Error al generar el cobro, detalles del error: ' . $th->getMessage());
        }
    }

    public function reporte(Request $r)
    {
        return view(
            'cortesias.reportes',
            [
                'tipos_cortesias' => tipo_cortesia::where('estado', true)->get(),
            ]
        );
    }

    public function reporteOpcion(Request $r)
    {
        $inicio = $r->inicio;
        $fin = $r->fin;
        $opcion = (int) $r->opcion;
        $tipo = $r->tipo;
        $cortesia = cortesias::whereBetween('fecha', [$inicio, $fin])->where('autorizado', true)->where('autoriza_users_id', '>', 0);
        $titular = control_cortesias::where('estado', true);
        $tipos_cortesias = tipo_cortesia::where('estado', true)->get();
        $filtro_cortesia = "TODOS LOS TIPOS DE CORTESIAS";
        if ($tipo > 0) {
            $tipoData = tipo_cortesia::where('id', $tipo)->get();

            $filtro_cortesia = $tipoData[0]->tipo;
            $titular = $titular->where('tipo_cortesias_id', $tipo)->get();
            $cortesia = $cortesia->whereIn('control_cortesias_id', $titular->pluck('id'));
        } else {
            $titular = $titular->get();
            $tipoData = $tipos_cortesias;
        }
        $cortesia = $cortesia->get();
        //return $cortesia;
        switch ($opcion) {
            case 1:
                return view('cortesias.reportes', [
                    'tipos_cortesias' => $tipos_cortesias,
                    'inicio' => $inicio,
                    'fin' => $fin,
                    'tipo' => $tipo,
                    'data' => $cortesia,
                    'tipoData' => $tipoData,
                    'titular' => $titular,
                ]);
                break;
            case 2:

                $pdf = Utils::getPdf();
                $pdf->loadView('cortesias.reporte_print', [
                    'tipos_cortesias' => $tipos_cortesias,
                    'tipo_cortesias' => $filtro_cortesia,
                    'fecha' => $inicio . ' - ' . $fin,
                    'tipo' => $tipo,
                    'data' => $cortesia,
                    'tipoData' => $tipoData,
                    'titular' => $titular,
                ]);
                $pdf->setPaper('letter', 'landscape');
                return $pdf->stream();
                break;
            case 3:
                $view = view(
                    'cortesias.reporte_excel',
                    [
                        'tipos_cortesias' => $tipos_cortesias,
                        'tipo_cortesias' => $filtro_cortesia,
                        'fecha' => $inicio . ' - ' . $fin,
                        'tipo' => $tipo,
                        'data' => $cortesia,
                        'tipoData' => $tipoData,
                        'titular' => $titular,
                    ]
                );
                $name_doc = 'reporte_cortesias_' . $inicio . '_al_' . $fin . '.xlsx';
                $format = [
                    'E' => NumberFormat::FORMAT_ACCOUNTING_USD,
                    'F' => NumberFormat::FORMAT_ACCOUNTING_USD,
                    'G' => NumberFormat::FORMAT_ACCOUNTING_USD,
                ];
                $rs = Excel::download(new ViewToExcel($view, $format), $name_doc, \Maatwebsite\Excel\Excel::XLSX);
                ob_end_clean();
                return $rs;
                break;
            default:
                return redirect()->route('cortesias.reporte')->with('message', 'Opcion no encontrada');
                break;
        }
    }
}
