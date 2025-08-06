<?php

use App\Http\Controllers\ServiciosController as Servicios;#Usar controllador
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    $path = 'servicios';#Ruta
    $name = $path;      #Nombre de ruta

    Route::get($path,[Servicios::class,'index'])
        ->name($name.'.index')
        ->middleware('permission:'.$path.'.index');

    Route::post($path,[Servicios::class,'search'])
        ->name($name.'.search')
        ->middleware('permission:'.$path.'.index');

    Route::post($path.'/api/search',[Servicios::class,'apiSearch'])
        ->name($name.'.apiSearch')
        ->middleware('permission:'.$path.'.index');

    Route::get($path.'/crear',[Servicios::class,'create'])
        ->name($name.'.create')
        ->middleware('permission:'.$path.'.create');

    Route::post($path.'/store',[Servicios::class,'store'])
        ->name($name.'.store')
        ->middleware('permission:'.$path.'.create');

    Route::get($path.'/edit/{id}',[Servicios::class,'edit'])
        ->name($name.'.edit')
        ->middleware('permission:'.$path.'.edit');

    Route::post($path.'/update',[Servicios::class,'update'])
        ->name($name.'.update')
        ->middleware('permission:'.$path.'.edit');

    Route::get($path.'/confirm/{id}',[Servicios::class,'confirm'])
        ->name($name.'.confirm')
        ->middleware('permission:'.$path.'.delete');

    Route::post($path.'/delete',[Servicios::class,'destroy'])
        ->name($name.'.delete')
        ->middleware('permission:'.$path.'.delete');

    Route::get($path.'/status/{id}',[Servicios::class,'status'])
        ->name($name.'.status')
        ->middleware('permission:'.$path.'.status');

    Route::get($path.'/statusServiciosIva/{id}',[Servicios::class,'statusServiciosIva'])
        ->name($name.'.statusServiciosIva')
        ->middleware('permission:'.$path.'.status');

    Route::get($path.'/statusServiciosCesc/{id}',[Servicios::class,'statusServiciosCesc'])
        ->name($name.'.statusServiciosCesc')
        ->middleware('permission:'.$path.'.status');

    Route::get($path.'/statusServiciosAdvalorem/{id}',[Servicios::class,'statusServiciosAdvalorem'])
        ->name($name.'.statusServiciosAdvalorem')
        ->middleware('permission:'.$path.'.status');

    Route::get($path.'/statusServiciosPropina/{id}',[Servicios::class,'statusServiciosPropina'])
        ->name($name.'.statusServiciosPropina')
        ->middleware('permission:'.$path.'.status');

    Route::get($path.'/statusServiciosPrecios/{id}',[Servicios::class,'statusServiciosPrecios'])
        ->name($name.'.statusServiciosPrecios')
        ->middleware('permission:'.$path.'.status');
    Route::get($path . '/serviciosDescuentos/{id}', [Servicios::class, 'serviciosDescuento'])
    ->name($name . '.serviciosDescuento')
    ->middleware('permission:' . $path . '.status');
});
