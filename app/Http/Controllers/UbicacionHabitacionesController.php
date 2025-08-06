<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storeubicacion_habitacionesRequest;
use App\Http\Requests\Updateubicacion_habitacionesRequest;
use App\Models\ubicacion_habitaciones;

#Agregar.
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Throwable;

class UbicacionHabitacionesController extends Controller
{
    private $table = 'ubicacion_habitaciones';

    public function __construct()
    {
        $this->getTh($this->table,'Ubicacion Habitaciones');    
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
            'p'=>ubicacion_habitaciones::orderBy('ubicacion_habitacion','ASC')->get()
        ]);
    }

    public function search(Request $r){
        if(isset($r->txtBusqueda)){
            $p = ubicacion_habitaciones::where('ubicacion_habitacion','like','%'.$r->txtBusqueda.'%')->get();

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
            'th'=>$this->th['create'],
            'data'=>[],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storeubicacion_habitacionesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storeubicacion_habitacionesRequest $r)
    {
        try{
            $p = new ubicacion_habitaciones;
            $p->ubicacion_habitacion = $r->ubicacion_habitacion;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Registro guardado correctamente: '.$p->ubicacion_habitacion)
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
     * @param  \App\Models\ubicacion_habitaciones  $ubicacion_habitaciones
     * @return \Illuminate\Http\Response
     */
    public function show(ubicacion_habitaciones $ubicacion_habitaciones)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ubicacion_habitaciones  $ubicacion_habitaciones
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            return view($this->table.'.edit',[
                'th'=>$this->th['edit'],
                'table'=>$this->table,
                'p'=>ubicacion_habitaciones::findOrFail(Crypt::decryptString($id))
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
     * @param  \App\Http\Requests\Updateubicacion_habitacionesRequest  $request
     * @param  \App\Models\ubicacion_habitaciones  $ubicacion_habitaciones
     * @return \Illuminate\Http\Response
     */
    public function update(Updateubicacion_habitacionesRequest $r)
    {
        try{
            $p = ubicacion_habitaciones::findOrFail($r->id);
            $p->ubicacion_habitacion = $r->ubicacion_habitacion;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Registro actualizado correctamente: '.$p->ubicacion_habitacion)
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
                'p'=>ubicacion_habitaciones::findOrFail(Crypt::decryptString($id))
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
     * @param  \App\Models\ubicacion_habitaciones  $ubicacion_habitaciones
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try{
            $p = ubicacion_habitaciones::findOrFail(Crypt::decryptString($r->id));
            $p->delete();

            return to_route($this->table.'.index')
                ->with('message','Registro eliminado correctamente: '.$p->ubicacion_habitacion)
                ->with('type','success');
        }
        catch(\Throwable $th){
            return to_route($this->table.'.index')
                ->with('message','Ocurrio un error al eliminar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }
}
