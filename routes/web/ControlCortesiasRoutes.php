<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ControlCortesiasController as ControlCortesias;#Usar controllador

Route::middleware(['auth'])->group(function(){
    $ruta = 'control_cortesias';
    $nombreRuta = $ruta;

    Route::get($ruta,[ControlCortesias::class,'index'])
        ->name($nombreRuta.'.index')
        ->middleware('permission:'.$ruta.'.index');

    Route::post($ruta,[ControlCortesias::class,'search'])
        ->name($nombreRuta.'.search')
        ->middleware('permission:'.$ruta.'.index');

    Route::get($ruta . '/crear', [ControlCortesias::class, 'create'])
        ->name($nombreRuta . '.create')
        ->middleware("permission:" . $ruta . ".create");

    Route::post($ruta.'/store',[ControlCortesias::class,'store'])
        ->name($nombreRuta.'.store')
        ->middleware('permission:'.$ruta.'.create');
        
    Route::get($ruta . '/edit/{id}', [ControlCortesias::class, 'edit'])
        ->name($nombreRuta . '.edit')
        ->middleware("permission:" . $ruta . ".edit");

    Route::post($ruta . '/update', [ControlCortesias::class, 'update'])
        ->name($nombreRuta . '.update')
        ->middleware("permission:" . $ruta . ".update");

    Route::get($ruta . '/confirm/{id}', [ControlCortesias::class, 'confirm'])
        ->name($nombreRuta . '.confirm')
        ->middleware("permission:" . $ruta . ".delete");

    Route::post($ruta . '/delete', [ControlCortesias::class, 'destroy'])
        ->name($nombreRuta . '.delete')
        ->middleware("permission:" . $ruta . ".delete");
    Route::get($ruta . '/status/{id}', [ControlCortesias::class, 'status'])
        ->name($nombreRuta . '.status')
        ->middleware("permission:" . $ruta . ".status");
    
});
