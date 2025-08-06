<?php

use App\Http\Controllers\CajasUsersController as CajasUsers; #Usar controllador
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    $path = 'cajas_users'; #Ruta
    $name = $path;        #Nombre de ruta

    Route::get($path, [CajasUsers::class, 'index'])
        ->name($name . '.index')
        ->middleware('permission:' . $path . '.index');

    Route::post($path, [CajasUsers::class, 'search'])
        ->name($name . '.search')
        ->middleware('permission:' . $path . '.index');
    Route::post($path, [CajasUsers::class, 'login'])
        ->name($name . '.login')
        ->middleware('permission:' . $path . '.login');
    Route::get($path . '/api/list', [CajasUsers::class, 'index_api'])
        ->name($name . '.index_api')
        ->middleware("permission:" . $path . ".index");
    Route::post($path . '/api/store', [CajasUsers::class, 'store_api'])
        ->name($name . '.store_api')
        ->middleware("permission:" . $path . ".create");

    Route::get($path . '/crear', [CajasUsers::class, 'create'])
        ->name($name . '.create')
        ->middleware('permission:' . $path . '.create');

    Route::post($path . '/store', [CajasUsers::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $path . '.create');

    Route::get($path . '/resetear/pin', [CajasUsers::class, 'resetearPin'])
        ->name($name . '.resetearPin')
        ->middleware('permission:' . $path . '.create');

    Route::get($path . '/show/{id}', [CajasUsers::class, 'show'])
        ->name($name . '.show')
        ->middleware('permission:' . $path . '.index');

    Route::post($path, [CajasUsers::class, 'search'])
        ->name($name . '.search')
        ->middleware('permission:' . $path . '.index');

    Route::get($path . '/edit/{id}', [CajasUsers::class, 'edit'])
        ->name($name . '.edit')
        ->middleware('permission:' . $path . '.edit');

    Route::post($path . '/update', [CajasUsers::class, 'update'])
        ->name($name . '.update')
        ->middleware('permission:' . $path . '.edit');

    Route::get($path . '/confirm/{id}', [CajasUsers::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware('permission:' . $path . '.delete');

    Route::post($path . '/delete', [CajasUsers::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware('permission:' . $path . '.delete');
    Route::post($path . '/delete/api', [CajasUsers::class, 'destroy_api'])
        ->name($name . '.destroy_api')
        ->middleware("permission:" . $path . ".delete");

    Route::get($path . '/status/{id}', [CajasUsers::class, 'status'])
        ->name($name . '.status')
        ->middleware('permission:' . $path . '.status');

    Route::post($path . '/pin', [CajasUsers::class, 'pin'])
        ->name($name . '.pin')
        ->middleware('permission:' . 'cajas.caja');

    Route::get($path . '/eliminar/caja/{id}', [CajasUsers::class, 'deleteCaja'])
        ->name($name . '.delete_caja')
        ->middleware('permission:' . $path . '.index');

    #----MOVIL----
    Route::post($path . '/app/pin', [CajasUsers::class, 'appPin'])
        ->name($name . '.appPin')
        ->middleware('permission:' . 'cajas.caja');
    #-------------
});
