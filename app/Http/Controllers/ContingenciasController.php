<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorecontingenciasRequest;
use App\Http\Requests\UpdatecontingenciasRequest;
use App\Models\contingencias;
use Exception;
#Add
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Predis\Command\Argument\Server\To;

class ContingenciasController extends Controller
{
    private $table = 'contingencias';

    public function __construct(){
        $this->getTh($this->table,'Contingencias');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->table.'.index',[
            'th'   =>$this->th['index'],
            'table'=>$this->table,
            'p'    =>contingencias::orderBy('id','desc')->get(),
        ]);
    }

    public function search(Request $r){
        return view($this->table.'.index',[
            'th'         =>$this->th['index'],
            'table'      =>$this->table,
            'p'          =>contingencias::where('valor','ilike','%'.$r->txtBusqueda.'%')->paginate(15),
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
     * @param  \App\Http\Requests\StorecontingenciasRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorecontingenciasRequest $r){
        try{
            $p = new contingencias();
            $p->codigo = $r->codigo;
            $p->valor  = $r->valor;
            $p->estado = true;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Registro guardado correctamente: '.$p->valor)
                ->with('type','success');
        }
        catch(Exception $e){
            return redirect()->back()
                ->with('message','Error al guardar el registro: '.$e->getMessage())
                ->with('type','danger');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\contingencias  $contingencias
     * @return \Illuminate\Http\Response
     */
    public function show(contingencias $contingencias)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\contingencias  $contingencias
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        try{
            return view($this->table.'.edit',[
                'th'=>$this->th['edit'],
                'p' =>contingencias::find(Crypt::decryptString($id)),
            ]);
        }
        catch(Exception $e){
            return to_route($this->table.'.index')
                ->with('message','Error al encontrar el registro: '.$e->getMessage())
                ->with('type','danger');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatecontingenciasRequest  $request
     * @param  \App\Models\contingencias  $contingencias
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatecontingenciasRequest $r){
        try{
            $p = contingencias::find(Crypt::decryptString($r->id));

            $p->codigo = $r->codigo;
            $p->valor  = $r->valor;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Registro guardado correctamente: '.$p->valor)
                ->with('type','success');
        }
        catch(Exception $e){
            return to_route($this->table.'.index')
                ->with('message','Error al editar el registro: '.$e->getMessage())
                ->with('type','danger');
        }
    }

    public function confirm($id){
        try{
            return view('confirmContingencias',[
                'th'=>$this->th['confirm'],
                'p' =>$id,
            ]);
        }
        catch(Exception $e){
            return redirect()->back()
                ->with('message','Ocurrio un error: '.$e->getMessage())
                ->with('type','danger');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\contingencias  $contingencias
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r){
        try{
            if(!isset($r->id) || empty(trim($r->id))){
                return to_route($this->table.'.index')
                    ->with('message','Ocurrior un error, el identificador de registro no cumple con los requerimientos necesarios.')
                    ->with('type','danger');
            }

            $p = contingencias::find(Crypt::decryptString($r->id));
            $p->delete();

            return to_route($this->table.'.index')
                ->with('message','Registro eliminado con exito: '.$p->valor)
                ->with('type','success');
        }
        catch(Exception $e){
            return redirect()->back()
                ->with('message','Error al eliminar el registro: '.$e->getMessage())
                ->with('type','danger');
        }
    }

    public function status($id){
        try{
            $p = contingencias::find(Crypt::decryptString($id));
            $p->estado = !$p->estado;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Se ha modificado el estado: '.$p->valor)
                ->with('type','success');
        }
        catch(Exception $e){
            return to_route($this->table.'.index')
                ->with('message','Error al cambiar estado: '.$e->getMessage())
                ->with('type','danger');
        }
    }
}
