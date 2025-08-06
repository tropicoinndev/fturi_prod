<?php

use App\Http\Controllers\MantenimientosController as Mantenimientos; #Usar controllador
use Illuminate\Support\Facades\Route;
Route::middleware(['auth'])->group(function () {
    $ruta = 'mantenimientos';
    $nombreRuta = $ruta;

    Route::get($ruta, [Mantenimientos::class, 'index'])
        ->name($nombreRuta . '.index')
        ->middleware('permission:' . $ruta . '.index');

    Route::post($ruta, [Mantenimientos::class, 'search'])
        ->name($nombreRuta . '.search')
        ->middleware('permission:' . $ruta . '.index');

    Route::get($ruta . '/crear', [Mantenimientos::class, 'create'])
        ->name($nombreRuta . '.create')
        ->middleware('permission:' . $ruta . '.create');
    Route::post($ruta . '/usuario/tipo', [Mantenimientos::class, 'usuariosBytipoMantenimiento'])
    ->name($nombreRuta . '.cargar_usuario')
    ->middleware('permission:' . $ruta . '.index');
    Route::post($ruta . '/asignar/mantenimiento', [Mantenimientos::class, 'asignarMantenimientos'])
        ->name($nombreRuta . '.asignarMantenimientos')
        ->middleware('permission:' . $ruta . '.create');
    Route::post($ruta . '/desasignar/mantenimiento', [Mantenimientos::class, 'desasignarMantenimiento'])
        ->name($nombreRuta . '.desasignarMantenimiento')
        ->middleware('permission:' . $ruta . '.create');

    Route::post($ruta . '/store', [Mantenimientos::class, 'store'])
        ->name($nombreRuta . '.store')
        ->middleware('permission:' . $ruta . '.create');
    Route::post($ruta . '/habitacion/mantenimiento', [Mantenimientos::class, 'detalleHabitacion'])
        ->name($nombreRuta . '.detalleHabitacion')
        ->middleware('permission:' . $ruta . '.create');

    Route::get($ruta . '/edit/{id}', [Mantenimientos::class, 'edit'])
        ->name($nombreRuta . '.edit')
        ->middleware('permission:' . $ruta . '.edit');

    Route::post($ruta . '/update', [Mantenimientos::class, 'update'])
        ->name($nombreRuta . '.update')
        ->middleware('permission:' . $ruta . '.edit');

    Route::get($ruta . '/confirm/{id}', [Mantenimientos::class, 'confirm'])
        ->name($nombreRuta . '.confirm')
        ->middleware('permission:' . $ruta . '.delete');

    Route::post($ruta . '/delete', [Mantenimientos::class, 'destroy'])
        ->name($nombreRuta . '.delete')
        ->middleware('permission:' . $ruta . '.delete');

    Route::post($ruta . '/status', [Mantenimientos::class, 'status'])
        ->name($nombreRuta . '.status')
        ->middleware('permission:' . $ruta . '.status');
    Route::get($ruta . '/asignar', [Mantenimientos::class, 'asignacionMantenimiento'])
        ->name($nombreRuta . '.asignacionMantenimiento')
        ->middleware('permission:' . $ruta . '.index');
    Route::get($ruta . '/confirmar', [Mantenimientos::class, 'confirmacionMantenimiento'])
        ->name($nombreRuta . '.confirmacionMantenimiento')
        ->middleware('permission:' . $ruta . '.index');
    Route::get($ruta . '/historial', [Mantenimientos::class, 'historialMantenimiento'])
        ->name($nombreRuta . '.historialMantenimiento')
        ->middleware('permission:' . $ruta . '.index');
    Route::get($ruta . '/pendientes', [Mantenimientos::class, 'mantenimientosPendientes'])
        ->name($nombreRuta . '.mantenimientosPendientes')
        ->middleware('permission:' . $ruta . '.index');
    Route::get($ruta . '/finalizar', [Mantenimientos::class, 'finalizacionMantenimiento'])
        ->name($nombreRuta . '.finalizacionMantenimiento')
        ->middleware('permission:' . $ruta . '.index');
    Route::get($ruta . '/supervisar', [Mantenimientos::class, 'supervisionMantenimiento'])
        ->name($nombreRuta . '.supervisionMantenimiento')
        ->middleware('permission:' . $ruta . '.index');

    Route::post($ruta . '/api/asignados', [Mantenimientos::class, 'obtenerMantenimientosByUser'])
        ->name($nombreRuta . '.obtenerMantenimientosByUser')
        ->middleware('permission:' . $ruta . '.index');
    Route::post($ruta . '/api/obtener/supervisados', [Mantenimientos::class, 'obtenerSupervisionByUser'])
        ->name($nombreRuta . '.obtenerSupervisionByUser')
        ->middleware('permission:' . $ruta . '.index');
    Route::post($ruta . '/No/asignados', [Mantenimientos::class, 'obtenerMantenimientosNoAsignados'])
        ->name($nombreRuta . '.obtenerMantenimientosNoAsignados')
        ->middleware('permission:' . $ruta . '.index');
    Route::post($ruta . '/No/supervisados', [Mantenimientos::class, 'obtenerMantenimientosNoSupervisados'])
        ->name($nombreRuta . '.obtenerMantenimientosNoSupervisados')
        ->middleware('permission:' . $ruta . '.index');
    Route::post($ruta . '/confirmacion', [Mantenimientos::class, 'confirmarMantenimientos'])
        ->name($nombreRuta . '.confirmarMantenimientos')
        ->middleware('permission:' . $ruta . '.index');
    Route::post($ruta . '/desconfirmacion', [Mantenimientos::class, 'desconfirmarMantenimientos'])
        ->name($nombreRuta . '.desconfirmarMantenimientos')
        ->middleware('permission:' . $ruta . '.index');
    Route::post($ruta . '/iniciar/estado', [Mantenimientos::class, 'iniciarMantenimientos'])
        ->name($nombreRuta . '.iniciarMantenimientos')
        ->middleware('permission:' . $ruta . '.index');
    Route::post($ruta . '/finalizar/estado', [Mantenimientos::class, 'finalizarEstado'])
        ->name($nombreRuta . '.finalizarEstado')
        ->middleware('permission:' . $ruta . '.index');
    Route::post($ruta . '/incompletar/estado', [Mantenimientos::class, 'mantenimientoIncompletado'])
        ->name($nombreRuta . '.mantenimientoIncompletado')
        ->middleware('permission:' . $ruta . '.index');
    Route::post($ruta . '/finalizar', [Mantenimientos::class, 'finalizarMantenimientos'])
        ->name($nombreRuta . '.finalizarMantenimientos')
        ->middleware('permission:' . $ruta . '.index');
    Route::post($ruta . '/eliminar/finalizar', [Mantenimientos::class, 'eliminarFinalizarMantenimientos'])
        ->name($nombreRuta . '.eliminarFinalizarMantenimientos')
        ->middleware('permission:' . $ruta . '.index');
    Route::post($ruta . '/confirmar/estado', [Mantenimientos::class, 'confirmarEstados'])
        ->name($nombreRuta . '.confirmarEstados')
        ->middleware('permission:' . $ruta . '.index');

    Route::get($ruta . '/mantenimientos/reporte', [Mantenimientos::class, 'mantenimientos_reporte'])
        ->name($nombreRuta . '.mantenimientos_reporte')
        ->middleware('permission:' . $ruta . '.index');
    Route::post($ruta . '/mantenimientos/reporte/', [Mantenimientos::class, 'mantenimientos_reporte'])
        ->name($nombreRuta . '.mantenimientos_reporte_search')
        ->middleware('permission:' . $ruta . '.index');
    Route::get($ruta . '/mantenimientos/reporte/{fecha_inicio}/{fecha_fin}', [Mantenimientos::class, 'getReportePDF'])
        ->name($nombreRuta . '.mantenimientos_reporte_pdf')
        ->middleware('permission:' . $ruta . '.index');

    Route::get($ruta . '/habitaciones', [Mantenimientos::class, 'habitaciones'])
        ->name($nombreRuta . '.habitaciones')
        ->middleware('permission:' . $ruta . '.habitaciones');
    Route::post($ruta . '/habitaciones', [Mantenimientos::class, 'habitacionesMantenimientos'])
        ->name($nombreRuta . '.habitaciones_store')
        ->middleware('permission:' . $ruta . '.habitaciones');
});
