<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storeopcion_turnosRequest;
use App\Http\Requests\Updateopcion_turnosRequest;
use App\Models\opcion_turnos;

#Agregar
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class OpcionTurnosController extends Controller
{
    private $table = 'opcion_turnos';

    public function __construct(){
        $this->getTh($this->table,'Opcion turnos');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->table.'.index',[
            'th'=>$this->th['index'],
            'p'=>opcion_turnos::orderBy('id','DESC')->paginate(15)
        ]);
    }

    public function search(Request $request)
    {
        if(isset($request->txtBusqueda)){
            $p = opcion_turnos::where('turno','like','%'.$request->txtBusqueda.'%')
                ->orWhere('apertura','like','%'.$request->txtBusqueda.'%')
                ->orWhere('cierre','like','%'.$request->txtBusqueda.'%')
                ->paginate();
            
            return view($this->table.'.index',[
                'th'=>$this->th['index'],
                'p'=>$p,
                'txtBusqueda'=>$request->txtBusqueda
            ]);
        }
        else{
            return to_route($this->table.'.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->table.'.create',[
            'th'=>$this->th['create']
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storeopcion_turnosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storeopcion_turnosRequest $request)
    {
        try{
            $p = new opcion_turnos;
            $p->turno = $request->turno;
            $p->apertura = $request->apertura;
            $p->cierre = $request->cierre;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Registro guardado correctamente: '.$p->turno)
                ->with('type','success');
        }
        catch(\Throwable $th){
            return redirect()->route($this->table.'.index')
                ->with('message','Error al guardar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\opcion_turnos  $opcion_turnos
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\opcion_turnos  $opcion_turnos
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            return view($this->table.'.edit',[
                'th'=>$this->th['edit'],
                'p'=>opcion_turnos::findOrFail(Crypt::decryptString($id))
            ]);
        }
        catch(\Throwable $th){
            return to_route($this->table.'.index')
                ->with('message','Error al encontar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updateopcion_turnosRequest  $request
     * @param  \App\Models\opcion_turnos  $opcion_turnos
     * @return \Illuminate\Http\Response
     */
    public function update(Updateopcion_turnosRequest $request)
    {
        try{
            $p = opcion_turnos::findOrFail($request->id);

            $p->turno = $request->turno;
            $p->apertura = $request->apertura;
            $p->cierre = $request->cierre;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Registro actualizado correctamente: '.$p->turno)
                ->with('type','success');
        }
        catch(\Throwable $th){
            return to_route($this->table.'.index')
                ->with('message','Error al actualizar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }

    public function confirm($id)
    {
        try{
            return view('confirm',[
                'th'=>$this->th['confirm'],
                'p'=>opcion_turnos::findOrFail(Crypt::decryptString($id))
            ]);
        }
        catch(\Throwable $t){
            return redirect()->back()
                ->with('message','Ocurrio un error ('.$t->getMessage().')')
                ->with('type','danger');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\opcion_turnos  $opcion_turnos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try{
            if(!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table .'.index')
                    ->with('message','Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type','danger');

                #opcion_turnos::destroy(Crypt::decryptString($r->id));
                $p = opcion_turnos::findOrFail(Crypt::decryptString($r->id));
                $p->delete();

            return to_route($this->table.'.index')
                ->with('message','Registro eliminado con exito: '.$p->turno);
        }
        catch(\Throwable $t){
            return redirect()->back()
                ->with('message','Ocurrio un error ('.$t->getMessage().')')
                ->with('type','danger');
        }
    }

    public function status($id)
    {
        try{
            $p = opcion_turnos::findOrFail(Crypt::decryptString($id));
            $p->estado = !$p->estado;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Estado modificado correctamente: '.$p->turno)
                ->with('type','success');
        }
        catch(\Throwable $th){
            return redirect()->route($this->table.'.index')
                ->with('message','Error al cambiar estado: '.$th->getMessage())
                ->with('type','danger');
        }
    }
}
