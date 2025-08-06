<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Illuminate\Support\Facades\Crypt;

#https: //github.com/diglactic/laravel-breadcrumbs
//Home > Blog

//***INICIO***
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Inicio', route('home'));
});

//***ROLES***
Breadcrumbs::for('roles', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('Roles', route($th['table'] . '.index'));
});
Breadcrumbs::for('roles.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('roles', $th);
    $trail->push('Crear rol', route($th['table'] . '.create'));
});
Breadcrumbs::for('roles.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('roles', $th);
    $trail->push('Eliminar rol', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('roles.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('roles', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('roles.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('roles', $th);
    $trail->push('Role: ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('roles.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('roles', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});

//**IDENTIFICACIONES**
Breadcrumbs::for('identificaciones', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('Identificaciones', route($th['table'] . '.index'));
});
Breadcrumbs::for('identificaciones.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('identificaciones', $th);
    $trail->push('Crear identificacion', route($th['table'] . '.create'));
});
Breadcrumbs::for('identificaciones.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('identificaciones', $th);
    $trail->push('Eliminar identificacion', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('identificaciones.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('identificaciones', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('identificaciones.destroy', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('identificaciones', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.destroy', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('identificaciones.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('identificaciones', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('identificaciones.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('identificaciones', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});


//**CONTACTOS**
Breadcrumbs::for('contactos', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('Contactos', route($th['table'] . '.index'));
});
Breadcrumbs::for('contactos.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('contactos', $th);
    $trail->push('Crear contacto', route($th['table'] . '.create'));
});
Breadcrumbs::for('contactos.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('contactos', $th);
    $trail->push('Eliminar', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('contactos.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('contactos', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('contactos.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('contactos', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('contactos.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('contactos', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});

//**clientes  */
Breadcrumbs::for('clientes', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('Clientes', route($th['table'] . '.index'));
});
Breadcrumbs::for('clientes.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('clientes', $th);
    $trail->push('Crear cliente', route($th['table'] . '.create'));
});
Breadcrumbs::for('clientes.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('clientes', $th);
    $trail->push('Eliminar', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('clientes.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('clientes', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('clientes.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('clientes', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('clientes.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('clientes', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});

//**paises  */
Breadcrumbs::for('paises', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('Paises', route($th['table'] . '.index'));
});
Breadcrumbs::for('paises.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('paises', $th);
    $trail->push('Crear pais', route($th['table'] . '.create'));
});
Breadcrumbs::for('paises.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('paises', $th);
    $trail->push('Eliminar', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('paises.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('paises', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('paises.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('paises', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('paises.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('paises', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});



//***GIROS***
Breadcrumbs::for('giros', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('Giros', route($th['table'] . '.index'));
});
Breadcrumbs::for('giros.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home', $th);
    $trail->push('Crear giro', route($th['table'] . '.create'));
});
Breadcrumbs::for('giros.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('giros', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('giros.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('giros', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('giros.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('giros', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('giros.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('giros', $th);
    $trail->push('Eliminar giro', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});



//***FORMA DE PAGOS***
Breadcrumbs::for('forma_pagos', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('Forma de pagos', route($th['table'] . '.index'));
});
Breadcrumbs::for('forma_pagos.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home', $th);
    $trail->push('Crear forma de pago', route($th['table'] . '.create'));
});
Breadcrumbs::for('forma_pagos.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('forma_pagos', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('forma_pagos.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('forma_pagos', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('forma_pagos.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('forma_pagos', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('forma_pagos.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('forma_pagos', $th);
    $trail->push('Eliminar forma de pago', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});



//***TIPO DE COMPROBANTES***
Breadcrumbs::for('tipo_comprobantes', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('Tipo de comprobantes', route($th['table'] . '.index'));
});
Breadcrumbs::for('tipo_comprobantes.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home', $th);
    $trail->push('Crear tipo de comprobante', route($th['table'] . '.create'));
});
Breadcrumbs::for('tipo_comprobantes.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_comprobantes', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('tipo_comprobantes.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_comprobantes', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('tipo_comprobantes.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_comprobantes', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('tipo_comprobantes.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_comprobantes', $th);
    $trail->push('Eliminar tipo de comprobante', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});

//***TIPO DE REGISTROS***
Breadcrumbs::for('tipo_registros', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('Tipo de registros', route($th['table'] . '.index'));
});
Breadcrumbs::for('tipo_registros.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home', $th);
    $trail->push('Crear tipo de registro', route($th['table'] . '.create'));
});
Breadcrumbs::for('tipo_registros.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_registros', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('tipo_registros.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_registros', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('tipo_registros.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_registros', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('tipo_registros.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_registros', $th);
    $trail->push('Eliminar tipo de registro', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});



//***ANULACIONES***
Breadcrumbs::for('anulaciones', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('Anulaciones', route($th['table'] . '.index'));
});
Breadcrumbs::for('anulaciones.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home', $th);
    $trail->push('Crear anulacion', route($th['table'] . '.create'));
});
Breadcrumbs::for('anulaciones.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('anulaciones', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('anulaciones.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('anulaciones', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('anulaciones.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('anulaciones', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('anulaciones.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('anulaciones', $th);
    $trail->push('Eliminar anulacion', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});



//***OPCION TURNOS***
Breadcrumbs::for('opcion_turnos', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('Opcion turnos', route($th['table'] . '.index'));
});
Breadcrumbs::for('opcion_turnos.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home', $th);
    $trail->push('Crear opcion de turno', route($th['table'] . '.create'));
});
Breadcrumbs::for('opcion_turnos.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('opcion_turnos', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('opcion_turnos.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('opcion_turnos', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('opcion_turnos.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('opcion_turnos', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('opcion_turnos.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('opcion_turnos', $th);
    $trail->push('Eliminar opcion de turno', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});



//***TIPO DE SERVICIOS***
Breadcrumbs::for('tipo_servicios', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('Tipo servicios', route($th['table'] . '.index'));
});
Breadcrumbs::for('tipo_servicios.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home', $th);
    $trail->push('Crear tipo de servicio', route($th['table'] . '.create'));
});
Breadcrumbs::for('tipo_servicios.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_servicios', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('tipo_servicios.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_servicios', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('tipo_servicios.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_servicios', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('tipo_servicios.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_servicios', $th);
    $trail->push('Eliminar tipo de servicio', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});



//***DESCUENTOS***
Breadcrumbs::for('descuentos', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('Descuentos', route($th['table'] . '.index'));
});
Breadcrumbs::for('descuentos.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home', $th);
    $trail->push('Crear descuento', route($th['table'] . '.create'));
});
Breadcrumbs::for('descuentos.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('descuentos', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('descuentos.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('descuentos', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('descuentos.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('descuentos', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('descuentos.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('descuentos', $th);
    $trail->push('Eliminar descuento', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});



//***SERVICIOS***
Breadcrumbs::for('servicios', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('Servicios', route($th['table'] . '.index'));
});
Breadcrumbs::for('servicios.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('Crear servicio', route($th['table'] . '.create'));
});
Breadcrumbs::for('servicios.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('servicios', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('servicios.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('servicios', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('servicios.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('servicios', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('servicios.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('servicios', $th);
    $trail->push('Eliminar servicio', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});
//***departamentos***
Breadcrumbs::for('departamentos', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('Departamentos', route($th['table'] . '.index'));
});
Breadcrumbs::for('departamentos.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('Crear departamento', route($th['table'] . '.create'));
});
Breadcrumbs::for('departamentos.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('departamentos', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('departamentos.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('departamentos', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('departamentos.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('departamentos', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('departamentos.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('departamentos', $th);
    $trail->push('Eliminar departamento', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});
//***municipios***
Breadcrumbs::for('municipios', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('Municipios', route($th['table'] . '.index'));
});
Breadcrumbs::for('municipios.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('Crear municipio', route($th['table'] . '.create'));
});
Breadcrumbs::for('municipios.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('municipios', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('municipios.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('municipios', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('municipios.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('municipios', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('municipios.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('municipios', $th);
    $trail->push('Eliminar municipio', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});


//***SUCURSALES***
Breadcrumbs::for('sucursales', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('Sucursales', route($th['table'] . '.index'));
});
Breadcrumbs::for('sucursales.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('sucursales', route('sucursales.index'));
    $trail->push('Crear sucursal', route($th['table'] . '.create'));
});
Breadcrumbs::for('sucursales.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('sucursales', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('sucursales.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('sucursales', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('sucursales.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('sucursales', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('sucursales.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('sucursales', $th);
    $trail->push('Eliminar sucursal', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});



//***CAJAS***
Breadcrumbs::for('cajas', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('cajas', route($th['table'] . '.index'));
});
Breadcrumbs::for('cajas.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('Crear caja', route($th['table'] . '.create'));
});
Breadcrumbs::for('cajas.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('cajas', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('cajas.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('cajas', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('cajas.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('cajas', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('cajas.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('cajas', $th);
    $trail->push('Eliminar caja', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});



//***CAJA TURNOS***
Breadcrumbs::for('caja_turnos', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('Caja turnos', route($th['table'] . '.index'));
});
Breadcrumbs::for('caja_turnos.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('Crear caja', route($th['table'] . '.create'));
});
Breadcrumbs::for('caja_turnos.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('caja_turnos', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('caja_turnos.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('caja_turnos', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('caja_turnos.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('caja_turnos', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('caja_turnos.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('caja_turnos', $th);
    $trail->push('Eliminar caja', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});

//**TURNOS */
Breadcrumbs::for('turnos', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push(' turnos', route($th['table'] . '.index'));
});
Breadcrumbs::for('turnos.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('Crear turno', route($th['table'] . '.create'));
});
Breadcrumbs::for('turnos.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('turnos', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('turnos.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('turnos', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('turnos.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('turnos', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('turnos.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('turnos', $th);
    $trail->push('Eliminar turno', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});


//***ORDENES***
Breadcrumbs::for('ordenes', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('Ordenes', route($th['table'] . '.index'));
});
Breadcrumbs::for('ordenes.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('Crear orden', route($th['table'] . '.create'));
});
Breadcrumbs::for('ordenes.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('ordenes', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('ordenes.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('ordenes', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('ordenes.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('ordenes', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('ordenes.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('ordenes', $th);
    $trail->push('Anular orden', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});



//***DETALLE DE ORDENES***
Breadcrumbs::for('detalle_ordenes', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('home');
    $trail->push('Detalle ordenes', route($th['table'] . '.index', ['detalleOrdenesId' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('detalle_ordenes.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('Crear detalle orden', route($th['table'] . '.create'));
});
Breadcrumbs::for('detalle_ordenes.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('detalle_ordenes', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('detalle_ordenes.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('detalle_ordenes', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('detalle_ordenes.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('detalle_ordenes', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('detalle_ordenes.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('detalle_ordenes', $th);
    $trail->push('Eliminar detalle de orden', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});



//***CLIENTES IDENTIFICACIONES***
Breadcrumbs::for('clientes_identificaciones', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('Clientes identificaciones', route($th['table'] . '.index'));
});
Breadcrumbs::for('clientes_identificaciones.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('Crear cliente identificacion', route($th['table'] . '.create'));
});
Breadcrumbs::for('clientes_identificaciones.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('clientes_identificaciones', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('clientes_identificaciones.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('clientes_identificaciones', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('clientes_identificaciones.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('clientes_identificaciones', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('clientes_identificaciones.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('clientes_identificaciones', $th);
    $trail->push('Eliminar cliente identificacion', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});



//***CLIENTES CONTACTOS***
Breadcrumbs::for('clientes_contactos', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('Clientes contactos', route($th['table'] . '.index'));
});
Breadcrumbs::for('clientes_contactos.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('Crear cliente contacto', route($th['table'] . '.create'));
});
Breadcrumbs::for('clientes_contactos.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('clientes_contactos', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('clientes_contactos.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('clientes_contactos', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('clientes_contactos.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('clientes_contactos', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('clientes_contactos.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('clientes_contactos', $th);
    $trail->push('Eliminar cliente contacto', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});



//***CLIENTES GIROS***
Breadcrumbs::for('clientes_giros', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('Clientes giros', route($th['table'] . '.index'));
});
Breadcrumbs::for('clientes_giros.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('Crear clientes giros', route($th['table'] . '.create'));
});
Breadcrumbs::for('clientes_giros.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('clientes_giros', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('clientes_giros.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('clientes_giros', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('clientes_giros.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('clientes_giros', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('clientes_giros.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('clientes_giros', $th);
    $trail->push('Eliminar clientes giros', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});



//***CAJAS USUARIO***
Breadcrumbs::for('cajas_users', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('Cajas users', route($th['table'] . '.index'));
});
Breadcrumbs::for('cajas_users.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('Crear cajas users', route($th['table'] . '.create'));
});
Breadcrumbs::for('cajas_users.login', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('Login cajas users', route($th['table'] . '.login'));
});
Breadcrumbs::for('cajas_users.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('cajas_users', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('cajas_users.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('cajas_users', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('cajas_users.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('cajas_users', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('cajas_users.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('cajas_users', $th);
    $trail->push('Eliminar cajas users', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});
//***TIPO DE MANTENIMIENTOS USUARIOS***
Breadcrumbs::for('tipo_mantenimiento_users', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('Tipo mantenimiento users', route($th['table'] . '.index'));
});

Breadcrumbs::for('tipo_mantenimiento_users.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_mantenimiento_users', $th);
    $trail->push('Eliminar mantenimientos users', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});


//***CORRELATIVOS***
Breadcrumbs::for('correlativos', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('Correlativos', route($th['table'] . '.index'));
});
Breadcrumbs::for('correlativos.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('Crear correlativos', route($th['table'] . '.create'));
});
Breadcrumbs::for('correlativos.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('correlativos', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('correlativos.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('correlativos', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('correlativos.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('correlativos', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('correlativos.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('correlativos', $th);
    $trail->push('Eliminar correlativos', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});



//***COMPROBANTES***
Breadcrumbs::for('comprobantes', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('Comprobantes', route($th['table'] . '.index'));
});
Breadcrumbs::for('comprobantes.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('Crear comprobantes', route($th['table'] . '.create'));
});
Breadcrumbs::for('comprobantes.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('comprobantes', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('comprobantes.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('comprobantes', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('comprobantes.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('comprobantes', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('comprobantes.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('comprobantes', $th);
    $trail->push('Eliminar comprobantes', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});



//**USUARIOS */
Breadcrumbs::for('users', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('users', route($th['table'] . '.index'));
});
Breadcrumbs::for('users.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('Crear users', route($th['table'] . '.create'));
});
Breadcrumbs::for('users.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('users', $th);
    $trail->push('Detalles ', route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('users.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('users', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('users.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('users', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('users.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('users', $th);
    $trail->push('Eliminar users', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});

/*CATEGORIAS */
Breadcrumbs::for('categorias', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('categorias', route($th['table'] . '.index'));
});
Breadcrumbs::for('categorias.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('Crear categorias', route($th['table'] . '.create'));
});
Breadcrumbs::for('categorias.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('categorias', $th);
    $trail->push('Detalles ', route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('categorias.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('categorias', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('categorias.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('categorias', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('categorias.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('categorias', $th);
    $trail->push('Eliminar categorias', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});
//**CATEGORIAS PRECIOS */
Breadcrumbs::for('categorias_precios', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('categorias_precios', route($th['table'] . '.index'));
});
Breadcrumbs::for('categorias_precios.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('Crear categorias precios', route($th['table'] . '.create'));
});
Breadcrumbs::for('categorias_precios.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('categorias_precios', $th);
    $trail->push('Detalles ', route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('categorias_precios.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('categorias_precios', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('categorias_precios.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('categorias_precios', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('categorias_precios.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('categorias_precios', $th);
    $trail->push('Eliminar categorias precios', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});
//**PRODUCTOS */
Breadcrumbs::for('productos', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('productos', route($th['table'] . '.index'));
});
Breadcrumbs::for('productos.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('productos', route('productos.index'));
    $trail->push('Crear productos', route($th['table'] . '.create'));
});
Breadcrumbs::for('productos.detalle', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('productos', $th);
    $trail->push('Detalles ', route($th['table'] . '.detalleProducto', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('productos.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('productos', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('productos.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('productos', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('productos.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('productos', $th);
    $trail->push('Eliminar producto', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});
//**DETALLE PRODUCTOS */
Breadcrumbs::for('detalle_productos', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('detalle_productos', route($th['table'] . '.index'));
});
Breadcrumbs::for('detalle_productos.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('Crear detalle_productos', route($th['table'] . '.create'));
});
Breadcrumbs::for('detalle_productos.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('detalle_productos', $th);
    $trail->push('Detalles ', route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('detalle_productos.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('detalle_productos', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('detalle_productos.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('detalle_productos', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('detalle_productos.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('detalle_productos', $th);
    $trail->push('Eliminar producto', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});
/**PRECIOS */
Breadcrumbs::for('precios', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('precios', route($th['table'] . '.index'));
});
Breadcrumbs::for('precios.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('precios', route('precios.index'));
    $trail->push('Crear precios', route($th['table'] . '.create'));
});
Breadcrumbs::for('precios.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('precios', $th);
    $trail->push('Detalles ', route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('precios.detallePrecio', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('precios', $th);
    $trail->push('Detalles ', route($th['table'] . '.detallePrecio', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('precios.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('precios', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('precios.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('precios', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('precios.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('precios', $th);
    $trail->push('Eliminar producto', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});
//***TIPO DE PAGOS***

Breadcrumbs::for('tipo_pagos', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('tipo de pagos', route($th['table'] . '.index'));
});
Breadcrumbs::for('tipo_pagos.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home', $th);
    $trail->push('Crear tipo de pago', route($th['table'] . '.create'));
});
Breadcrumbs::for('tipo_pagos.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_pagos', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('tipo_pagos.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_pagos', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('tipo_pagos.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_pagos', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('tipo_pagos.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_pagos', $th);
    $trail->push('Eliminar tipo de pago', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});

//***BODEGAS***
Breadcrumbs::for('bodegas', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('bodegas', route($th['table'] . '.index'));
});
Breadcrumbs::for('bodegas.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('Crear bodegas', route($th['table'] . '.create'));
});
Breadcrumbs::for('bodegas.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('bodegas', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('bodegas.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('bodegas', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('bodegas.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('bodegas', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('bodegas.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('bodegas', $th);
    $trail->push('Eliminar bodegas', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});

//***PROVEEDORES***
Breadcrumbs::for('proveedores', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('proveedor', route($th['table'] . '.index'));
});
Breadcrumbs::for('proveedores.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home', $th);
    $trail->push('proveedores', route('proveedores.index'));
    $trail->push('Crear proveedor', route($th['table'] . '.create'));
});
Breadcrumbs::for('proveedores.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('proveedores', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('proveedores.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('proveedores', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('proveedores.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('proveedores', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('proveedores.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('proveedores', $th);
    $trail->push('Eliminar proveedor', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});
/** CAJAS PRECIOS */

Breadcrumbs::for('caja_precios', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('caja_precios', route($th['table'] . '.index'));
});
Breadcrumbs::for('caja_precios.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('caja_precios', $th);
    $trail->push('Crear cajas precios', route($th['table'] . '.create'));
});
Breadcrumbs::for('caja_precios.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('caja_precios', $th);
    $trail->push('Eliminar', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('caja_precios.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('caja_precios', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('caja_precios.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('caja_precios', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('caja_precios.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('caja_precios', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
/**PRECIO PRODUCTOS */

Breadcrumbs::for('precio_productos', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('precio_productos', route($th['table'] . '.index'));
});
Breadcrumbs::for('precio_productos.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('precio_productos', $th);
    $trail->push('Crear precios productos', route($th['table'] . '.create'));
});
Breadcrumbs::for('precio_productos.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('precio_productos', $th);
    $trail->push('Eliminar', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('precio_productos.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('precio_productos', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('precio_productos.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('precio_productos', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('precio_productos.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('precio_productos', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
/**TEMPORADAS */

Breadcrumbs::for('temporadas', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('temporadas', route($th['table'] . '.index'));
});
Breadcrumbs::for('temporadas.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('temporadas', $th);
    $trail->push('Crear temporadas', route($th['table'] . '.create'));
});
Breadcrumbs::for('temporadas.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('temporadas', $th);
    $trail->push('Eliminar', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('temporadas.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('temporadas', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('temporadas.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('temporadas', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('temporadas.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('temporadas', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
/**TARIFAS */

Breadcrumbs::for('tarifas', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('tarifas', route($th['table'] . '.index'));
});
Breadcrumbs::for('tarifas.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('tarifas', $th);
    $trail->push('Crear tarifas', route($th['table'] . '.create'));
});
Breadcrumbs::for('tarifas.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tarifas', $th);
    $trail->push('Eliminar', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('tarifas.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tarifas', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('tarifas.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tarifas', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('tarifas.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tarifas', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('tarifas.detalle', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tarifas', $th);
    $trail->push('Detalles ', route($th['table'] . '.detalle', ['id' => Crypt::encryptString($p->id)]));
});

//***REQUISICIONES***
Breadcrumbs::for('requisiciones', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('requisiciones', route($th['table'] . '.index'));
});
Breadcrumbs::for('requisiciones.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('Crear requisiciones', route($th['table'] . '.create'));
});
Breadcrumbs::for('requisiciones.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('requisiciones', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('requisiciones.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('requisiciones', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('requisiciones.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('requisiciones', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('requisiciones.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('requisiciones', $th);
    $trail->push('Anular requisiciones', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});
//** COMPRAS */
Breadcrumbs::for('compras', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('compras', route($th['table'] . '.index'));
});
Breadcrumbs::for('compras.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('Crear compras', route($th['table'] . '.create'));
});
Breadcrumbs::for('compras.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('compras', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('compras.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('compras', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('compras.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('compras', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('compras.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('requisiciones', $th);
    $trail->push('Anular compras', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});

//***TIPO DE HABITACIONES***
Breadcrumbs::for('tipo_habitaciones', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('tipo_habitaciones', route($th['table'] . '.index'));
});
Breadcrumbs::for('tipo_habitaciones.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('Crear tipo de habitacion', route($th['table'] . '.create'));
});
Breadcrumbs::for('tipo_habitaciones.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_habitaciones', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('tipo_habitaciones.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_habitaciones', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('tipo_habitaciones.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_habitaciones', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('tipo_habitaciones.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_habitaciones', $th);
    $trail->push('Eliminar tipo de habitacion', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});

//***FORMA DE HABITACIONES***
Breadcrumbs::for('forma_habitaciones', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('forma_habitaciones', route($th['table'] . '.index'));
});
Breadcrumbs::for('forma_habitaciones.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('Crear forma de habitacion', route($th['table'] . '.create'));
});
Breadcrumbs::for('forma_habitaciones.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('forma_habitaciones', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('forma_habitaciones.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('forma_habitaciones', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('forma_habitaciones.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('forma_habitaciones', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('forma_habitaciones.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('forma_habitaciones', $th);
    $trail->push('Eliminar forma de habitacion', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});

//***ESTADO DE HABITACIONES***
Breadcrumbs::for('estado_habitaciones', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('estado_habitaciones', route($th['table'] . '.index'));
});
Breadcrumbs::for('estado_habitaciones.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('Crear habitacion', route($th['table'] . '.create'));
});
Breadcrumbs::for('estado_habitaciones.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('estado_habitaciones', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('estado_habitaciones.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('estado_habitaciones', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('estado_habitaciones.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('estado_habitaciones', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('estado_habitaciones.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('estado_habitaciones', $th);
    $trail->push('Eliminar habitacion', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});

//***TIPO DE CAMAS***
Breadcrumbs::for('tipo_camas', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('tipo_camas', route($th['table'] . '.index'));
});
Breadcrumbs::for('tipo_camas.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('Crear camas', route($th['table'] . '.create'));
});
Breadcrumbs::for('tipo_camas.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_camas', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('tipo_camas.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_camas', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('tipo_camas.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_camas', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('tipo_camas.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_camas', $th);
    $trail->push('Eliminar camas', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});

//***TIPO DE RESERVACIONES***
Breadcrumbs::for('tipo_reservaciones', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('tipo_reservaciones', route($th['table'] . '.index'));
});
Breadcrumbs::for('tipo_reservaciones.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('Crear tipo de reservacion', route($th['table'] . '.create'));
});
Breadcrumbs::for('tipo_reservaciones.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_reservaciones', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('tipo_reservaciones.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_reservaciones', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('tipo_reservaciones.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_reservaciones', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('tipo_reservaciones.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_reservaciones', $th);
    $trail->push('Eliminar tipo de reservacion', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});

//***UBICACION DE HABITACIONES***
Breadcrumbs::for('ubicacion_habitaciones', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('ubicacion_habitaciones', route($th['table'] . '.index'));
});
Breadcrumbs::for('ubicacion_habitaciones.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('Crear ubicacion', route($th['table'] . '.create'));
});
Breadcrumbs::for('ubicacion_habitaciones.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('ubicacion_habitaciones', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('ubicacion_habitaciones.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('ubicacion_habitaciones', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('ubicacion_habitaciones.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('ubicacion_habitaciones', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('ubicacion_habitaciones.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('ubicacion_habitaciones', $th);
    $trail->push('Eliminar ubicacion', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});

//***RESERVACIONES***
Breadcrumbs::for('reservaciones', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('reservaciones', route($th['table'] . '.index'));
});
Breadcrumbs::for('reservaciones.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('Crear reservacion', route($th['table'] . '.create'));
});
Breadcrumbs::for('reservaciones.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('reservaciones', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('reservaciones.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('reservaciones', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('reservaciones.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('reservaciones', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('reservaciones.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('reservaciones', $th);
    $trail->push('Eliminar reservacion', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});
//***TIPO DE MANTENIMIENTOS***
Breadcrumbs::for('tipo_mantenimientos', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('tipo_mantenimientos', route($th['table'] . '.index'));
});
Breadcrumbs::for('tipo_mantenimientos.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('Crear tipo_mantenimientos', route($th['table'] . '.create'));
});
Breadcrumbs::for('tipo_mantenimientos.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_mantenimientos', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('tipo_mantenimientos.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_mantenimientos', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('tipo_mantenimientos.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_mantenimientos', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('tipo_mantenimientos.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_mantenimientos', $th);
    $trail->push('Eliminar tipo_mantenimientos', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});
//***MANTENIMIENTOS***

Breadcrumbs::for('mantenimientos.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('Crear mantenimientos', route($th['table'] . '.create'));
});
Breadcrumbs::for('mantenimientos.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('mantenimientos', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('mantenimientos.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('mantenimientos', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('mantenimientos.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('mantenimientos', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('mantenimientos.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('mantenimientos', $th);
    $trail->push('Anular mantenimientos', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});


//***ADMINISTRAR HABITACIONES***
Breadcrumbs::for('administrar_habitaciones', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('Administrar habitaciones', route($th['table'] . '.index'));
});
Breadcrumbs::for('administrar_habitaciones.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('administrar_habitaciones', $th);
    $trail->push('Crear habitacion', route($th['table'] . '.create'));
});
Breadcrumbs::for('administrar_habitaciones.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('administrar_habitaciones', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('administrar_habitaciones.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('administrar_habitaciones', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('administrar_habitaciones.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('administrar_habitaciones', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('administrar_habitaciones.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('administrar_habitaciones', $th);
    $trail->push('Eliminar habitacion', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});
//***ADMINISTRAR MANTENIMIENTOS***
Breadcrumbs::for('administracion_mantenimientos', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('Administrar mantenimientos', route($th['table'] . '.index'));
});
Breadcrumbs::for('administracion_mantenimientos.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('home', $th);
    $trail->push('Crear mantenimiento', route($th['table'] . '.create'));
});
Breadcrumbs::for('administracion_mantenimientos.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('administracion_mantenimientos', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('administracion_mantenimientos.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('administracion_mantenimientos', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('administracion_mantenimientos.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('administracion_mantenimientos', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('administracion_mantenimientos.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('administracion_mantenimientos', $th);
    $trail->push('Eliminar mantenimiento', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});


//***Huespedes***
Breadcrumbs::for('huespedes', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('Huespedes', route($th['table'] . '.index'));
});
Breadcrumbs::for('huespedes.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('huespedes', $th);
    $trail->push('Crear ' . $th['table'], route($th['table'] . '.create'));
});

Breadcrumbs::for('huespedes.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('huespedes', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});

Breadcrumbs::for('huespedes.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('huespedes', $th);
    $trail->push('Eliminar ' . $p->name, route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});

//***Anticipos***
Breadcrumbs::for('anticipos', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('anticipos', route($th['table'] . '.index'));
});
Breadcrumbs::for('anticipos.create', function (BreadcrumbTrail $trail, $th,) {
    $trail->parent('anticipos', $th);
    $trail->push('Crear ' . $th['table'], route($th['table'] . '.create'));
});

Breadcrumbs::for('anticipos.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('anticipos', $th);
    $trail->push('Editar ' . $th['table'] . ': ' . $p->id, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});

Breadcrumbs::for('anticipos.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('anticipos', $th);
    $trail->push('Eliminar ' . $p->name, route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});
//**Montajes */
Breadcrumbs::for('montajes', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('montajes', route($th['table'] . '.index'));
});
Breadcrumbs::for('montajes.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('montajes', $th);
    $trail->push('Crear montaje', route($th['table'] . '.create'));
});
Breadcrumbs::for('montajes.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('montajes', $th);
    $trail->push('Eliminar', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('montajes.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('montajes', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('montajes.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('montajes', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('montajes.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('montajes', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
//**Galerias */
Breadcrumbs::for('galerias', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('galerias', route($th['table'] . '.index'));
});
Breadcrumbs::for('galerias.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('galerias', $th);
    $trail->push('Crear galeria', route($th['table'] . '.create'));
});
Breadcrumbs::for('galerias.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('galerias', $th);
    $trail->push('Eliminar', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('galerias.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('galerias', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('galerias.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('galerias', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('galerias.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('galerias', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
//**Tipo cortesia */
Breadcrumbs::for('tipo_cortesia', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('tipo_cortesia', route($th['table'] . '.index'));
});
Breadcrumbs::for('tipo_cortesia.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('tipo_cortesia', $th);
    $trail->push('Crear tipo cortesia', route($th['table'] . '.create'));
});
Breadcrumbs::for('tipo_cortesia.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_cortesia', $th);
    $trail->push('Eliminar', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('tipo_cortesia.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_cortesia', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('tipo_cortesia.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_cortesia', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('tipo_cortesia.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('tipo_cortesia', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
//**sonidos */
Breadcrumbs::for('sonidos', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('sonidos', route($th['table'] . '.index'));
});
Breadcrumbs::for('sonidos.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('sonidos', $th);
    $trail->push('Crear sonidos', route($th['table'] . '.create'));
});
Breadcrumbs::for('sonidos.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('sonidos', $th);
    $trail->push('Eliminar', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('sonidos.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('sonidos', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('sonidos.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('sonidos', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('sonidos.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('sonidos', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
//**salones */
Breadcrumbs::for('salones', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('salones', route($th['table'] . '.index'));
});
Breadcrumbs::for('salones.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('salones', $th);
    $trail->push('Crear salones', route($th['table'] . '.create'));
});
Breadcrumbs::for('salones.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('salones', $th);
    $trail->push('Eliminar', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('salones.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('salones', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('salones.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('salones', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('salones.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('salones', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
//**control cortesias*/
Breadcrumbs::for('control_cortesias', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('control_cortesias', route($th['table'] . '.index'));
});
Breadcrumbs::for('control_cortesias.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('control_cortesias', $th);
    $trail->push('Crear control_cortesias', route($th['table'] . '.create'));
});
Breadcrumbs::for('control_cortesias.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('control_cortesias', $th);
    $trail->push('Eliminar', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('control_cortesias.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('control_cortesias', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('control_cortesias.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('control_cortesias', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('control_cortesias.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('control_cortesias', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
//** cortesias*/
Breadcrumbs::for('cortesias', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('cortesias', route($th['table'] . '.index'));
});
Breadcrumbs::for('cortesias.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('cortesias', $th);
    $trail->push('Crear cortesias', route($th['table'] . '.create'));
});
Breadcrumbs::for('cortesias.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('cortesias', $th);
    $trail->push('Eliminar', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('cortesias.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('cortesias', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('cortesias.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('cortesias', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('cortesias.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('cortesias', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
//** categoria_fotos*/
Breadcrumbs::for('categoria_fotos', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('categoria_fotos', route($th['table'] . '.index'));
});
Breadcrumbs::for('categoria_fotos.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('categoria_fotos', $th);
    $trail->push('Crear categoria_fotos', route($th['table'] . '.create'));
});
Breadcrumbs::for('categoria_fotos.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('categoria_fotos', $th);
    $trail->push('Eliminar', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('categoria_fotos.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('categoria_fotos', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('categoria_fotos.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('categoria_fotos', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
//** RUBROS*/
Breadcrumbs::for('rubros', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('rubros', route($th['table'] . '.index'));
});
Breadcrumbs::for('rubros.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('rubros', $th);
    $trail->push('Crear rubros', route($th['table'] . '.create'));
});
Breadcrumbs::for('rubros.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('rubros', $th);
    $trail->push('Eliminar', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('rubros.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('rubros', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('rubros.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('rubros', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});

//** Cargos*/

Breadcrumbs::for('cargos', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('cargos', route($th['table'] . '.index'));
});
Breadcrumbs::for('cargos.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('cargos', $th);
    $trail->push('Crear cargos', route($th['table'] . '.create'));
});
Breadcrumbs::for('cargos.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('cargos', $th);
    $trail->push('Eliminar', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('cargos.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('cargos', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('cargos.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('cargos', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
//**actividades economicas  */

Breadcrumbs::for('actividades_economicas', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('actividades_economicas', route($th['table'] . '.index'));
});
Breadcrumbs::for('actividades_economicas.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('actividades_economicas', $th);
    $trail->push('Crear actividad economica', route($th['table'] . '.create'));
});
Breadcrumbs::for('actividades_economicas.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('actividades_economicas', $th);
    $trail->push('Eliminar actividad economica', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});

Breadcrumbs::for('actividades_economicas.destroy', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('actividades_economicas', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.destroy', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('actividades_economicas.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('actividades_economicas', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('actividades_economicas.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('actividades_economicas', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
//**empleados */
Breadcrumbs::for('empleados', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('empleados', route($th['table'] . '.index'));
});
Breadcrumbs::for('empleados.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('empleados', $th);
    $trail->push('Crear empleado', route($th['table'] . '.create'));
});
Breadcrumbs::for('empleados.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('empleados', $th);
    $trail->push('Eliminar empleado', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});

Breadcrumbs::for('empleados.destroy', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('empleados', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.destroy', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('empleados.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('empleados', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('empleados.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('empleados', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});

#---SOLICITANTES---
Breadcrumbs::for('solicitantes', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('solicitantes', route($th['table'] . '.index'));
});
Breadcrumbs::for('solicitantes.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('solicitantes', $th);
    $trail->push('Crear solicitantes', route($th['table'] . '.create'));
});
Breadcrumbs::for('solicitantes.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('solicitantes', $th);
    $trail->push('Eliminar', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id), 'clientes_id' => Crypt::encryptString($p->clientes_id)]));
});
Breadcrumbs::for('solicitantes.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('solicitantes', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('solicitantes.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('solicitantes', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});

#---AJUSTED DE INVENTARIO---
Breadcrumbs::for('ajustes_inventarios', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('ajustes_inventarios', route($th['table'] . '.index'));
});
Breadcrumbs::for('ajustes_inventarios.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('ajustes_inventarios', $th);
    $trail->push('Crear ajuste de inventario', route($th['table'] . '.create'));
});
Breadcrumbs::for('ajustes_inventarios.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('ajustes_inventarios', $th);
    $trail->push('Eliminar ajuste de inventario', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});

Breadcrumbs::for('ajustes_inventarios.destroy', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('ajustes_inventarios', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.destroy', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('ajustes_inventarios.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('ajustes_inventarios', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('ajustes_inventarios.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('ajustes_inventarios', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});

#---AGREGAR PRECIOS A CAJAS---
Breadcrumbs::for('precios.precioCajasIndex', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('precios', $th);
    $trail->push('Asignar precios a cajas', route($th['table'] . '.precioCajasIndex'));
});

#---PERIODOS CREDITOS---
Breadcrumbs::for('periodos_creditos', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('home');
    $trail->push('Peridos creditos', route($th['table'] . '.index'));
});
Breadcrumbs::for('periodos_creditos.create', function (BreadcrumbTrail $trail, $th) {
    $trail->parent('periodos_creditos', $th);
    $trail->push('Crear pais', route($th['table'] . '.create'));
});
Breadcrumbs::for('periodos_creditos.confirm', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('periodos_creditos', $th);
    $trail->push('Eliminar', route($th['table'] . '.confirm', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('periodos_creditos.update', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('periodos_creditos', $th);
    $trail->push('Actualizar ' . $p->name, route($th['table'] . '.update', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('periodos_creditos.edit', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('periodos_creditos', $th);
    $trail->push('Editar ' . $p->name, route($th['table'] . '.edit', ['id' => Crypt::encryptString($p->id)]));
});
Breadcrumbs::for('periodos_creditos.show', function (BreadcrumbTrail $trail, $th, $p) {
    $trail->parent('periodos_creditos', $th);
    $trail->push('Detalles ' . $p->name, route($th['table'] . '.show', ['id' => Crypt::encryptString($p->id)]));
});
