<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalonOcupadoRequest;
use App\Models\eventos;
use App\Models\eventos_salones;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Crypt;
use Throwable;

class EventosSalonesController extends Controller
{
    public function createEventoSalones($separado, $evento, $salones)
    {
        try {
            $salonesArray = array_map('intval', explode(',', $salones));
            foreach ($salonesArray as $salonId) {
                $p = new eventos_salones();
                $p->eventos_id = $evento;
                $p->salones_id = $salonId;
                if ($separado != null)
                    $p->separado = $separado;
                $p->save();
            }
            return true;
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrió un problema al asignar salones al evento, error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function duplicarEventoSalones($evento, $salones, $mismos = null)
    {
        try {
            foreach ($salones as $salonId) {
                $p = new eventos_salones();
                $p->eventos_id = $evento;
                $p->salones_id = $salonId;
                $mismos !== null ? $mismos : false;

                $p->save();
            }
            return true;
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrió un problema al duplicar salones con evento, error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    //**esta funncion la refactorizare se hara mas optima debe incluir fecha_fin pendiente */
    public function checkDisponibilidad(SalonOcupadoRequest $request)
    {

        try {

            $fecha = $request->fecha;
            $fin = $request->fecha_fin;
            $inicio = $request->inicio;
            $finalizacion = $request->finalizacion;

            // Parsear la fecha utilizando Carbon
            $fechaP = Carbon::createFromFormat('Y-m-d', $fecha);
            $fechaFin = Carbon::createFromFormat('Y-m-d', $fin);

            $inicio .= ':00';
            $finalizacion .= ':00';

            $horaInicio = Carbon::createFromFormat('H:i:s', $inicio);
            $horaFinalizacion = Carbon::createFromFormat('H:i:s', $finalizacion);
            $horaFinalizacionMas60 = $horaFinalizacion->copy()->addMinutes(60);
            /* $salonesOcupados = eventos_salones::leftJoin('eventos', 'eventos_salones.eventos_id', '=', 'eventos.id')
            ->leftJoin('salones', 'eventos_salones.salones_id', '=', 'salones.id')
            ->whereBetween('eventos.fecha',[$fechaP,$fechaFin])
            ->orWhereBetween('eventos.fecha_fin',[$fechaFin,$fechaP])
            ->whereDate('eventos.fecha','<=',$fechaFin)
                ->whereDate('eventos.fecha_fin', '>=', $fechaP)
            ->whereDate('eventos.fecha', '=', $fechaP->toDateString())
                ->whereDate('eventos.fecha_fin', '=', $fechaFin->toDateString())
                ->where(function ($query) use ($horaInicio, $horaFinalizacion, $horaFinalizacionMas60) {
                    $query->where(function ($query) use ($horaInicio) {
                        // Caso 1: El evento actual comienza durante otro evento
                        $query->whereTime('eventos.inicio', '<=', $horaInicio)
                            ->whereTime('eventos.finalizacion', '>', $horaInicio);
                    })
                        ->orWhere(function ($query) use ($horaInicio, $horaFinalizacionMas60) {
                            // Caso 2: El evento actual comienza y termina durante el período de otro evento
                            $query->whereTime('eventos.inicio', '>=', $horaInicio)
                                ->whereTime('eventos.inicio', '<', $horaFinalizacionMas60);
                        })
                        ->orWhere(function ($query) use ($horaInicio, $horaFinalizacionMas60) {
                            // Caso 3: El evento actual termina durante otro evento
                            $query->whereTime('eventos.finalizacion', '>', $horaInicio)
                                ->whereTime('eventos.finalizacion', '<=', $horaFinalizacionMas60);
                        })
                        ->orWhere(function ($query) use ($horaFinalizacion,$horaFinalizacionMas60) {
                            // Caso 4: El evento actual se superpone con el período de limpieza de otro evento
                            $query->whereTime('eventos.inicio', '>=', $horaFinalizacion)
                                ->whereTime('eventos.inicio', '<=', $horaFinalizacionMas60);
                        });
                })
            ->select( 'salones.id')
            ->distinct()
            ->get();
*/

            // Devolver la lista de salones ocupados al frontend
            return response()->json([
                'salones' => [] //$salonesOcupados,
            ]);
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrió un problema al actualizar los salones, error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    public function editSalonesEvento(Request $r)
    {
        try {
            if (isset($r->salones) && is_array($r->salones)) {
                foreach ($r->salones as $salon_id) {
                    $es = new eventos_salones();
                    $es->salones_id = $salon_id;
                    $es->eventos_id = Crypt::decryptString($r->eventos_id);
                    $es->save();
                }
            }

            return redirect()
                ->back()
                ->with('message', 'Se actualizaron los salones del evento #: ' . Crypt::decryptString($r->eventos_id))
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrió un problema al actualizar los salones, error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
    //**funcion para eliminar salones de un evento cuando se actualiza la fecha y hora de inicio y de finalizacion  */
    public function eliminarSalones($evento_salon)
    {
        eventos_salones::where('eventos_id', $evento_salon)->delete();
    }
    public function salonesEvento(Request $r)
    {
        try {
            $evento_id = Crypt::decryptString($r->eventos_id);
            $e = eventos::find($evento_id);
            $salonesEvento =
                eventos_salones::leftJoin('eventos', 'eventos.id', '=', 'eventos_salones.eventos_id')
                ->leftJoin('salones', 'eventos_salones.salones_id', '=', 'salones.id')
                ->where('eventos.id', $e->id)
                ->whereDate('eventos.fecha', $e->fecha)
                ->whereDate('eventos.fecha_fin', $e->fecha_fin)
                ->whereTime('eventos.inicio', '=', $e->inicio)
                ->whereTime('eventos.finalizacion', '=', $e->finalizacion)
                ->select('salones.id', 'salones.salon')
                ->distinct()
                ->get();

            return response()->json([
                'salonesEvento' => $salonesEvento,
            ]);
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'error' => 'Ocurrió un error al obtener los salones del evento.',
                ],
                500,
            );
        }
    }

    public function eliminarSalon($salon_id)
    {
        try {
            eventos_salones::where('salones_id', Crypt::decryptString($salon_id))->delete();
            return redirect()->back()->with('message', 'Se elimino el salon que estaba asociado al evento: ')->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Ocurrió un problema al eliminar el salon, error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }
}
