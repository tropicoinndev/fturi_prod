<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storeestado_habitacionesRequest;
use App\Http\Requests\Updateestado_habitacionesRequest;
use App\Models\estado_habitaciones;

#Agregar.
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;

class EstadoHabitacionesController extends Controller
{
    private $table = 'estado_habitaciones';
        private $tokenEstados = [['value'=> '1401', 'text'=>'Vacia Limpia'],
        ['value'=> '1402', 'text'=>'Vacia Sucia'],['value'=> '1403', 'text'=>'Ocupada Sucia'],
        ['value'=> '1404', 'text'=>'Ocupada'],['value'=> '1405', 'text'=>'Mantenimiento'],
        ['value'=> '1406', 'text'=>'Bloqueo']
    ];

    public function __construct()
    {
        $this->getTh($this->table,'Estado Habitaciones');    
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
            'p'=>estado_habitaciones::orderBy('estado_habitacion','ASC')->get(),
            'data' => [
                'tokenEstados' => $this->tokenEstados
            ]
        ]);
    }

    public function search(Request $r){
        if(isset($r->txtBusqueda)){
            $p = estado_habitaciones::where('estado_habitacion','ilike','%'.$r->txtBusqueda.'%')
                ->orWhere('token','ilike','%'.$r->txtBusqueda.'%')
                ->get();

            return view($this->table.'.index',[
                'th'=>$this->th['create'],
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
            'table'=>$this->table,
            'data' => [
                'tokenEstados' => $this->tokenEstados
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storeestado_habitacionesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storeestado_habitacionesRequest $r)
    {
        try{
            $p = new estado_habitaciones;
            $p->estado_habitacion = $r->estado_habitacion;
            $p->token = $r->token;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Registro guardado correctamente: '.$p->estado_habitacion)
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
     * @param  \App\Models\estado_habitaciones  $estado_habitaciones
     * @return \Illuminate\Http\Response
     */
    public function show(estado_habitaciones $estado_habitaciones)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\estado_habitaciones  $estado_habitaciones
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            return view($this->table.'.edit',[
                'th'=>$this->th['edit'],
                'table'=>$this->table,
                'p'=>estado_habitaciones::findOrFail(Crypt::decryptString($id)),
                'data' => [
                'tokenEstados' => $this->tokenEstados
            ]
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
     * @param  \App\Http\Requests\Updateestado_habitacionesRequest  $request
     * @param  \App\Models\estado_habitaciones  $estado_habitaciones
     * @return \Illuminate\Http\Response
     */
    public function update(Updateestado_habitacionesRequest $r)
    {
        try{
            $p = estado_habitaciones::findOrFail($r->id);
            $p->estado_habitacion = $r->estado_habitacion;
            $p->token = $r->token;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Registro actualizado correctamente: '.$p->estado_habitacion)
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
                'p'=>estado_habitaciones::findOrFail(Crypt::decryptString($id))
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
     * @param  \App\Models\estado_habitaciones  $estado_habitaciones
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try{
            $p = estado_habitaciones::findOrFail(Crypt::decryptString($r->id));
            $p->delete();

            return to_route($this->table.'.index')
                ->with('message','Registro eliminado correctamente: '.$p->estado_habitacion)
                ->with('type','success');
        }
        catch(\Throwable $th){
            return to_route($this->table.'.index')
                ->with('message','Ocurrio un error al eliminar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }
}