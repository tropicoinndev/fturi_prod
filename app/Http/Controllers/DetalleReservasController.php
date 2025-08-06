<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storedetalle_reservasRequest;
use App\Http\Requests\Updatedetalle_reservasRequest;
use App\Mail\changeMail;
use App\Models\detalle_reservas;
use App\Models\forma_habitaciones;
use App\Models\forma_pagos;
use App\Models\reservaciones;
use App\Models\habitaciones;
use App\Models\recepciones;
use App\Models\sucursales;
use App\Models\tarifas;
use App\Models\tipo_habitaciones;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

class DetalleReservasController extends Controller
{
    private $table = 'detalle_reservas';

    protected $habController;

    #Se inyecta la dependencia del controlador externo en el constructor de esta clase.
    public function __construct(HabitacionesController $habitController)
    {
        $this->getTh($this->table, 'Detalle reservas');

        $this->habController = $habitController;
    }

    /**
     * Display a listing of the resource.
     *reservaciones
     * @return \Illuminate\Http\Response
     */
    public function index(Request $r)
    {
        $fechaIngreso  = $r->fecha2Ingreso;
        $fechaSalida   = $r->fecha2Salida;
        $id            = Crypt::decryptString($r->id);
        $reservaciones = reservaciones::with('anticipos')->findOrFail($id);

        $habitaciones = (!empty($fechaIngreso) && !empty($fechaSalida)) ? $this->habController->getDataHabitacionesDisponibles($fechaIngreso, $fechaSalida) : [];

        return view($this->table . '.index', [
            'table' => $this->table,
            'th'   => $this->th['index'],
            'p'    => $reservaciones,
            'fechaIngreso'   => $fechaIngreso,
            'fechaSalida'    => $fechaSalida,
            'habitaciones'   => $habitaciones,
            'forma_pagos'    => forma_pagos::whereNotIn('token', [6002, 6004])->get(),
            'tipo_habitaciones' => tipo_habitaciones::all(),
            'forma_habitaciones' => forma_habitaciones::all(),
            'detalleReservas' => detalle_reservas::with(['relacionHabitaciones', 'relacionTarifas', 'relacionUsuarios', 'huespedes'])
                ->where('reservaciones_id', $id)
                ->get(),
            'sucursales'    => sucursales::all(),
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
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storedetalle_reservasRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storedetalle_reservasRequest $r)
    {
        try {
            $valid = (new HabitacionesController)->isValidHabitacion($r->fecha_ingreso, $r->fecha_salida, $r->habitaciones_id);
            if (!$valid)
                return redirect()->back()->with('message', 'La reservación ya fue agregada.')
                    ->with('type', 'success');
            $reservacion = reservaciones::findOrFail($r->reservaciones_id);
            if ($reservacion->completa)
                return throw new Exception('No se pueden agregar mas reservaciones porque la reservacion esta completa.');

            $p                    = new detalle_reservas;
            $p->reservaciones_id  = $reservacion->id;
            $p->habitaciones_id   = $r->habitaciones_id;
            $p->fecha_ingreso     = $r->fecha_ingreso;
            $p->fecha_salida      = $r->fecha_salida;
            $p->tarifas_id        = $r->tarifas_id;
            $p->users_id          = Auth::id();
            $p->cantidad_personas = $r->cantidad_personas;
            $p->descripcion       = $r->descripcion;
            $p->save();

            //agregar huespedes
            if (isset($r->huespedes) && count($r->huespedes) > 0)
                foreach ($r->huespedes as $h) (new HuespedReservasController)->save($h, $p->id);


            return redirect()->route('detalle_reservas.index', [
                'id' => Crypt::encryptString($p->reservaciones_id),
                'fecha2Ingreso' => $r->fecha2Ingreso,
                'fecha2Salida' => $r->fecha2Salida,
            ])
                ->with('message', 'Reservacion guardada correctamente.')
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Error al guardar la reservacion: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\detalle_reservas  $detalle_reservas
     * @return \Illuminate\Http\Response
     */
    public function show(detalle_reservas $detalle_reservas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\detalle_reservas  $detalle_reservas
     * @return \Illuminate\Http\Response
     */
    public function edit(detalle_reservas $detalle_reservas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatedetalle_reservasRequest  $request
     * @param  \App\Models\detalle_reservas  $detalle_reservas
     * @return \Illuminate\Http\Response
     */
    public function update(Updatedetalle_reservasRequest $request, detalle_reservas $detalle_reservas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\detalle_reservas  $detalle_reservas
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r)
    {
        try {
            #Devolver el estado de la habitacion a disponible. 6 = Vacia limpia
            #habitaciones::where('numero_habitacion',$r->num_hab)->update(['estado_habitaciones_id'=>6]);

            #Eliminar reserva.
            $dr = detalle_reservas::find($r->idEliminarDetReserva);
            $recepciones = recepciones::where('detalle_reservas_id', $dr->id)->get();
            if (count($recepciones) > 0)
                recepciones::where('id', $recepciones->pluck('id'))->update(['detalle_reservas_id' => null]);


            $reservacion = reservaciones::find($dr->reservaciones_id);
            if ($reservacion->completa)
                return throw new Exception('Esta reservacion ya esta completa');

            $dr->delete();

            return redirect()
                ->back()
                ->with('message', 'Reserva eliminada correctamente.')
                ->with('type', 'success');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('message', 'Error al eliminar la reservacion: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function editarTarifa(Request $r)
    {
        try {
            $p = detalle_reservas::find(Crypt::decryptString($r->id));
            if ($p->ingreso) {
                $ms = "El usuario " . Auth::user()->name . ' esta intentando cambiar la tarifa de la reservacion Nº ' . $p->reservaciones_id . '. Esta reservacion ya ingreso, no es permitido realizar cambios posterior al ingreso. (Esto puede generar manipulación del reporte de ventas de habitaciones)';
                $mail = env('MAIL_NOTIFICACION', 'change@tropicoinn.com.sv');
                if (strlen($mail) > 5 && strlen($ms) > 5) {
                    Mail::to(trim($mail))->queue(new changeMail($ms));
                }
                throw new Exception('No se permite cambiar la tarifa, porque esta reservacion ya tiene ingreso.');
            }
            if (!$p->estado)
                throw new Exception('Esta reservacion esta anulada');


            $h = habitaciones::find($p->habitaciones_id);
            $tarifas = (new RecepcionesController)->getTarifasHabitacionTipo($h->tipo_habitaciones_id);
            return view('detalle_reservas.editar_tarifa', ['p' => $p, 'habitacion' => $h, 'tarifas' => $tarifas]);
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()
                ->with('message', 'Error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function updateTarifa(Request $r)
    {
        $r->validate([
            'id' => ['required', 'string'],
            'tarifas_id' => ['required', 'string'],
            'confirm' => ['required', 'accepted'],

        ]);
        try {
            $p = detalle_reservas::find(Crypt::decryptString($r->id));
            if ($p->ingreso) {
                $ms = "El usuario " . Auth::user()->name . ' esta intentando cambiar la tarifa de la reservacion Nº ' . $p->reservaciones_id . '. Esta reservacion ya ingreso, no es permitido realizar cambios posterior al ingreso. (Esto puede generar manipulación del reporte de ventas de habitaciones)';
                $mail = env('MAIL_NOTIFICACION', 'change@tropicoinn.com.sv');
                if (strlen($mail) > 5 && strlen($ms) > 5) {
                    Mail::to(trim($mail))->queue(new changeMail($ms));
                }
                throw new Exception('No se permite cambiar la tarifa, porque esta reservacion ya tiene ingreso.');
            }

            if (!$p->estado)
                throw new Exception('Esta reservacion esta anulada');

            $p->tarifas_id = Crypt::decryptString($r->tarifas_id);
            $p->save();
            return redirect()->route('detalle_reservas.index', ['id' => Crypt::encryptString($p->reservaciones_id)])
                ->with('message', 'Cambio de tarifa realizado.');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()
                ->with('message', 'Error: ' . $th->getMessage())
                ->with('type', 'danger');
        }
    }

    public function editarDescripcion(Request $r){
        $p = detalle_reservas::find(Crypt::decryptString($r->id));

        if($p->ingreso)
            throw new Exception('No se permite cambiar la descripción, porque esta reservación ya tiene ingreso.');

        if(!$p->estado)
            throw new Exception('Esta reservación está anulada.');

        return view($this->table.'.editar_descripcion',[
            'p'=>$p,
        ]);
    }

    public function updateDescripcion(Request $r){
        $r->validate([
            'id'         => ['required','string'],
            'descripcion'=> ['required','string','min:3','max:255'],
            'confirm'    => ['required','accepted'],
        ], [
            'id.required'         =>'El campo id es obligatorio.',
            'id.string'           =>'El campo id debe ser un string.',
            'descripcion.required'=>'El campo descripción es obligatorio.',
            'descripcion.min'     =>'El campo descripción debe tener al menos :min carácteres.',
            'descripcion.max'     =>'El campo descripción no puede ser mayor a :max carácteres.',
            'confirm.required'    =>'El campo confirmación es obligatorio.',
            'confirm.accepted'    =>'Debe aceptar los términos y condiciones.',
        ]);

        try{
            $p = detalle_reservas::find(Crypt::decryptString($r->id));

            if($p->ingreso){
                $ms = "El usuario ".Auth::user()->name.' está intentando cambiar la tarifa de la reservación Nº '.$p->reservaciones_id.'. Esta reservación ya ingresó, no es permitido realizar cambios posterior al ingreso. (Esto puede generar manipulación del reporte de ventas de habitaciones.)';
                $mail = env('MAIL_NOTIFICACION', 'change@tropicoinn.com.sv');
                if(strlen($mail) > 5 && strlen($ms) > 5) {
                    Mail::to(trim($mail))->queue(new changeMail($ms));
                }

                throw new Exception('No se permite cambiar la descripción, porque esta reservación ya tiene ingreso.');
            }

            if(!$p->estado)
                throw new Exception('Esta reservación está anulada.');

            $p->descripcion = $r->descripcion;
            $p->save();

            return to_route($this->table.'.index',['id'=>Crypt::encryptString($p->reservaciones_id)])
                ->with('message','La descripción de la reserva fué realizada con exito.')
                ->with('type','success');
        }
        catch(Throwable $th){
            return redirect()->back()
                ->with('message','Error: '.$th->getMessage())
                ->with('type','danger');
        }
    }
}
