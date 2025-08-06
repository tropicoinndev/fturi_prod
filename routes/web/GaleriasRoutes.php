<?php

use App\Http\Controllers\EventosGaleriasController as EventoGaleria;
use App\Http\Controllers\GaleriasController as Galerias; #Usar controllador
use App\Http\Controllers\MontajesGaleriasController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    $ruta = 'galerias';
    $nombreRuta = $ruta;

    Route::get($ruta, [Galerias::class, 'index'])
        ->name($nombreRuta . '.index')
        ->middleware('permission:' . $ruta . '.index');

    Route::post($ruta, [Galerias::class, 'search'])
        ->name($nombreRuta . '.search')
        ->middleware('permission:' . $ruta . '.index');

    Route::get($ruta . '/crear', [Galerias::class, 'create'])
        ->name($nombreRuta . '.create')
        ->middleware("permission:" . $ruta . ".create");

    Route::post($ruta . '/store', [Galerias::class, 'store'])
        ->name($nombreRuta . '.store')
        ->middleware('permission:' . $ruta . '.create');

    Route::get($ruta . '/edit/{id}', [Galerias::class, 'edit'])
        ->name($nombreRuta . '.edit')
        ->middleware("permission:" . $ruta . ".edit");

    Route::post($ruta . '/update', [Galerias::class, 'update'])
        ->name($nombreRuta . '.update')
        ->middleware("permission:" . $ruta . ".edit");
    Route::post($ruta . '/add/set', [Galerias::class, 'galeriaFoto'])
        ->name($nombreRuta . '.set_foto')
        ->middleware("permission:" . $ruta . ".create");
    Route::post($ruta . '/agregar/galeria', [Galerias::class, 'addGaleria'])
        ->name($nombreRuta . '.cargar_galeria')
        ->middleware("permission:" . $ruta . ".create");
    Route::get($ruta . '/confirm/{id}', [Galerias::class, 'confirm'])
        ->name($nombreRuta . '.confirm')
        ->middleware("permission:" . $ruta . ".delete");
    Route::get($ruta . '/show/{id}', [Galerias::class, 'show'])
        ->name($nombreRuta . '.show')
        ->middleware("permission:" . $ruta . ".show");

    Route::post($ruta . '/delete', [Galerias::class, 'destroy'])
        ->name($nombreRuta . '.delete')
        ->middleware("permission:" . $ruta . ".delete");
    Route::post($ruta . '/api/montajes', [MontajesGaleriasController::class, 'storeMontaje'])
        ->name('galerias.montajes')
        ->middleware('permission:' . $ruta . '.show');
    Route::post($ruta . '/evento/galeria', [EventoGaleria::class, 'editFoto'])
        ->name('galerias.edit_galeria')
        ->middleware('permission:' . $ruta . '.edit');
    Route::post($ruta . '/add', [EventoGaleria::class, 'addFoto'])
        ->name('galerias.add_foto')
        ->middleware('permission:' . $ruta . '.create');
});
