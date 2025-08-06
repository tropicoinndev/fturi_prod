<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CajaTurnosController as CajaTurnos;#Usar controllador

Route::middleware(['auth'])->group(function(){
    $path = 'caja_turnos';#Ruta
    $name = $path;        #Nombre de ruta

    Route::get($path,[CajaTurnos::class,'index'])
        ->name($name.'.index')
        ->middleware('permission:'.$path.'.index');

    Route::post($path,[CajaTurnos::class,'search'])
        ->name($name.'.search')
        ->middleware('permission:'.$path.'.index');
        
    Route::get($path . '/api/list', [CajaTurnos::class, 'index_api'])
        ->name($name . '.index_api')
        ->middleware("permission:" . $path . ".index");
     Route::post($path . '/api/store', [CajaTurnos::class, 'store_api'])
        ->name($name . '.store_api')
        ->middleware("permission:" . $path . ".create");

    Route::get($path.'/crear',[CajaTurnos::class,'create'])
        ->name($name.'.create')
        ->middleware('permission:'.$path.'.create');

    Route::post($path.'/store',[CajaTurnos::class,'store'])
        ->name($name.'.store')
        ->middleware('permission:'.$path.'.create');

    Route::get($path.'/show/{id}',[CajaTurnos::class,'show'])
        ->name($name.'.show')
        ->middleware('permission:'.$path.'.index');
    
    Route::get($path.'/edit/{id}',[CajaTurnos::class,'edit'])
        ->name($name.'.edit')
        ->middleware('permission:'.$path.'.edit');

    Route::post($path.'/update',[CajaTurnos::class,'update'])
        ->name($name.'.update')
        ->middleware('permission:'.$path.'.edit');

    Route::get($path.'/confirm/{id}',[CajaTurnos::class,'confirm'])
        ->name($name.'.confirm')
        ->middleware('permission:'.$path.'.delete');
    
    Route::post($path.'/delete',[CajaTurnos::class,'destroy'])
        ->name($name.'.delete')
        ->middleware('permission:'.$path.'.delete');

    Route::get($path.'/status/{id}',[CajaTurnos::class,'status'])
        ->name($name.'.status')
        ->middleware('permission:'.$path.'.status');
     Route::post($path . '/delete/api', [CajaTurnos::class, 'destroy_api'])
        ->name($name . '.destroy_api')
        ->middleware("permission:" . $path . ".delete");
});
