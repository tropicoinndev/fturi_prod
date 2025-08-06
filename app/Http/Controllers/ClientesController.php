<?php

namespace App\Http\Controllers;

use App\Http\Requests\duplicarClienteRequest;
use App\Http\Requests\StoreclientesRequest;
use App\Http\Requests\UpdateclientesRequest;
use App\Models\actividades_economicas;
use App\Models\cliente_identificaciones;
use App\Models\clientes;
use App\Models\clientes_contactos;
use App\Models\clientes_giros;
use App\Models\clientes_identificaciones;
use App\Models\comandas;
use App\Models\comprobantes;
use App\Models\contactos;
use App\Models\departamentos;
use App\Models\descuentos;
use App\Models\detalle_contribuyentes;
use App\Models\Enums\niveles_cautela;
use App\Models\giros;
use App\Models\identificaciones;
use App\Models\municipios;
use App\Models\ordenes;
use App\Models\paises;
use App\Models\periodos_creditos;
use App\Models\recepciones;
use App\Models\solicitantes;
use App\Models\solicitantes_clientes;
use App\Models\sucursales;
use App\Models\vcreditos_pendientes;
use App\Models\ventas_credito;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Throwable;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ViewToExcel;

class ClientesController extends Controller
{
    private $table = 'clientes';
    public $categorias;

    public function __construct()
    {
        $this->getTh($this->table, 'Clientes');
        $this->categorias = [
            ['id' => 1, 'categoria' => ' Pequeño'],
            ['id' => 2, 'categoria' => 'Mediano'],
            ['id' => 3, 'categoria' => 'Grande'],
            ['id' => 4, 'categoria' => 'Otros'],
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->table . '.index', [
            'th'   => $this->th['index'],
            'p'    => clientes::with([
                'contactos',
                'identificaciones',
                'detalle',
                'municipios',
                'actividades',
                'extranjero'
            ])->orderBy('id', 'DESC')->paginate(15),
            'table' => $this->table,
            'categorias' => $this->categorias,
            'data' => [
                'actividades' => actividades_economicas::orderBy('actividad', 'DESC')->get(),
                'categorias' => $this->categorias
            ],
        ]);
    }

    public function search(Request $r)
    {
        return view($this->table . '.index', [
            'th'         => $this->th['index'],
            'p'          => clientes::with([
                'contactos',
                'identificaciones',
                'detalle',
                'municipios',
                'actividades',
                'extranjero'
            ])->where('nombre', 'ilike', '%' . $r->txtBusqueda . '%')
                ->orWhere('email', 'ilike', '%' . $r->txtBusqueda . '%')
                ->paginate(200),
            'txtBusqueda' => $r->txtBusqueda,
            'table'      => $this->table,
            'categorias' => $this->categorias,
            'data' => [
                'categorias' => $this->categorias
            ],
        ]);
    }

    public function apiSearchClientes2(Request $r)
    {

        return response([
            'clientes' => clientes::where('nombre', 'ilike', '%' . $r->txtBusqueda . '%')->get(),
            'txt'     => $r->txtBusqueda,
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
            'th'   => $this->th['create'],
            'table' => $this->table,
            'data' => [
                'categorias' => $this->categorias
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreclientesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreclientesRequest $request)
    {
        try {
            $data = new Clientes;
            $data->nombre       = $request->nombre;
            $data->email       = $request->email;
            $data->direccion    = $request->direccion;
            $data->observaciones = $request->observaciones;
            $data->tipo_cliente = $request->tipo_cliente;
            $data->credito      = false;
            $data->estado       = $request->tipo_cliente;
            $data->categoria       = $request->categoria;
            $data->municipios_id = $request->municipios_id ?? null;
            $data->extranjeros_id = $request->extranjeros_id ?? null;
            $data->actividades_economicas_id = $request->actividades_economicas_id;
            $data->save();

            return redirect()
                ->route($this->table . '.show', ['id' => Crypt::encryptString($data->id)])
                ->with('message', 'Registro guardado correctamente: ' . $data->nombre)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\clientes  $clientes
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cliente = clientes::findOrFail(Crypt::decryptString($id));

        return view($this->table . '.show', [
            'th' => $this->th['show'] = [
                'title' => $cliente->nombre,
                'sub'  => 'Detalles ' . $cliente->tipo_cliente ? 'del cliente' : 'de la empresa',
                'table' => $this->table,
                'bread' => $this->table . '.show'
            ],
            'p'                 => $cliente,
            'detalle'           => detalle_contribuyentes::where('clientes_id', $cliente->id)->first(),
            'table'             => $this->table,
            'identificaciones'  => identificaciones::where('tipo_cliente', $cliente->tipo_cliente)->where('estado', true)->get(),
            'contactos'         => contactos::where('estado', true)->get(),
            'giros'             => $cliente->tipo_cliente ? null : giros::where('estado', true)->get(),
            'clIdenti'          => clientes_identificaciones::where('clientes_id', $cliente->id)->get(),
            'clContacto'        => clientes_contactos::where('clientes_id', $cliente->id)->get(),
            'clGiro'            => clientes_giros::where('clientes_id', $cliente->id)->get(),
            'solicitantes'      => solicitantes_clientes::where('clientes_id', $cliente->id)->get(),
            'categorias'        => $this->categorias,
            'data' => [
                'identificaciones' => identificaciones::orderBy('identificacion', 'asc')->get(),
            ],
            'periodosCreditos' => periodos_creditos::orderBy('periodo', 'asc')->where('estado', true)->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\clientes  $clientes
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            return view($this->table . ".edit", [
                'th'   => $this->th['edit'],
                'p'    => clientes::findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,
                'data' => [
                    'categorias' => $this->categorias
                ],
            ]);
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al encontrar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateclientesRequest  $request
     * @param  \App\Models\clientes  $clientes
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateclientesRequest $request)
    {
        try {

            $p = clientes::findOrFail($request->id);
            $p->nombre = $request->nombre;
            $p->email = $request->email;
            $p->direccion = $request->direccion;
            $p->observaciones = $request->observaciones;
            $p->tipo_cliente = $request->tipo_cliente;
            $p->descuentos_id = $request->descuentos_id;
            $p->municipios_id = $request->municipios_id ?? null;
            $p->extranjeros_id = $request->extranjeros_id ?? null;
            $p->actividades_economicas_id = $request->actividades_economicas_id;
            $p->categoria = $request->categoria;
            $p->save();

            return redirect()->route($this->table . '.index')
                ->with('message', 'Registro editado correctamente: ' . $p->nombre)
                ->with('type', 'info');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al editar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\clientes  $clientes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            if (!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table . '.index')
                    ->with('message', 'Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type', 'danger');
            $cl = clientes::find(Crypt::decryptString($r->id));
            if (($cl->contactos->count() > 0) || ($cl->identificaciones->count() > 0)) {
                return redirect()->route($this->table . '.index')
                    ->with('message', 'No se puede eliminar el cliente porque tiene identificaciones o contactos asociados.')
                    ->with('type', 'warning');
            }
            if (($cl->ordenesActivas->count() > 0) || ($cl->comandasActivas->count() > 0) || ($cl->recepcionesActivas->count() > 0) || ($cl->anticipos->count() > 0)) {
                return redirect()->route($this->table . '.index')
                    ->with('message', 'No se puede eliminar el cliente porque tiene cuentas activas o anticipos  asociadas.')
                    ->with('type', 'warning');
            }
            detalle_contribuyentes::where('clientes_id', $cl->id)->delete();
            $cl->delete();

            return redirect()->route($this->table . '.index')
                ->with('message', 'Registro eliminado con exito');
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
    public function confirm($id)
    {
        try {
            return view("confirm", [
                'th' => $this->th['confirm'],
                'p' => Clientes::findOrFail(Crypt::decryptString($id))
            ]);
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

    public function status($id)
    {
        try {
            $p = clientes::findOrFail(Crypt::decryptString($id));
            $p->estado = !$p->estado;
            $p->save();

            return redirect()->back()
                ->with('message', 'Estado modificado correctamente: ' . $p->nombre)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al cambiar estado: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function credito($id)
    {
        try {
            $p = clientes::findOrFail(Crypt::decryptString($id));
            $p->credito = !$p->credito;
            $p->save();

            return redirect()->back()
                ->with('message', 'Se modificado la opcion credito de este cliente')
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al cambiar estado: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function ccf($id)
    {
        try {
            $p = clientes::findOrFail(Crypt::decryptString($id));
            $p->ccf = !$p->ccf;
            $p->save();

            return redirect()->back()
                ->with('message', 'Se modificado que permita credito fiscal de este cliente')
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al cambiar que permita credito fiscal: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function retencion($id)
    {
        try {
            $p = detalle_contribuyentes::findOrFail(Crypt::decryptString($id));
            $p->percepcion = !$p->percepcion;
            $p->save();

            return redirect()->back()
                ->with('message', 'Se modificado la opcion retencion de este cliente')
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al cambiar estado: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function getPersonasNaturales(Request $r)
    {
        $dataView = DB::table('getclientes')
            ->where('cliente', 'ilike', '%' . $r->busqueda . '%')
            ->orWhere('nombre', 'ilike', '%' . $r->busqueda . '%')
            ->orWhere('numero', 'ilike', '%' . $r->busqueda . '%')
            ->get();

        $dataClientes = clientes::whereIn('id', $dataView->pluck('id'))
            ->with(['municipiosDepartamentos', 'extranjero', 'identificaciones'])
            ->get();

        return response()->json([
            'clientes' => $dataClientes,
            'busqueda' => $r->busqueda,
        ]);
    }

    public function getClientes(Request $r)
    {
        return response()->json([
            'clientes' => DB::table('getclientes')
                ->where('cliente', 'like', '%' . $r->busqueda . '%')
                ->orWhere('nombre', 'like', '%' . $r->busqueda . '%')
                ->orWhere('numero', 'like', '%' . $r->busqueda . '%')
                ->get(),
            'busqueda' => $r->busqueda,
        ]);
    }
    public function getClientesList(Request $r)
    {
        return response()->json([
            'list' => DB::table('getclientes')
                ->where('cliente', 'like', '%' . strtoupper($r->busqueda) . '%')
                ->orWhere('nombre', 'like', '%' . $r->busqueda . '%')
                ->orWhere('numero', 'like', '%' . $r->busqueda . '%')
                ->get(),
            'busqueda' => $r->busqueda,
        ]);
    }
    public function getClientesListNaturales(Request $r)
    {
        return response()->json([
            'list' => DB::table('getclientes')
                ->where('tipo_cliente', true)
                ->where(function ($q) use ($r) {
                    $q->where('cliente', 'like', '%' . strtoupper($r->busqueda) . '%')
                        ->orWhere('nombre', 'like', '%' . $r->busqueda . '%')
                        ->orWhere('numero', 'like', '%' . $r->busqueda . '%');
                })

                ->get(),
            'busqueda' => $r->busqueda,
        ]);
    }
    public function cuentas(Request $r)
    {

        $r->validate([
            'clientes_id' => ['required', 'numeric'],
            'origen' => ['required', 'string'],
            'origen_id' => ['required', 'string'],
        ]);
        try {
            $cl = clientes::find($r->clientes_id);
            $origen = Crypt::decryptString($r->origen);
            $origen_id = Crypt::decryptString($r->origen_id);
            $p = null;
            switch ($origen) {
                case 1:
                    $p = ordenes::find($origen_id);
                    break;
                case 2:
                    $p = recepciones::find($origen_id);
                    break;
                case 3:
                    $p = comandas::find($origen_id);
                    break;
            }
            if ($p != null) {
                $p->clientes_id = $cl->id;
                $p->save();
            } else return throw new Exception('No se pudo encontrar la cuenta.');
            return redirect()->back()->with('message', 'Se edito el cliente correctamente');
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Ocurrió un error al editar el cliente en esta cuenta, detalle: ' . $th->getMessage());
        }
    }
    //esta funcion tiene la responsabilidad de  asignara la opcion de  descuento al cliente
    public function asignarDescuento($id)
    {

        $cl = Crypt::decryptString($id);
        try {

            $cliente = clientes::find($cl);
            $cliente->descuento = !$cliente->descuento;

            $cliente->save();
            $message = $cliente->descuento
                ? 'Permite la opción descuento a este cliente ' . $cliente->nombre
                : 'Este cliente ya no permite descuento ' . $cliente->nombre;
            $type = $cliente->descuento ? 'success' : 'danger';

            return redirect()->back()
                ->with('message', $message)
                ->with('type', $type);
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Ocurrió un error al asignar descuento al cliente, detalle: ' . $th->getMessage());
        }
    }
    //*creacion de clientes sucursales//
    public function sucursalJuridico($id)
    {
        try {
            $cliente = clientes::find(Crypt::decryptString($id));
            return view('clientes.cliente-sucursal', [
                'p' => $cliente,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    //**cliente refactorizar consultar bien el requerimiento y cambiar esta funcion */
    public function clienteSucursal(duplicarClienteRequest $r)
    {
        try {
            $clienteOriginal = clientes::findOrFail(Crypt::decryptString($r->clientes_id));
            $cln = $clienteOriginal->replicate();
            $cln->nombre = $r->nombre;
            $cln->direccion = $r->direccion;
            $cln->email = $r->email;
            $cln->municipios_id = $r->municipios_id;
            $cln->estado = false;
            $cln->save();
            $this->duplicarDetalles($clienteOriginal->id, $cln->id);
            $this->identificaciones($clienteOriginal->id, $cln->id);
            $this->giros($clienteOriginal->id, $cln->id);

            return redirect()->route('clientes.index')
                ->with('message', 'Se copiaron datos  exitosamente.')
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Ocurrió un error al crear cliente, detalle: ' . $th->getMessage());
        }
    }

    private function duplicarDetalles($clienteOriginal, $duplicado)
    {
        try {
            $cliente_detalle = detalle_contribuyentes::where('clientes_id', $clienteOriginal)->get();
            if (isset($cliente_detalle) && count($cliente_detalle) > 0) {
                foreach ($cliente_detalle as $d) {
                    $nd = $d->replicate();
                    $nd->clientes_id = $duplicado;
                    $nd->save();
                }
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Ocurrió un error al duplicar detalles de contribuyente del cliente, detalle: ' . $th->getMessage());
        }
    }
    private function identificaciones($clienteOriginal, $duplicado)
    {
        try {
            $identificacion = clientes_identificaciones::where('clientes_id', $clienteOriginal)->get();

            if (isset($identificacion) && count($identificacion) > 0) {
                foreach ($identificacion as $d) {
                    $nd = $d->replicate();
                    $nd->clientes_id = $duplicado;
                    $nd->save();
                }
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Ocurrió un error al duplicar identificaciones del cliente, detalle: ' . $th->getMessage());
        }
    }

    private function giros($clienteOriginal, $duplicado)
    {
        try {
            $giro = clientes_giros::where('clientes_id', $clienteOriginal)->get();
            if (isset($giro) && count($giro) > 0) {
                foreach ($giro as $d) {
                    $nd = $d->replicate();
                    $nd->clientes_id = $duplicado;
                    $nd->save();
                }
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Ocurrió un error al duplicar giros del cliente, detalle: ' . $th->getMessage());
        }
    }



    public function getActividades(Request $r)
    {
        try {
            $b = $r->txtBq;
            $actividades = DB::table('getactividadeseconomicas')
                ->orWhere('actividad_economica', 'like', '%' . $b . '%')
                ->orWhere('actividad', 'like', '%' . $b . '%')
                ->orWhere('id', 'like', '%' . $b . '%')
                ->get();

            return response()->json(['list' => $actividades]);
        } catch (\Throwable $th) {
            return response()->json(['list' => $th->getMessage()]);
        }
    }


    public function editarNivelCautela(Request $r)
    {
        try {
            $clientes = clientes::where('estado', true)->orderByDesc('id')->paginate(50);

            return view('clientes.nivel_cautela', ['data' => $clientes, 'nivelesCautela' => niveles_cautela::getAll(), 'paises' => paises::all()]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function searchNivelCautela(Request $r)
    {
        try {


            $clientes = clientes::where('estado', true);
            if (isset($r->nombre) && $r->nombre != null)
                $clientes = $clientes->where(DB::raw('upper(nombre)'), 'ilike', "%" . strtoupper($r->nombre) . "%");

            if (isset($r->pais) && $r->pais != null)
                if ($r->pais == 1)
                    $clientes = $clientes->where('municipios_id', '>', 0);
                else
                    $clientes = $clientes->where('extranjeros_id',  $r->pais);

            if (isset($r->etiqueta) && $r->etiqueta != null)
                $clientes = $clientes->where('nivel_cautela', null);

            $clientes = $clientes->orderByDesc('id')->paginate(100);

            return view(
                'clientes.nivel_cautela',
                [
                    'data' => $clientes,
                    'nivelesCautela' => niveles_cautela::getAll(),
                    'paises' => paises::all(),
                    'nombre' => $r->nombre,
                    'pais' => $r->pais,
                    'etiqueta' => $r->etiqueta,
                    'busqueda' => true
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function editarEmpleado()
    {
        try {
            $clientes = clientes::where('estado', true)->orderByDesc('id')->paginate(50);

            return view(
                'clientes.empleados',
                [
                    'data' => $clientes,
                    'periodos' => periodos_creditos::where('estado', true)->get()
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function searchEmpleado(Request $r)
    {
        try {


            $clientes = clientes::where('estado', true);
            if (isset($r->nombre) && $r->nombre != null)
                $clientes = $clientes->where(DB::raw('upper(nombre)'), 'ilike', "%" . strtoupper($r->nombre) . "%");

            if (isset($r->credito) && $r->credito != null)
                $clientes = $clientes->where('credito', true);

            if (isset($r->empleado) && $r->empleado != null)
                $clientes = $clientes->where('empleado', false);

            $clientes = $clientes->orderByDesc('id')->paginate(100);

            return view(
                'clientes.empleados',
                [
                    'data' => $clientes,
                    'periodos' => periodos_creditos::where('estado', true)->get(),
                    'nombre' => $r->nombre,
                    'credito' => $r->credito,
                    'empleado' => $r->empleado,
                    'busqueda' => true
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }



    public function updateNivelCautela(Request $r)
    {
        try {
            $cliente = clientes::find(Crypt::decryptString($r->id));
            $cliente->nivel_cautela = $r->val == 0 ? null : $r->val;
            $cliente->save();
            return response()->json(['status' => true]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Error: ' . $th->getMessage()]);
        }
    }

    public function updateNotificacion(Request $r)
    {
        try {
            $cliente = clientes::find(Crypt::decryptString($r->id));
            $cliente->notificacion = !$cliente->notificacion;
            $cliente->save();
            return response()->json(['status' => true]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Error: ' . $th->getMessage()]);
        }
    }

    public function updateEmpleado(Request $r)
    {
        try {
            $cliente = clientes::find(Crypt::decryptString($r->id));
            $cliente->empleado = !$cliente->empleado;
            $cliente->save();
            return response()->json(['status' => true]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Error: ' . $th->getMessage()]);
        }
    }


    public function confirmPeriodosCreditos($id)
    {
        try {
            return view('confirmPeriodosCreditos', [
                'th' => $this->th['confirm'],
                'p' => clientes::find(Crypt::decryptString($id)),
            ]);
        } catch (Throwable $th) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $th->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

    public function deletePeriodosCreditos(Request $r)
    {
        try {
            if (!isset($r->id) || empty(trim($r->id)))
                return to_route($this->table . '.index')
                    ->with('message', 'Error, el identificador del registro no cumple los requisitos necesarios.')
                    ->with('type', 'danger');

            $cl = clientes::find(Crypt::decryptString($r->id));
            $cl->periodos_creditos_id = null;
            $cl->save();

            return to_route('clientes.show', ['id' => Crypt::encryptString($cl->id)])
                ->with('message', 'Periodo de crédito eliminado con exito.')
                ->with('type', 'success');
        } catch (Throwable $th) {
            return redirect()->back()
                ->with('message', 'Error al eliminar el perido del crédito: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function periodoCreditoStore(Request $r)
    {
        try {
            $cl = clientes::find(Crypt::decryptString($r->clientes_id));
            $cl->periodos_creditos_id = Crypt::decryptString($r->periodos_creditos_id);
            $cl->save();

            return to_route('clientes.show', ['id' => $r->clientes_id]) #id de cliente ya viene encriptado
                ->with('message', 'Periodo de crédito guadardo correctamente: (' . $cl->periodosCreditos->periodo . ' · ' . $cl->periodosCreditos->dias . ' días)')
                ->with('type', 'success');
        } catch (Throwable $th) {
            return to_route('clientes.show', ['id' => $r->clientes_id]) #id de cliente ya viene encriptado
                ->with('message', 'Error al guardar el periodo de crédito: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function periodoCreditoUpdate(Request $r)
    {
        try {
            $cl = clientes::find(Crypt::decryptString($r->id));
            $cl->periodos_creditos_id = $r->value;
            $cl->save();
            return response()->json(['status' => true]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Error: ' . $th->getMessage()]);
        }
    }

    public function alertasEfectivo(Request $r)
    {
        $monto = $r->monto ?? env('monto_efectivo', 10000);
        $fecha = Carbon::createFromFormat('Y-m', $r->fecha ?? date("Y-m"));
        $data = DB::table('get_efectivo')
            ->whereMonth('fecha', $fecha->format('m'))
            ->whereYear('fecha', $fecha->format('Y'))
            ->where('clientes_id', '>', 0)
            ->where('total_efectivo', '>=', $monto);

        if (isset($r->nombre) && $r->nombre != null && strlen($r->nombre) > 0)
            $data = $data->where('titular', 'like', '%' . strtoupper($r->nombre) . '%');

        $data = $data->get();
        $clientes = clientes::whereIn('id', $data->pluck('clientes_id'))->get();
        return view('clientes.alertas_efectivo', [
            'data' => $data,
            'clientes' => $clientes,
            'mes' =>  $fecha->format("Y-m"),
            'monto' =>  $monto,
            'nombre' => $r->nombre
        ]);
    }

    public function alertasBanco(Request $r)
    {
        $monto = $r->monto ?? env('monto_banco', 25000);
        $fecha = Carbon::createFromFormat('Y-m', $r->fecha ?? date("Y-m"));
        $data = DB::table('get_bancos')
            ->whereMonth('fecha', $fecha->format('m'))
            ->whereYear('fecha', $fecha->format('Y'))
            ->where('clientes_id', '>', 0)
            ->where('total_bancos', '>=', $monto);

        if (isset($r->nombre) && $r->nombre != null && strlen($r->nombre) > 0)
            $data = $data->where('titular', 'like', '%' . strtoupper($r->nombre) . '%');

        $data = $data->get();
        $clientes = clientes::whereIn('id', $data->pluck('clientes_id'))->get();
        return view('clientes.alertas_banco', [
            'data' => $data,
            'clientes' => $clientes,
            'mes' =>  $fecha->format("Y-m"),
            'monto' =>  $monto,
            'nombre' => $r->nombre
        ]);
    }


    public function comprobantes(Request $r)
    {
        $clientes = clientes::findOrFail(Crypt::decryptString($r->id));
        $mes = Carbon::createFromFormat('Y-m', $r->mes ?? date('Y-m'));
        $data = comprobantes::where('clientes_id', $clientes->id)
            ->whereMonth('fecha', $mes->format('m'))
            ->whereYear('fecha', $mes->format('Y'))
            ->get();
        return view('clientes.show_comprobante', ['p' => $clientes, 'mes' => $mes->format("Y-m"), 'data' => $data]);
    }


    public function empleadosPanel(Request $r)
    {
        $fecha = Carbon::createFromFormat('Y-m', $r->fecha ?? date('Y-m'));
        $data = ventas_credito::where('empleado', true)
            ->whereMonth('fecha', $fecha->format('m'))
            ->whereYear('fecha', $fecha->format('Y'));

        if (isset($r->nombre) && $r->nombre != null && strlen($r->nombre) > 0)
            $data = $data->where('titular', 'like', '%' . strtoupper($r->nombre) . '%');
        $data = $data->get();

        $clientes = clientes::whereIn('id', $data->pluck('clientes_id'))->get();
        return view('clientes.empleados.index', ['data' => $data, 'fecha' => $fecha->format("Y-m"), 'clientes' => $clientes]);
    }

    public function reporteCreditoEmpleadosForm()
    {
        return view('report.panel_reportes.credito_empleados.credito_empleados_form');
    }

    public function reporteCreditoEmpleadosAcciones(Request $r)
    {
        $opcion = $r->opcion ?? 1;
        $inicio = $r->inicio;
        $fin = $r->fin;
        $sinAbonos = (isset($r->sinAbonos) && $r->sinAbonos == 1) ? true : false;

        $data = ventas_credito::with(['abono' => function($q) use ($sinAbonos){
            if(!$sinAbonos){
                $q->whereNotNull('abonos_id');
            }
            else{
                $q->whereNull('abonos_id');
            }
        }])
            ->where('empleado', true)
            ->whereBetween('fecha', [$inicio, $fin])
            ->get();

        $clientes = clientes::whereIn('id', $data->pluck('clientes_id'))->get();

        switch ($opcion) {
            case 1: #Preview
                return view('report.panel_reportes.credito_empleados.credito_empleados_preview', [
                    'data' => $data,
                    'inicio' => $inicio,
                    'fin' => $fin,
                    'clientes' => $clientes,
                    'sinAbonos' => $sinAbonos,
                ]);
                break;
            case 2: #PDF
                break;
            case 3: #Excel
                $v = view('report.panel_reportes.credito_empleados.export_excel', [
                    'data' => $data,
                    'inicio' => $inicio,
                    'fin' => $fin,
                    'clientes' => $clientes,
                    'sinAbonos' => $sinAbonos,
                ]);

                $rs = Excel::download(new ViewToExcel($v), 'reporte de creditos a empleados.xlsx', \Maatwebsite\Excel\Excel::XLSX);
                ob_end_clean();

                return $rs;
                break;
        }
    }

    public function comprobantesEmpleados(Request $r)
    {
        $clientes = clientes::findOrFail(Crypt::decryptString($r->id));
        $mes = Carbon::createFromFormat('Y-m', $r->mes ?? date('Y-m'));
        $data = comprobantes::where('clientes_id', $clientes->id)
            ->whereMonth('fecha', $mes->format('m'))
            ->whereYear('fecha', $mes->format('Y'))
            ->get();
        return view('clientes.empleados.show_comprobante', ['p' => $clientes, 'mes' => $mes->format("Y-m"), 'data' => $data]);
    }

    public function actividadEconomicaUpdate(Request $r)
    {
        try {

            $a = actividades_economicas::find($r->actividades_economicas_id);
            if ($a == null) {
                throw new Exception('Seleccione una actividad economica valida');
            }
            $p = clientes::find(Crypt::decryptString($r->id));
            $p->actividades_economicas_id = $a->id;
            $p->save();
            return redirect()->back()->with('message', 'Se realizo el cambio');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('message', 'Error: ' . $th->getMessage());
        }
    }

    public function panel(Request $r)
    {

        return view('clientes.panel.index');
    }

    public function reporteCredito(Request $r)
    {
        return view('clientes.reportes.creditoForm', ['departamentos' => departamentos::all()]);
    }

    public function reporteCreditoAcciones(Request $r)
    {

        $data = $this->getCreditosQuery($r->depto, $r->fecha, $r->tipo_cliente)->get();
        $depto = $r->depto != null && $r->depto > 0 ? departamentos::find($r->depto)->depatamento : 'Todos';
        $fecha = Carbon::parse($r->fecha);
        $tipo_cliente = ['TODOS LOS CLIENTE', 'CLIENTES', 'EMPLEADOS', 'ACCIONISTAS'];
        $segmento = $r->tipo_cliente > 3 || $r->tipo_cliente == 0 ? $tipo_cliente[0] : $tipo_cliente[$r->tipo_cliente];

        switch ($r->opcion) {
            case 1:
                return view('clientes.reportes.creditoPreview', ['data' => $data, 'depto' => $depto, 'fecha' => $fecha->format('d-m-Y'), 'segmento' => $segmento]);
                break;
            case 2:
                $snap = SnappyPdf::loadView(
                    'clientes.reportes.creditoPrint',
                    ['data' => $data, 'depto' => $depto, 'fecha' => $fecha->format('d-m-Y'), 'segmento' => $segmento]
                )
                    ->setPaper('letter', 'landscape')
                    ->setOption('margin-top', '10mm')
                    ->setOption('margin-bottom', '10mm')
                    ->setOption('margin-left', '10mm')
                    ->setOption('margin-right', '10mm');
                return $snap->inline('reporte_saldos_por_antiguedad.pdf');
                break;
            default:
                # code...
                break;
        }
    }

    public function getCreditosQuery($depto = null, $fecha = null, $tipo_cliente = null)
    {

        $query = DB::table('ventas_credito_pendientes')
            ->select([
                'clientes_id',
                'nombre AS cliente_nombre',
                DB::raw('ROUND(SUM(CASE WHEN dias_transcurridos BETWEEN 1 AND 30 THEN monto ELSE 0 END)::numeric, 2) AS monto_30'),
                DB::raw('ROUND(SUM(CASE WHEN dias_transcurridos BETWEEN 31 AND 60 THEN monto ELSE 0 END)::numeric, 2) AS monto_60'),
                DB::raw('ROUND(SUM(CASE WHEN dias_transcurridos BETWEEN 61 AND 90 THEN monto ELSE 0 END)::numeric, 2) AS monto_90'),
                DB::raw('ROUND(SUM(CASE WHEN dias_transcurridos BETWEEN 91 AND 120 THEN monto ELSE 0 END)::numeric, 2) AS monto_120'),
                DB::raw('ROUND(SUM(CASE WHEN dias_transcurridos > 120 THEN monto ELSE 0 END)::numeric, 2) AS monto_superior'),
                DB::raw('ROUND(SUM(monto)::numeric, 2) AS total_monto'),
                DB::raw('ROUND(SUM(CASE WHEN dias IS NOT NULL AND dias_transcurridos <= dias THEN monto ELSE 0 END)::numeric, 2) AS monto_tiempo'),
                DB::raw('ROUND(SUM(CASE WHEN dias IS NULL OR dias_transcurridos > dias THEN monto ELSE 0 END)::numeric, 2) AS monto_vencido')
            ]);

        if ($depto != null && $depto > 0)
            $query = $query->where('departamentos_id', $depto);

        if ($fecha != null)
            $query = $query->where('fecha', '<=', $fecha);

        if ($tipo_cliente != null && $tipo_cliente > 0)
            switch ($tipo_cliente) {
                case 1:
                    $query = $query->where('empleado', false)->where('accionista', false);
                    break;
                case 2:
                    $query = $query->where('empleado', true);
                    break;
                case 3:
                    $query = $query->where('accionista', true);
                    break;
            }

        $query = $query->groupBy(['clientes_id', 'nombre'])->orderBy('nombre');
        return $query;
    }

    public function accionista($id)
    {
        try {
            $p = clientes::findOrFail(Crypt::decryptString($id));
            $p->accionista = !$p->accionista;
            $p->save();

            return redirect()->back()
                ->with('message', 'Se modificado la opcion accionista de este cliente')
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al cambiar estado: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function updateAccionista(Request $r)
    {
        try {
            $cliente = clientes::find(Crypt::decryptString($r->id));
            $cliente->accionista = !$cliente->accionista;
            $cliente->save();
            return response()->json(['status' => true]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Error: ' . $th->getMessage()]);
        }
    }

    public function alertasCredito(Request $r)
    {
        $dias = $r->dias ?? env('dias_credito', 5);
        $comprobantes = vcreditos_pendientes::whereNotNull('dias')
            ->whereNotNull('dias_transcurridos')
            ->whereRaw('dias <= dias_transcurridos + ' . $dias)
            ->where('empleado', false)
            ->get();


        return view('clientes.creditos.alertas', ['comprobantes' => $comprobantes]);
    }

    public function alertasClientes(Request $r)
    {

        $periodo = clientes::where('credito', true)->where('periodos_creditos_id', null)->where('estado', true)->count();
        $locales = clientes::where('municipios_id', '>', 0)->where('estado', true)->whereDoesntHave('identificacion')->count();
        $extranjeros = clientes::where('extranjeros_id', '>', 0)->where('estado', true)->whereDoesntHave('identificacion')->count();
        $nlocalidad = clientes::where('tipo_cliente', true)->where('estado', true)->where('extranjeros_id', null)->where('municipios_id', null)->count();
        $jlocalidad = clientes::where('tipo_cliente', false)->where('estado', true)->where('municipios_id', null)->count();
        $jactividad = clientes::where('tipo_cliente', false)->where('estado', true)->where('actividades_economicas_id', null)->count();
        return view('clientes.creditos.errores', compact('periodo', 'locales', 'extranjeros', 'nlocalidad', 'jlocalidad', 'jactividad'));
    }

    public function alertasClientesPeriodos()
    {
        return view('clientes.creditos.periodos', [
            'data' => clientes::where('credito', true)->where('estado', true)->where('periodos_creditos_id', null)->get(),
            'periodos' => periodos_creditos::where('estado', true)->get()
        ]);
    }

    public function alertasClientesMunicipios()
    {
        return view('clientes.creditos.municipios', [
            'data' => clientes::where('tipo_cliente', false)->where('estado', true)->where('municipios_id', null)->get(),
            'dataList' => municipios::leftJoin('departamentos', 'departamentos.id', 'municipios.departamentos_id')
                ->select(
                    'municipios.id',
                    DB::raw("CONCAT(municipio, ', ', departamento) as municipio")
                )
                ->get()
        ]);
    }
    public function alertasClientesPaisMunicipios()
    {
        return view('clientes.creditos.municipios_pais', [
            'data' => clientes::where('tipo_cliente', true)->where('estado', true)->where('extranjeros_id', null)->where('municipios_id', null)->get(),
            'municipioList' => municipios::leftJoin('departamentos', 'departamentos.id', 'municipios.departamentos_id')
                ->select(
                    'municipios.id',
                    DB::raw("CONCAT(municipio, ', ', departamento) as municipio")
                )
                ->get(),
            'paisList' => paises::all()
        ]);
    }

    public function alertasClientesActividades()
    {
        return view('clientes.creditos.actividades', [
            'data' => clientes::where('tipo_cliente', false)->where('estado', true)->where('actividades_economicas_id', null)->get(),
            'dataList' => actividades_economicas::all()
        ]);
    }


    public function apiClientesMunicipios(Request $r)
    {
        try {
            $cliente = clientes::find(Crypt::decryptString($r->id));
            $cliente->municipios_id = $r->value;
            $cliente->save();
            return response()->json(['status' => true]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Error: ' . $th->getMessage()]);
        }
    }

    public function apiClientesPais(Request $r)
    {
        try {
            $cliente = clientes::find(Crypt::decryptString($r->id));
            $cliente->extranjeros_id = $r->value;
            $cliente->save();
            return response()->json(['status' => true]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Error: ' . $th->getMessage()]);
        }
    }
    public function apiClientesActividad(Request $r)
    {
        try {
            $cliente = clientes::find(Crypt::decryptString($r->id));
            $cliente->actividades_economicas_id = $r->value;
            $cliente->save();
            return response()->json(['status' => true]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Error: ' . $th->getMessage()]);
        }
    }


    public function alertasClientesIdentificaciones()
    {
        return view('clientes.creditos.identificaciones', [
            'data' => clientes::where('municipios_id', '>', 0)->where('estado', true)->whereDoesntHave('identificacion')->get(),
            'identificaciones' => identificaciones::where('estado', true)->get()
        ]);
    }
    public function alertasClientesIdentificacionesExtranjeros()
    {
        return view('clientes.creditos.identificacionesExtranjeros', [
            'data' => clientes::where('extranjeros_id', '>', 0)->where('estado', true)->whereDoesntHave('identificacion')->get(),
            'identificaciones' => identificaciones::where('estado', true)->get()
        ]);
    }

    public function apiClientesIdentificaciones(Request $r)
    {
        try {
            $cliente = clientes::find(Crypt::decryptString($r->id));
            $p = new clientes_identificaciones;
            $p->clientes_id = $cliente->id;
            $p->identificaciones_id = $r->identificaciones;
            $p->numero = $r->numero;
            $p->save();
            return response()->json(['status' => true]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Error: ' . $th->getMessage()]);
        }
    }
}
