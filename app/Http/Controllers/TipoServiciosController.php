<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storetipo_serviciosRequest;
use App\Http\Requests\Updatetipo_serviciosRequest;
use App\Models\tipo_servicios;

#Agregar
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class TipoServiciosController extends Controller
{
    private $table = 'tipo_servicios';

    private $tipoServicioTokens = [
        ['id'=>1,'tipo_servicio'=>'ALIMENTOS Y BEBIDAS','token'=>12001],
        ['id'=>2,'tipo_servicio'=>'HOTEL',              'token'=>12002],
        ['id'=>3,'tipo_servicio'=>'SERVICIOS',          'token'=>12003],
        ['id'=>4,'tipo_servicio'=>'BEBIDAS ALCOHOLICAS','token'=>12004],
        ['id'=>5,'tipo_servicio'=>'TABACOS',            'token'=>12005]
    ];

    public function __construct(){
        $this->getTh($this->table,'Tipo de servicios');
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
            'data'=>[
                'tipoServicioTokens'=>$this->tipoServicioTokens
            ],
            'p'=>tipo_servicios::orderBy('id','DESC')->paginate(15)
        ]);
    }

    public function search(Request $request)
    {
        if(isset($request->txtBusqueda)){
            $p = tipo_servicios::where('tipo_servicio','ilike','%'.$request->txtBusqueda.'%')
                ->orWhere('token','ilike','%'.$request->txtBusqueda.'%')
                ->paginate();
            
            return view($this->table.'.index',[
                'th'=>$this->th['create'],
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
            'th'=>$this->th['create'],
            'data'=>[
                'tipoServicioTokens'=>$this->tipoServicioTokens
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storetipo_serviciosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storetipo_serviciosRequest $request)
    {
        try{
            $p = new tipo_servicios;
            $p->tipo_servicio = $request->tipo_servicio;
            $p->token = $request->token;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Registro guardado correctamente: '.$p->tipo_servicio)
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
     * @param  \App\Models\tipo_servicios  $tipo_servicios
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\tipo_servicios  $tipo_servicios
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            return view($this->table.'.edit',[
                'th'=>$this->th['edit'],
                'p'=>tipo_servicios::findOrFail(Crypt::decryptString($id)),
                'data'=>[
                'tipoServicioTokens'=>$this->tipoServicioTokens
                ],
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
     * @param  \App\Http\Requests\Updatetipo_serviciosRequest  $request
     * @param  \App\Models\tipo_servicios  $tipo_servicios
     * @return \Illuminate\Http\Response
     */
    public function update(Updatetipo_serviciosRequest $request)
    {
        try{
            $p = tipo_servicios::findOrFail($request->id);

            $p->tipo_servicio = $request->tipo_servicio;
            $p->token = $request->token;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Registro actualizado correctamente: '.$p->tipo_servicio)
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
                'p'=>tipo_servicios::findOrFail(Crypt::decryptString($id))
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
     * @param  \App\Models\tipo_servicios  $tipo_servicios
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try{
            if(!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table .'.index')
                    ->with('message','Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type','danger');

                #tipo_servicios::destroy(Crypt::decryptString($r->id));
                $p = tipo_servicios::findOrFail(Crypt::decryptString($r->id));
                $p->delete();

            return to_route($this->table.'.index')
                ->with('message','Registro eliminado con exito: '.$p->tipo_servicio);
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
            $p = tipo_servicios::findOrFail(Crypt::decryptString($id));
            $p->estado = !$p->estado;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Estado modificado correctamente: '.$p->tipo_servicio)
                ->with('type','success');
        }
        catch(\Throwable $th){
            return redirect()->route($this->table.'.index')
                ->with('message','Error al cambiar estado: '.$th->getMessage())
                ->with('type','danger');
        }
    }
}
