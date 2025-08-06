<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController as role;

Route::middleware(['auth', 'permission:admin'])->group(function () {
    $path = 'roles'; #Ruta
    $name = $path; #Nombre de ruta

    Route::get($path, [role::class, 'index'])
        ->name($name . '.index')
        ->middleware("permission:" . $path . ".index");

    Route::post($path . '/api/search', [role::class, 'apiSearch'])
        ->name($name . '.api_search')
        ->middleware("permission:" . $path . ".index");

    Route::post($path, [role::class, 'search'])
        ->name($name . '.search')
        ->middleware("permission:" . $path . ".index");

    Route::get($path . '/crear', [role::class, 'create'])
        ->name($name . '.create')
        ->middleware("permission:" . $path . ".create");

    Route::post($path . '/store', [role::class, 'store'])
        ->name($name . '.store')
        ->middleware("permission:" . $path . ".create");

    Route::post($path . '/store/permission', [role::class, 'store_permission'])
        ->name($name . '.permission_create')
        ->middleware("permission:" . $path . ".create");

    Route::post($path . '/permission/list', [role::class, 'list_permission'])
        ->name($name . '.permission_list')
        ->middleware("permission:" . $path . ".create");

    Route::get($path . '/show/{id}', [role::class, 'show'])
        ->name($name . '.show')
        ->middleware("permission:" . $path . ".index");

    Route::get($path . '/usuarios/role/{id}', [role::class, 'usuarios'])
        ->name($name . '.usuarios')
        ->middleware("permission:" . $path . ".create");

    Route::post($path . '/api/usuarios/role/', [role::class, 'getUsuariosApi'])
        ->name($name . '.usuarios_api')
        ->middleware("permission:" . $path . ".create");

    Route::post($path . '/api/set/usuarios/role/', [role::class, 'setUsuariosApi'])
        ->name($name . '.set_usuarios_api')
        ->middleware("permission:" . $path . ".create");


    Route::get($path . '/edit/{id}', [role::class, 'edit'])
        ->name($name . '.edit')
        ->middleware("permission:" . $path . ".edit");

    Route::post($path . '/update', [role::class, 'update'])
        ->name($name . '.update')
        ->middleware("permission:" . $path . ".edit");

    Route::get($path . '/confirm/{id}', [role::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware("permission:" . $path . ".delete");

    Route::post($path . '/delete', [role::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware("permission:" . $path . ".delete");
});
