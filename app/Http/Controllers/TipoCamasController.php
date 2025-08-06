<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storetipo_camasRequest;
use App\Http\Requests\Updatetipo_camasRequest;
use App\Models\tipo_camas;

#Agregar.
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Throwable;

class TipoCamasController extends Controller
{
    private $table = 'tipo_camas';

    public function __construct()
    {
        $this->getTh($this->table,'Tipo Camas');    
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
            'table'=>$this->table,
            'p'=>tipo_camas::orderBy('tipo_cama','ASC')->get()
        ]);
    }

    public function search(Request $r){
        if(isset($r->txtBusqueda)){
            $p = tipo_camas::where('tipo_cama','ilike','%'.$r->txtBusqueda.'%')->get();

            return view($this->table.'.index',[
                'th'=>$this->th['index'],
                'table'=>$this->table,
                'p'=>$p,
                'txtBusqueda'=>$r->txtBusqueda
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storetipo_camasRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storetipo_camasRequest $r)
    {
        try{
            $p = new tipo_camas;
            $p->tipo_cama = $r->tipo_cama;
            $p->largo = $r->largo;
            $p->ancho = $r->ancho;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Registro guardado correctamente: '.$p->tipo_cama)
                ->with('type','success');
        }
        catch(\Throwable $th){
            return to_route($this->table.'.index')
                ->with('message','Error al guardar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\tipo_camas  $tipo_camas
     * @return \Illuminate\Http\Response
     */
    public function show(tipo_camas $tipo_camas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\tipo_camas  $tipo_camas
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            return view($this->table.'.edit',[
                'th'=>$this->th['edit'],
                'table'=>$this->table,
                'p'=>tipo_camas::findOrFail(Crypt::decryptString($id)),
                'data'=>[]
            ]);
        }
        catch(\Throwable $th){
            return to_route($this->table.'.index')
                ->with('message','Error al encontrar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatetipo_camasRequest  $request
     * @param  \App\Models\tipo_camas  $tipo_camas
     * @return \Illuminate\Http\Response
     */
    public function update(Updatetipo_camasRequest $r)
    {
        try{
            $p = tipo_camas::findOrFail($r->id);
            $p->tipo_cama = $r->tipo_cama;
            $p->largo = $r->largo;
            $p->ancho = $r->ancho;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Registro actualizado correctamente: '.$p->tipo_cama)
                ->with('type','succes');
        }
        catch(\Throwable $th){
            return to_route($this->table.'.index')
                ->with('message','Error al actualizar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }

    public function confirm($id){
        try{
            return view('confirm',[
                'th'=>$this->th['confirm'],
                'p'=>tipo_camas::findOrFail(Crypt::decryptString($id))
            ]);
        }
        catch(\Throwable $t){
            return redirect()->back()
                ->with('message','Ocurrio un error: '.$t->getMessage())
                ->with('type','danger');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\tipo_camas  $tipo_camas
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try{
            $p = tipo_camas::findOrFail(Crypt::decryptString($r->id));
            $p->delete();

            return to_route($this->table.'.index')
                ->with('message','Registro eliminado correctamente: '.$p->tipo_cama)
                ->with('type','success');
        }
        catch(\Throwable $th){
            return to_route($this->table.'.index')
                ->with('message','Ocurrio un error al eliminar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }
}
