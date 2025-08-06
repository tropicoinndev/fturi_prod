<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientesContactosController as ClientesContactos;#Usar controllador

Route::middleware(['auth'])->group(function(){
    $path = 'clientes_contactos';#Ruta
    $name = $path;               #Nombre de ruta

    Route::get($path,[ClientesContactos::class,'index'])
        ->name($name.'.index')
        ->middleware('permission:'.$path.'.index');

    Route::post($path,[ClientesContactos::class,'search'])
        ->name($name.'.search')
        ->middleware('permission:'.$path.'.index');

    Route::get($path.'/crear',[ClientesContactos::class,'create'])
        ->name($name.'.create')
        ->middleware('permission:'.$path.'.create');

    Route::post($path.'/store',[ClientesContactos::class,'store'])
        ->name($name.'.store')
        ->middleware('permission:'.$path.'.create');

    Route::get($path.'/show/{id}',[ClientesContactos::class,'show'])
        ->name($name.'.show')
        ->middleware('permission:'.$path.'.index');
    
    Route::get($path.'/edit/{id}',[ClientesContactos::class,'edit'])
        ->name($name.'.edit')
        ->middleware('permission:'.$path.'.edit');

    Route::post($path.'/update',[ClientesContactos::class,'update'])
        ->name($name.'.update')
        ->middleware('permission:'.$path.'.edit');

    Route::get($path.'/confirm/{id}',[ClientesContactos::class,'confirm'])
        ->name($name.'.confirm')
        ->middleware('permission:'.$path.'.delete');
    
    Route::post($path.'/delete',[ClientesContactos::class,'destroy'])
        ->name($name.'.delete')
        ->middleware('permission:'.$path.'.delete');

});
