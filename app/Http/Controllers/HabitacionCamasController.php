<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storehabitacion_camasRequest;
use App\Http\Requests\Updatehabitacion_camasRequest;
use App\Models\habitacion_camas;
use App\Models\habitaciones;
use App\Models\tipo_camas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class HabitacionCamasController extends Controller
{
    private $table = 'habitacion_camas';

    public function __construct()
    {
        $this->getTh($this->table, 'Habitaciones Camas');
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
            'p' => habitacion_camas::orderBy('id', 'DESC')->paginate(15),
            'table' => $this->table,
            'data' => [
                'habitaciones' => habitaciones::orderBy('id', 'ASC')->get(),
                'tipo_camas' => tipo_camas::orderBy('id', 'ASC')->get(),
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
            'p' => habitacion_camas::orderBy('id', 'DESC')->paginate(15),
            'table' => $this->table,
            'data' => [
                'habitaciones' => habitaciones::orderBy('id', 'ASC')->get(),
                'tipo_camas' => tipo_camas::orderBy('id', 'ASC')->get(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storehabitacion_camasRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storehabitacion_camasRequest $request)
    {
        try {
            $p = new habitacion_camas();
            $p->habitaciones_id = $request->habitaciones_id;
            $p->tipo_camas_id = $request->tipo_camas_id;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro guardado correctamente.')
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
     * @param  \App\Models\habitacion_camas  $habitacion_camas
     * @return \Illuminate\Http\Response
     */
    public function show(habitacion_camas $habitacion_camas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\habitacion_camas  $habitacion_camas
     * @return \Illuminate\Http\Response
     */
    public function edit(habitacion_camas $habitacion_camas)
    {
        try {
            return view($this->table . '.edit', [
                'th' => $this->th['edit'],
                'p' => habitacion_camas::findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,
                'data' => [
                    'habitaciones' => habitaciones::orderBy('id', 'ASC')->get(),
                    'tipo_camas' => tipo_camas::orderBy('id', 'ASC')->get(),
                ],
            ]);
        } catch (\Throwable $th) {
            return to_route($this->table . '.index')
                ->with('message', 'Error al encontrar el registro: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatehabitacion_camasRequest  $request
     * @param  \App\Models\habitacion_camas  $habitacion_camas
     * @return \Illuminate\Http\Response
     */
    public function update(Updatehabitacion_camasRequest $request)
    {
        try {
            $p = habitacion_camas::findOrFail($request->id);
            $p->habitaciones_id = $request->habitaciones_id;
            $p->tipo_camas_id = $request->tipo_camas_id;
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
                'p' => habitacion_camas::findOrFail(Crypt::decryptString($id)),
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
     * @param  \App\Models\habitacion_camas  $habitacion_camas
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
            $p = habitacion_camas::findOrFail(Crypt::decryptString($r->id));
            $p->delete();

            return to_route($this->table . '.index')->with('message', 'Registro eliminado con exito.');
        } catch (\Throwable $t) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
}
