<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\StoredepartamentosRequest;
use App\Http\Requests\UpdatedepartamentosRequest;
use App\Models\departamentos;
use App\Models\paises;


class DepartamentosController extends Controller
{
    private $table = 'departamentos';

    public function __construct(){
        $this->getTh($this->table,'Departamentos');
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
            'p'=>departamentos::orderBy('id','DESC')->paginate(14),
            'table'=>$this->table,
            'data' => [
                'paises'=>paises::orderBy('pais', 'ASC')->get(),
            ],
        ]);
    }

    public function search(Request $r){
        return view($this->table . '.index', [
            'th' => $this->th['index'],
            'p' => departamentos::where('departamento', 'ilike', '%' . $r->txtBusqueda . '%')->paginate(),
            'txtBusqueda' => $r->txtBusqueda,
            'table'=>$this->table,
            'data' => [
                'paises'=>paises::orderBy('pais', 'ASC')->get(),
            ],
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
            'th'=>$this->th['create'],
            'table'=>$this->table,
            'data' => [
                'paises'=>paises::orderBy('pais', 'ASC')->get(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoredepartamentosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoredepartamentosRequest $request)
    {
        try{
            $data = new Departamentos;
            $data->departamento = $request->departamento;
            $data->codigo_postal = $request->codigo_postal;
            $data->codigo_mh = $request->codigo_mh;
            $data->paises_id = $request->paises_id;
            $data->save();

            return to_route($this->table.'.index')
                ->with('message','Registro guardado correctamente: '.$data->departamento)
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
     * @param  \App\Models\departamentos  $departamentoss
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\departamentos  $departamentos
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            return view($this->table . ".edit",[
                'th' => $this->th['edit'],
                'p' => departamentos::findOrFail(Crypt::decryptString($id)),
                'table'=>$this->table,
                'data' => [
                    'paises'=>paises::orderBy('pais', 'ASC')->get(),
            ],]);
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
     * @param  \App\Http\Requests\UpdatedepartamentosRequest  $request
     * @param  \App\Models\departamentos  $departamentos
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatedepartamentosRequest $request)
    {
        try{
            $p = departamentos::findOrFail($request->id);
            $p->departamento = $request->departamento;
            $p->codigo_postal = $request->codigo_postal;
            $p->codigo_mh = $request->codigo_mh;
            $p->paises_id = $request->paises_id;
        
            $p->save();

            return redirect()->route($this->table.'.index')
                ->with('message','Registro editado correctamente: '.$p->departamento)
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
                'p' => Departamentos::findOrFail(Crypt::decryptString($id))
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
     * @param  \App\Models\departamentos  $departamentos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            if (!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table . '.index')
                    ->with('message', 'Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type', 'danger');

            Departamentos::destroy(Crypt::decryptString($r->id));

            return redirect()->route($this->table . '.index')
                ->with('message', 'Registro eliminado con exito');
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

    public function apiGetDepartamentos(Request $r){
        #Utilizar unaccent para eliminar acentos en la base de datos y en el término de búsqueda
        #Ejucutar en Postgres: CREATE EXTENSION IF NOT EXISTS unaccent;
        return response()->json([
            'departamentos'=>departamentos::whereRaw('unaccent(departamento) ILIKE unaccent(?)',['%'.$r->busqueda.'%'])->get(),
        ]);
    }
}
