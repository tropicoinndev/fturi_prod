<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoredescuentosRequest;
use App\Http\Requests\UpdatedescuentosRequest;
use App\Models\descuentos;

#Agregar
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class DescuentosController extends Controller
{
    private $table = 'descuentos';

    public function __construct(){
        $this->getTh($this->table,'Descuentos');
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
            'p'=>descuentos::orderBy('id','DESC')->paginate(15)
        ]);
    }

    public function search(Request $request)
    {
        if(isset($request->txtBusqueda)){
            $p = descuentos::where('descuento','ilike','%'.$request->txtBusqueda.'%')->paginate();

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
     * @param  \App\Http\Requests\StoredescuentosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoredescuentosRequest $request)
    {
        try{
            $p = new descuentos;
            $p->descuento = $request->descuento;
            $p->porcentaje = $request->porcentaje;
            $p->decimales = $request->decimales;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Registro guardado correctamente: '.$p->descuento)
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
     * @param  \App\Models\descuentos  $descuentos
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\descuentos  $descuentos
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            return view($this->table.'.edit',[
                'th'=>$this->th['edit'],
                'p'=>descuentos::findOrFail(Crypt::decryptString($id))
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
     * @param  \App\Http\Requests\UpdatedescuentosRequest  $request
     * @param  \App\Models\descuentos  $descuentos
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatedescuentosRequest $request)
    {
        try{
            $p = descuentos::findOrFail($request->id);

            $p->descuento = $request->descuento;
            $p->porcentaje = $request->porcentaje;
            $p->decimales = $request->decimales;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Registro actualizado correctamente: '.$p->descuento)
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
                'p'=>descuentos::findOrFail(Crypt::decryptString($id))
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
     * @param  \App\Models\descuentos  $descuentos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try{
            if(!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table .'.index')
                    ->with('message','Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type','danger');

                #descuentos::destroy(Crypt::decryptString($r->id));
                $p = descuentos::findOrFail(Crypt::decryptString($r->id));
                $p->delete();

            return to_route($this->table.'.index')
                ->with('message','Registro eliminado con exito: '.$p->descuento);
        }
        catch(\Throwable $t){
            return redirect()->back()
                ->with('message','Ocurrio un error ('.$t->getMessage().')')
                ->with('type','danger');
        }
    }
    
}
