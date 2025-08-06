<?php

use App\Http\Controllers\PermissionController as permission;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    $path = 'permissions'; #Ruta
    $name = $path; #Nombre de ruta

    Route::get($path, [permission::class, 'index'])
        ->name($name . '.index')
        ->middleware("permission:" . $path . ".index");

    Route::get($path . '/api/list', [permission::class, 'index_api'])
        ->name($name . '.index_api')
        ->middleware("permission:" . $path . ".index");

    Route::post($path, [permission::class, 'search'])
        ->name($name . '.search')
        ->middleware("permission:" . $path . ".index");

    Route::get($path . '/crear', [permission::class, 'create'])
        ->name($name . '.create')
        ->middleware("permission:" . $path . ".create");

    Route::post($path . '/store', [permission::class, 'store'])
        ->name($name . '.store')
        ->middleware("permission:" . $path . ".create");

    Route::post($path . '/api/store', [permission::class, 'store_api'])
        ->name($name . '.store_api')
        ->middleware("permission:" . $path . ".create");

    Route::get($path . '/confirm/{id}', [permission::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware("permission:" . $path . ".delete");

    Route::post($path . '/delete/api', [permission::class, 'destroy_api'])
        ->name($name . '.destroy_api')
        ->middleware("permission:" . $path . ".delete");

    Route::post($path . '/delete', [permission::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware("permission:" . $path . ".delete");
});
