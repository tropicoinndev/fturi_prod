<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storeforma_habitacionesRequest;
use App\Http\Requests\Updateforma_habitacionesRequest;
use App\Models\forma_habitaciones;

#Agregar.
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;

class FormaHabitacionesController extends Controller
{
    private $table = 'forma_habitaciones';

    public function __construct()
    {
        $this->getTh($this->table,'Forma Habitaciones');    
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
            'p'=>forma_habitaciones::orderBy('forma_habitacion','ASC')->get()
        ]);
    }

    public function search(Request $r){
        if(isset($r->txtBusqueda)){
            $p = forma_habitaciones::where('forma_habitacion','ilike','%'.$r->txtBusqueda.'%')->get();

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
        return view($this->table.'.create',[
            'th'=>$this->th['create']
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storeforma_habitacionesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storeforma_habitacionesRequest $r)
    {
        try{
            $p = new forma_habitaciones;
            $p->forma_habitacion = $r->forma_habitacion;
            $p->max_personas = $r->max_personas;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Registro guardado correctamente: '.$p->forma_habitacion)
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
     * @param  \App\Models\forma_habitaciones  $forma_habitaciones
     * @return \Illuminate\Http\Response
     */
    public function show(forma_habitaciones $forma_habitaciones)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\forma_habitaciones  $forma_habitaciones
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            return view($this->table.'.edit',[
                'th'=>$this->th['edit'],
                'table'=>$this->table,
                'p'=>forma_habitaciones::findOrFail(Crypt::decryptString($id)),
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
     * @param  \App\Http\Requests\Updateforma_habitacionesRequest  $request
     * @param  \App\Models\forma_habitaciones  $forma_habitaciones
     * @return \Illuminate\Http\Response
     */
    public function update(Updateforma_habitacionesRequest $r)
    {
        try{
            $p = forma_habitaciones::findOrFail($r->id);
            $p->forma_habitacion = $r->forma_habitacion;
            $p->max_personas = $r->max_personas;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Registro actualizado correctamente: '.$p->forma_habitacion)
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
                'p'=>forma_habitaciones::findOrFail(Crypt::decryptString($id))
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
     * @param  \App\Models\forma_habitaciones  $forma_habitaciones
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try{
            $p = forma_habitaciones::findOrFail(Crypt::decryptString($r->id));
            $p->delete();

            return to_route($this->table.'.index')
                ->with('message','Registro eliminado correctamente: '.$p->forma_habitacion)
                ->with('type','success');
        }
        catch(\Throwable $th){
            return to_route($this->table.'.index')
                ->with('message','Ocurrio un error al eliminar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }
}
