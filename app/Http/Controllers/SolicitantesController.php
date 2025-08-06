<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoresolicitantesRequest;
use App\Http\Requests\UpdatesolicitantesRequest;
use App\Models\identificaciones;
use App\Models\solicitantes;

#Add
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Throwable;

class SolicitantesController extends Controller
{
    private $table = 'solicitantes';

    public function __construct(){
        $this->getTh($this->table,'Solicitantes');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return view($this->table.'.index',[
            'th'   =>$this->th['index'],
            'table'=>$this->table,
            'p'    =>solicitantes::orderBy('id','desc')->get(),
            'data' =>[
                'identificaciones'=>identificaciones::orderBy('identificacion','asc')->get(),
            ],
        ]);
    }

    public function search(Request $r){

        $p = solicitantes::where('nombre_completo','ilike','%'.$r->txtBusqueda.'%')
            ->orWhere('numero_documento','ilike','%'.$r->txtBusqueda.'%')
            ->orWhere('telefono','ilike','%'.$r->txtBusqueda.'%')
            ->orWhere('correo','ilike','%'.$r->txtBusqueda.'%')
            ->paginate(15);

        return view($this->table.'.index',[
            'th'         =>$this->th['index'],
            'table'      =>$this->table,
            'p'          =>$p,
            'txtBusqueda'=>$r->txtBusqueda,
            'data' => [
                'identificaciones' => identificaciones::orderBy('identificacion', 'asc')->get(),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        return view($this->table.'.create',[
            'th'   =>$this->th['index'],
            'table'=>$this->table,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoresolicitantesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoresolicitantesRequest $r){
        try{
            $p = new solicitantes();
            $p->nombre_completo = $r->nombre_completo;
            $p->identificaciones_id = $r->identificaciones_id;
            $p->numero_documento = $r->numero_documento;
            $p->telefono = $r->telefono;
            $p->correo = $r->correo;
            $p->save();
            if(!empty($r->clientes_id) && !empty($p->id)){
                (new SolicitantesClientesController())->store($p->id,$r->clientes_id );
            }

            return redirect()->back()
                ->with('message','Solicitante guardado correctamente: '.$p->nombre_completo)
                ->with('type','success');
        }
        catch(Throwable $th){
            return redirect()->back()
                ->with('message','Error al guardar el solicitante: '.$th->getMessage())
                ->with('type','danger');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\solicitantes  $solicitantes
     * @return \Illuminate\Http\Response
     */
    public function show(solicitantes $solicitantes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\solicitantes  $solicitantes
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        try{
            return view($this->table.'.edit',[
                'th'=>$this->th['edit'],
                'p' =>solicitantes::find(Crypt::decryptString($id)),
                'data' =>[
                    'identificaciones'=>identificaciones::orderBy('identificacion','asc')->get(),
                ],
            ]);
        }
        catch(Throwable $th){
            return to_route($this->table.'.index')
                ->with('message','Error al encontrar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatesolicitantesRequest  $request
     * @param  \App\Models\solicitantes  $solicitantes
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatesolicitantesRequest $r){
        try{
            $p = solicitantes::find(Crypt::decryptString($r->id));

            $p->nombre_completo = $r->nombre_completo;
            $p->identificaciones_id = $r->identificaciones_id;
            $p->numero_documento = $r->numero_documento;
            $p->telefono = $r->telefono;
            $p->correo = $r->correo;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Registro editado correctamente: '.$p->nombre_completo)
                ->with('type','success');
        }
        catch(Throwable $th){
            return to_route($this->table.'.index')
                ->with('message','Error al editar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }

    public function confirm($id){
        try{
            return view('confirm',[
                'th'=>$this->th['confirm'],
                'p' =>solicitantes::findOrFail(Crypt::decryptString($id)),

            ]);
        }
        catch(Throwable $th){
            return redirect()->back()
                ->with('message','Ocurrio un error: '.$th->getMessage())
                ->with('type','danger');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\solicitantes  $solicitantes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r){
        try{
            if(!isset($r->id) || empty(trim($r->id))){
                return to_route($this->table.'.index')
                    ->with('message','Ocurrior un error, el identificador de registro no cumple con los requerimientos necesarios.')
                    ->with('type','danger');
            }

            $p = solicitantes::findOrFail(Crypt::decryptString($r->id));
            if ($p->solicitante_cliente->count() > 0) {
                return redirect()->route($this->table . '.index')
                    ->with('message', 'No se puede eliminar el solicitante porque tiene cliente asociado.')
                    ->with('type', 'danger');
            }
            $p->delete();

            return to_route($this->table . '.index')
                ->with('message','Registro eliminado con exito: '.$p->nombre_completo)
                ->with('type','success');
        }
        catch(Throwable $th){
            return redirect()->back()
                ->with('message','Error al eliminar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }

    public function status($id){
        try{
            $p = solicitantes::find(Crypt::decryptString($id));
            $p->estado = !$p->estado;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Se ha modificado el estado: '.$p->nombre_completo)
                ->with('type','success');
        }
        catch(Throwable $th){
            return to_route($this->table.'.index')
                ->with('message','Error al cambiar estado: '.$th->getMessage())
                ->with('type','danger');
        }
    }
}
