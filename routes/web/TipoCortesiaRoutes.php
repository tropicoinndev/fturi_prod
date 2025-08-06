<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TipoCortesiaController as TipoCortesia;#Usar controllador

Route::middleware(['auth'])->group(function(){
    $ruta = 'tipo_cortesia';
    $nombreRuta = $ruta;

    Route::get($ruta,[TipoCortesia::class,'index'])
        ->name($nombreRuta.'.index')
        ->middleware('permission:'.$ruta.'.index');

    Route::post($ruta,[TipoCortesia::class,'search'])
        ->name($nombreRuta.'.search')
        ->middleware('permission:'.$ruta.'.index');

    Route::get($ruta . '/crear', [TipoCortesia::class, 'create'])
        ->name($nombreRuta . '.create')
        ->middleware("permission:" . $ruta . ".create");

    Route::post($ruta.'/store',[TipoCortesia::class,'store'])
        ->name($nombreRuta.'.store')
        ->middleware('permission:'.$ruta.'.create');
        
    Route::get($ruta . '/edit/{id}', [TipoCortesia::class, 'edit'])
        ->name($nombreRuta . '.edit')
        ->middleware("permission:" . $ruta . ".edit");

    Route::post($ruta . '/update', [TipoCortesia::class, 'update'])
        ->name($nombreRuta . '.update')
        ->middleware("permission:" . $ruta . ".update");

    Route::get($ruta . '/confirm/{id}', [TipoCortesia::class, 'confirm'])
        ->name($nombreRuta . '.confirm')
        ->middleware("permission:" . $ruta . ".delete");

    Route::post($ruta . '/delete', [TipoCortesia::class, 'destroy'])
        ->name($nombreRuta . '.delete')
        ->middleware("permission:" . $ruta . ".delete");
    Route::get($ruta . '/status/{id}', [TipoCortesia::class, 'status'])
        ->name($nombreRuta . '.status')
        ->middleware("permission:" . $ruta . ".status");
    
});
