<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storehabitaciones_estadosRequest;
use App\Http\Requests\Updatehabitaciones_estadosRequest;
use App\Models\estado_habitaciones;
use App\Models\habitaciones;
use App\Models\habitaciones_estados;
use App\Models\solicitantes;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Telegram\Bot\Laravel\Facades\Telegram;

class HabitacionesEstadosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('habitaciones.solicitudes', ["data" => habitaciones_estados::where("completado", false)->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('habitaciones.cambio_estado', ['habitaciones' => habitaciones::orderBy('numero_habitacion')->get(), 'estados' => estado_habitaciones::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storehabitaciones_estadosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storehabitaciones_estadosRequest $r)
    {
        try {
            $confirmationCode = rand(100000, 999999);
            $h = habitaciones::find($r->habitaciones_id);
            $e = estado_habitaciones::find($r->estado_habitaciones_id);
            $p = new habitaciones_estados;
            $p->estado_habitaciones_id = $e->id;
            $p->habitaciones_id = $h->id;
            $p->justificacion = $r->justificacion;
            $p->codigo = $confirmationCode;
            $p->users_id = Auth::user()->id;
            $p->save();

            $text = "<b>" . strtoupper($p->usuario->name) . "</b> solicita cambiar el estado de la habitación <b>" .
                $p->habitacion->numero_habitacion . "</b> a <b>" . strtoupper($p->estado->estado_habitacion) .
                "</b> \n Justificación: <i>" . strtoupper($p->justificacion) .
                "</i> \nPor favor confirme que el estado es correcto, de lo contrario no comparta el código de confirmación, hasta que sea correcto." .
                "\nCódigo de confirmación para cambio de estado: \n<span class='tg-spoiler'>" . $p->codigo . "</span>";

            $chat = $h->sucursales_id == 1 ? env('tgtropico') : env('tgtropiclub');
            if (!empty($chat))
                Telegram::sendMessage([
                    'parse_mode' => 'HTML',
                    'chat_id' => $chat,
                    'text' => $text,
                ]);

            return redirect()->route("habitaciones.cambio_estado")->with("message", "Se agrego la solicitud, debe consultar el chat de mantenimientos de ama de llaves de la sucursal para tener el codigo de confirmación del estado");
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route("habitaciones.cambio_estado")->with("message", "Error: " . $th->getMessage())->with('type', 'danger');
        }
    }

    public function reenviar(Request $r)
    {
        try {
            $p = habitaciones_estados::findOrFail(Crypt::decryptString($r->id));
            $text = "<i>Reenvió de codigo</i> \n<b>" . strtoupper($p->usuario->name) . "</b> solicita cambiar el estado de la habitación <b>" .
                $p->habitacion->numero_habitacion . "</b> a <b>" . strtoupper($p->estado->estado_habitacion) .
                "</b> \n Justificación: <i>" . $p->justificacion .
                "</i> \nPor favor confirme que el estado es correcto, de lo contrario no comparta el código de confirmación, hasta que sea correcto." .
                "\nCódigo de confirmación para cambio de estado: \n<span class='tg-spoiler'>" . $p->codigo . "</span>";

            $chat = $p->habitacion->sucursales_id == 1 ? env('tgtropico') : env('tgtropiclub');
            if (!empty($chat))
                Telegram::sendMessage([
                    'parse_mode' => 'HTML',
                    'chat_id' => $chat,
                    'text' => $text,
                ]);

            return redirect()->back()->with("message", "Se reenvió el codigo");
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function confirmacion(Request $r)
    {
        try {

            if (strlen(trim($r->codigo)) !== 6)
                throw new Exception("El codigo debe contener 6 digitos (0 - 9). Intente de nuevo con el codigo correcto.");

            $p = habitaciones_estados::findOrFail(Crypt::decryptString($r->id));
            $codigo = intval(trim($r->codigo));
            if (!$p->created_at->isToday()) {
                $p->completado = true;
                $p->save();
                throw new Exception("La solicitud ya excedió el tiempo, debe crear una nueva solicitud.");
            }

            if ($p->codigo === $codigo) {
                $h = habitaciones::find($p->habitaciones_id);
                $h->estado_habitaciones_id = $p->estado_habitaciones_id;
                $h->save();

                $p->completado = true;
                $p->save();
            } else
                throw new Exception("No coincide el codigo de confirmación");

            return redirect()->back()->with('message', 'Se realizo el cambio de estado en la habitación ' . $h->numero_habitacion);
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Error: ' . $th->getMessage())->with('type', 'danger');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\habitaciones_estados  $habitaciones_estados
     * @return \Illuminate\Http\Response
     */
    public function show(habitaciones_estados $habitaciones_estados)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\habitaciones_estados  $habitaciones_estados
     * @return \Illuminate\Http\Response
     */
    public function edit(habitaciones_estados $habitaciones_estados)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatehabitaciones_estadosRequest  $request
     * @param  \App\Models\habitaciones_estados  $habitaciones_estados
     * @return \Illuminate\Http\Response
     */
    public function update(Updatehabitaciones_estadosRequest $request, habitaciones_estados $habitaciones_estados)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\habitaciones_estados  $habitaciones_estados
     * @return \Illuminate\Http\Response
     */
    public function destroy(habitaciones_estados $habitaciones_estados)
    {
        //
    }
}
