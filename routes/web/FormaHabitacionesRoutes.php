<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormaHabitacionesController as FormaHabitaciones;#Usar controllador

Route::middleware(['auth'])->group(function(){
    $path = 'forma/habitaciones';#Ruta
    $name = 'forma_habitaciones';#Nombre de ruta

    Route::get($path,[FormaHabitaciones::class,'index'])
        ->name($name.'.index')
        ->middleware('permission:'.$path.'.index');

    Route::post($path,[FormaHabitaciones::class,'search'])
        ->name($name.'.search')
        ->middleware('permission:'.$path.'.index');

    Route::get($path.'/crear',[FormaHabitaciones::class,'create'])
        ->name($name.'.create')
        ->middleware('permission:'.$path.'.create');

    Route::post($path.'/store',[FormaHabitaciones::class,'store'])
        ->name($name.'.store')
        ->middleware('permission:'.$path.'.create');
    
    Route::get($path.'/edit/{id}',[FormaHabitaciones::class,'edit'])
        ->name($name.'.edit')
        ->middleware('permission:'.$path.'.edit');

    Route::post($path.'/update',[FormaHabitaciones::class,'update'])
        ->name($name.'.update')
        ->middleware('permission:'.$path.'.edit');

    Route::get($path.'/confirm/{id}',[FormaHabitaciones::class,'confirm'])
        ->name($name.'.confirm')
        ->middleware('permission:'.$path.'.delete');
    
    Route::post($path.'/delete',[FormaHabitaciones::class,'destroy'])
        ->name($name.'.delete')
        ->middleware('permission:'.$path.'.delete');
});
