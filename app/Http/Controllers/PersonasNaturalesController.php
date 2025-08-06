<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storepersonas_naturalesRequest;
use App\Http\Requests\Updatepersonas_naturalesRequest;
use App\Models\clientes_personas;
use App\Models\identificaciones;
use App\Models\personas_naturales;
use App\Models\operaciones_reguladas;
use Throwable;

#Add
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class PersonasNaturalesController extends Controller
{
    private $table = 'personas_naturales';

    public function __construct()
    {
        $this->getTh($this->table, 'Personas Naturales');
    }

    public function index()
    {
        return view($this->table . '.index', [
            'th' => $this->th['index'],
            'data' => personas_naturales::orderBy('id', 'desc')->paginate(15),
            #'identificaciones' => identificaciones::orderBy('identificacion', 'asc')->get(),
        ]);
    }

    public function search(Request $r){
        $data = personas_naturales::where('nombre','ilike','%'.$r->txtBusqueda.'%')
            ->orWhere('apellidos','ilike','%'.$r->txtBusqueda.'%')
            ->orWhere('identificacion','ilike','%'.$r->txtBusqueda.'%')
            ->paginate(200);

        return view($this->table.'.index',[
            'th'=>$this->th['index'],
            'data'=>$data,
            'txtBusqueda'=>$r->txtBusqueda,
        ]);
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
     * @param  \App\Http\Requests\Storepersonas_naturalesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storepersonas_naturalesRequest $r)
    {
        try {
            $p = new personas_naturales;
            $p->apellidos = $r->apellidos;
            $p->nombre = $r->nombre;
            $p->profesion = $r->profesion;
            $p->nacimiento = $r->nacimiento;
            $p->departamentos_id = $r->departamentos_id;
            $p->fecha_nacimiento = $r->fecha_nacimiento;
            $p->paises_id = $r->paises_id;
            $p->estado_civil = $r->estado_civil;
            $p->identificaciones_id = $r->identificaciones_id;
            $p->identificacion = $r->identificacion;
            $p->domicilio = $r->domicilio;
            $p->observaciones = $r->observaciones;
            #$p->persona_riesgo = $r->persona_riesgo;

            $p->persona_riesgo = $r->has('persona_riesgo') ? (bool) $r->persona_riesgo : false;
            $p->save();

            if (isset($r->clientes_id) && $r->clientes_id != null) {
                $cp = new clientes_personas;
                $cp->clientes_id = $r->clientes_id;
                $cp->personas_naturales_id = $p->id;
                $cp->save();
            }

            return redirect()->back()
                ->with('type', 'success')
                ->with('message', 'Registro guardado correctamente: ' . $p->apellidos);
        } catch (Throwable $th) {
            return to_route($this->table . '.index')
                ->with('type', 'danger')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\personas_naturales  $personas_naturales
     * @return \Illuminate\Http\Response
     */
    public function show(personas_naturales $personas_naturales)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\personas_naturales  $personas_naturales
     * @return \Illuminate\Http\Response
     */
    public function edit(personas_naturales $personas_naturales)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatepersonas_naturalesRequest  $request
     * @param  \App\Models\personas_naturales  $personas_naturales
     * @return \Illuminate\Http\Response
     */
    public function update(Updatepersonas_naturalesRequest $request, personas_naturales $personas_naturales)
    {
        //
    }

    public function confirm($id){
        try{
            return view('confirm',[
                'th'=>$this->th['confirm'],
                'p'=>personas_naturales::find(Crypt::decryptString($id)),
            ]);
        }
        catch(Throwable $th){
            return redirect()->back()
                ->with('type','danger')
                ->with('message','Ocurrio un error: '.$th->getMessage());
        }
    }

    public function destroy(Request $r){
        try{
            if(!isset($r->id) || empty(trim($r->id)))
                return to_route($this->table.'.index')
                    ->with('type','danger')
                    ->with('message','Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.');

            $pn = personas_naturales::find(Crypt::decryptString($r->id));

            #1.- Eliminar registro de la tabla 'clientes_personas'
            $cp = clientes_personas::where('personas_naturales_id',$pn->id)->first();
            if($cp)
                $cp->delete();

            #2.- Eliminar registro de la tabla 'operaciones_reguladas'
            $opRe = operaciones_reguladas::where('seccion_a_persona_id',$pn->id)->orWhere('seccion_b_persona_id',$pn->id)->first();
            if($opRe)
                $opRe->delete();

            #3.- Eliminar registro de ésta misma tabla 'personas_naturales'
            $pn->delete();

            return to_route($this->table.'.index')
                ->with('type','success')
                ->with('message','Registro eliminado con exito: '.$pn->nombre.' '.$pn->apellidos);
        }
        catch(Throwable $th){
            return to_route($this->table.'.index')
                ->with('type','danger')
                ->with('message','Ocurrio un error: '.$th->getMessage());
        }
    }
}
