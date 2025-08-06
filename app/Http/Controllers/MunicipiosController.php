<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoremunicipiosRequest;
use App\Http\Requests\UpdatemunicipiosRequest;

use App\Models\departamentos;
use App\Models\municipios;
use App\Models\paises;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class MunicipiosController extends Controller
{

    private $table = 'municipios';

    public function __construct()
    {
        $this->getTh($this->table, 'Municipios');
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
            'p' => municipios::orderBy('id', 'DESC')->paginate(20),
            'table' => $this->table,
            'data' => [
                'departamentos' => departamentos::orderBy('departamento', 'ASC')->get(),

            ],
        ]);
    }
    public function search(Request $r)
    {
        return view($this->table . '.index', [
            'th' => $this->th['index'],
            'p' => municipios::where('municipio', 'ilike', '%' . $r->txtBusqueda . '%')->paginate(),
            'txtBusqueda' => $r->txtBusqueda,
            'table' => $this->table,
            'data' => [
                'departamentos' => departamentos::orderBy('departamento', 'ASC')->get(),

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
        return view($this->table . '.create', [
            'th' => $this->th['create'],
            'table' => $this->table,
            'data' => [
                'departamentos' => departamentos::orderBy('departamento', 'ASC')->get(),

            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoremunicipiosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoremunicipiosRequest $request)
    {

        try {
            $data = new Municipios;
            $data->municipio = $request->municipio;
            $data->codigo_postal = $request->codigo_postal;
            $data->codigo_mh = $request->codigo_mh;
            $data->departamentos_id = $request->departamentos_id;
            $data->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro guardado correctamente: ' . $data->municipio)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\municipios  $municipios
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return view($this->table . '.show', [
                'th' => $this->th['show'],
                'p' => municipios::findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,
                'data' => [
                    'departamentos' => departamentos::orderBy('departamento', 'ASC')->get(),

                ],
            ]);
        } catch (\Throwable $th) {
            return to_route($this->table . '.index')
                ->with('message', 'Error al encontrar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\municipios  $municipios
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {

            return view($this->table . ".edit", [

                'th' => $this->th['edit'],
                'p' => municipios::findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,
                'data' => [
                    'departamentos' => departamentos::orderBy('departamento', 'ASC')->get(),

                ],
            ]);
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al encontrar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatemunicipiosRequest  $request
     * @param  \App\Models\municipios  $municipios
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatemunicipiosRequest $request)
    {
        try {
            $p = municipios::findOrFail($request->id);
            $p->municipio = $request->municipio;
            $p->codigo_postal = $request->codigo_postal;
            $p->codigo_mh = $request->codigo_mh;
            $p->departamentos_id = $request->departamentos_id;

            $p->save();

            return redirect()->route($this->table . '.index')
                ->with('message', 'Registro editado correctamente: ' . $p->municipio)
                ->with('type', 'info');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al editar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\municipios  $municipios
     * @return \Illuminate\Http\Response
     */
    public function confirm($id)
    {
        try {
            return view("confirm", [
                'th' => $this->th['confirm'],
                'p' => Municipios::findOrFail(Crypt::decryptString($id))
            ]);
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

    public function destroy(Request $r)
    {
        try {
            if (!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table . '.index')
                    ->with('message', 'Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type', 'danger');

            Municipios::destroy(Crypt::decryptString($r->id));

            return redirect()->route($this->table . '.index')
                ->with('message', 'Registro eliminado con exito');
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

    public function getByCiudad(Request $r)
    {
        try {
            $b = $r->txtBq;
            $ciudades = DB::table('getciudad')
                ->orWhere('municipio', 'like', '%' . $b . '%')
                ->orWhere('departamento', 'like', '%' . $b . '%')
                ->orWhere('codigo_postal', 'like', '%' . $b . '%')
                ->orWhere('ciudad', 'like', '%' . $b . '%')
                ->orWhere('pais', 'like', '%' . $b . '%')
                ->get();

            return response()->json(['list' => $ciudades]);
        } catch (\Throwable $th) {
            return response()->json(['list' => $th->getMessage()]);
        }
    }
    public function getByPais(Request $r)
    {
        try {
            $b = $r->txtBq;
            $paises = paises::Where('pais', 'ilike', '%' . $b . '%')
                ->get();

            return response()->json(['list' => $paises]);
        } catch (\Throwable $th) {
            return response()->json(['list' => $th->getMessage()]);
        }
    }
}
