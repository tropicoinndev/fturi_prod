        <?php

        use Illuminate\Support\Facades\Route;

        use App\Http\Controllers\UsuariosController as Usuarios; #Usar controllador
        Route::middleware(['auth'])->group(function () {
            $ruta = 'users';
            $nombreRuta = $ruta;

            Route::get($ruta, [Usuarios::class, 'index'])
                ->name($nombreRuta . '.index')
                ->middleware('permission:' . $ruta . '.index');


            Route::post($ruta, [Usuarios::class, 'search'])
                ->name($nombreRuta . '.search')
                ->middleware('permission:' . $ruta . '.index');

            Route::get($ruta . '/api/list/users', [Usuarios::class, 'api_usuario'])
                ->name($nombreRuta . '.api_usuario')
                ->middleware('permission:' . $ruta . '.index');

            Route::get($ruta . '/api/list', [Usuarios::class, 'index_api'])
                ->name($nombreRuta . '.index_api')
                ->middleware('permission:' . $ruta . '.index');
            Route::get($ruta . '/menu/', [Usuarios::class, 'menu'])
                ->name($nombreRuta . '.menu')
                ->middleware('permission:' . $ruta . '.index');

            Route::post($ruta . '/api/store', [Usuarios::class, 'store_apiCaja'])
                ->name($nombreRuta . '.store_apiCaja')
                ->middleware('permission:' . $ruta . '.create');
            Route::post($ruta . '/cajas/list', [Usuarios::class, 'list_cajas'])
                ->name($nombreRuta . '.list_cajas')
                ->middleware('permission:' . $ruta . '.create');
            Route::post($ruta . '/mantenimientos/list', [Usuarios::class, 'list_tipoMantenimientos'])
                ->name($nombreRuta . '.list_tipoMantenimientos')
                ->middleware('permission:' . $ruta . '.create');
            Route::post($ruta . '/mantenimiento/store', [Usuarios::class, 'store_apiTipoMantenimientos'])
                ->name($nombreRuta . '.store_apiTipoMantenimientos')
                ->middleware('permission:' . $ruta . '.create');

            Route::get($ruta . '/crear', [Usuarios::class, 'create'])
                ->name($nombreRuta . '.create')
                ->middleware('permission:' . $ruta . '.create');

            Route::post($ruta . '/store', [Usuarios::class, 'store'])
                ->name($nombreRuta . '.store')
                ->middleware('permission:' . $ruta . '.create');

            Route::post($ruta . '/asignar/rol', [Usuarios::class, 'asignarRol'])
                ->name($nombreRuta . '.asignarRol')
                ->middleware('permission:' . $ruta . '.create');

            Route::post($ruta . '/desasignar/rol', [Usuarios::class, 'desasignar_Rol'])
                ->name($nombreRuta . '.desasignar_Rol')
                ->middleware('permission:' . $ruta . '.create');

            Route::get($ruta . '/show/{id}', [Usuarios::class, 'show'])
                ->name($nombreRuta . '.show')
                ->middleware('permission:' . $ruta . '.show');

            Route::get($ruta . '/edit/{id}', [Usuarios::class, 'edit'])
                ->name($nombreRuta . '.edit')
                ->middleware('permission:' . $ruta . '.edit');

            Route::post($ruta . '/update', [Usuarios::class, 'update'])
                ->name($nombreRuta . '.update')
                ->middleware('permission:' . $ruta . '.edit');


            Route::get($ruta . '/confirm/{id}', [Usuarios::class, 'confirm'])
                ->name($nombreRuta . '.confirm')
                ->middleware('permission:' . $ruta . '.delete');

            Route::get($ruta . '/change', [Usuarios::class, 'showPasswordForm'])
                ->name($nombreRuta . '.showPasswordForm');

            Route::post($ruta . '/password', [Usuarios::class, 'changePassword'])
                ->name($nombreRuta . '.changePassword')
                ->middleware('permission:' . $ruta . '.edit');
            Route::post($ruta . '/delete', [Usuarios::class, 'destroy'])
                ->name($nombreRuta . '.delete')
                ->middleware('permission:' . $ruta . '.delete');

            Route::post($ruta . '/delete/api', [Usuarios::class, 'destroy_api'])
                ->name($nombreRuta . '.destroy_api')
                ->middleware("permission:" . $ruta . ".delete");
            Route::get($ruta . '/restablecer/{id}', [Usuarios::class, 'restablecerPassword'])
                ->name($nombreRuta . '.restablecer')
                ->middleware('permission:' . $ruta . '.restablecer');

            Route::post($ruta . '/api/token/store', [Usuarios::class, 'setToken'])
                ->name($nombreRuta . '.api_usuario_token')
                ->middleware('permission:' . $ruta . '.admin');
        });
