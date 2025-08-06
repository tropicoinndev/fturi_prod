<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoretarifasRequest;
use App\Http\Requests\UpdatetarifasRequest;
use App\Models\tarifas;
use App\Models\temporadas;
use App\Models\habitaciones;
use App\Models\tarifa_detalles;
use App\Models\forma_habitaciones;
use App\Models\tipo_habitaciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class TarifasController extends Controller
{
    private $table = 'tarifas';

    public function __construct()
    {
        $this->getTh($this->table, 'Tarifas');
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
            'p' => tarifas::orderBy('id', 'DESC')->paginate(15),
            'data' => [
                'temporadas' => temporadas::orderBy('temporada', 'ASC')->get(),
            ],
        ]);
    }

    public function apiGetTarifas()
    {
        return response([
            'tarifas' => tarifas::with('temporadas')
                ->orderBy('tarifa', 'ASC')
                ->get(),
        ]);
    }

    public function search(Request $r)
    {
        return view($this->table . '.index', [
            'th' => $this->th['index'],
            'p' => tarifas::where('tarifa', 'ilike', '%' . $r->txtBusqueda . '%')->paginate(),
            'txtBusqueda' => $r->txtBusqueda,
            'table' => $this->table,
            'data' => [
                'temporadas' => temporadas::orderBy('temporada', 'ASC')->get(),
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
                'temporadas' => temporadas::orderBy('temporada', 'ASC')->get(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoretarifasRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoretarifasRequest $request)
    {
        try {
            $data = new tarifas();
            $data->tarifa = $request->tarifa;
            $data->precio = $request->precio;
            $data->numero_dias = $request->numero_dias;
            $data->temporadas_id = $request->temporadas_id;
            $data->estado = true;
            $data->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro guardado correctamente: ' . $data->tarifa)
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
     * @param  \App\Models\tarifas  $tarifas
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tarifa = tarifas::findOrFail(Crypt::decryptString($id));
        $tarifaDetalles = tarifa_detalles::where('tarifas_id', '=', $tarifa->id)
            ->get();
        return view($this->table . '.show', [
            'th' => ($this->th['show'] = [
                'title' => $tarifa->tarifa,
                'table' => $this->table,
                'bread' => $this->table . '.show',
            ]),
            'p' => $tarifa,
            'table' => $this->table,
            'habitaciones' => habitaciones::with('relacionTipoHabitaciones', 'relacionFormaHabitaciones')
                ->orderBy('numero_habitacion', 'ASC')
                ->whereIn('tipo_habitaciones_id', $tarifaDetalles->pluck('tipo_habitaciones.id'))
                ->whereIn('forma_habitaciones_id', $tarifaDetalles->pluck('forma_habitaciones.id'))
                ->get(),
            'tarifas' => tarifas::where('id', $tarifa->id)->get(),

        ]);
    }
    /**detalle tarifas */
    public function detalle(Request $r)
    {
        $tarifa = tarifas::findOrFail(Crypt::decryptString($r->id));
        $tarifaDetalles = tarifa_detalles::where('tarifas_id', '=', $tarifa->id)->get();

        return view($this->table . '.detalle', [
            'th' => ($this->th['detalle'] = [
                'title' => $tarifa->tarifa,
                'table' => $this->table,
                'bread' => $this->table . '.detalle',
            ]),
            'p' => $tarifa,
            'table' => $this->table,
            'tipoHabitaciones' => tipo_habitaciones::orderBy('tipo_habitacion', 'ASC')->get(),
            'formaHabitaciones' => forma_habitaciones::orderBy('forma_habitacion', 'ASC')->get(),
            'tarifaDetalles' => tarifa_detalles::with('forma_habitaciones', 'tipo_habitaciones')
                ->where('tarifas_id', '=', $tarifa->id)
                ->get(),
            'tarifas' => tarifas::all(),
            'habitaciones' => habitaciones::with('relacionTipoHabitaciones', 'relacionFormaHabitaciones')
                ->orderBy('numero_habitacion', 'ASC')
                ->whereIn('tipo_habitaciones_id', $tarifaDetalles->pluck('tipo_habitaciones.id'))
                ->whereIn('forma_habitaciones_id', $tarifaDetalles->pluck('forma_habitaciones.id'))
                ->get(),
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\tarifas  $tarifas
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            return view($this->table . '.edit', [
                'th' => $this->th['edit'],
                'p' => tarifas::with('temporadas')->findOrFail(Crypt::decryptString($id)),
                'table' => $this->table,
                'data' => [
                    'temporadas' => temporadas::orderBy('temporada', 'ASC')->get(),
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
     * @param  \App\Http\Requests\UpdatetarifasRequest  $request
     * @param  \App\Models\tarifas  $tarifas
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatetarifasRequest $request)
    {
        try {
            $p = tarifas::findOrFail($request->id);
            $p->tarifa = $request->tarifa;
            $p->precio = $request->precio;
            $p->numero_dias = $request->numero_dias;
            $p->temporadas_id = $request->temporadas_id;
            $p->save();

            return to_route($this->table . '.index')
                ->with('message', 'Registro guardado correctamente: ' . $p->tarifa)
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
                'p' => tarifas::findOrFail(Crypt::decryptString($id)),
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
     * @param  \App\Models\tarifas  $tarifas
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

            $p = tarifas::findOrFail(Crypt::decryptString($r->id));
            $p->delete();

            return to_route($this->table . '.index')->with('message', 'Registro eliminado con exito: ' . $p->tarifa);
        } catch (\Throwable $t) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrio un error (' . $t->getMessage() . ')')
                ->with('type', 'danger');
        }
    }
    public function statusTarifas($id)
    {
        try {
            $p = tarifas::findOrFail(Crypt::decryptString($id));
            $p->estado = !$p->estado;
            $p->save();

            return redirect()
                ->back()
                ->with('message', 'Se ha modificado el estado ' . $p->tarifa)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Error al cambiar estado: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function statusPaquete($id)
    {
        try {
            $p = tarifas::findOrFail(Crypt::decryptString($id));
            $p->paquete = !$p->paquete;
            $p->save();

            return redirect()
                ->back()
                ->with('message', 'Se ha modificado  ' . $p->tarifa)
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->route($this->table . '.index')
                ->with('message', 'Error al cambiar estado: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
}
