<?php

namespace App\Http\Controllers;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Http\Request;
use App\Http\Requests\Storetipo_cortesiaRequest;
use App\Http\Requests\Updatetipo_cortesiaRequest;
use App\Models\tipo_cortesia;
use Illuminate\Support\Facades\Crypt;

class TipoCortesiaController extends Controller
{   
    private $table = 'tipo_cortesia';

    public function __construct(){
        $this->getTh($this->table,'Tipo cortesia');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->table. '.index',[
            'th'=>$this->th['index'],
            'p'=>tipo_cortesia::orderBy('id','DESC')->paginate(15)
        ]);
    }
    public function search(Request $r){
        return view($this->table . '.index', [
            'th'         => $this->th['index'],
            'p'       => tipo_cortesia::where('tipo', 'ilike', '%' . $r->txtBusqueda . '%')->paginate(),
            'txtBusqueda' => $r->txtBusqueda,
        ]);
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
     * @param  \App\Http\Requests\Storetipo_cortesiaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storetipo_cortesiaRequest $request)
    {
        try{
            $data = new Tipo_cortesia;
        
            $data->tipo = $request->tipo;
            $data->descripcion = $request->descripcion;
            $data->save();

            return to_route($this->table.'.index')
                ->with('message','Registro guardado correctamente: '.$data->tipo)
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
     * @param  \App\Models\tipo_cortesia  $tipo_cortesia
     * @return \Illuminate\Http\Response
     */
    public function show(tipo_cortesia $tipo_cortesia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\tipo_cortesia  $tipo_cortesia
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
        return view($this->table . ".edit",[
            'th' => $this->th['edit'],
            'p' => tipo_cortesia::findOrFail(Crypt::decryptString($id))
        ]);
        } catch (\Throwable $th) {
            return redirect()->route($this->table. '.index')
            ->with('message','Error al encontrar el registro:'.$th->getMessage())
            ->with('type', 'danger');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatetipo_cortesiaRequest  $request
     * @param  \App\Models\tipo_cortesia  $tipo_cortesia
     * @return \Illuminate\Http\Response
     */
    public function update(Updatetipo_cortesiaRequest $request)
    {
        try{
            $p = tipo_cortesia::findOrFail($request->id);
            $p->tipo = $request->tipo;
            $p->descripcion = $request->descripcion;
            $p->save();

            return redirect()->route($this->table.'.index')
                ->with('message','Registro editado correctamente: '.$p->tipo)
                ->with('type','info');
        }
        catch(\Throwable $th){
            return redirect()->route($this->table.'.index')
                ->with('message','Error al editar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }
    /**
     * funcion para cconfirm
     */
    public function confirm($id){
        try {
        return view("confirm", [
            'th'=> $this->th['confirm'],
            'p' => tipo_cortesia::findOrFail(Crypt::decryptString($id))
        ]);
        } catch (\Throwable $th) {
            return redirect()-back()
            ->with('message', 'Ocurrio un error ('. $t->getMessage() . ')')
            ->with('type', 'danger');
        }
    }
    /*
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\tipo_cortesia  $tipo_cortesia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            if (!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table . '.index')
                    ->with('message', 'Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type', 'danger');

            tipo_cortesia::destroy(Crypt::decryptString($r->id));

            return redirect()->route($this->table . '.index')
                ->with('message', 'Registro eliminado con exito');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $th->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
        public function status($id)
    {
        try{
            $p = tipo_cortesia::findOrFail(Crypt::decryptString($id));
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
}
