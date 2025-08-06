<?php



use App\Http\Controllers\ClientesController as Clientes; #Usar controllador

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    $ruta = 'clientes';
    $nombreRuta = $ruta;

    Route::get($ruta, [Clientes::class, 'index'])
        ->name($nombreRuta . '.index')
        ->middleware('permission:' . $ruta . '.index');

    Route::post($ruta, [Clientes::class, 'search'])
        ->name($nombreRuta . '.search')
        ->middleware('permission:' . $ruta . '.index');

    Route::post($ruta . '/api/search/Clientes2', [Clientes::class, 'apiSearchClientes2'])
        ->name($nombreRuta . '.apiSearchClientes2')
        ->middleware('permission:' . $ruta . '.index');

    Route::post($ruta . '/api/get/search/clienes', [Clientes::class, 'getClientes'])
        ->name($nombreRuta . '.apiGetClientes')
        ->middleware('permission:' . $ruta . '.index');

    Route::get($ruta . '/crear', [Clientes::class, 'create'])
        ->name($nombreRuta . '.create')
        ->middleware("permission:" . $ruta . ".create");

    Route::post($ruta . '/store', [Clientes::class, 'store'])
        ->name($nombreRuta . '.store')
        ->middleware('permission:' . $ruta . '.create');

    Route::get($ruta . '/show/{id}', [Clientes::class, 'show'])
        ->name($nombreRuta . '.show')
        ->middleware('permission:' . $ruta . '.index');

    Route::get($ruta . '/edit/{id}', [Clientes::class, 'edit'])
        ->name($nombreRuta . '.edit')
        ->middleware("permission:" . $ruta . ".edit");


    Route::post($ruta . '/update', [Clientes::class, 'update'])
        ->name($nombreRuta . '.update')
        ->middleware("permission:" . $ruta . ".edit");

    Route::get($ruta . '/delete/{id}', [Clientes::class, 'confirm'])
        ->name($nombreRuta . '.confirm')
        ->middleware('permission:' . $ruta . '.delete');

    Route::post($ruta . '/delete', [Clientes::class, 'destroy'])
        ->name($nombreRuta . '.delete')
        ->middleware("permission:" . $ruta . ".delete");

    Route::get($ruta . '/api/search', [Clientes::class, 'apiSearch'])
        ->name($nombreRuta . '.apiSearch')
        ->middleware('permission:' . $ruta . '.index');


    Route::get($ruta . '/status/{id}', [clientes::class, 'status'])
        ->name($nombreRuta . '.state')
        ->middleware('permission:' . $ruta . '.status');
    Route::get($ruta . '/credito/{id}', [clientes::class, 'credito'])
        ->name($nombreRuta . '.credito')
        ->middleware('permission:' . $ruta . '.credito');

    Route::get($ruta . '/retencion/{id}', [clientes::class, 'retencion'])
        ->name($nombreRuta . '.retencion')
        ->middleware('permission:' . $ruta . '.retencion');

    Route::get($ruta . '/ccf/{id}', [clientes::class, 'ccf'])
        ->name($nombreRuta . '.ccf')
        ->middleware('permission:' . $ruta . '.ccf');

    Route::post($ruta . '/api/search/', [clientes::class, 'getClientes'])
        ->name($nombreRuta . '.api_search')
        ->middleware('permission:' . $ruta . '.index');

    Route::post($ruta . '/api/search/list', [clientes::class, 'getClientesList'])
        ->name($nombreRuta . '.api_search_list')
        ->middleware('permission:' . $ruta . '.index');

    Route::post($ruta . '/api/search/list/naturales', [clientes::class, 'getClientesListNaturales'])
        ->name($nombreRuta . '.api_search_list_naturales')
        ->middleware('permission:' . $ruta . '.index');

    Route::post($ruta . '/editar/cuentas', [clientes::class, 'cuentas'])
        ->name($nombreRuta . '.cuentas')
        ->middleware('permission:' . $ruta . '.cuentas');
    Route::get($ruta . '/descuento/{id}', [clientes::class, 'asignarDescuento'])
        ->name($nombreRuta . '.descuento')
        ->middleware('permission:' . $ruta . '.descuento');

    Route::get($ruta . '/sucursales/{id}', [clientes::class, 'sucursalJuridico'])
        ->name($nombreRuta . '.juridico_sucursal')
        ->middleware('permission:' . $ruta . '.sucursal');

    Route::post($ruta . '/api/duplicar/clientes', [clientes::class, 'clienteSucursal'])
        ->name($nombreRuta . '.cliente_sucursal')
        ->middleware('permission:' . $ruta . '.sucursal');

    Route::post($ruta . '/api/actividad/economica', [clientes::class, 'getActividades'])
        ->name($nombreRuta . '.get_actividades')
        ->middleware('permission:' . $ruta . '.index');

    Route::get($ruta . '/nivel/cautela', [clientes::class, 'editarNivelCautela'])
        ->name($nombreRuta . '.nivel_cautela')
        ->middleware('permission:' . $ruta . '.nivel_cautela');

    Route::post($ruta . '/nivel/cautela', [clientes::class, 'searchNivelCautela'])
        ->name($nombreRuta . '.nivel_cautela_search')
        ->middleware('permission:' . $ruta . '.nivel_cautela');

    Route::post($ruta . '/nivel/cautela/update', [clientes::class, 'updateNivelCautela'])
        ->name($nombreRuta . '.nivel_cautela_update')
        ->middleware('permission:' . $ruta . '.nivel_cautela');

    Route::post($ruta . '/notificacion/update', [clientes::class, 'updateNotificacion'])
        ->name($nombreRuta . '.notificacion_update')
        ->middleware('permission:' . $ruta . '.nivel_cautela');


    #Eliminar periodo del crédito
    Route::get($ruta . '/periodo/credito/delete/{id}', [Clientes::class, 'confirmPeriodosCreditos'])
        ->name($nombreRuta . '.periodos_creditos_confirm')
        ->middleware('permission:' . $ruta . '.delete');

    Route::post($ruta . '/periodo/credito/delete', [Clientes::class, 'deletePeriodosCreditos'])
        ->name($nombreRuta . '.deletePeriodosCreditos')
        ->middleware("permission:" . $ruta . ".delete");

    Route::post($ruta . '/periodo/credito/store', [Clientes::class, 'periodoCreditoStore'])
        ->name($nombreRuta . '.periodoCreditoStore')
        ->middleware('permission:' . $ruta . '.create');


    Route::get($ruta . '/empleados/edicion', [clientes::class, 'editarEmpleado'])
        ->name($nombreRuta . '.empleado_edit')
        ->middleware('permission:' . $ruta . '.empleado');

    Route::post($ruta . '/empleados/edicion', [clientes::class, 'searchEmpleado'])
        ->name($nombreRuta . '.empleado_search')
        ->middleware('permission:' . $ruta . '.empleado');

    Route::post($ruta . '/empleado/update', [clientes::class, 'updateEmpleado'])
        ->name($nombreRuta . '.empleado_update')
        ->middleware('permission:' . $ruta . '.empleado');

    Route::post($ruta . '/periodo/credito/update', [Clientes::class, 'periodoCreditoUpdate'])
        ->name($nombreRuta . '.periodoCreditoUpdate')
        ->middleware('permission:' . $ruta . '.credito');

    Route::get($ruta . '/alertas/efectivo', [clientes::class, 'alertasEfectivo'])
        ->name($nombreRuta . '.alertasEfectivo')
        ->middleware('permission:' . $ruta . '.alertas');

    Route::post($ruta . '/alertas/efectivo', [clientes::class, 'alertasEfectivo'])
        ->name($nombreRuta . '.alertasEfectivoSearch')
        ->middleware('permission:' . $ruta . '.alertas');

    Route::get($ruta . '/alertas/bancos', [clientes::class, 'alertasBanco'])
        ->name($nombreRuta . '.alertasBanco')
        ->middleware('permission:' . $ruta . '.alertas');

    Route::post($ruta . '/alertas/bancos', [clientes::class, 'alertasBanco'])
        ->name($nombreRuta . '.alertasBancoSearch')
        ->middleware('permission:' . $ruta . '.alertas');

    Route::get($ruta . '/comprobantes/{id}/{mes}', [clientes::class, 'comprobantes'])
        ->name($nombreRuta . '.comprobantes_mes')
        ->middleware('permission:' . $ruta . '.comprobantes');

    Route::get($ruta . '/comprobantes/', [clientes::class, 'comprobantes'])
        ->name($nombreRuta . '.comprobantes')
        ->middleware('permission:' . $ruta . '.comprobantes');


    Route::get($ruta . '/empleados', [clientes::class, 'empleadosPanel'])
        ->name($nombreRuta . '.empleados')
        ->middleware('permission:' . $ruta . '.empleado');

    Route::post($ruta . '/empleados', [clientes::class, 'empleadosPanel'])
        ->name($nombreRuta . '.empleados_search')
        ->middleware('permission:' . $ruta . '.empleado');

    Route::get($ruta . '/comprobantes/empleados/{id}/{mes}', [clientes::class, 'comprobantesEmpleados'])
        ->name($nombreRuta . '.comprobantes_empleado_mes')
        ->middleware('permission:' . $ruta . '.empleado');

    Route::get($ruta . '/comprobantes/empleados/', [clientes::class, 'comprobantesEmpleados'])
        ->name($nombreRuta . '.comprobantes_empleado')
        ->middleware('permission:' . $ruta . '.empleado');

    Route::get($ruta . '/comprobantes/', [clientes::class, 'comprobantesEmpleados'])
        ->name($nombreRuta . '.comprobantes')
        ->middleware('permission:' . $ruta . '.empleado');

    Route::post($ruta . '/update/actividades/economicas', [clientes::class, 'actividadEconomicaUpdate'])
        ->name($nombreRuta . '.actividadEconomicaUpdate');

    Route::get($ruta . '/accionista/{id}', [clientes::class, 'accionista'])
        ->name($nombreRuta . '.accionista')
        ->middleware('permission:' . $ruta . '.accionista');

    Route::post($ruta . '/clientes/accionista/update', [clientes::class, 'updateAccionista'])
        ->name($nombreRuta . '.accionista_update')
        ->middleware('permission:' . $ruta . '.empleado');

    Route::post($ruta . '/api/get/search/clientes/naturales', [Clientes::class, 'getPersonasNaturales'])
        ->name($nombreRuta . '.getPersonasNaturales')
        ->middleware('permission:' . $ruta . '.index');

    #Reporte de crédito a empleados
    Route::get($ruta . '/reporte/credito/empleados', [Clientes::class, 'reporteCreditoEmpleadosForm'])
        ->name($nombreRuta . '.reporteCreditoEmpleadosForm')
        ->middleware('permission:' . $ruta . '.credito');

    Route::post($ruta . '/reporte/credito/empleados/acciones', [Clientes::class, 'reporteCreditoEmpleadosAcciones'])
        ->name($nombreRuta . '.reporteCreditoEmpleadosAcciones')
        ->middleware('permission:' . $ruta . '.credito');
});

Route::middleware(['auth', 'permission:clientes.credito'])->group(function () {
    $ruta = 'clientes';
    $name = $ruta;

    Route::get($ruta . '/panel/creditos', [clientes::class, 'panel'])
        ->name($name . '.panel.creditos');

    Route::get($ruta . '/reportes/creditos', [clientes::class, 'reporteCredito'])
        ->name($name . '.reporte.creditos');

    Route::post($ruta . '/reportes/creditos/acciones', [clientes::class, 'reporteCreditoAcciones'])
        ->name($name . '.reporte.creditosAcciones');

    Route::get($ruta . '/alertas/creditos', [clientes::class, 'alertasCredito'])
        ->name($name . '.reporte.alertasCredito');

    Route::get($ruta . '/alertas/configuraciones', [clientes::class, 'alertasClientes'])
        ->name($name . '.reporte.alertasClientes');

    Route::get($ruta . '/alertas/configuraciones/periodos', [clientes::class, 'alertasClientesPeriodos'])
        ->name($name . '.reporte.alertasClientesPeriodos');

    Route::get($ruta . '/alertas/configuraciones/municipios', [clientes::class, 'alertasClientesMunicipios'])
        ->name($name . '.reporte.alertasClientesMunicipios');

    Route::get($ruta . '/alertas/configuraciones/municipios/pais', [clientes::class, 'alertasClientesPaisMunicipios'])
        ->name($name . '.reporte.alertasClientesMunicipiosPais');

    Route::get($ruta . '/alertas/configuraciones/actividades/economicas', [clientes::class, 'alertasClientesActividades'])
        ->name($name . '.reporte.alertasClientesActividades');

    Route::post($ruta . '/api/municipios/update', [clientes::class, 'apiClientesMunicipios'])
        ->name($name . '.api_municipios_update');

    Route::post($ruta . '/api/pais/update', [clientes::class, 'apiClientesPais'])
        ->name($name . '.api_pais_update');

    Route::post($ruta . '/api/actividad/economica/update', [clientes::class, 'apiClientesActividad'])
        ->name($name . '.api_actividad_update');

    Route::get($ruta . '/alertas/configuraciones/identificaciones', [clientes::class, 'alertasClientesIdentificaciones'])
        ->name($name . '.reporte.alertasClientesIdentificaciones');

    Route::post($ruta . '/api/identificaciones/update', [clientes::class, 'apiClientesIdentificaciones'])
        ->name($name . '.api_identificacion_update');

    Route::get($ruta . '/alertas/configuraciones/identificaciones/extranjeros', [clientes::class, 'alertasClientesIdentificacionesExtranjeros'])
        ->name($name . '.reporte.alertasClientesIdentificacionesExtranjeros');
});
