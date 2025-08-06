<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreanulacionesRequest;
use App\Http\Requests\UpdateanulacionesRequest;
use App\Models\anulaciones;

#Agregar
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class AnulacionesController extends Controller
{
    private $table = 'anulaciones';

    public $arrayAnulaciones = [
        ['codigo' => 1, 'valor' => 'Error en la información del DTE a invalidar'],
        ['codigo' => 2, 'valor' => 'Rescindir de la operación realizada'],
        ['codigo' => 3, 'valor' => 'Otro'],
    ];

    public function __construct()
    {
        $this->getTh($this->table, 'Anulaciones');
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
            'p' => anulaciones::orderBy('id', 'DESC')->paginate(15)
        ]);
    }

    public function search(Request $request)
    {
        if (isset($request->txtBusqueda)) {
            $p = anulaciones::where('anulacion', 'ilike', '%' . $request->txtBusqueda . '%')->paginate();

            return view($this->table . '.index', [
                'th' => $this->th['index'],
                'p' => $p,
                'txtBusqueda' => $request->txtBusqueda
            ]);
        } else {
            return to_route($this->table . '.index');
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
            'data' => [
                'arrayAnulaciones' => $this->arrayAnulaciones,
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreanulacionesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreanulacionesRequest $r)
    {
        try {
            $codigo    = null;
            $anulacion = null;

            switch ($r->codigo) {
                case 1: #Error en la información del DTE a invalidar
                    $codigo = 1;
                    $anulacion = 'Error en la información del DTE a invalidar';
                    break;
                case 2: #Rescindir de la operación realizada
                    $codigo = 2;
                    $anulacion = 'Rescindir de la operación realizada';
                    break;
                case 3: #Otro
                    $codigo = 3;
                    $anulacion = $r->anulacion;
                    break;
                default:
                    #Code...
                    break;
            }



            $p = new anulaciones;
            $p->anulacion = $anulacion;
            $p->codigo = $codigo;
            $p->estado = true;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro guardado correctamente: ' . $p->anulacion)
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
     * @param  \App\Models\anulaciones  $anulaciones
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\anulaciones  $anulaciones
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            return view($this->table . '.edit', [
                'th' => $this->th['edit'],
                'p' => anulaciones::findOrFail(Crypt::decryptString($id))
            ]);
        } catch (\Throwable $th) {
            return to_route($this->table . '.index')
                ->with('message', 'Error al encontar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateanulacionesRequest  $request
     * @param  \App\Models\anulaciones  $anulaciones
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateanulacionesRequest $r)
    {
        try {
            $codigo    = null;
            $anulacion = null;

            switch ($r->codigo) {
                case 1: #Error en la información del DTE a invalidar
                    $codigo = 1;
                    $anulacion = 'Error en la información del DTE a invalidar';
                    break;
                case 2: #Rescindir de la operación realizada
                    $codigo = 2;
                    $anulacion = 'Rescindir de la operación realizada';
                    break;
                case 3: #Otro
                    $codigo = 3;
                    $anulacion = $r->anulacion;
                    break;
                default:
                    #Code...
                    break;
            }

            if ($codigo == 1 || $codigo == 2) {
                $existe = anulaciones::whereIn('codigo', [1, 2])->exists();

                if ($existe) {
                    return redirect()->back()
                        ->with('message', 'Este tipo de anulación ya existe.')
                        ->with('type', 'danger');
                }
            }

            $p = anulaciones::find($r->id);
            $p->anulacion = $anulacion;
            $p->codigo = $codigo;
            $p->estado = true;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro editado correctamente: ' . $p->anulacion)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return to_route($this->table . '.index')
                ->with('message', 'Error al editar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function confirm($id)
    {
        try {
            return view('confirm', [
                'th' => $this->th['confirm'],
                'p' => anulaciones::findOrFail(Crypt::decryptString($id))
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
     * @param  \App\Models\anulaciones  $anulaciones
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            if (!isset($r->id) || empty(trim($r->id)))
                return redirect()->route($this->table . '.index')
                    ->with('message', 'Ocurrio un error, el identificador del registro no cumple los requerimientos necesarios.')
                    ->with('type', 'danger');

            #anulaciones::destroy(Crypt::decryptString($r->id));
            $p = anulaciones::findOrFail(Crypt::decryptString($r->id));
            $p->delete();

            return to_route($this->table . '.index')
                ->with('message', 'Registro eliminado con exito: ' . $p->anulacion);
        } catch (\Throwable $t) {
            return redirect()->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }

    public function status($id)
    {
        try {
            $p = anulaciones::findOrFail(Crypt::decryptString($id));
            $p->estado = !$p->estado;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Estado modificado correctamente: ' . $p->anulacion)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route($this->table . '.index')
                ->with('message', 'Error al cambiar estado: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
}
