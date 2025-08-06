<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProveedoresController as Proveedores;#Usar controllador

Route::middleware(['auth'])->group(function(){
    $ruta       = 'proveedores';
    $nombreRuta = $ruta;

    Route::get($ruta,[Proveedores::class,'index'])
        ->name($nombreRuta.'.index')
        ->middleware('permission:'.$ruta.'.index');

    Route::post($ruta,[Proveedores::class,'search'])
        ->name($nombreRuta.'.search')
        ->middleware('permission:'.$ruta.'.index');

    Route::get($ruta . '/crear', [Proveedores::class, 'create'])
        ->name($nombreRuta . '.create')
        ->middleware("permission:" . $ruta . ".create");

    Route::post($ruta.'/store',[Proveedores::class,'store'])
        ->name($nombreRuta.'.store')
        ->middleware('permission:'.$ruta.'.create');

    Route::get($ruta . '/edit/{id}', [Proveedores::class, 'edit'])
        ->name($nombreRuta . '.edit')
        ->middleware("permission:" . $ruta . ".edit");

    Route::post($ruta . '/update', [Proveedores::class, 'update'])
        ->name($nombreRuta . '.update')
        ->middleware("permission:" . $ruta . ".edit");

    Route::get($ruta . '/confirm/{id}', [Proveedores::class, 'confirm'])
        ->name($nombreRuta . '.confirm')
        ->middleware("permission:" . $ruta . ".delete");

    Route::post($ruta . '/delete', [Proveedores::class, 'destroy'])
        ->name($nombreRuta . '.delete')
        ->middleware("permission:" . $ruta . ".delete");

    Route::get($ruta.'/statusPermiteCredito/{id}',[Proveedores::class,'statusPermiteCredito'])
        ->name($nombreRuta.'.statusPermiteCredito')
        ->middleware('permission:'.$ruta.'.status');

    /*Route::get($ruta.'/status/{id}',[Proveedores::class,'status'])
        ->name($nombreRuta.'.status')
        ->middleware('permission:'.$ruta.'.status');*/
});
