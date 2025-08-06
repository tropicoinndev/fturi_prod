<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientesIdentificacionesController as ClientesIdentificaciones;#Usar controllador

Route::middleware(['auth'])->group(function(){
    $path = 'clientes_identificaciones';#Ruta
    $name = $path;                      #Nombre de ruta

    Route::get($path,[ClientesIdentificaciones::class,'index'])
        ->name($name.'.index')
        ->middleware('permission:'.$path.'.index');

    Route::post($path,[ClientesIdentificaciones::class,'search'])
        ->name($name.'.search')
        ->middleware('permission:'.$path.'.index');

    Route::get($path.'/crear',[ClientesIdentificaciones::class,'create'])
        ->name($name.'.create')
        ->middleware('permission:'.$path.'.create');

    Route::post($path.'/store',[ClientesIdentificaciones::class,'store'])
        ->name($name.'.store')
        ->middleware('permission:'.$path.'.create');

    Route::get($path.'/show/{id}',[ClientesIdentificaciones::class,'show'])
        ->name($name.'.show')
        ->middleware('permission:'.$path.'.index');
    
    Route::get($path.'/edit/{id}',[ClientesIdentificaciones::class,'edit'])
        ->name($name.'.edit')
        ->middleware('permission:'.$path.'.edit');

    Route::post($path.'/update',[ClientesIdentificaciones::class,'update'])
        ->name($name.'.update')
        ->middleware('permission:'.$path.'.edit');

    Route::get($path.'/confirm/{id}',[ClientesIdentificaciones::class,'confirm'])
        ->name($name.'.confirm')
        ->middleware('permission:'.$path.'.delete');
    
    Route::post($path.'/delete',[ClientesIdentificaciones::class,'destroy'])
        ->name($name.'.delete')
        ->middleware('permission:'.$path.'.delete');

});
