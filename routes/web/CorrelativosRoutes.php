
<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CorrelativosController as Correlativos; #Usar controllador

Route::middleware(['auth'])->group(function () {
    $ruta       = 'correlativos';
    $nombreRuta = $ruta;

    Route::get($ruta, [Correlativos::class, 'index'])
        ->name($nombreRuta . '.index')
        ->middleware('permission:' . $ruta . '.index');

    Route::post($ruta, [Correlativos::class, 'search'])
        ->name($nombreRuta . '.search')
        ->middleware('permission:' . $ruta . '.index');

    Route::get($ruta . '/crear/', [Correlativos::class, 'create'])
        ->name($nombreRuta . '.create')
        ->middleware("permission:" . $ruta . ".create");

    Route::post($ruta . '/store', [Correlativos::class, 'store'])
        ->name($nombreRuta . '.store')
        ->middleware('permission:' . $ruta . '.create');

    Route::get($ruta . '/edit/{id}', [Correlativos::class, 'edit'])
        ->name($nombreRuta . '.edit')
        ->middleware("permission:" . $ruta . ".edit");

    Route::post($ruta . '/update', [Correlativos::class, 'update'])
        ->name($nombreRuta . '.update')
        ->middleware("permission:" . $ruta . ".edit");

    Route::get($ruta . '/confirm/{id}', [Correlativos::class, 'confirm'])
        ->name($nombreRuta . '.confirm')
        ->middleware("permission:" . $ruta . ".delete");

    Route::post($ruta . '/delete', [Correlativos::class, 'destroy'])
        ->name($nombreRuta . '.delete')
        ->middleware("permission:" . $ruta . ".delete");

    Route::get($ruta . '/status/{id}', [Correlativos::class, 'status'])
        ->name($nombreRuta . '.status')
        ->middleware('permission:' . $ruta . '.status');
});
