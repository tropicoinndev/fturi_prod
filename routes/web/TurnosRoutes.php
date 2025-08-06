<?php

use App\Http\Controllers\TurnosController as Turnos; #Usar controllador

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    $ruta = 'turnos';
    $nombreRuta = $ruta;

    Route::get($ruta, [Turnos::class, 'index'])
        ->name($nombreRuta . '.index')
        ->middleware('permission:' . $ruta . '.index');

    Route::post($ruta, [Turnos::class, 'search'])
        ->name($nombreRuta . '.search')
        ->middleware('permission:' . $ruta . '.index');

    Route::get($ruta . '/crear', [Turnos::class, 'create'])
        ->name($nombreRuta . '.create')
        ->middleware("permission:" . $ruta . ".create");

    Route::post($ruta . '/store', [Turnos::class, 'store'])
        ->name($nombreRuta . '.store')
        ->middleware('permission:' . $ruta . '.create');

    Route::get($ruta . '/show/{id}', [Turnos::class, 'show'])
        ->name($nombreRuta . '.show')
        ->middleware('permission:' . $ruta . '.index');

    Route::get($ruta . '/edit/{id}', [Turnos::class, 'edit'])
        ->name($nombreRuta . '.edit')
        ->middleware("permission:" . $ruta . ".edit");


    Route::post($ruta . '/update', [Turnos::class, 'update'])
        ->name($nombreRuta . '.update')
        ->middleware("permission:" . $ruta . ".edit");

    Route::get($ruta . '/confirm/{id}', [Turnos::class, 'confirm'])
        ->name($nombreRuta . '.confirm')
        ->middleware("permission:" . $ruta . ".delete");


    Route::post($ruta . '/delete', [Turnos::class, 'destroy'])
        ->name($nombreRuta . '.delete')
        ->middleware("permission:" . $ruta . ".delete");
    Route::get($ruta . '/status/{id}', [Turnos::class, 'status'])
        ->name($nombreRuta . '.status')
        ->middleware('permission:' . $ruta . '.status');
});
