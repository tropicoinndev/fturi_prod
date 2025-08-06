<?php

namespace App\Http\Controllers;

use App\Events\CajasEvent;
use App\Exports\ReservacionesExport;
use App\Http\Requests\StoreanticiposRequest as StoreRequest;
use App\Http\Requests\UpdateanticiposRequest as UpdateRequest;
use App\Mail\changeMail;
use App\Models\anticipos as model;
use App\Models\anticipos;
use App\Models\anticipos_cobros;
use App\Models\clientes;
use App\Models\forma_pagos;
use App\Models\turnos;
use App\Models\User;
use App\Utils;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;
use App\Models\cajas_users;
use App\Models\cajas;
use Barryvdh\Snappy\Facades\SnappyPdf;
use App\Exports\ViewToExcel;

class AnticiposController extends Controller
{
    private $table = 'anticipos';

    public function __construct()
    {
        $this->getTh($this->table, 'Anticipos');
        $this->th['index']['btnAdd'] = false;
    }

    public function index()
    {
        $a = model::with(['clientes', 'users'])->orderBy('id', 'DESC')->paginate(15);
        return view($this->table . '.index', [
            'th'        => $this->th['index'],
            'p'         => $a,
            'table'     => $this->table,
        ]);
    }

    public function search(Request $r)
    {
        $p = model::with('clientes')
            ->where('id', 'ilike', '%' . $r->txtBusqueda . '%')
            ->orWhereHas('clientes', function ($query) use ($r) {
                $query->where('nombre', 'ilike', '%' . $r->txtBusqueda . '%');
            });
        if (isset($r->activos) &&  $r->activos == 1)
            $p = $p->where('estado', true)->where('anulado', false);

        $p = $p->paginate(500);

        return view($this->table . '.index', [
            'th'            => $this->th['index'],
            'p'             => $p,
            'txtBusqueda'   => $r->txtBusqueda,
            'table'         => $this->table,

        ]);
    }
    public function api_buscar(Request $r)
    {
        return response()->json(
            [
                'list' => model::where(DB::raw('upper(nombre)'), 'like', '%' . $r->buscar . '%')
                    ->orWhere('identificacion', 'like', '%' . $r->buscar . '%')
                    ->orWhere('telefono', 'like', '%' . $r->buscar . '%')
                    ->with(['municipios', 'identificaciones'])
                    ->get()
            ]
        );
    }

    public function create()
    {
        if (!session('caja') || !session('turno'))
            return redirect()->route("cajas.login");
        return view($this->table . '.create', [
            'th' => $this->th['create'],
            'table' => $this->table,
            'forma_pagos' => forma_pagos::whereNotIn('token', [6002, 6004])->get(),
        ]);
    }

    public function store(StoreRequest $r)
    {
        if (!session('caja') || !session('turno'))
            return redirect()->route("cajas.login");
        try {
            $turno = turnos::find(session('turno')->id);
            if ($turno == null || !$turno->estado)
                return redirect()->route("cajas.logout")->with('message', 'El turno ya fue cerrado, debe volver a iniciar sesión con el nuevo turno');

            $p = new model;
            $p->monto = $r->monto;
            $p->monto_historico = $r->monto;
            $p->fecha_aplicacion = $r->fecha_aplicacion;
            $p->fecha = date("Y-m-d");
            $p->concepto = $r->concepto;
            $p->turnos_id = session('turno')->id;
            $p->clientes_id = $r->clientes_id;
            $p->forma_pagos_id = $r->forma_pagos_id;
            $p->users_id = Auth::user()->id;
            $p->save();

            if (isset($r->tipo_reservacion) && isset($r->reservacion_id)) {
                (new AnticipoReservacionController)->save(
                    Crypt::decryptString($r->tipo_reservacion),
                    Crypt::decryptString($r->reservacion_id),
                    $p->id
                );
            }
            return redirect()
                ->back()
                ->with('message', 'Registro guardado correctamente: ' . $p->id)
                ->with('type', 'success')
                ->with('redirect', route('anticipos.container', ['id' => Crypt::encryptString($p->id)]));
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    //*funcion para guardar anticipos de eventos */
    public function anticipoEventos(Request $r)
    {
        if (!session('caja') || !session('turno'))
            return redirect()->route("cajas.login");
        try {
            $p = new model;
            $p->monto = $r->monto;
            $p->monto_historico = $r->monto;
            $p->fecha_aplicacion = $r->fecha_aplicacion;
            $p->fecha = date("Y-m-d");
            $p->concepto = $r->concepto;
            $p->turnos_id = session('turno')->id;
            $p->clientes_id = $r->clientes_id;
            $p->forma_pagos_id = $r->forma_pagos_id;
            $p->users_id = Auth::user()->id;
            $p->estado = true;

            $p->save();

            if (isset($r->tipo_reservacion) && isset($r->reservacion_id)) {
                (new AnticipoReservacionController)->save(
                    Crypt::decryptString($r->tipo_reservacion),
                    Crypt::decryptString($r->reservacion_id),
                    $p->id
                );
            }
            return redirect()
                ->route('eventos.detalle', ['id' => $r->reservacion_id])
                ->with('message', 'Registro guardado correctamente: ' . $p->id)
                ->with('type', 'success')
                ->with('redirect', route('anticipos.container', ['id' => Crypt::encryptString($p->id)]));
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function edit($id)
    {
        $id = Crypt::decryptString($id);
        $model = Model::find($id);

        if (!$model) {
            return redirect()->back()->with('message', "Anticipo no encontrado.")->with('type', 'danger');
        }

        return view('anticipos.anticipo_cliente', [
            'p' => $model
        ]);
    }

    public function update(UpdateRequest $r)
    {
        try {
            $p = model::findOrFail($r->id);
            $p->nombre = $r->nombre;
            $p->nacimiento = $r->nacimiento;
            $p->telefono = $r->telefono;
            $p->identificaciones_id = $r->identificaciones_id;
            $p->identificacion = $r->identificacion;
            $p->municipios_id = $r->municipios_id;
            $p->forma_pagos_id = $r->forma_pagos_id;
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
    public function destroy(Request $r)
    {
        try {
            $body = "Usuario: " . Auth::user()->name . ' intento eliminar el anticipo ';
            if (!isset($r->id) || empty(trim($r->id)))
                $body = $body . ' ' . Crypt::decryptString($r->id);

            Mail::to('norvinrequeno@tropicoinn.com.sv')->queue(new changeMail($body));

            return to_route($this->table . '.index')
                ->with('message', 'No es posible anular');
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
                'p' => model::findOrFail(Crypt::decryptString($id))
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
            $p = model::findOrFail(Crypt::decryptString($id));
            if ($p->anulado) {
                return redirect()->route($this->table . '.index')
                    ->with('message', 'El anticipo no se puede desactivar porque está anulado.')
                    ->with('type', 'danger');
            }
            $p->estado = !$p->estado;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Se ' . ($p ? 'activo' : 'desactivo') . ' el anticipo: ' . $p->clientes->nombre)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al cambiar estado: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function anular($id)
    {
        try {
            $p = model::findOrFail(Crypt::decryptString($id));
            $p->anulado = !$p->anulado;
            $p->save();

            return redirect()->back()
                ->with('message', 'Se ' . ($p ? 'activo' : 'desactivo') . ' el huesped: ' . $p->nombre)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', 'Error al cambiar estado: ' . $th->getMessage())
                ->with('type', 'danger');
        }
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
    public function print($id)
    {
        $p = anticipos::find(Crypt::decryptString($id));
        $pdf = $this->getPDF();
        $pdf->loadView(
            'anticipos.print',
            [
                'p' => $p,
                'letras' => (new Utils)->toMoney($p->monto)
            ]
        );
        //return $view;
        $pdf->setPaper('letter');
        return $pdf->stream();
    }
    public function impresion(Request $r)
    {
        return view('anticipos.container_print', ['url' => route('anticipos.print', ["id" => $r->id])]);
    }

    public function devolucion($anticipo, $monto)
    {
        try {
            if ($monto == null || $monto == 0)
                throw new Exception('El monto a devolver debe ser mayor a cero.');
            $anticipo = anticipos::find($anticipo);
            $anticipo->monto += $monto;
            $anticipo->estado = true;
            $anticipo->save();
            return $anticipo;
        } catch (\Throwable $th) {
            return throw $th;
        }
    }

    public function aplicar($anticipo, $monto)
    {
        try {
            $anticipo = anticipos::find($anticipo);
            $anticipo->monto -= $monto;
            if ($anticipo->monto < 0)
                throw new Exception('El monto que quiere aplicar es mayor que el anticipo.');

            if ($anticipo->monto == 0)
                $anticipo->estado = false;

            if ($anticipo->monto > 0)
                $anticipo->separado = true;

            $anticipo->save();
            return $anticipo;
        } catch (\Throwable $th) {
            return throw $th;
        }
    }
    public function anularAnticipo(Request $r)
    {
        $id = Crypt::decryptString($r->id);
        $model = Model::find($id);

        if (!$model) {
            // Manejar la situación si el ID no es válido
            return redirect()->back()->withErrors('Anticipo no encontrado.');
        }

        return view('anticipos.anular_anticipo', [
            'p' => $model
        ]);
    }
    public function anulacionAnticipo(Request $r)
    {
        $r->validate([
            'confirmacion' => 'required|integer|in:1',
            'motivo_anulacion' => 'required|string',
        ], [
            'confirmacion.in' => 'Debe confirmar que está seguro de anular el anticipo.',
            'motivo_anulacion.required' => 'Debe proporcionar un motivo para la anulación.',
        ]);

        try {
            $p = Model::findOrFail(Crypt::decryptString($r->id));


            $creacion_anticipo = Carbon::parse($p->created_at);
            $permite_anulacion = Carbon::now()->subDays(30);

            if ($creacion_anticipo->lessThanOrEqualTo($permite_anulacion)) {
                return redirect()->back()
                    ->with('message', "El anticipo No. " . $p->id . " no puede ser anulado ya que han pasado más de 30 días desde su creación.")
                    ->with('type', 'danger');
            }

            // Verificar si el anticipo ya ha sido aplicado

            if ($p->aplicado->count() > 0 || $p->separado) {
                return redirect()->back()
                    ->with('message', "El anticipo No. " . $p->id . " ya ha sido aplicado y no puede ser anulado.")
                    ->with('type', 'danger');
            }

            // Registrar la razón de la anulación y otros detalles
            $p->motivo_anulacion = $r->motivo_anulacion;
            $p->anulado = true;
            $p->estado = false;
            $p->users_anula_id = Auth::user()->id;
            $p->save();

            return redirect()->route('anticipos.index')
                ->with('message', "Se anuló el anticipo No. " . $p->id . " del cliente " . $p->clientes->nombre)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', "Ocurrió un error al anular la reservación No. " . $p->id . ". Error: " . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function updateClienteAnticipo(Request $r)
    {
        $r->validate([
            'confirmacion' => 'required|integer|in:1',
            'clientes_id' => 'required',
        ], [
            'confirmacion.in' => 'Debe confirmar que está seguro de actualizar el cliente.',
            'clientes_id.required' => 'Debe proporcionar el cliente para poder actualizar.',
        ]);

        try {
            $p = Model::findOrFail(Crypt::decryptString($r->id));
            $cl = clientes::find($r->clientes_id);
            if ($p->clientes_id == $cl->id) {
                return redirect()->back()
                    ->with('message', 'El anticipo ya está asignado al cliente seleccionado.')
                    ->with('type', 'info');
            }
            if ($p->anulado || $p->aplicado->count() > 0 || $p->separado) {
                return redirect()->back()
                    ->with('message', 'El anticipo esta anulado o esta aplicado no deberia de poder actualizar el cliente.')
                    ->with('type', 'danger');
            }

            $p->clientes_id = $cl->id;
            $p->save();

            return redirect()->route('anticipos.index')
                ->with('message', "Se actualizo el cliente " . $p->clientes->nombre . " del anticipo No. " . $p->id)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', "Ocurrió un error al anular la reservación No. " . $p->id . ". Error: " . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    //*/reportes de anticipos disponibles y anulados

    public function reporteAnticipo(Request $r)
    {
        return view('anticipos.reportes', [
            'cliente' => clientes::all(),
        ]);
    }

    public function reporteAnticiposAuditoria(Request $r)
    {
        try {
            $opcion = $r->opcion;
            $fecha = $r->fecha ?? date('Y-m-d');
            $clienteId = $r->clientes_id;
            $anticipoModel = model::whereDate('fecha', $fecha)->where('anulado', false);

            if ($clienteId > 0) {
                $anticipoModel->where('clientes_id', $clienteId);
            }

            $anticipos = $anticipoModel->with(['forma_pagos', 'clientes', 'users'])->get();


            $clientes = clientes::whereIn('id', $anticipos->pluck('clientes_id'))->get();
            $creadores = User::whereIn('id', $anticipos->pluck('users_id'))->get();


            switch ($opcion) {
                case 1:
                    return view('anticipos.reportes', [
                        'cliente' => $clientes,
                        'anticipos' => $anticipos,
                        'creadores' => $creadores,
                        'fecha' => $fecha,
                        'clienteId' => $clienteId,
                    ]);
                case 2:
                    $pdf = $this->getPDF();
                    $pdf->loadView('anticipos.reportes_print', [
                        'anticipos' => $anticipos,
                        'clientes' => $clientes,
                        'creadores' => $creadores,
                    ]);
                    $pdf->setPaper('letter', 'landscape');

                    return $pdf->stream();
                    break;
                case 3:

                    $view = view(
                        'anticipos.reportes_excel',
                        [
                            'anticipos' => $anticipos,
                            'clientes' => $clientes,
                            'fecha' => $fecha,

                        ]
                    );
                    $rs = Excel::download(new ReservacionesExport($view), 'reporte_anticipos_disponibles_dia_' . $fecha . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
                    ob_end_clean();
                    return $rs;
                    break;
                default:
                    return redirect()->back()->withErrors(['opcion' => 'Opción no válida']);
            }
        } catch (\Throwable $th) {
            return throw $th;
        }
    }
    //**reporte de anticipos anulados se filtra por fecha y cliente */
    public function anuladoAnticipo(Request $r)
    {
        return view('anticipos.anulado', [
            'cliente' => clientes::all(),
        ]);
    }
    public function reporteAnuladosAnticipos(Request $r)
    {
        try {
            $opcion = $r->opcion;
            $fecha = $r->fecha ?? date('Y-m-d');
            $clienteId = $r->clientes_id;
            $anticipoModel = Model::whereDate('fecha', $fecha)
                ->where('anulado', true);

            if ($clienteId > 0) {
                $anticipoModel->where('clientes_id', $clienteId);
            }

            $anticipos = $anticipoModel->with(['forma_pagos', 'clientes', 'users'])->get();


            $clientes = clientes::whereIn('id', $anticipos->pluck('clientes_id'))->get();
            $anuladores = User::whereIn('id', $anticipos->pluck('users_anula_id'))->get();


            switch ($opcion) {
                case 1:
                    return view('anticipos.anulado', [
                        'cliente' => $clientes,
                        'anticipos' => $anticipos,
                        'anuladores' => $anuladores,
                        'fecha' => $fecha,
                        'clienteId' => $clienteId,
                    ]);
                case 2:
                    $pdf = $this->getPDF();
                    $pdf->loadView('anticipos.anulado_print', [
                        'anticipos' => $anticipos,
                        'clientes' => $clientes,
                        'anuladores' => $anuladores,
                    ]);
                    $pdf->setPaper('letter', 'landscape');

                    return $pdf->stream();
                default:

                    return redirect()->back()->withErrors(['opcion' => 'Opción no válida']);
            }
        } catch (\Throwable $th) {
            return throw $th;
        }
    }

    public function cambiarAplicacion(Request $r)
    {
        $p = anticipos::findOrFail(Crypt::decryptString($r->id));
        return view('anticipos.fecha_aplicacion', ['p' => $p]);
    }

    public function separar(Request $r)
    {
        $p = anticipos::findOrFail(Crypt::decryptString($r->id));
        return view('anticipos.separacion', ['p' => $p]);
    }

    public function separarStore(Request $r)
    {
        $r->validate([
            'id' => ['required', 'string'],
            'monto' => ['required', 'numeric'],
            'observaciones' => ['required', 'string'],
            'confirm' => ['required', 'accepted']
        ]);
        try {
            $p = anticipos::findOrFail(Crypt::decryptString($r->id));
            $p->monto = $p->monto - $r->monto;
            $p->monto_historico = $p->monto_historico - $r->monto;
            $p->concepto = $p->concepto . ' Separación:' . date('Y-m-d') . "-- " . Auth::user()->user . " " . $r->observaciones;
            $p->separado = true;
            if ($p->monto == 0)
                $p->estado = false;
            $p->save();

            $s = new anticipos;
            $s->fecha = $p->fecha;
            $s->concepto = $p->concepto;
            $s->fecha_aplicacion = $p->fecha_aplicacion;
            $s->monto = $r->monto;
            $s->monto_historico = $r->monto;
            $s->turnos_id = $p->turnos_id;
            $s->clientes_id = $p->clientes_id;
            $s->users_id = $p->users_id;
            $s->forma_pagos_id = $p->forma_pagos_id;
            $s->separado = false;
            $s->save();

            return redirect()
                ->back()
                ->with('message', 'Se separo el anticipo Nº ' . $p->id . ' correctamente, el nuevo anticipo separado: ' . $s->id)
                ->with('type', 'success')
                ->with('redirect', route('anticipos.container', ['id' => Crypt::encryptString($s->id)]));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function cambiarAplicacionStore(Request $r)
    {
        $r->validate([
            'id' => ['required', 'string'],
            'fecha_aplicacion' => ['required', 'date'],
            'confirm' => ['required', 'accepted']
        ]);
        try {
            $p = anticipos::findOrFail(Crypt::decryptString($r->id));
            $p->fecha_aplicacion = $r->fecha_aplicacion;
            $p->save();
            return redirect()->route('anticipos.index')->with('message', 'Se cambio la fecha de aplicacion del anticipo Nº ' . $p->id);
        } catch (\Throwable $th) {

            return redirect()->route('anticipos.index')->with('type', 'danger')
                ->with('message', 'Error: ' . $th->getMessage());
        }
    }

    public function cambiarFormaPago(Request $r)
    {
        $p = anticipos::find(Crypt::decryptString($r->id));
        return view($this->table . '.forma_pago', [
            'p' => $p,
            'formaPagos' => forma_pagos::orderBy('forma', 'asc')->get(),
        ]);
    }

    public function cambiarFormaPagoStore(Request $r)
    {
        $r->validate([
            'id' => ['required', 'string'],
            'forma_pagos_id' => ['required', 'string'],
            'confirm' => ['required', 'accepted'],
        ]);

        try {
            $p = anticipos::where('id', Crypt::decryptString($r->id))
                ->where('anulado', false)
                ->where('separado', false)
                ->first();

            if (!$p)
                return to_route($this->table . '.index')->with('type', 'danger')->with('message', 'No se encontró el registro solicitado.');

            $p->forma_pagos_id = Crypt::decryptString($r->forma_pagos_id);
            $p->save();

            return to_route($this->table . '.index')
                ->with('type', 'success')
                ->with('message', 'Se cambió la forma de pago del anticipo Nº #' . $p->id . ' a ' . $p->forma_pagos->forma);
        } catch (Throwable $th) {
            return to_route($this->table . '.index')
                ->with('type', 'danger')
                ->with('message', 'Error: ' . $th->getMessage());
        }
    }

    #Alertas
    public function alertaAnticipos()
    {
        return view($this->table . '.alerta', [
            'anticipos' => DB::table('alertas_anticipos')->get(),
        ]);
    }

    #Reportes
    public function getUserCajas($caja = null)
    {
        if ($caja == null) {
            $userCajas = cajas_users::where('users_id', Auth::user()->id)->get();
            return cajas::whereIn('id', $userCajas->pluck('cajas_id'))->get();
        } else {
            return cajas::whereIn('id', $caja)->get();
        }
    }

    #Formato 1
    public function reporteAnticiposForm1()
    {
        return view($this->table . '.reporte_anticipos_form1', [
            'cajas' => $this->getUserCajas(),
        ]);
    }

    public function reporteAnticiposAcciones1(Request $r)
    {
        $r->validate([
            'cajas_id' => ['required', 'array'],
            'inicio'  => ['required', 'date'],
            'fin'     => ['required', 'date', 'after_or_equal:inicio'],
            'opcion'  => ['required', 'integer', 'min:1', 'max:3'],
        ], [
            'cajas_id.required' => 'El campo cajas_id es requerido.',
            'cajas_id.array'   => 'El campo cajas_id debe ser un array.',
            'inicio.required'  => 'La fecha de inicio es requerida.',
            'inicio.date'      => 'La fecha de inicio debe ser una fecha válida.',
            'fin.required'     => 'La fecha final es requerida.',
            'fin.date'         => 'La fecha final debe ser una fecha válida.',
            'opcion.required'  => 'El campo opcion es requerido.',
            'opcion.integer'   => 'El campo opcion debe ser un número entero.',
            'opcion.min'       => 'El campo opcion debe ser al menos :min.',
            'opcion.max'       => 'El campo opcion no puede ser mayor que :max.',
        ]);

        try {
            #Cajas
            $cajas = $r->cajas_id;
            $selectedCajas = [];
            if (is_array($cajas) && count($cajas) > 0) {
                foreach ($cajas as $v) {
                    $c = Crypt::decryptString($v);
                    if ($c == 0) {
                        $selectedCajas = null;
                        break;
                    }
                    array_push($selectedCajas, $c);
                }
            }

            #Verificar si se eligió la opción 'Todas las cajas' o cajas individuales
            $caja = $this->getUserCajas($selectedCajas);

            if ($selectedCajas == null || (is_array($selectedCajas) && count($selectedCajas)))
                $selectedCajas = $caja;

            #Para saber cuales cajas se seleccionaron
            $cajas = cajas::whereIn('id', $caja->pluck('id'))->get();

            #Obtenemos los turnos según las cajas seleccionadas
            $turnos = turnos::whereIn('cajas_id', $caja->pluck('id'));

            #Obtenemos todos los anticipos según los turnos de las cajas
            $anticipos = anticipos::whereIn('turnos_id', $turnos->pluck('id'))
                ->whereBetween('fecha_aplicacion', [$r->inicio, $r->fin])
                ->where('estado', true)
                ->get();

            switch ($r->opcion) {
                case 1: #Vista previa
                    return view($this->table . '.reporte_anticipos_preview1', [
                        'cajas' => $cajas,
                        'inicio' => $r->inicio,
                        'fin' => $r->fin,
                        'anticipos' => $anticipos,
                    ]);
                    break;
                case 2: #PDF
                    $snap = SnappyPdf::loadView($this->table . '.reporte_anticipos_print1', [
                        'cajas' => $cajas,
                        'inicio' => $r->inicio,
                        'fin' => $r->fin,
                        'anticipos' => $anticipos,
                    ])->setPaper('letter', 'landscape');

                    return $snap->inline('reporte_anticipos_activos.pdf');
                    break;
                case 3: #Excel
                    $v = view($this->table . '.reporte_anticipos_excel1', [
                        'cajas' => $cajas,
                        'inicio' => $r->inicio,
                        'fin' => $r->fin,
                        'anticipos' => $anticipos,
                    ]);

                    $rs = Excel::download(new ViewToExcel($v), 'reporte_anticipos_activos.xlsx', \Maatwebsite\Excel\Excel::XLSX);
                    ob_end_clean();

                    return $rs;
                    break;
            }
        } catch (Throwable $th) {
            return redirect()->back()
                ->with('type', 'danger')
                ->with('message', 'Error: ' . $th->getMessage());
        }
    }

    #Formato 2
    public function reporteAnticiposForm2(){
        return view($this->table.'.reporte_anticipos_form2',[
            'cajas'=>$this->getUserCajas(),
        ]);
    }

    public function reporteAnticiposAcciones2(Request $r)
    {
        $r->validate([
            'cajas_id'        =>['required', 'array'],
            'fecha_aplicacion'=>['required', 'date'],
            'opcion'          =>['required', 'integer', 'min:1', 'max:3'],
        ], [
            'cajas_id.required'        =>'El campo cajas_id es requerido.',
            'cajas_id.array'           =>'El campo cajas_id debe ser un array.',
            'fecha_aplicacion.required'=>'La fecha de fecha de aplicacion es requerida.',
            'fecha_aplicacion.date'    =>'La fecha de fecha de aplicacion debe ser una fecha válida.',
            'opcion.required'          =>'El campo opcion es requerido.',
            'opcion.integer'           =>'El campo opcion debe ser un número entero.',
            'opcion.min'               =>'El campo opcion debe ser al menos :min.',
            'opcion.max'               =>'El campo opcion no puede ser mayor que :max.',
        ]);

        try {
            #Cajas
            $cajas = $r->cajas_id;
            $selectedCajas = [];
            if (is_array($cajas) && count($cajas) > 0) {
                foreach ($cajas as $v) {
                    $c = Crypt::decryptString($v);
                    if ($c == 0) {
                        $selectedCajas = null;
                        break;
                    }
                    array_push($selectedCajas, $c);
                }
            }

            #Verificar si se eligió la opción 'Todas las cajas' o cajas individuales
            $caja = $this->getUserCajas($selectedCajas);

            if ($selectedCajas == null || (is_array($selectedCajas) && count($selectedCajas)))
                $selectedCajas = $caja;

            #Para saber cuales cajas se seleccionaron
            $cajas = cajas::whereIn('id', $caja->pluck('id'))->get();

            #Obtenemos los turnos según las cajas seleccionadas
            $turnos = turnos::whereIn('cajas_id', $caja->pluck('id'));

            #Obtenemos todos los anticipos según los turnos de las cajas
            $anticipos = anticipos::whereIn('turnos_id', $turnos->pluck('id'))
                ->where('fecha_aplicacion', '<=', $r->fecha_aplicacion)
                ->where('estado', true)
                ->orderBy('fecha_aplicacion','asc')
                ->get();

            switch ($r->opcion) {
                case 1: #Vista previa
                    return view($this->table . '.reporte_anticipos_preview2', [
                        'cajas' => $cajas,
                        'fecha_aplicacion' => $r->fecha_aplicacion,
                        'anticipos' => $anticipos,
                    ]);
                    break;
                case 2: #PDF
                    $snap = SnappyPdf::loadView($this->table . '.reporte_anticipos_print2', [
                        'cajas' => $cajas,
                        'fecha_aplicacion' => $r->fecha_aplicacion,
                        'anticipos' => $anticipos,
                    ])->setPaper('letter', 'landscape');

                    return $snap->inline('reporte_anticipos_activos.pdf');
                    break;
                case 3: #Excel
                    $v = view($this->table . '.reporte_anticipos_excel2', [
                        'cajas' => $cajas,
                        'fecha_aplicacion' => $r->fecha_aplicacion,
                        'anticipos' => $anticipos,
                    ]);

                    $rs = Excel::download(new ViewToExcel($v), 'reporte_anticipos_activos.xlsx', \Maatwebsite\Excel\Excel::XLSX);
                    ob_end_clean();

                    return $rs;
                    break;
            }
        } catch (Throwable $th) {
            return redirect()->back()
                ->with('type', 'danger')
                ->with('message', 'Error: ' . $th->getMessage());
        }
    }
}
