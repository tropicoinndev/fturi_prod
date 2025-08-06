<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\Storetarifa_detallesRequest;
use App\Http\Requests\Updatetarifa_detallesRequest;
use App\Models\tarifa_detalles;
use App\Models\tarifas;
use App\Models\tipo_habitaciones;
use App\Models\forma_habitaciones;

class TarifaDetallesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function getHabitacionesByTarifa(Request $r)
    {
        return response()->json(
            [
                'modelo'=>tarifa_detalles::with('habitaciones')->where('tarifas_id', $r->id)->get(),
                'leftJoin'=> tarifa_detalles::getHabitaciones()->where('tarifas_id', $r->id)->get(),
            ]);

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    /**
 * Validar si los tres parámetros están definidos.
 *
 * @param int $tarifa
 * @param int $tipo_habitaciones_id
 * @param int $forma_habitaciones_id
 * @return bool true
 */
    /**funcion parav validar los parametros */
    private function validarTiposFormas($tarifa, $tipo_habitaciones_id, $forma_habitaciones_id)
    {
        return isset($tarifa) && isset($tipo_habitaciones_id) && isset($forma_habitaciones_id);//aqui valido si existen los parametros
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storetarifa_detallesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $r)
    {
        try {
            $tiposFormas = $r->all();

            foreach ($tiposFormas as $tf) {
                $tarifa = Crypt::decryptString($tf['tarifa']);
                $tipo_habitaciones_id = $tf['tipo_habitaciones_id'];
                $forma_habitaciones_id = $tf['forma_habitaciones_id'];

                if ($this->validarTiposFormas($tarifa, $tipo_habitaciones_id, $forma_habitaciones_id)) {
                    // Verifico si ya existe un registro con los mismos valores
                    $existeDetalle = tarifa_detalles::where('tarifas_id', $tarifa)
                        ->where('tipo_habitaciones_id', $tipo_habitaciones_id)
                        ->where('forma_habitaciones_id', $forma_habitaciones_id)
                        ->first();

                    if (!$existeDetalle) {
                        // Crear un nuevo registro de TarifaDetalle con los datos recibidos
                        $tarifaDetalle = new tarifa_detalles();
                        $tarifaDetalle->tarifas_id = $tarifa;
                        $tarifaDetalle->tipo_habitaciones_id = $tipo_habitaciones_id;
                        $tarifaDetalle->forma_habitaciones_id = $forma_habitaciones_id;
                        $tarifaDetalle->save();
                        $message = 'Tipo y forma de habitación agregados a esta tarifa';
                    } else {
                        $message = 'Registro duplicado, no se puede agregar registros duplicados';
                    }
                } else {
                    $message = 'Datos incompletos, no se agregó el registro';
                }
            }

            return response()->json([
                'message' => $message,
                'type' => 'success',
                'tarifaDetalles' => $this->getDetalleTarifas($tarifa),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al guardar el registro: ' . $th->getMessage(),
                'type' => 'danger',
                'tarifaDetalles' => [],
            ]);
        }
    }

    private function getDetalleTarifas($tarifa_id)
    {
        return tarifa_detalles::with('tipo_habitaciones')
            ->with('forma_habitaciones')
            ->where('tarifas_id', '=', $tarifa_id)
            ->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\tarifa_detalles  $tarifa_detalles
     * @return \Illuminate\Http\Response
     */
    public function show(tarifa_detalles $tarifa_detalles)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\tarifa_detalles  $tarifa_detalles
     * @return \Illuminate\Http\Response
     */
    public function edit(tarifa_detalles $tarifa_detalles)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatetarifa_detallesRequest  $request
     * @param  \App\Models\tarifa_detalles  $tarifa_detalles
     * @return \Illuminate\Http\Response
     */
    public function update(Updatetarifa_detallesRequest $request, tarifa_detalles $tarifa_detalles)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\tarifa_detalles  $tarifa_detalles
     * @return \Illuminate\Http\Response
     */
    public function destroy(tarifa_detalles $tarifa_detalles)
    {
        //
    }
    public function destroy_api(Request $r)
    {
        $m = 'Se eliminaron los tipos y formas de habitacion a esta tarifa';
        $t = true;
        $tarifaDetalles = [];
        try {
            $tarifa = tarifa_detalles::find($r->id)->tarifas_id;
            tarifa_detalles::destroy($r->id);
            $tarifaDetalles = $this->getDetalleTarifas($tarifa);
        } catch (\Throwable $th) {
            $t = false;
            $m = 'Error: ' . $th->getMessage();
        }
        return response()->json([
            'message' => $m,
            'type' => $t ? 'success' : 'danger',
            'tarifaDetalles' => $tarifaDetalles,
        ]);
    }
}
