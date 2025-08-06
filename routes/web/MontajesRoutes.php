<?php
use App\Http\Controllers\MontajesController as Montajes;#Usar controllador
use App\Http\Controllers\MontajesGaleriasController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    $ruta = 'montajes';
    $nombreRuta = $ruta;

    Route::get($ruta,[Montajes::class,'index'])
        ->name($nombreRuta.'.index')
        ->middleware('permission:'.$ruta.'.index');

    Route::post($ruta,[Montajes::class,'search'])
        ->name($nombreRuta.'.search')
        ->middleware('permission:'.$ruta.'.index');

    Route::get($ruta . '/crear', [Montajes::class, 'create'])
        ->name($nombreRuta . '.create')
        ->middleware("permission:" . $ruta . ".create");

    Route::post($ruta.'/store',[Montajes::class,'store'])
        ->name($nombreRuta.'.store')
        ->middleware('permission:'.$ruta.'.create');

    Route::get($ruta . '/edit/{id}', [Montajes::class, 'edit'])
        ->name($nombreRuta . '.edit')
        ->middleware("permission:" . $ruta . ".edit");

    Route::post($ruta . '/update', [Montajes::class, 'update'])
        ->name($nombreRuta . '.update')
        ->middleware("permission:" . $ruta . ".edit");

    Route::get($ruta . '/confirm/{id}', [Montajes::class, 'confirm'])
        ->name($nombreRuta . '.confirm')
        ->middleware("permission:" . $ruta . ".delete");
    Route::get($ruta . '/show/{id}', [Montajes::class, 'show'])
        ->name($nombreRuta . '.show')
        ->middleware("permission:" . $ruta . ".show");

    Route::post($ruta . '/delete', [Montajes::class, 'destroy'])
        ->name($nombreRuta . '.delete')
        ->middleware("permission:" . $ruta . ".delete");
    Route::get($ruta . '/status/{id}', [Montajes::class, 'status'])
        ->name($nombreRuta . '.status')
        ->middleware("permission:" . $ruta . ".status");
    Route::post($ruta . '/api/galerias', [ MontajesGaleriasController::class, 'store'])
                ->name('montajes.galeria')
                ->middleware('permission:' . $ruta . '.show');

});
