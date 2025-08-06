<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storetipo_reservacionesRequest;
use App\Http\Requests\Updatetipo_reservacionesRequest;
use App\Models\tipo_reservaciones;

#Agregar.
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\React;
use Throwable;

class TipoReservacionesController extends Controller
{
    private $table = 'tipo_reservaciones';

    public function __construct()
    {
        $this->getTh($this->table,'Tipo Reservaciones');    
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
            'p'=>tipo_reservaciones::orderBy('tipo_reservacion','ASC')->get()
        ]);
    }

    public function search(Request $r){
        if(isset($r->txtBusqueda)){
            $p = tipo_reservaciones::where('tipo_reservacion','ilike','%'.$r->txtBusqueda.'%')->get();

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
     * @param  \App\Http\Requests\Storetipo_reservacionesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storetipo_reservacionesRequest $r)
    {
        try{
            $p = new tipo_reservaciones;
            $p->tipo_reservacion = $r->tipo_reservacion;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Registro guardado correctamente: '.$p->tipo_reservacion)
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
     * @param  \App\Models\tipo_reservaciones  $tipo_reservaciones
     * @return \Illuminate\Http\Response
     */
    public function show(tipo_reservaciones $tipo_reservaciones)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\tipo_reservaciones  $tipo_reservaciones
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            return view($this->table.'.edit',[
                'th'=>$this->th['edit'],
                'table'=>$this->table,
                'p'=>tipo_reservaciones::findOrFail(Crypt::decryptString($id)),
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
     * @param  \App\Http\Requests\Updatetipo_reservacionesRequest  $request
     * @param  \App\Models\tipo_reservaciones  $tipo_reservaciones
     * @return \Illuminate\Http\Response
     */
    public function update(Updatetipo_reservacionesRequest $r)
    {
        try{
            $p = tipo_reservaciones::findOrFail($r->id);
            $p->tipo_reservacion = $r->tipo_reservacion;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Registro actualizado correctamente: '.$p->tipo_reservacion)
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
                'p'=>tipo_reservaciones::findOrFail(Crypt::decryptString($id))
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
     * @param  \App\Models\tipo_reservaciones  $tipo_reservaciones
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try{
            $p = tipo_reservaciones::findOrFail(Crypt::decryptString($r->id));
            $p->delete();

            return to_route($this->table.'.index')
                ->with('message','Registro eliminado correctamente: '.$p->tipo_reservacion)
                ->with('type','success');
        }
        catch(\Throwable $th){
            return to_route($this->table.'.index')
                ->with('message','Ocurrio un error al eliminar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }
}
