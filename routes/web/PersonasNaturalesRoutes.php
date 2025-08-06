<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonasNaturalesController as Controller;#Usar controllador

Route::middleware(['auth'])->group(function(){
    $path = 'personas/naturales';#Ruta
    $name = 'personas_naturales';#Nombre de ruta

    Route::get($path,[Controller::class,'index'])
        ->name($name.'.index')
        ->middleware('permission:'.$path.'.index');

    Route::post($path,[Controller::class,'search'])
        ->name($name.'.search')
        ->middleware('permission:'.$path.'.index');

    Route::post($path.'/store',[Controller::class,'store'])
        ->name($name.'.store')
        ->middleware('permission:'.$path.'.create');

    Route::get($path.'/delete/{id}',[Controller::class,'confirm'])
        ->name($name.'.confirm')
        ->middleware('permission:'.$path.'.delete');

    Route::post($path.'/delete',[Controller::class,'destroy'])
        ->name($name.'.delete')
        ->middleware('permission:'.$path.'.delete');
});
