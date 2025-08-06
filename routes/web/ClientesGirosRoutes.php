<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientesGirosController as ClientesGiros;#Usar controllador

Route::middleware(['auth'])->group(function(){
    $path = 'clientes_giros';#Ruta
    $name = $path;  #Nombre de ruta

    Route::get($path,[ClientesGiros::class,'index'])
        ->name($name.'.index')
        ->middleware('permission:'.$path.'.index');

    Route::post($path,[ClientesGiros::class,'search'])
        ->name($name.'.search')
        ->middleware('permission:'.$path.'.index');

    Route::get($path.'/crear',[ClientesGiros::class,'create'])
        ->name($name.'.create')
        ->middleware('permission:'.$path.'.create');

    Route::post($path.'/store',[ClientesGiros::class,'store'])
        ->name($name.'.store')
        ->middleware('permission:'.$path.'.create');

    Route::get($path.'/show/{id}',[ClientesGiros::class,'show'])
        ->name($name.'.show')
        ->middleware('permission:'.$path.'.index');
    
    Route::get($path.'/edit/{id}',[ClientesGiros::class,'edit'])
        ->name($name.'.edit')
        ->middleware('permission:'.$path.'.edit');

    Route::post($path.'/update',[ClientesGiros::class,'update'])
        ->name($name.'.update')
        ->middleware('permission:'.$path.'.edit');

    Route::get($path.'/confirm/{id}',[ClientesGiros::class,'confirm'])
        ->name($name.'.confirm')
        ->middleware('permission:'.$path.'.delete');
    
    Route::post($path.'/delete',[ClientesGiros::class,'destroy'])
        ->name($name.'.delete')
        ->middleware('permission:'.$path.'.delete');

    Route::get($path.'/status/{id}',[ClientesGiros::class,'status'])
        ->name($name.'.status')
        ->middleware('permission:'.$path.'.status');
});
