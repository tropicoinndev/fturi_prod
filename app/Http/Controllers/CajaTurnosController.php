<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storecaja_turnosRequest;
use App\Http\Requests\Updatecaja_turnosRequest;
use App\Models\caja_turnos;

#Agregar
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\opcion_turnos;
use App\Models\cajas;

class CajaTurnosController extends Controller
{
    private $table = 'caja_turnos';

    public function __construct()
    {
        $this->getTh($this->table,'Caja turnos');
    }
    
    /**funcion para listar opcion turnos */
        public function index_api()
    {
        return response()->json(['listf' => $this->getList()]);
    }

    private function getList()
    {
        return opcion_turnos::orderBy('turno','ASC')->get();
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
            'p'=>caja_turnos::orderBy('id','DESC')->paginate(15),
            'table'=>$this->table,
            'data'=>[
                'opcionTurnos'=>opcion_turnos::orderBy('turno','ASC')->where('estado',true)->get(),
                'cajas'=>cajas::orderBy('caja','ASC')->where('estado',true)->get()
            ],
        ]);
    }

    public function search(Request $request)
    {
        // if(isset($request->txtBusqueda)){
        //     $p = caja_turnos::where('opcion_turnos_id','like','%'.$request->txtBusqueda.'%')
        //         ->orWhere('cajas_id','like','%'.$request->txtBusqueda.'%')
        //         ->paginate();

        //     return view($this->table.'.index',[
        //         'th'=>$this->th['index'],
        //         'p'=>$p,
        //         'txtBusqueda'=>$request->txtBusqueda,
        //         'data'=>[
        //             'opcionTurnos'=>opcion_turnos::orderBy('turno','ASC')->where('estado',true)->get(),
        //             'cajas'=>cajas::orderBy('caja','ASC')->where('estado',true)->get()
        //         ],
        //     ]);
        // }
        // else{
        //     return to_route($this->table.'.index');
        // }
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
            'data'=>[
                'opcionTurnos'=>opcion_turnos::orderBy('turno','ASC')->where('estado',true)->get(),
                'cajas'=>cajas::orderBy('caja','ASC')->where('estado',true)->get()
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storecaja_turnosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storecaja_turnosRequest $request)
    {
        try{
            $p = new caja_turnos;
            $p->opcion_turnos_id = $request->opcion_turnos_id;
            $p->cajas_id = $request->cajas_id;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Registro guardado correctamente.')
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
     * @param  \App\Models\caja_turnos  $caja_turnos
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            return view($this->table.'.show',[
                'th'=>$this->th['show'],
                'p'=>caja_turnos::findOrFail(Crypt::decryptString($id)),
                'table'=>$this->table,
                'data'=>[
                    'opcionTurnos'=>opcion_turnos::orderBy('turno','ASC')->where('estado',true)->get(),
                    'cajas'=>cajas::orderBy('caja','ASC')->where('estado',true)->get()
                ],
            ]);
        }
        catch(\Throwable $th){
            return to_route($this->table.'.index')
                ->with('message','Error al encontrar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\caja_turnos  $caja_turnos
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            return view($this->table.'.edit',[
                'th'=>$this->th['edit'],
                'p'=>caja_turnos::findOrFail(Crypt::decryptString($id)),
                'table'=>$this->table,
                'data'=>[
                    'opcionTurnos'=>opcion_turnos::orderBy('turno','ASC')->where('estado',true)->get(),
                    'cajas'=>cajas::orderBy('caja','ASC')->where('estado',true)->get()
                ],
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
     * @param  \App\Http\Requests\Updatecaja_turnosRequest  $request
     * @param  \App\Models\caja_turnos  $caja_turnos
     * @return \Illuminate\Http\Response
     */
    public function update(Updatecaja_turnosRequest $request)
    {
        try{
            $p = caja_turnos::findOrFail($request->id);

            $p->opcion_turnos_id = $request->opcion_turnos_id;
            $p->cajas_id = $request->cajas_id;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Registro actualizado correctamente.')
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
                'p'=>caja_turnos::findOrFail(Crypt::decryptString($id))
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
     * @param  \App\Models\caja_turnos  $caja_turnos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try{
            if(!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table .'.index')
                    ->with('message','Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type','danger');

                #caja_turnos::destroy(Crypt::decryptString($r->id));
                $p = caja_turnos::findOrFail(Crypt::decryptString($r->id));
                $p->delete();

            return to_route($this->table.'.index')
                ->with('message','Registro eliminado con exito.');
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
            $p = caja_turnos::findOrFail(Crypt::decryptString($id));
            $p->estado = !$p->estado;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Estado modificado correctamente.')
                ->with('type','success');
        }
        catch(\Throwable $th){
            return redirect()->route($this->table.'.index')
                ->with('message','Error al cambiar estado: '.$th->getMessage())
                ->with('type','danger');
        }
    }
        public function destroy_api(Request $r)
    {
        $m = "Se elimino turno ";
        $t = true;
        try {
            opcion_turnos::destroy($r->id);
        } catch (\Throwable $th) {
            $t = false;
            $m = "Error: ". $th->getMessage();
        }
        return response()->json([
            'listf' => $this->getList(),
            'message' => $m,
            'type' => $t ? 'success' : 'danger',
        ]);
    }
}
