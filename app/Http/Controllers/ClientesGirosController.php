<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storeclientes_girosRequest;
use App\Http\Requests\Updateclientes_girosRequest;
use App\Models\clientes_giros;

#Agregar
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\giros;
use App\Models\clientes;

class ClientesGirosController extends Controller
{
    private $table = 'clientes_giros';

    public function __construct()
    {
        $this->getTh($this->table, 'Clientes giros');
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
            'p' => clientes_giros::orderBy('id', 'DESC')->paginate(15),
            'table' => $this->table,
            'data' => [
                'giros' => giros::orderBy('giro', 'ASC')->get(),
                'clientes' => clientes::orderBy('nombre', 'ASC')->get()
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
                'giros' => giros::orderBy('giro', 'ASC')->get(),
                'clientes' => clientes::orderBy('nombre', 'ASC')->get()
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storeclientes_girosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $r)
    {
        try {
            $giros = giros::where('estado', true)->get();
            foreach ($giros as $g) {
                if (isset($r['giro-' . $g->id]))
                    $this->setClientesGiro($g->id, $r->clientes_id);
            }
            return redirect()->back()
                ->with('message', 'Registro guardado correctamente.')
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al guardar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    private function setClientesGiro($giro, $cliente)
    {
        if (clientes_giros::where('giros_id', $giro)->where('clientes_id', $cliente)->count() > 0)
            return true;
        $p = new clientes_giros;
        $p->giros_id = $giro;
        $p->clientes_id = $cliente;
        return $p->save();
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\clientes_giros  $clientes_giros
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return view($this->table . '.show', [
                'th' => $this->th['show'],
                'p' => clientes_giros::findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,
                'data' => [
                    'giros' => giros::orderBy('giro', 'ASC')->get(),
                    'clientes' => clientes::orderBy('nombre', 'ASC')->get()
                ],
            ]);
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al encontrar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\clientes_giros  $clientes_giros
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            return view($this->table . '.edit', [
                'th' => $this->th['edit'],
                'p' => clientes_giros::findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,
                'data' => [
                    'giros' => giros::orderBy('giro', 'ASC')->get(),
                    'clientes' => clientes::orderBy('nombre', 'ASC')->get()
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
     * @param  \App\Http\Requests\Updateclientes_girosRequest  $request
     * @param  \App\Models\clientes_giros  $clientes_giros
     * @return \Illuminate\Http\Response
     */
    public function update(Updateclientes_girosRequest $request)
    {
        try {
            $p = clientes_giros::findOrFail($request->id);

            $p->giros_id = $request->giros_id;
            $p->clientes_id = $request->clientes_id;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro actualizado correctamente.')
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return to_route($this->table . '.index')
                ->with('message', 'Error al actualizar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function confirm($id)
    {
        try {
            return view('confirm', [
                'th' => $this->th['confirm'],
                'p' => clientes_giros::findOrFail(Crypt::decryptString($id))
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
     * @param  \App\Models\clientes_giros  $clientes_giros
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            if (!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table . '.index')
                    ->with('message', 'Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type', 'danger');

            #clientes_giros::destroy(Crypt::decryptString($r->id));
            $p = clientes_giros::findOrFail(Crypt::decryptString($r->id));
            $p->delete();

            return to_route('clientes.show', ['id' => Crypt::encryptString($p->clientes_id)])
                ->with('message', 'Registro eliminado con exito.');
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
}
