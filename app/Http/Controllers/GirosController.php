<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoregirosRequest;
use App\Http\Requests\UpdategirosRequest;
use App\Models\giros;

#Agregar
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\VarDumper\VarDumper;

class GirosController extends Controller
{
    private $table = 'giros';

    public function __construct(){
        $this->getTh($this->table,'Giros');
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
            'p'=>giros::orderBy('id','DESC')->paginate(15)
        ]);
    }

    public function search(Request $request)
    {
        if(isset($request->txtBusqueda)){
            $p = giros::where('giro','ilike','%'.$request->txtBusqueda.'%')
                ->orWhere('codigo','ilike','%'.$request->txtBusqueda.'%')
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
     * @param  \App\Http\Requests\StoregirosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoregirosRequest $request)
    {
        try{
            $p = new giros;
            $p->giro = $request->giro;
            $p->codigo = $request->codigo;
            $p->descripcion = $request->descripcion;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Registro guardado correctamente: '.$p->giro)
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
     * @param  \App\Models\giros  $giros
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            return view($this->table.'.show',[
                'th'=>$this->th['show'],
                'p'=>giros::findOrFail(Crypt::decryptString($id))
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
     * @param  \App\Models\giros  $giros
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            return view($this->table.'.edit',[
                'th'=>$this->th['edit'],
                'p'=>giros::findOrFail(Crypt::decryptString($id))
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
     * @param  \App\Http\Requests\UpdategirosRequest  $request
     * @param  \App\Models\giros  $giros
     * @return \Illuminate\Http\Response
     */
    public function update(UpdategirosRequest $request)
    {
        try{
            $p = giros::findOrFail($request->id);

            $p->giro = $request->giro;
            $p->codigo = $request->codigo;
            $p->descripcion = $request->descripcion;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Registro actualizado correctamente: '.$p->giro)
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
                'p'=>giros::findOrFail(Crypt::decryptString($id))
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
     * @param  \App\Models\giros  $giros
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try{
            if(!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table .'.index')
                    ->with('message','Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type','danger');

                #giros::destroy(Crypt::decryptString($r->id));
                $p = giros::findOrFail(Crypt::decryptString($r->id));
                $p->delete();

            return to_route($this->table.'.index')
                ->with('message','Registro eliminado con exito: '.$p->giro);
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
            $p = giros::findOrFail(Crypt::decryptString($id));
            $p->estado = !$p->estado;
            $p->save();

            return to_route($this->table.'.index')
                ->with('message','Estado modificado correctamente: '.$p->giro)
                ->with('type','success');
        }
        catch(\Throwable $th){
            return redirect()->route($this->table.'.index')
                ->with('message','Error al cambiar estado: '.$th->getMessage())
                ->with('type','danger');
        }
    }
}
