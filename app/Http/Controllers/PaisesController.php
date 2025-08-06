<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Http\Request;

use App\Http\Requests\StorepaisesRequest;
use App\Http\Requests\UpdatepaisesRequest;
use App\Models\paises;
use Illuminate\Support\Facades\Crypt;

class PaisesController extends Controller
{
    private $table = 'paises';

    public function __construct(){
        $this->getTh($this->table,'Paises');
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
            'p'=>paises::orderBy('id','DESC')->paginate(15)
        ]);
    }
    public function search(Request $r){
        return view($this->table . '.index', [
            'th'         => $this->th['index'],
            'p'       => paises::where('pais', 'ilike', '%' . $r->txtBusqueda . '%')->paginate(),
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
     * @param  \App\Http\Requests\StorepaisesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorepaisesRequest $request)
    {
        try{
            $data = new Paises;
            $data->pais = $request->pais;
            $data->nacionalidad = $request->nacionalidad;
            $data->codigo_mh = $request->codigo_mh;
            $data->save();

            return to_route($this->table.'.index')
                ->with('message','Registro guardado correctamente: '.$data->nacionalidad)
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
     * @param  \App\Models\paises  $paises
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\paises  $paises
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            return view($this->table . ".edit",[
                'th' => $this->th['edit'],
                'p' => paises::findOrFail(Crypt::decryptString($id))
            ]);
        
        }
        catch(\Throwable $th){
            return redirect()->route($this->table.'.index')
                ->with('message','Error al encontrar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatepaisesRequest  $request
     * @param  \App\Models\paises  $paises
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatepaisesRequest $request)
    {
        try{
            $p = paises::findOrFail($request->id);
            $p->pais = $request->pais;
            $p->nacionalidad = $request->nacionalidad;
            $p->codigo_mh = $request->codigo_mh;
            $p->save();

            return redirect()->route($this->table.'.index')
                ->with('message','Registro editado correctamente: '.$p->pais)
                ->with('type','info');
        }
        catch(\Throwable $th){
            return redirect()->route($this->table.'.index')
                ->with('message','Error al editar el registro: '.$th->getMessage())
                ->with('type','danger');
        }
    }

    public function confirm($id)
    {
        try {
            return view("confirm", [
                'th' => $this->th['confirm'],
                'p' => Paises::findOrFail(Crypt::decryptString($id))
            ]);
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\paises  $paises
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            if (!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table . '.index')
                    ->with('message', 'Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type', 'danger');

            Paises::destroy(Crypt::decryptString($r->id));

            return redirect()->route($this->table . '.index')
                ->with('message', 'Registro eliminado con exito');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $th->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

    public function apiGetPaises(Request $r){
        #Utilizar unaccent para eliminar acentos en la base de datos y en el término de búsqueda
        #Ejucutar en Postgres: CREATE EXTENSION IF NOT EXISTS unaccent;
        return response()->json([
            'paises'=>paises::whereRaw('unaccent(pais) ILIKE unaccent(?)',['%'.$r->busqueda.'%'])->get(),
        ]);
    }
}
