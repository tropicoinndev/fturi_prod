<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storeactividades_economicasRequest;
use App\Http\Requests\Updateactividades_economicasRequest;
use App\Models\actividades_economicas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ActividadesEconomicasController extends Controller
{
    private $table = 'actividades_economicas';
    public function __construct()
    {
        $this->getTh($this->table, 'Actividades económicas');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->table . '.index', [
            'th' => $this->th['index'],
            'p' => actividades_economicas::orderBy('id', 'DESC')->paginate(15),

        ]);
    }
    //funcion  para buscar actividades
    public function search(Request $r){
        if(isset($r->txtBusqueda)){
            $p = actividades_economicas::where('actividad','ilike','%'.$r->txtBusqueda.'%')
                ->orWhere('codigo','ilike','%'.$r->txtBusqueda.'%')->paginate();

            return view($this->table.'.index', [
                'th'=>$this->th['index'],
                'p' =>$p,
                'txtBusqueda'=>$r->txtBusqueda,
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
        return view($this->table . '.create', [
            'th' => $this->th['create'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storeactividades_economicasRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storeactividades_economicasRequest $request)
    {
        try {

            $ae= new actividades_economicas();
            $ae->codigo = $request->codigo;
            $ae->actividad = $request->actividad;
            $ae->save();


            return to_route($this->table . '.index')
                ->with('message', 'Registro guardado correctamente: '.$ae->actividad)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\actividades_economicas  $actividades_economicas
     * @return \Illuminate\Http\Response
     */
    public function show(actividades_economicas $actividades_economicas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\actividades_economicas  $actividades_economicas
     * @return \Illuminate\Http\Response
     */
    public function edit( $id)
    {
        try {
            return view($this->table . '.edit', [
                'th' => $this->th['edit'],
                'p' => actividades_economicas::findOrFail(Crypt::decryptString($id)),
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Error al encontrar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updateactividades_economicasRequest  $request
     * @param  \App\Models\actividades_economicas  $actividades_economicas
     * @return \Illuminate\Http\Response
     */
    public function update(Updateactividades_economicasRequest $request)
    {
        try {
            $p = actividades_economicas::findOrFail($request->id);

            $p->codigo = $request->codigo;
            $p->actividad = $request->actividad;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro guardado correctamente: ' . $p->actividad)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function confirm($id)
    {
        try {
            return view('confirm', [
                'th' => $this->th['confirm'],
                'p' => actividades_economicas::findOrFail(Crypt::decryptString($id)),
            ]);
        } catch (\Throwable $t) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\actividades_economicas  $actividades_economicas
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            if (!isset($r->id) || empty(trim($r->id))) {
                return redirect()
                    ->route($this->table . '.index')
                    ->with('message', 'Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type', 'danger');
            }

            actividades_economicas::destroy(Crypt::decryptString($r->id));

            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Registro eliminado con exito');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrio un error (' . $th->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
}
