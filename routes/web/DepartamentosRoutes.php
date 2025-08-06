<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartamentosController as Departamentos;#Usar controllador

Route::middleware(['auth'])->group(function(){
    $path = 'departamentos';#Ruta
    $name = $path;          #Nombre de ruta

    Route::get($path,[Departamentos::class,'index'])
        ->name($name.'.index')
        ->middleware('permission:'.$path.'.index');

    Route::post($path,[Departamentos::class,'search'])
        ->name($name.'.search')
        ->middleware('permission:'.$path.'.index');

    Route::get($path.'/crear',[Departamentos::class,'create'])
        ->name($name.'.create')
        ->middleware('permission:'.$path.'.create');

    Route::post($path.'/store',[Departamentos::class,'store'])
        ->name($name.'.store')
        ->middleware('permission:'.$path.'.create');
    
    Route::get($path.'/edit/{id}',[Departamentos::class,'edit'])
        ->name($name.'.edit')
        ->middleware('permission:'.$path.'.edit');

    Route::post($path.'/update',[Departamentos::class,'update'])
        ->name($name.'.update')
        ->middleware('permission:'.$path.'.edit');

    Route::get($path.'/confirm/{id}',[Departamentos::class,'confirm'])
        ->name($name.'.confirm')
        ->middleware('permission:'.$path.'.delete');
    
    Route::post($path.'/delete',[Departamentos::class,'destroy'])
        ->name($name.'.delete')
        ->middleware('permission:'.$path.'.delete');

    Route::post($path.'/api/get/departamentos',[Departamentos::class,'apiGetDepartamentos'])
        ->name($name.'.apiGetDepartamentos')
        ->middleware('permission:'.$path.'.index');
});
