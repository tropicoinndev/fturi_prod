<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PaisesController as Paises;#Usar controllador

Route::middleware(['auth'])->group(function(){
    $ruta = 'paises';
    $nombreRuta = $ruta;

    Route::get($ruta,[Paises::class,'index'])
        ->name($nombreRuta.'.index')
        ->middleware('permission:'.$ruta.'.index');

    Route::post($ruta,[Paises::class,'search'])
        ->name($nombreRuta.'.search')
        ->middleware('permission:'.$ruta.'.index');

    Route::get($ruta . '/crear', [Paises::class, 'create'])
        ->name($nombreRuta . '.create')
        ->middleware("permission:" . $ruta . ".create");

    Route::post($ruta.'/store',[Paises::class,'store'])
        ->name($nombreRuta.'.store')
        ->middleware('permission:'.$ruta.'.create');
        
    Route::get($ruta . '/edit/{id}', [Paises::class, 'edit'])
        ->name($nombreRuta . '.edit')
        ->middleware("permission:" . $ruta . ".edit");

    Route::post($ruta . '/update', [Paises::class, 'update'])
        ->name($nombreRuta . '.update')
        ->middleware("permission:" . $ruta . ".update");

    Route::get($ruta . '/confirm/{id}', [Paises::class, 'confirm'])
        ->name($nombreRuta . '.confirm')
        ->middleware("permission:" . $ruta . ".delete");

    Route::post($ruta . '/delete', [Paises::class, 'destroy'])
        ->name($nombreRuta . '.delete')
        ->middleware("permission:" . $ruta . ".delete");

    Route::post($ruta.'/api/get/paises',[Paises::class,'apiGetPaises'])
        ->name($nombreRuta.'.apiGetPaises')
        ->middleware('permission:'.$ruta.'.index');
});
