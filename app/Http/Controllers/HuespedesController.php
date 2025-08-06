<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorehuespedesRequest as StoreRequest;
use App\Http\Requests\UpdatehuespedesRequest as UpdateRequest;
use App\Models\huespedes as model;
use App\Models\identificaciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

#Mail
use App\Mail\SendHuespedesStoreMail;
use App\Models\municipios;
use App\Models\paises;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Models\personas_alertas;
use Throwable;
use Exception;

class HuespedesController extends Controller
{
    private $table = 'huespedes';

    public function __construct()
    {
        $this->getTh($this->table, 'Huespedes');
        $this->th['index']['btnAdd'] = false;
    }

    public function index()
    {
        return view($this->table . '.index', [
            'th'        => $this->th['index'],
            'p'         => model::orderBy('nombre', 'DESC')->paginate(10),
            'table'     => $this->table,
        ]);
    }

    public function search(Request $r)
    {
        return view($this->table . '.index', [
            'th'            => $this->th['index'],
            'p'             => model::where('nombre', 'like', '%' . $r->txtBusqueda . '%')
                ->orWhere('identificacion', 'like', '%' . $r->txtBusqueda . '%')
                ->orWhere('telefono', 'like', '%' . $r->txtBusqueda . '%')
                ->paginate(),
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
        return view($this->table . '.create', [
            'th' => $this->th['create'],
            'table' => $this->table,
            'identificaciones' => identificaciones::all(),

        ]);
    }

    public function store(StoreRequest $r)
    {
        try {
            $p = new model;
            $p->nombre = $r->nombre;
            $p->nacimiento = $r->nacimiento;
            $p->telefono = $r->telefono;
            $p->identificaciones_id = $r->identificaciones_id;
            $p->identificacion = $r->identificacion;
            $p->municipios_id = $r->municipios_id ?? null;
            $p->paises_id = $r->paises_id ?? null;
            $p->save();

            #Enviar email solo si coincide el número de identificación
            if((isset($r->esPersonaBuscada) && $r->esPersonaBuscada) && (isset($r->personaAlertaId) && $r->personaAlertaId != null)){
                #---PERSONA CON ALERTA---
                    $pAlert = personas_alertas::find(Crypt::decryptString($r->personaAlertaId));

                    if(!$pAlert)
                        throw new Exception('No se encontró el registro solicitado: (persona con alerta).');
                #------------------------

                #---HUÉSPED---
                    #Obtener relaciones
                    $tipoIdentificacion = identificaciones::find($r->identificaciones_id);
                    $municipio          = municipios::find($r->municipios_id);
                    $pais               = paises::find($r->paises_id);
                #-------------

                $data = [
                    #Info huésped
                    'nombre'            =>$r->nombre,
                    'nacimiento'        =>$r->nacimiento,
                    'telefono'          =>$r->telefono ?? 'Sin teléfono',
                    'tipoIdentificacion'=>$tipoIdentificacion->identificacion ?? 'Sin identificación',
                    'identificacion'    =>$r->identificacion,
                    'municipio'         =>$municipio->municipio ?? 'Sin municipio',
                    'pais'              =>$pais->pais ?? 'Sin país',

                    #Info persona con alerta
                    'nombres'  =>$pAlert->nombres ?? 'Sin nombres',
                    'apellidos'=>$pAlert->apellidos ?? 'Sin apellidos',
                    'alias'    =>$pAlert->alias ?? 'Sin alias',
                    'n_identificacion'=>$pAlert->numero_identificacion ?? 'Sin número de identificación',
                    'ilicita'  =>$pAlert->ilicita,
                    'peps'     =>$pAlert->peps,
                ];
                
                Mail::to(strtolower(trim(env('huesped_alerta','norvinrequeno@tropicoinn.com.sv'))))->queue(new SendHuespedesStoreMail($data));
            }

            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Registro guardado correctamente: ' . $p->nombre)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function show()
    {
        //
    }

    public function edit($id)
    {
        try {

            return view($this->table . ".edit", [

                'th' => $this->th['edit'],
                'p' => model::findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,
                'identificaciones' => identificaciones::all(),

            ]);
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al encontrar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
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
            $p->municipios_id = $r->municipios_id ?? null;
            $p->paises_id = $r->paises_id ?? null;
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
            if (!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table . '.index')
                    ->with('message', 'Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type', 'danger');

            $p = model::findOrFail(Crypt::decryptString($r->id));
            if ((isset($p->municipios) && $p->municipios->count() > 0) || ((isset($p->identificaciones) && $p->identificaciones->count() > 0)|| (isset($p->paises) && $p->paises->count() > 0))) {
                return redirect()->route($this->table . '.index')
                    ->with('message', 'No se puede eliminar el huesped porque tiene identifcaciones o municipios o paises asociados.')
                    ->with('type', 'danger');
            }
            $p->delete();

            return to_route($this->table . '.index')
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
            $p->estado = !$p->estado;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Se ' . ($p ? 'activo' : 'desactivo') . ' el huesped: ' . $p->nombre)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al cambiar estado: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function bloquear($id)
    {
        try {
            $p = model::findOrFail(Crypt::decryptString($id));
            $p->bloqueado = !$p->bloqueado;
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
}
