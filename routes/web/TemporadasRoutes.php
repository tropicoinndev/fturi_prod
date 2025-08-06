<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TemporadasController as Temporadas;#Usar controllador

Route::middleware(['auth'])->group(function(){
    $path = 'temporadas';#Ruta
    $name = $path;       #Nombre de ruta

    Route::get($path,[Temporadas::class,'index'])
        ->name($name.'.index')
        ->middleware('permission:'.$path.'.index');

    Route::post($path,[Temporadas::class,'search'])
        ->name($name.'.search')
        ->middleware('permission:'.$path.'.index');

    Route::get($path.'/crear',[Temporadas::class,'create'])
        ->name($name.'.create')
        ->middleware('permission:'.$path.'.create');

    Route::post($path.'/store',[Temporadas::class,'store'])
        ->name($name.'.store')
        ->middleware('permission:'.$path.'.create');
    
     Route::get($path.'/edit/{id}',[Temporadas::class,'edit'])
        ->name($name.'.edit')
        ->middleware('permission:'.$path.'.edit');

    Route::post($path.'/update',[Temporadas::class,'update'])
        ->name($name.'.update')
        ->middleware('permission:'.$path.'.edit'); 

    Route::get($path.'/confirm/{id}',[Temporadas::class,'confirm'])
        ->name($name.'.confirm')
        ->middleware('permission:'.$path.'.delete');
    
    Route::post($path.'/delete',[Temporadas::class,'destroy'])
        ->name($name.'.delete')
        ->middleware('permission:'.$path.'.delete');
    Route::get($path . '/status/{id}', [Temporadas::class, 'status'])
                ->name($name . '.status')
                ->middleware('permission:' . $path . '.status');
});
