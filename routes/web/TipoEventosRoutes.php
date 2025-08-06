<?php
use App\Http\Controllers\TipoEventosController as TipoEventosController;#Usar controllador

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    $ruta = 'tipo_eventos';
    $nombreRuta = $ruta;

    Route::get($ruta,[TipoEventosController::class,'index'])
        ->name($nombreRuta.'.index')
        ->middleware('permission:'.$ruta.'.index');

    Route::post($ruta,[TipoEventosController::class,'search'])
        ->name($nombreRuta.'.search')
        ->middleware('permission:'.$ruta.'.index');

    Route::get($ruta . '/crear', [TipoEventosController::class, 'create'])
        ->name($nombreRuta . '.create')
        ->middleware("permission:" . $ruta . ".create");

    Route::post($ruta.'/store',[TipoEventosController::class,'store'])
        ->name($nombreRuta.'.store')
        ->middleware('permission:'.$ruta.'.create');

    Route::get($ruta . '/edit/{id}', [TipoEventosController::class, 'edit'])
        ->name($nombreRuta . '.edit')
        ->middleware("permission:" . $ruta . ".edit");

    Route::post($ruta . '/update', [TipoEventosController::class, 'update'])
        ->name($nombreRuta . '.update')
        ->middleware("permission:" . $ruta . ".edit");

    Route::get($ruta . '/confirm/{id}', [TipoEventosController::class, 'confirm'])
        ->name($nombreRuta . '.confirm')
        ->middleware("permission:" . $ruta . ".delete");

    Route::post($ruta . '/delete', [TipoEventosController::class, 'destroy'])
        ->name($nombreRuta . '.delete')
        ->middleware("permission:" . $ruta . ".delete");
    Route::get($ruta . '/status/{id}', [TipoEventosController::class, 'status'])
        ->name($nombreRuta . '.status')
        ->middleware("permission:" . $ruta . ".status");
    Route::get($ruta . '/salon/{id}', [TipoEventosController::class, 'salon'])
        ->name($nombreRuta . '.salon')
        ->middleware("permission:" . $ruta . ".salon");
    Route::post($ruta . '/tipo/evento', [TipoEventosController::class, 'salonTipoEvento'])
        ->name($nombreRuta . '.check_salon')
        ->middleware("permission:" . $ruta . ".salon");
});
