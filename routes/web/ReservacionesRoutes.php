<?php

use App\Http\Controllers\ReservacionesController as Reservaciones; #Usar controllador

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    $ruta       = 'reservaciones';
    $nombreRuta = $ruta;

    Route::get($ruta, [Reservaciones::class, 'index'])
        ->name($nombreRuta . '.index')
        ->middleware('permission:' . $ruta . '.index');
    Route::get($ruta . '/historial', [Reservaciones::class, 'history'])
        ->name($nombreRuta . '.history')

        ->middleware('permission:' . $ruta . '.index');
    Route::post($ruta, [Reservaciones::class, 'search'])
        ->name($nombreRuta . '.search')
        ->middleware('permission:' . $ruta . '.index');

    Route::post($ruta . '/reservas/disponibles', [Reservaciones::class, 'reservasDisponibles'])
        ->name($nombreRuta . '.reservas_disponibles')
        ->middleware('permission:' . $ruta . '.index');

    /*Route::post('/api/reservaciones/create', [Reservaciones::class, 'apiReservacionesCreate'])
        ->name('apiReservacionesCreate')
        ->middleware("permission:" . $ruta . ".create");*/

    Route::get($ruta . '/crear', [Reservaciones::class, 'create'])
        ->name($nombreRuta . '.create')
        ->middleware("permission:" . $ruta . ".create");

    Route::post($ruta . '/store', [Reservaciones::class, 'store'])
        ->name($nombreRuta . '.store')
        ->middleware('permission:' . $ruta . '.create');

    Route::get($ruta . '/completa/{id}', [Reservaciones::class, 'complete'])
        ->name($nombreRuta . '.completa')
        ->middleware("permission:" . $ruta . ".create");

    Route::get($ruta . '/desbloquear/{id}', [Reservaciones::class, 'desbloquear'])
        ->name($nombreRuta . '.desbloquear')
        ->middleware("permission:" . $ruta . ".desbloquear");

    Route::get($ruta . '/impresion/{id}', [Reservaciones::class, 'imprimir'])
        ->name($nombreRuta . '.imprimir')
        ->middleware("permission:" . $ruta . ".imprimir");

    Route::get($ruta . '/container/impresion/{id}', [Reservaciones::class, 'imprimirContainer'])
        ->name($nombreRuta . '.imprimir_container')
        ->middleware("permission:" . $ruta . ".imprimir");

    Route::get($ruta . '/edit/{id}', [Reservaciones::class, 'edit'])
        ->name($nombreRuta . '.edit')
        ->middleware("permission:" . $ruta . ".edit");

    Route::post($ruta . '/update', [Reservaciones::class, 'update'])
        ->name($nombreRuta . '.update')
        ->middleware("permission:" . $ruta . ".edit");

    Route::get($ruta . '/anular/{id}', [Reservaciones::class, 'anular'])
        ->name($nombreRuta . '.anular')
        ->middleware("permission:" . $ruta . ".delete");

    Route::post($ruta . '/anulacion', [Reservaciones::class, 'anulacion'])
        ->name($nombreRuta . '.anulacion')
        ->middleware("permission:" . $ruta . ".delete");

    Route::get($ruta . '/anulacion/detalle/{id}', [Reservaciones::class, 'anulacion_detalle'])
        ->name($nombreRuta . '.anulacion_detalle')
        ->middleware("permission:" . $ruta . ".index");


    Route::get($ruta . '/status/{id}', [Reservaciones::class, 'status'])
        ->name($nombreRuta . '.status')
        ->middleware('permission:' . $ruta . '.status');

    Route::get($ruta . '/reporte/habitacion/', [Reservaciones::class, 'reportHabitacion'])
        ->name($nombreRuta . '.reporte_habitacion')
        ->middleware('permission:' . $ruta . '.create');

    Route::post($ruta . '/api/habitaciones', [Reservaciones::class, 'apiHabitaciones'])
        ->name($nombreRuta . '.api_habitaciones')
        ->middleware("permission:" . $ruta . ".create");

    Route::get($ruta . '/reporte/venta', [Reservaciones::class, 'reporteVenta'])
        ->name($nombreRuta . '.reporte_venta')
        ->middleware("permission:" . $ruta . ".create");

    Route::post($ruta . '/reporte/venta', [Reservaciones::class, 'reporteVentaOpciones'])
        ->name($nombreRuta . '.reporte_venta_opcion')
        ->middleware("permission:" . $ruta . ".create");

    Route::get($ruta . '/reporte/ingreso', [Reservaciones::class, 'reporteIngreso'])
        ->name($nombreRuta . '.reporte_ingreso')
        ->middleware("permission:recepciones.create");

    Route::post($ruta . '/reporte/ingreso', [Reservaciones::class, 'reporteIngreso'])
        ->name($nombreRuta . '.reporte_ingreso_search')
        ->middleware("permission:recepciones.create");
    //reservaciones.reporte_ingreso
    Route::post($ruta . '/evento/reserva', [Reservaciones::class, 'reservacionEvento'])
        ->name($nombreRuta . '.reserva_evento')
        ->middleware('permission:' . $ruta . '.create');

    Route::get($ruta . '/disponibilidad/habitaciones', [Reservaciones::class, 'disponibilidadHabitacionesView'])
        ->name($nombreRuta . '.disponibilidad_habitaciones_view')
        ->middleware('permission:' . $ruta . '.create');

    #Detalle de hospedaje de huésped
    Route::get($ruta.'/detalle/hospedaje/huesped',[Reservaciones::class,'detalleHospedajeHuesped'])
        ->name($nombreRuta.'.detalleHospedajeHuesped')
        ->middleware('permission:'.$ruta.'.index');

    Route::post($ruta.'/detalle/hospedaje/huesped',[Reservaciones::class,'detalleHospedajeHuespedSearch'])
        ->name($nombreRuta.'.detalleHospedajeHuespedSearch')
        ->middleware('permission:'.$ruta.'.index');
});
