/*
# Vista getCiudades
*/
drop view IF EXISTS getciudad;
create view getciudad as
select
	UPPER(CONCAT(municipios.municipio,', ',departamentos.departamento,' (', departamentos.codigo_postal,') ', paises.pais )) as ciudad
	,municipios.id
	,UPPER(municipios.municipio) AS municipio
	,municipios.departamentos_id
	,UPPER(departamentos.departamento) AS departamento
	,departamentos.codigo_postal
	,departamentos.paises_id
	,UPPER(paises.pais) AS pais
	,UPPER(paises.nacionalidad) AS nacionalidad
from
	municipios
left join
	departamentos
		on municipios.departamentos_id = departamentos.id
left join
	paises
		on departamentos.paises_id = paises.id;

/*
#Vista getclientes
*/
drop view IF EXISTS getclientes;
create view getclientes as
select
	upper(concat(cl.nombre,', ', i.identificacion, ': ', ci.numero)) as cliente
	,cl.id
	,upper(cl.nombre) as nombre
	,ci.numero
	,upper(i.identificacion) as identificacion
    ,cl.tipo_cliente
from
	clientes as cl
left join
	clientes_identificaciones as ci
		on cl.id = ci.clientes_id
left join
	identificaciones as i
		on ci.identificaciones_id = i.id
        where cl.estado = true;

/*Vista getProveedores*/
drop view if exists getProveedores;
create view getProveedores as
select pro.id,
	   upper(concat(pro.proveedor,', ',pro.dui)) as proveedor,
	   pro.proveedor as proveedorName,
	   pro.dui,
	   pro.nit,
	   pro.nrc,
	   pro.permite_credito
	   from compras as cp
left join proveedores as pro on cp.proveedores_id = pro.id;

--Vista de tarifas en modulo de reservaciones
drop view if exists getTarifasReservaciones;
create view getTarifasReservaciones as
select
	t.id,
	tarifa,
	precio,
	numero_dias,
	paquete,
	estado,
	temporadas_id
from
	tarifas as t
left join
	tarifa_detalles as d
		on t.id = d.tarifas_id;

/** vista habitaciones aplica a tarifa pdt corregir */
drop view if exists getTarifasHabitaciones;
create view getTarifasHabitaciones
as
SELECT
	h.*
	,d.tarifas_id
	,tf.tarifa
	,tf.precio
	,tf.numero_dias
	,tf.paquete
	,tf.estado as estado_tarifa
	,tf.temporadas_id
	,tm.temporada
	,tm.fecha_inicio
	,tm.fecha_finalizacion
	,tm.estado as estado_temporada
FROM
	habitaciones as h

left join
	tarifa_detalles as d
		on
			h.tipo_habitaciones_id = d.tipo_habitaciones_id
		and
			h.forma_habitaciones_id = d.forma_habitaciones_id
left join
	tarifas as tf
		on tf.id = d.tarifas_id and tf.estado= true
inner join
	temporadas as tm
		on
			tm.id = tf.temporadas_id
		and
			tm.estado = true;
/*
Actualizacion de vista se quitan los parametros por defecto.
		and
			tm.fecha_inicio <= now()::date
		and
			tm.fecha_finalizacion >= now()::date;
*/
/* Vista get_reservaciones: todas las reservaciones, con cliente y numero de habitaciones*/
drop view IF EXISTS get_reservaciones;
create or replace view get_reservaciones as
select
	r.*
	,c.credito
	,case
		when r.clientes_id > 0
			then
				UPPER(c.nombre)
		else
			UPPER(r.titular)
	end as nombre
	,(
		select
			count(id)
		from
			detalle_reservas as dr
		where dr.reservaciones_id = r.id
	) as numero_habitaciones
from
	reservaciones as r
left join
	clientes as c
		on c.id = r.clientes_id
group by r.id, c.id;

drop view IF EXISTS get_recepciones;
create view get_recepciones as
select
	r.*
	,c.credito
	,cc.valor
	,cc.observaciones
	,ct.contacto as tipo_contacto
	,case
		when r.clientes_id > 0
			then
				UPPER(c.nombre)
		else
			UPPER(r.titular)
	end as nombre
from
	recepciones as r
left join
	clientes as c
		on c.id = r.clientes_id
left join
	clientes_contactos as cc
		on c.id = cc.clientes_id
left join
	contactos as ct
		on cc.contactos_id = ct.id;

/*vista de habitaciones reservadas*
drop view IF EXISTS getReservacionHabitaciones;
create view getReservacionHabitaciones
as
SELECT
    *
FROM
    habitaciones
where
    habitaciones.id not in (
        select
            habitaciones_id
        from
            detalle_reservas as dr
		inner join
			reservaciones as r
				on r.id = dr.reservaciones_id
        where
			(
				('2023-09-05' BETWEEN dr.fecha_ingreso and dr.fecha_salida - 1)
			or
				('2023-09-07' BETWEEN dr.fecha_ingreso and dr.fecha_salida - 1)
			)
		and
			r.eliminado = false
    );*/
/*vista productoexistencia con upper*/
DROP VIEW IF EXISTS getExistenciasByProducto;

CREATE VIEW getExistenciasByProducto AS
SELECT
    productos.id AS producto_id,
    upper(productos.nombre::text) || ' vence: ' || COALESCE(existencias.vencimiento::text, 'No vence') AS producto_nombre,
    existencias.bodegas_id,
    existencias.productos_id,
    existencias.existencia AS existencia_sum,
    existencias.id AS lote_id,
    existencias.vencimiento
FROM existencias
JOIN productos ON existencias.productos_id = productos.id AND productos.estado = true
WHERE existencias.estado = true
AND existencias.existencia > 0
GROUP BY existencias.productos_id, existencias.bodegas_id, existencias.existencia, productos.id,
    existencias.id, existencias.vencimiento, upper(productos.nombre::text)
ORDER BY existencias.vencimiento;

/* Vista get_reservaciones: todas las reservaciones, con cliente y numero de habitaciones*/
drop view IF EXISTS get_reservaciones;
create view get_reservaciones as
select
	r.*
	,c.credito
	,cc.valor
	,cc.observaciones
	,ct.contacto as tipo_contacto
	,case
		when r.clientes_id > 0
			then
				UPPER(c.nombre)
		else
			UPPER(r.titular)
	end as nombre
	,(
		select
			count(id)
		from
			detalle_reservas as dr
		where dr.reservaciones_id = r.id
	) as numero_habitaciones
from
	reservaciones as r
left join
	clientes as c
		on c.id = r.clientes_id
left join
	clientes_contactos as cc
		on c.id = cc.clientes_id
left join
	contactos as ct
		on cc.contactos_id = ct.id;

/**vista de recepciones*/
drop view IF EXISTS get_recepciones;
create view get_recepciones as
select
	r.*
	,c.credito
	,cc.valor
	,cc.observaciones
	,ct.contacto as tipo_contacto
	,case
		when r.clientes_id > 0
			then
				UPPER(c.nombre)
		else
			UPPER(r.titular)
	end as nombre
from
	recepciones as r
left join
	clientes as c
		on c.id = r.clientes_id
left join
	clientes_contactos as cc
		on c.id = cc.clientes_id
left join
	contactos as ct
		on cc.contactos_id = ct.id;
/**
* Vista para reservaciones disponibles HOY
*/
drop view IF EXISTS getReservacionesValidas;
CREATE OR REPLACE VIEW getReservacionesValidas
AS
SELECT
	dr.*
	,r.clientes_id
	,r.titular
	,r.contacto
	,r.tipo_reservaciones_id
	,r.eliminado
	,r.estado AS estado_reservaciones
	,r.completa
FROM
	detalle_reservas AS dr
JOIN
	reservaciones AS r
ON
	dr.reservaciones_id = r.id
AND
	r.estado = true
AND
	r.eliminado = false
WHERE
	dr.fecha_ingreso >= current_date
and
	dr.ingreso = false;

drop view if exists  getPrecios;
create view getPrecios
as
SELECT
	pr.*,
	UPPER(pr.detalle) as nombre,
	e.id as lote,
    e.productos_id,
    p.nombre as producto,
    e.bodegas_id,
    b.bodega,
	e.existencia,
    (e.existencia/ pp.descargo) as existencia_group,
	e.vencimiento,
	pp.descargo,
	cp.categoria,
	cp.descripcion,
	cp.token
FROM
precios pr
JOIN
	categorias_precios cp on pr.categorias_precios_id = cp.id and cp.estado = true
JOIN
    precio_productos pp ON pp.precios_id = pr.id
left JOIN
    existencias e on e.productos_id = pp.productos_id and e.estado = true
left JOIN
    productos p ON e.productos_id = p.id
left JOIN
    bodega_cajas bc ON e.bodegas_id = bc.bodegas_id
JOIN
    bodegas b ON e.bodegas_id = b.id
where
	(e.existencia/ pp.descargo) > 0
and
	(constante = true or (pr.fecha_inicio <= now()::date and pr.fecha_final >= now()::date))
and
	pr.estado = true
and
	cp.token = 1101
group by pr.id, pr.detalle, e.id,cp.id, p.nombre, b.bodega, pp.descargo, e.bodegas_id;

drop view if exists  getPreciosSinExistencias;
create view getPreciosSinExistencias
as
SELECT
	pr.*,
	UPPER(pr.detalle) as nombre,
    0 as existencia_group,
	cp.categoria,
	cp.descripcion,
	cp.token
FROM
precios pr
JOIN
	categorias_precios cp on pr.categorias_precios_id = cp.id and cp.estado = true
where

	(constante = true or (pr.fecha_inicio <= now()::date and pr.fecha_final >= now()::date))
and
	pr.estado = true
and
	cp.token = 1105;

drop view if exists  getCierreComandas;
create view getCierreComandas
as
select
	p.*,
	c.nombre,
	c.credito
from
	comandas p
left join
	clientes c
	on
		c.id = p.clientes_id
	and
		p.estado = true
where
	p.anulada = false
and
	p.eliminada = false
and
	p.facturada = false
and
	p.estado = true
and
	(c.id is null or c.credito = false);

drop view if exists  getCierreOrdenes;
create view getCierreOrdenes
as
select
	p.*,
	c.nombre,
	c.credito
from
	ordenes p
left join
	clientes c
	on
		c.id = p.clientes_id
	and
		p.estado = true
where
	p.estado = true
and
	(c.id is null or c.credito = false);

drop view if exists  getCierreRecepciones;
create view getCierreRecepciones
as
select
	h.sucursales_id,
	p.*,
	c.nombre,
	c.credito
from
	recepciones p
left join
	clientes c
	on
		c.id = p.clientes_id
	and
		p.estado = true

left join
	habitaciones h
	on
		p.habitaciones_id = h.id
where
	p.eliminado = false
and
	p.facturada = false
and
	p.estado = true
	and
	p.fecha_salida <= current_date
and
	(c.id is null or c.credito = false);

drop view if exists  get_opcion_turnos;
create view get_opcion_turnos
as
SELECT
opcion_turnos.*,
    CURRENT_DATE + apertura as f_apertura,
	CASE
		WHEN
			cierre < apertura
		THEN
			(CURRENT_DATE + cierre) + interval '1 day'
		ELSE
			CURRENT_DATE + cierre
	END as f_cierre
FROM opcion_turnos;

/** vista para vtablero de existencia de productos con existencia mininima*/
DROP VIEW IF EXISTS existenciasminimasbyProductos;

CREATE VIEW existenciasminimasbyProductos AS
SELECT
    productos.*,
    existencias.bodegas_id,
    SUM(existencia) as existencia
FROM
    productos
JOIN existencias ON productos.id = existencias.productos_id
WHERE
    productos.estado = true AND
    productos.minimos > 0 AND
    productos.minimos >= (
        SELECT SUM(existencia)
        FROM existencias e
        WHERE estado = true AND e.productos_id = productos.id and e.bodegas_id = existencias.bodegas_id
    )
    group by existencias.bodegas_id, productos.id;

/**vista para productos proximos a vencer para dashboard iventario en bodegas lo trabaje de esta forma tenia conflictos en vencimiento en existencias y productos pdt refactorizar */
drop view if exists  existenciasbyVencimiento;
CREATE VIEW existenciasbyVencimiento AS
SELECT
    e.id AS id_exis,
	e.existencia AS existe_avencer,
    e.cantidad_historial AS cantida_hist,
    e.bodegas_id AS bodega,
    e.vencimiento AS vencimiento_exis,
    e.requisicion_detalles_id AS r_detalle,
    e.precio_costo AS p_costo,
    e.estado AS estado_exist,

    p.*
FROM
    existencias e
LEFT JOIN
    productos p
ON
    e.productos_id = p.id
WHERE
    e.existencia > 0
    AND e.vencimiento <= CURRENT_DATE + INTERVAL '15 days'
    AND e.vencimiento >= CURRENT_DATE;

/* VISTA COMPROBANTES CON SALDO EN FORMA DE PAGOS CRÉDITO*/
DROP VIEW IF EXISTS  getComprobantesCreditos;
CREATE OR REPLACE VIEW getComprobantesCreditos AS
SELECT
a.*,
c.forma,
c.token,
b.titular,
b.clientes_id,
b.total,
b.correlativo,
b.fecha,
t.tipo
FROM
	public.comprobantes_pagos as a
LEFT JOIN
	public.comprobantes as b
		ON a.comprobantes_id = b.id
LEFT JOIN
	public.forma_pagos as c
		ON c.id = a.forma_pagos_id
LEFT JOIN
	PUBLIC.tipo_comprobantes as t ON t.id = b.tipo_comprobantes_id
WHERE c.token = 6002 AND a.monto > COALESCE((SELECT SUM(monto) FROM public.abonos_detalles as ad WHERE a.comprobantes_id = ad.comprobantes_id and ad.estado = true), 0);
/*VISTA DE GETACTIVIDADES ECONOMICAS*/
DROP VIEW IF EXISTS getactividadeseconomicas;
CREATE VIEW getactividadeseconomicas AS
SELECT
    UPPER(CONCAT(ae.actividad, ', ', ae.codigo)) AS actividad_economica,
    ae.id,
    UPPER(ae.actividad) AS actividad,
    ae.codigo
FROM
    actividades_economicas AS ae;


/*VISTA: Reporte historial de ajustes de inventarios*/
DROP VIEW IF EXISTS getReporteHistorialAjuste;
CREATE OR REPLACE VIEW getReporteHistorialAjuste AS
SELECT
    ajustes_inventarios.id AS ajustes_inventarios_id,
    ajustes_inventarios.observacion AS observacion,
    ajustes_inventarios.fecha_proceso AS fecha_proceso,

    ajustes_existencias.id AS ajustes_existencias_id,
	ajustes_existencias.accion AS accion,
	ajustes_existencias.cantidad AS cantidad,

    existencias.id AS existencias_id,
    existencias.existencia AS existencia,

    bodegas.id AS bodegas_id,
    bodegas.bodega AS bodega,

    productos.id AS productos_id,
    productos.nombre AS nombre_producto,

	solicitante_users_id as user_solicitante_id,
    user_solicitante.name AS user_solicitante,

    user_realiza.name AS user_realiza,
    user_autoriza.name AS user_autoriza
FROM
    ajustes_existencias
LEFT JOIN ajustes_inventarios
    ON ajustes_existencias.ajustes_inventarios_id = ajustes_inventarios.id
LEFT JOIN existencias
    ON ajustes_existencias.id = existencias.id
LEFT JOIN bodegas
    ON existencias.bodegas_id = bodegas.id
LEFT JOIN productos
    ON existencias.productos_id = productos.id
LEFT JOIN users AS user_solicitante
    ON ajustes_inventarios.solicitante_users_id = user_solicitante.id
LEFT JOIN users AS user_realiza
    ON ajustes_inventarios.realiza_users_id = user_realiza.id
LEFT JOIN users AS user_autoriza
    ON ajustes_inventarios.autoriza_users_id = user_autoriza.id;



DROP VIEW IF EXISTS libro_consumidor_final;
DROP VIEW IF EXISTS consumidor_final;
DROP VIEW IF EXISTS anulaciones_consumidor_final;
CREATE OR REPLACE VIEW consumidor_final AS
select
	comprobantes.fecha,
	comprobantes.sucursales_id,
	SUM(CASE WHEN ac.fecha IS NULL OR ac.fecha > comprobantes.fecha THEN neto ELSE 0 END) AS neto,
    SUM(CASE WHEN ac.fecha IS NULL OR ac.fecha > comprobantes.fecha THEN iva ELSE 0 END) AS iva,
    SUM(CASE WHEN ac.fecha IS NULL OR ac.fecha > comprobantes.fecha THEN cesc ELSE 0 END) AS cesc,
    SUM(CASE WHEN ac.fecha IS NULL OR ac.fecha > comprobantes.fecha THEN advalorem ELSE 0 END) AS advalorem,
    SUM(CASE WHEN ac.fecha IS NULL OR ac.fecha > comprobantes.fecha THEN propina ELSE 0 END) AS propina,
    SUM(CASE WHEN ac.fecha IS NULL OR ac.fecha > comprobantes.fecha THEN gravado ELSE 0 END) AS gravado,
    SUM(CASE WHEN ac.fecha IS NULL OR ac.fecha > comprobantes.fecha THEN exento ELSE 0 END) AS exento,
    SUM(CASE WHEN ac.fecha IS NULL OR ac.fecha > comprobantes.fecha THEN percepcion ELSE 0 END) AS percepcion,
    SUM(CASE WHEN ac.fecha IS NULL OR ac.fecha > comprobantes.fecha THEN total ELSE 0 END) AS total,
	MIN(CAST(RIGHT(dtes.correlativo, 15) AS BIGINT)) as inicio,
	max(CAST(RIGHT(dtes.correlativo, 15) AS BIGINT)) as ultimo
from
	comprobantes
inner join dtes
	ON dtes.comprobantes_id = comprobantes.id
left join
	anulacion_comprobantes as ac
ON
	ac.comprobantes_id = comprobantes.id
and
    comprobantes.estado = false
and
    ac.eliminada = false
where
	dtes.tipo_dte = 1
and
	comprobantes.eliminado = false
group by
	comprobantes.fecha,
	comprobantes.sucursales_id
order by
	comprobantes.fecha;


CREATE OR REPLACE VIEW anulaciones_consumidor_final AS
select
	ac.fecha,
	comprobantes.sucursales_id,
	SUM(CASE WHEN ac.fecha IS NULL OR ac.fecha > comprobantes.fecha THEN (neto * -1) ELSE 0 END) AS neto,
    SUM(CASE WHEN ac.fecha IS NULL OR ac.fecha > comprobantes.fecha THEN (iva * -1) ELSE 0 END) AS iva,
    SUM(CASE WHEN ac.fecha IS NULL OR ac.fecha > comprobantes.fecha THEN (cesc * -1) ELSE 0 END) AS cesc,
    SUM(CASE WHEN ac.fecha IS NULL OR ac.fecha > comprobantes.fecha THEN (advalorem * -1) ELSE 0 END) AS advalorem,
    SUM(CASE WHEN ac.fecha IS NULL OR ac.fecha > comprobantes.fecha THEN (propina * -1) ELSE 0 END) AS propina,
    SUM(CASE WHEN ac.fecha IS NULL OR ac.fecha > comprobantes.fecha THEN (gravado * -1) ELSE 0 END) AS gravado,
    SUM(CASE WHEN ac.fecha IS NULL OR ac.fecha > comprobantes.fecha THEN (exento * -1) ELSE 0 END) AS exento,
    SUM(CASE WHEN ac.fecha IS NULL OR ac.fecha > comprobantes.fecha THEN (percepcion * -1) ELSE 0 END) AS percepcion,
    SUM(CASE WHEN ac.fecha IS NULL OR ac.fecha > comprobantes.fecha THEN (total * -1) ELSE 0 END) AS total,
	MIN(CAST(RIGHT(dtes.correlativo, 15) AS BIGINT)) as inicio,
	max(CAST(RIGHT(dtes.correlativo, 15) AS BIGINT)) as ultimo
from
	comprobantes
inner join dtes
	ON dtes.comprobantes_id = comprobantes.id
left join
	anulacion_comprobantes as ac
ON
	ac.comprobantes_id = comprobantes.id
and
    comprobantes.estado = false
and
    ac.eliminada = false
where
	dtes.tipo_dte = 1
and
	comprobantes.estado = false
and
	comprobantes.eliminado = false
group by
	comprobantes.id,
	ac.fecha,
	comprobantes.sucursales_id
order by
	ac.fecha;



CREATE OR REPLACE VIEW libro_consumidor_final AS
select
	*,
	1 as origen
from
	consumidor_final
union
select
	*,
	2 as origen
from
	anulaciones_consumidor_final
order by fecha asc, origen asc, inicio asc;






DROP VIEW IF EXISTS anexo_consumidor;
DROP VIEW IF EXISTS anexo_consumidor_activas;
CREATE OR REPLACE VIEW anexo_consumidor_activas AS
select
	TO_CHAR(c.fecha::DATE, 'DD/MM/YYYY') AS emision
	,4 as clase_documento
	,LPAD(cast(d.tipo_dte as text),2, '0') as tipo_documento
	,'N/A' as numero_resolucion
	,'N/A' as numero_serie
	,'N/A' as interno_del
	,'N/A' as interno_al
	,replace(min(d.codigo_generacion), '-', '') as numero_documento_del
	,replace(max(d.codigo_generacion), '-', '') as numero_documento_al
	,'' as maquina
	,ROUND(sum(cast((CASE WHEN ac.fecha IS NULL OR ac.fecha > c.fecha THEN c.exento ELSE 0 END) AS NUMERIC)), 2) as exento
	,cast(0 AS NUMERIC) as internas_no_sujetas
	,cast(0 AS NUMERIC) as no_sujetas
	,ROUND(sum(cast((CASE WHEN ac.fecha IS NULL OR ac.fecha > c.fecha THEN c.gravado ELSE 0 END) AS NUMERIC)), 2) as gravado
	,cast(0 AS NUMERIC) as exportaciones_ca
	,cast(0 AS NUMERIC) as exportaciones
	,cast(0 AS NUMERIC) as exportaciones_servicios
	,cast(0 AS NUMERIC) as ventas_zonas
	,cast(0 AS NUMERIC) as cuentas_terceros
	,ROUND(sum(cast((CASE WHEN ac.fecha IS NULL OR ac.fecha > c.fecha THEN c.iva ELSE 0 END) AS NUMERIC)), 2) as iva
	,ROUND(sum(cast((CASE WHEN ac.fecha IS NULL OR ac.fecha > c.fecha THEN (c.total + c.percepcion) ELSE 0 END)AS NUMERIC)), 2) as total_ventas
	,c.fecha
	,2 as anexo
from
	comprobantes as c
inner join
	dtes as d
	on
		d.comprobantes_id = c.id
left join
	anulacion_comprobantes as ac
		ON
			ac.comprobantes_id = c.id
		and c.estado = false
        and ac.eliminada = false
where
	d.tipo_dte = 1
and
	c.eliminado = false
group by
	c.fecha, d.tipo_dte
ORDER BY
    c.fecha;



DROP VIEW IF EXISTS anexo_consumidor_anuladas;
CREATE OR REPLACE VIEW anexo_consumidor_anuladas AS
select
	TO_CHAR(ac.fecha::DATE, 'DD/MM/YYYY') AS emision
	,4 as clase_documento
	,LPAD(cast(d.tipo_dte as text),2, '0') as tipo_documento
	,'N/A' as numero_resolucion
	,'N/A' as numero_serie
	,'N/A' as interno_del
	,'N/A' as interno_al
	,replace(d.codigo_generacion, '-', '') as numero_documento_del
	,replace(d.codigo_generacion, '-', '') as numero_documento_al
	,'' as maquina
	,ROUND(cast((CASE WHEN ac.fecha IS NULL OR ac.fecha > c.fecha THEN (c.exento *-1) ELSE 0 END) AS NUMERIC), 2) as exento
	,cast(0 AS NUMERIC) as internas_no_sujetas
	,cast(0 AS NUMERIC) as no_sujetas
	,ROUND(cast((CASE WHEN ac.fecha IS NULL OR ac.fecha > c.fecha THEN (c.gravado *-1) ELSE 0 END) AS NUMERIC), 2) as gravado
	,cast(0 AS NUMERIC) as exportaciones_ca
	,cast(0 AS NUMERIC) as exportaciones
	,cast(0 AS NUMERIC) as exportaciones_servicios
	,cast(0 AS NUMERIC) as ventas_zonas
	,cast(0 AS NUMERIC) as cuentas_terceros
	,ROUND(cast((CASE WHEN ac.fecha IS NULL OR ac.fecha > c.fecha THEN (c.iva *-1) ELSE 0 END) AS NUMERIC), 2) as iva
	,ROUND(cast((CASE WHEN ac.fecha IS NULL OR ac.fecha > c.fecha THEN ((c.total + c.percepcion)*-1) ELSE 0 END)AS NUMERIC), 2) as total_ventas
	,ac.fecha
	,2 as anexo
from
	comprobantes as c
inner join
	dtes as d
	on
		d.comprobantes_id = c.id
left join
	anulacion_comprobantes as ac
		ON
			ac.comprobantes_id = c.id
		and c.estado = false
        and ac.eliminada = false
where
	d.tipo_dte = 1
and
	c.eliminado = false
and
	c.estado = false
ORDER BY
    c.fecha;

CREATE OR REPLACE VIEW anexo_consumidor AS
select
	*,
	1 as origen
from
	anexo_consumidor_activas
union
select
	*,
	2 as origen
from
	anexo_consumidor_anuladas
order by fecha asc, origen asc;







DROP VIEW IF EXISTS anexo_invalidaciones;
CREATE OR REPLACE VIEW anexo_invalidaciones AS
select
	d.correlativo as numero_resolucion
	,4 as clase_documento
	,cast(0 AS NUMERIC) as desde
	,cast(0 AS NUMERIC) as hasta
	,LPAD(cast(d.tipo_dte as text),2, '0') as tipo_documento
	,'D' as tipo_detalle
	,d.sello_recibido as serie
	,cast(0 AS NUMERIC) as desdec
	,cast(0 AS NUMERIC) as hastac
	,d.codigo_generacion
	,case
	when ac.response IS NOT NULL and ac.response::JSON->>'estado' IS NOT NULL and coalesce(ac.response::JSON->>'estado', '') = 'PROCESADO'
	then
		ac.fecha
	ELSE null
	END as anulacion
	,(ac.response::JSON->>'estado') as anulacion_estado
	,(ac.response::JSON->>'selloRecibido') as anulacion_sello
	,c.fecha
from
	comprobantes as c
inner join
	dtes as d
	on
		d.comprobantes_id = c.id
left join
	anulacion_comprobantes as ac
		ON
			ac.comprobantes_id = c.id
		and c.estado = false
where
	c.estado = false
and
	c.eliminado = false;



DROP VIEW IF EXISTS anexo_sujetos;
CREATE OR REPLACE VIEW anexo_sujetos AS
select
	case
		when LENGTH(d.json::JSON->'sujetoExcluido'->>'numDocumento') = 14 THEN 1
		when LENGTH(d.json::JSON->'sujetoExcluido'->>'numDocumento') = 9  then 2
		else 3
	end as tipo_documento
	,replace(d.json::JSON->'sujetoExcluido'->>'numDocumento', '-', '') as documento
	,(d.json::JSON->'sujetoExcluido'->>'nombre') as nombre
	,TO_CHAR(c.fecha::DATE, 'DD/MM/YYYY') AS emision
	,d.sello_recibido as numero_serie
	,replace(d.codigo_generacion, '-', '') as numero_documento
	,(d.json::JSON->'resumen'->>'totalCompra') as monto
	,cast(0 AS NUMERIC) as retencion
	,cast(c.tipo_operacion AS NUMERIC) as tipo_operacion
	,cast(c.clasificacion AS NUMERIC) as clasificacion
	,cast(c.sector AS NUMERIC) as sector
	,cast(c.tipo_clasificacion AS NUMERIC) as tipo_clasificacion
	,5 as anexo
	,c.fecha
    ,d.codigo_generacion
    ,(d.json::JSON->'resumen'->>'reteRenta') as renta
from
	sujeto_excluidos as c
inner join
	dtes as d
	on
		d.sujeto_excluidos_id = c.id
where
	c.estado = true

	order by fecha asc;



DROP VIEW IF EXISTS ventas_rubros;
DROP VIEW IF EXISTS comprobantes_ventas_rubros;
CREATE OR REPLACE VIEW comprobantes_ventas_rubros AS
select
	comprobantes.correlativo,
	comprobantes.fecha,
	comprobantes.id as comprobantes_id,
	detalle_comprobantes.concepto,
	detalle_comprobantes.cantidad,
	case
        when anulacion_comprobantes.fecha is null or anulacion_comprobantes.fecha > comprobantes.fecha then detalle_comprobantes.neto
        else 0
    end as neto,
	case
        when anulacion_comprobantes.fecha is null or anulacion_comprobantes.fecha > comprobantes.fecha then detalle_comprobantes.gravado
        else 0
    end as gravado,
    case
        when anulacion_comprobantes.fecha is null or anulacion_comprobantes.fecha > comprobantes.fecha then detalle_comprobantes.propina
        else 0
    end as propina,
    case
        when anulacion_comprobantes.fecha is null or anulacion_comprobantes.fecha > comprobantes.fecha then detalle_comprobantes.iva
        else 0
    end as iva,
    case
        when anulacion_comprobantes.fecha is null or anulacion_comprobantes.fecha > comprobantes.fecha then detalle_comprobantes.cesc
        else 0
    end as cesc,
    case
        when anulacion_comprobantes.fecha is null or anulacion_comprobantes.fecha > comprobantes.fecha then detalle_comprobantes.advalorem
        else 0
    end as advalorem,
    case
        when anulacion_comprobantes.fecha is null or anulacion_comprobantes.fecha > comprobantes.fecha then detalle_comprobantes.total
        else 0
    end as total,
	detalle_comprobantes.rubros_id,
	detalle_comprobantes.registro as cuenta,
	detalle_comprobantes.tipo_registros,
    CAST(RIGHT(dtes.correlativo, 15) AS BIGINT) as numero_control,
	case
		when detalle_comprobantes.tipo_registros = 1 then ordenes.cajas_id
		when detalle_comprobantes.tipo_registros = 2 then turnos.cajas_id
		when detalle_comprobantes.tipo_registros = 3 then comandas.cajas_id
	end as cajas_id
from
	comprobantes
left join
	anulacion_comprobantes
ON
	anulacion_comprobantes.comprobantes_id = comprobantes.id
left join
	dtes
on
	dtes.comprobantes_id = comprobantes.id
left join
	tipo_comprobantes
ON
	tipo_comprobantes.id = comprobantes.tipo_comprobantes_id
left join
	detalle_comprobantes
ON
	detalle_comprobantes.comprobantes_id = comprobantes.id
left join
	turnos
ON
	turnos.id = comprobantes.turnos_id

left join
	comandas
on
	detalle_comprobantes.registro = comandas.id and detalle_comprobantes.tipo_registros = 3
left join
	ordenes
on
	detalle_comprobantes.registro = ordenes.id and detalle_comprobantes.tipo_registros = 1

where
	comprobantes.eliminado = false
and
	tipo_comprobantes.token in (7001,7002);

DROP VIEW IF EXISTS anulaciones_ventas_rubros;
CREATE OR REPLACE VIEW anulaciones_ventas_rubros AS
select
    comprobantes.correlativo,
    anulacion_comprobantes.fecha,
    comprobantes.id as comprobantes_id,
    detalle_comprobantes.concepto,
    detalle_comprobantes.cantidad,
    case
        when comprobantes.fecha = anulacion_comprobantes.fecha then detalle_comprobantes.neto * 0
        when anulacion_comprobantes.fecha > comprobantes.fecha then detalle_comprobantes.neto * -1
        else detalle_comprobantes.neto
    end as neto,
    case
        when comprobantes.fecha = anulacion_comprobantes.fecha then detalle_comprobantes.gravado * 0
        when anulacion_comprobantes.fecha > comprobantes.fecha then detalle_comprobantes.gravado * -1
        else detalle_comprobantes.gravado
    end as gravado,
    case
        when comprobantes.fecha = anulacion_comprobantes.fecha then detalle_comprobantes.propina * 0
        when anulacion_comprobantes.fecha > comprobantes.fecha then detalle_comprobantes.propina * -1
        else detalle_comprobantes.propina
    end as propina,
    case
        when comprobantes.fecha = anulacion_comprobantes.fecha then detalle_comprobantes.iva * 0
        when anulacion_comprobantes.fecha > comprobantes.fecha then detalle_comprobantes.iva * -1
        else detalle_comprobantes.iva
    end as iva,
    case
        when comprobantes.fecha = anulacion_comprobantes.fecha then detalle_comprobantes.cesc * 0
        when anulacion_comprobantes.fecha > comprobantes.fecha then detalle_comprobantes.cesc * -1
        else detalle_comprobantes.cesc
    end as cesc,
    case
        when comprobantes.fecha = anulacion_comprobantes.fecha then detalle_comprobantes.advalorem * 0
        when anulacion_comprobantes.fecha > comprobantes.fecha then detalle_comprobantes.advalorem * -1
        else detalle_comprobantes.advalorem
    end as advalorem,
    case
        when comprobantes.fecha = anulacion_comprobantes.fecha then detalle_comprobantes.total * 0
        when anulacion_comprobantes.fecha > comprobantes.fecha then detalle_comprobantes.total * -1
        else detalle_comprobantes.total
    end as total,
    detalle_comprobantes.rubros_id,
    detalle_comprobantes.registro as cuenta,
    detalle_comprobantes.tipo_registros,
    CAST(RIGHT(dtes.correlativo, 15) AS BIGINT) as numero_control,
    case
        when detalle_comprobantes.tipo_registros = 1 then ordenes.cajas_id
        when detalle_comprobantes.tipo_registros = 2 then turnos.cajas_id
        when detalle_comprobantes.tipo_registros = 3 then comandas.cajas_id
    end as cajas_id
from
    comprobantes
left join
    anulacion_comprobantes
on
    anulacion_comprobantes.comprobantes_id = comprobantes.id
left join
    dtes
on
    dtes.comprobantes_id = comprobantes.id
left join
    tipo_comprobantes
on
    tipo_comprobantes.id = comprobantes.tipo_comprobantes_id
left join
    detalle_comprobantes
on
    detalle_comprobantes.comprobantes_id = comprobantes.id
left join
    turnos
on
    turnos.id = comprobantes.turnos_id
left join
    comandas
on
    detalle_comprobantes.registro = comandas.id and detalle_comprobantes.tipo_registros = 3
left join
    ordenes
on
    detalle_comprobantes.registro = ordenes.id and detalle_comprobantes.tipo_registros = 1
where
    comprobantes.eliminado = false
and
    comprobantes.estado = false
and
    tipo_comprobantes.token in (7001, 7002);


DROP VIEW IF EXISTS nc_ventas_rubros;
CREATE OR REPLACE VIEW nc_ventas_rubros AS
select
	comprobantes.correlativo,
	comprobantes.fecha,
	comprobantes.id as comprobantes_id,
	detalle_comprobantes.concepto,
	detalle_comprobantes.cantidad,
	detalle_comprobantes.neto *-1 as neto,
	detalle_comprobantes.gravado *-1 as gravado,
	detalle_comprobantes.propina *-1 as propina,
	detalle_comprobantes.iva *-1 as iva,
	detalle_comprobantes.cesc *-1 as cesc,
	detalle_comprobantes.advalorem *-1 as advalorem,
	detalle_comprobantes.total *-1 as total,
	detalle_comprobantes.rubros_id,
	detalle_comprobantes.registro as cuenta,
	detalle_comprobantes.tipo_registros,
    CAST(RIGHT(dtes.correlativo, 15) AS BIGINT) as numero_control,
	case
		when detalle_comprobantes.tipo_registros = 1 then ordenes.cajas_id
		when detalle_comprobantes.tipo_registros = 2 then turnos.cajas_id
		when detalle_comprobantes.tipo_registros = 3 then comandas.cajas_id
	end as cajas_id
from
	comprobantes
left join
	dtes
on
	dtes.comprobantes_id = comprobantes.id
left join
	tipo_comprobantes
ON
	tipo_comprobantes.id = comprobantes.tipo_comprobantes_id
left join
	detalle_comprobantes
ON
	detalle_comprobantes.comprobantes_id = comprobantes.id
left join
	turnos
ON
	turnos.id = comprobantes.turnos_id

left join
	comandas
on
	detalle_comprobantes.registro = comandas.id and detalle_comprobantes.tipo_registros = 3
left join
	ordenes
on
	detalle_comprobantes.registro = ordenes.id and detalle_comprobantes.tipo_registros = 1

where
	comprobantes.eliminado = false
and
	comprobantes.estado = true
and
	dtes.error = false
and
	dtes.estado = 'PROCESADO'
and
	tipo_comprobantes.token = 7003;


/*AGRUPACION DE ANULACIONES Y VIGENTES*/
CREATE OR REPLACE VIEW ventas_rubros AS
select
	*,
	1 as origen
from
	comprobantes_ventas_rubros
union
select
	*,
	2 as origen
from
	anulaciones_ventas_rubros where neto < 0
union
select
	*,
	2 as origen
from
	nc_ventas_rubros
order by fecha asc, origen asc;


DROP VIEW IF EXISTS anexo_contribuyentes;
CREATE OR REPLACE VIEW anexo_contribuyentes AS
select
	TO_CHAR(c.fecha::DATE, 'DD/MM/YYYY') AS emision
	,4 as clase_documento
	,LPAD(cast(d.tipo_dte as text),2, '0') as tipo_documento
	,replace(d.correlativo, '-', '') as numero_resolucion
	,d.sello_recibido as numero_serie
	,replace(d.codigo_generacion, '-', '') as numero_documento
	,c.correlativo as correlativo_interno
	,(d.json::JSON->'receptor'->>'nombre') as nombre
	,(d.json::JSON->'receptor'->>'nrc') as nrc
	,case
		when d.json::JSON->'receptor'->>'nrc' is NOT NULL THEN d.json::JSON->'receptor'->>'nrc'
		when LENGTH(d.json::JSON->'receptor'->>'nit') = 14  then d.json::JSON->'receptor'->>'nit'
		else null
	end as identificacion
	,case
		when (d.json::JSON->'receptor'->>'nrc') is NULL AND LENGTH(d.json::JSON->'receptor'->>'nit') = 9  then d.json::JSON->'receptor'->>'nit'
		else null
	end as dui
	,ROUND(cast(c.exento AS NUMERIC), 2) as exento
	,cast(0 AS NUMERIC) as no_sujetas
	,ROUND(cast(c.gravado AS NUMERIC), 2) as gravado
	,ROUND(cast(c.iva AS NUMERIC), 2) as iva
	,cast(0 AS NUMERIC)  as cuentas_tercero
	,cast(0 AS NUMERIC) as debito_cuentas_tercero
	,ROUND(cast((c.total + c.percepcion)AS NUMERIC), 2) as total_ventas
    ,c.estado
	,case
	when ac.response IS NOT NULL and ac.response::JSON->>'estado' IS NOT NULL and coalesce(ac.response::JSON->>'estado', '') = 'PROCESADO'
	then
		ac.fecha
	ELSE null
	END as anulacion
	,(ac.response::JSON->>'estado') as anulacion_estado
	,(ac.response::JSON->>'selloRecibido') as anulacion_sello
	,c.fecha
	, 1 as anexo
from
	comprobantes as c
inner join
	dtes as d
	on
		d.comprobantes_id = c.id
left join
	anulacion_comprobantes as ac
		ON
			ac.comprobantes_id = c.id
		and c.estado = false
where
	d.tipo_dte in (3, 5)
and
	c.eliminado = false;



DROP VIEW IF EXISTS ventas_habitaciones;
CREATE OR REPLACE VIEW ventas_habitaciones AS
select
	comprobantes.correlativo,
	comprobantes.fecha,
	comprobantes.titular,
	comprobantes.id as comprobantes_id,
	detalle_comprobantes.concepto,
	detalle_comprobantes.cantidad,
	detalle_comprobantes.neto,
	detalle_comprobantes.total,
	ROUND((detalle_comprobantes.neto::numeric) * (detalle_comprobantes.cantidad::numeric), 2) as tneto,
	ROUND((detalle_comprobantes.total::numeric) * (detalle_comprobantes.cantidad::numeric), 2) as ttotal,
	detalle_comprobantes.rubros_id,
	detalle_comprobantes.registro as cuenta,
	detalle_comprobantes.tipo_registros,
	detalle_reservas.users_id as reserva_user_id,
	detalle_reservas.reservaciones_id as reservacion,
	recepciones.users_id as recepcion_user_id,
	recepciones.id as recepcion,
	habitaciones.sucursales_id,
	habitaciones.numero_habitacion

from
	comprobantes
left join
	tipo_comprobantes
ON
	tipo_comprobantes.id = comprobantes.tipo_comprobantes_id
left join
	detalle_comprobantes
ON
	detalle_comprobantes.comprobantes_id = comprobantes.id
left join
	turnos
ON
	turnos.id = comprobantes.turnos_id

left join
	recepciones
on
	detalle_comprobantes.registro = recepciones.id and detalle_comprobantes.tipo_registros = 2
LEFT JOIN
	habitaciones
ON
	habitaciones.id = recepciones.habitaciones_id
left join
	detalle_reservas
ON
	detalle_reservas.id = recepciones.detalle_reservas_id
where
	comprobantes.eliminado = false
and
	comprobantes.estado = true
and
	tipo_comprobantes.token in (7001,7002)
and
	detalle_comprobantes.tipo_registros = 2;


/*vistas para alertas de UIF*/
DROP VIEW IF EXISTS get_efectivo;
DROP VIEW IF EXISTS get_bancos;
DROP VIEW IF EXISTS get_comprobantes_pagos;
CREATE OR REPLACE VIEW get_comprobantes_pagos AS
select
	comprobantes.*,
	SUM(comprobantes_pagos.monto) forma_pago_total,
	forma_pagos.token
from
	comprobantes
left join
	comprobantes_pagos
ON
	comprobantes_pagos.comprobantes_id = comprobantes.id
left join
	forma_pagos
ON
	forma_pagos.id = comprobantes_pagos.forma_pagos_id
where
	comprobantes.eliminado = false
group by comprobantes.id, forma_pagos.token;


CREATE OR REPLACE VIEW get_efectivo AS
select
	clientes_id,
	round(cast(sum(forma_pago_total) as numeric) , 2) as total_efectivo,
	fecha,
	count(id) as numero_comprobantes,
	titular
from
	get_comprobantes_pagos
where
	token = 6001
group by clientes_id, fecha, titular;


CREATE OR REPLACE VIEW get_bancos AS
select
	clientes_id,
	round(cast(sum(forma_pago_total) as numeric) , 2) as total_bancos,
	fecha,
	count(id) as numero_comprobantes,
	titular
from
	get_comprobantes_pagos
where
	token = 6003
group by clientes_id, fecha, titular;



/* VENTAS A CREDITO */
DROP VIEW IF EXISTS ventas_credito;
CREATE OR REPLACE VIEW ventas_credito AS
select
	comprobantes.*,
	clientes.nombre,
	clientes.empleado,
	clientes.credito,
	clientes.tipo_cliente,
	clientes.email,
	comprobantes_pagos.monto,
	comprobantes_pagos.forma_pagos_id,
	forma_pagos.forma,
	forma_pagos.token
from
	comprobantes
inner join
	clientes
on
	clientes.id = comprobantes.clientes_id
left join
	comprobantes_pagos
ON
	comprobantes_pagos.comprobantes_id = comprobantes.id
left join
	forma_pagos
ON
	forma_pagos.id = comprobantes_pagos.forma_pagos_id
where

	comprobantes.eliminado = false
and
	comprobantes.estado = true
and
	forma_pagos.token = 6002;

DROP VIEW IF EXISTS ventas_credito_pendientes;
CREATE OR REPLACE VIEW ventas_credito_pendientes AS
select
	comprobantes.*,
	clientes.nombre,
	clientes.empleado,
	clientes.credito,
	clientes.accionista,
	clientes.tipo_cliente,
	clientes.email,
	comprobantes_pagos.monto,
	comprobantes_pagos.forma_pagos_id,
	forma_pagos.forma,
	forma_pagos.token,
	DATE_PART('day', NOW() - comprobantes.fecha) AS dias_transcurridos,
	municipios.departamentos_id,
	periodos_creditos.dias
from
	comprobantes
inner join
	clientes
on
	clientes.id = comprobantes.clientes_id
left join
	municipios
ON
	municipios.id = clientes.municipios_id
left join
	comprobantes_pagos
ON
	comprobantes_pagos.comprobantes_id = comprobantes.id
left join
	forma_pagos
ON
	forma_pagos.id = comprobantes_pagos.forma_pagos_id
left join
	periodos_creditos
ON
	periodos_creditos.id = clientes.periodos_creditos_id
where

	comprobantes.eliminado = false
and
	comprobantes.estado = true
and
	forma_pagos.token = 6002
and
	comprobantes.id not in (select abonos_detalles.comprobantes_id from abonos_detalles);

DROP VIEW IF EXISTS ventas_usuarios;
CREATE OR REPLACE VIEW ventas_usuarios AS
select
	comprobantes.fecha,
	comprobantes.correlativo,
	comandas.id,
	precios.detalle,
	comanda_detalles.cantidad,
	comanda_detalles.precio,
	ROUND(cast((comanda_detalles.cantidad  * comanda_detalles.precio) as NUMERIC), 2) as venta,
	comanda_detalles.users_comanda_id,
	comandas.cajas_id,
	comanda_detalles.id as comanda_detalles_id
from
	comandas
left join
	comanda_detalles
on
	comanda_detalles.comandas_id = comandas.id
and
	comanda_detalles.anulado = false
left join
	precios
ON
	precios.id = comanda_detalles.precios_id
left join
	registros
on
	comandas.id = registros.registro
and
	registros.tipo_registros = 3
left join
	comprobantes
ON
	comprobantes.id = registros.comprobantes_id and comprobantes.estado = true and comprobantes.eliminado = false
where
	comprobantes.id > 0
order by comprobantes.fecha;


DROP VIEW IF EXISTS alertas_anticipos;
CREATE OR REPLACE VIEW alertas_anticipos as
select
	anticipos.*,
	clientes.nombre as nombre_cliente,
	users.name as user_realiza,
	cajas.caja as nombre_caja
from
	anticipos
left join
	clientes
ON
	clientes.id = anticipos.clientes_id
left join
	users
ON
	users.id = anticipos.users_id
left join
	turnos
ON
	turnos.id = anticipos.turnos_id
left join
	cajas
ON
	cajas.id = turnos.cajas_id
where
	(anticipos.fecha_aplicacion >= CURRENT_DATE + INTERVAL '3 months'
	 or (CURRENT_DATE - INTERVAL '3 months') >= anticipos.fecha_aplicacion)
	and anticipos.estado = true
	and anticipos.anulado = false
	and anticipos.monto > 0
order by anticipos.fecha_aplicacion desc;


DROP VIEW IF EXISTS comandas_activas_turno;
CREATE OR REPLACE VIEW comandas_activas_turno as
select
	comandas.id,
	comandas.fecha,
	comandas.turnos_id,
	comandas.tipo_comanda,
    comandas.clientes_id,
	turnos.fecha as fecha_turnos,
	clientes.nombre,
	clientes.credito
from
	comandas
left join
	turnos
on
	turnos.id = comandas.turnos_id
left join
	clientes
on
	comandas.clientes_id = clientes.id
where
	comandas.estado = true
and
	comandas.facturada = false
and
	comandas.eliminada = false
and
	comandas.anulada = false
and
	comandas.tipo_comanda = 1
and
	turnos.estado = false
order by clientes.id desc;
