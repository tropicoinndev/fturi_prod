<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <table style="text-transform: uppercase;">
        <tbody>
            <tr>
                <td colspan="2" valign="middle"
                    style="font-weight: bold; text-transform: uppercase; font-size: 14pt; text-align: center; height: 60px;">
                    {{ env('empresa') }}
                </td>
            </tr>
            <tr>
                <td colspan="2" style="word-wrap: break-word; white-space: normal; height: 100px;" valign="middle">
                    Art. 9 Ley de Lavado de Dinero y Activos:
                    Las instituciones deberán completar este formulario por operaciones realizadas por
                    los clientes, sea individual o multiple, que en un mismo dia sobrepasen los $10,000.00 en efectivo o
                    $25,000.00 en cheque.
                </td>
            </tr>
            <tr>
                <td colspan="2"
                    style="background: #ECEFF1; font-weight: bold; text-transform: uppercase; font-size: 14pt; text-align: center;">
                    FORMULARIO DE OPERACIONES REGULADAS
                </td>
            </tr>

            <tr>
                <td colspan="2"
                    style="background: #ECEFF1; font-weight: bold; text-transform: uppercase; font-size: 12pt; text-align: center;">
                    PARTE I - PERSONAS INVOLUCRADAS EN LA TRANSACCIÓN
                </td>
            </tr>
            <tr>
                <td colspan="2"
                    style="background: #ECEFF1; font-weight: bold; text-transform: uppercase; font-size: 12pt; text-align: center;">
                    SECCIÓN A: Persona que realiza físicamente la transacción
                </td>
            </tr>
            <tr>
                <td style="width: 6cm">
                    Distinta al cliente:
                </td>
                <td style="width: 8cm">
                    {{ $p->distinto ? 'SI' : 'NO' }}
                </td>
            </tr>
            @php
                $ap = $p->seccion_a_persona_id === null;
            @endphp
            <tr>
                <td>
                    Apellidos:
                </td>
                <td>
                    {{ $p?->apersona?->apellidos }}
                </td>
            </tr>
            <tr>
                <td>
                    Nombres:
                </td>
                <td>
                    {{ $p?->apersona?->nombre }}
                </td>
            </tr>
            <tr>
                <td>
                    Lugar de nacimiento:
                </td>
                <td>
                    {{ $p?->apersona?->nacimiento }}
                </td>
            </tr>
            <tr>
                <td>
                    Departamento:
                </td>
                <td>
                    {{ $p?->apersona?->departamentos?->departamento }}
                </td>
            </tr>
            <tr>
                <td>
                    Fecha de nacimiento:
                </td>
                <td>
                    {{ $p?->apersona?->fecha_nacimiento }}
                </td>
            </tr>
            <tr>
                <td>
                    Nacionalidad:
                </td>
                <td>
                    {{ $p?->apersona?->departamentos?->paises?->nacionalidad ?? $p?->apersona?->paises->nacionalidad }}
                </td>
            </tr>
            <tr>
                <td>
                    Estado civil:
                </td>
                <td>
                    {{ $p?->apersona?->estado_civil }}
                </td>
            </tr>
            <tr>
                <td>
                    Tipo de documento:
                </td>
                <td>
                    {{ $p?->apersona?->identificaciones?->identificacion }}
                </td>
            </tr>
            <tr>
                <td>
                    Nº documento:
                </td>
                <td>
                    {{ $p?->apersona?->identificacion }}
                </td>
            </tr>
            <tr>
                <td>
                    Profesión u Oficio:
                </td>
                <td>
                    {{ $p?->apersona?->profesion }}
                </td>
            </tr>
            <tr>
                <td>
                    Domicilio:
                </td>
                <td>
                    {{ $p?->apersona?->domicilio }}
                </td>
            </tr>
            <tr>
                <td colspan="2"
                    style="background: #ECEFF1; font-weight: bold; text-transform: uppercase; font-size: 12pt; text-align: center;">
                    SECCIÓN B: Persona o Personas a cuyo nombre se realiza la transacción
                </td>
            </tr>
            <tr>
                <td>
                    Tipo de persona:
                </td>
                <td>
                    {{ $p->tipo_persona ? 'Persona natural' : 'Jurídica' }}
                </td>
            </tr>
            <tr>
                <td colspan="2"
                    style="background: #ECEFF1; font-weight: bold; text-transform: uppercase; font-size: 12pt; text-align: center;">
                    B-1 Persona Natural
                </td>
            </tr>
            @php
                $ap = $p->seccion_b_persona_id === null;
            @endphp
            <tr>
                <td>
                    Apellidos:
                </td>
                <td>
                    {{ $p?->bpersona?->apellidos }}
                </td>
            </tr>
            <tr>
                <td>
                    Nombres:
                </td>
                <td>
                    {{ $p?->bpersona?->nombre }}
                </td>
            </tr>
            <tr>
                <td>
                    Lugar de nacimiento:
                </td>
                <td>
                    {{ $p?->bpersona?->nacimiento }}
                </td>
            </tr>
            <tr>
                <td>
                    Departamento:
                </td>
                <td>
                    {{ $p?->bpersona?->departamentos?->departamento }}
                </td>
            </tr>
            <tr>
                <td>
                    Fecha de nacimiento:
                </td>
                <td>
                    {{ $p?->bpersona?->fecha_nacimiento }}
                </td>
            </tr>
            <tr>
                <td>
                    Nacionalidad:
                </td>
                <td>
                    {{ $p?->bpersona?->departamentos?->paises?->nacionalidad ?? $p?->bpersona?->paises->nacionalidad }}
                </td>
            </tr>
            <tr>
                <td>
                    Estado civil:
                </td>
                <td>
                    {{ $p?->bpersona?->estado_civil }}
                </td>
            </tr>
            <tr>
                <td>
                    Tipo de documento:
                </td>
                <td>
                    {{ $p?->bpersona?->identificaciones?->identificacion }}
                </td>
            </tr>
            <tr>
                <td>
                    Nº documento:
                </td>
                <td>
                    {{ $p?->bpersona?->identificacion }}
                </td>
            </tr>
            <tr>
                <td>
                    Profesión u Oficio:
                </td>
                <td>
                    {{ $p?->bpersona?->profesion }}
                </td>
            </tr>
            <tr>
                <td>
                    Domicilio:
                </td>
                <td>
                    {{ $p?->bpersona?->domicilio }}
                </td>
            </tr>
            <tr>
                <td colspan="2"
                    style="background: #ECEFF1; font-weight: bold; text-transform: uppercase; font-size: 12pt; text-align: center;">
                    B-2 Persona Jurídica
                </td>
            </tr>
            @php
                $ap = $p->seccion_b_juridico_id === null;
            @endphp
            <tr>
                <td>
                    Nombre o Razón Social:
                </td>
                <td>
                    {{ $p?->bjuridico?->nombre }}
                </td>
            </tr>
            <tr>
                <td valign="middle">
                    Dirección Comercial:
                </td>
                <td style="word-wrap: break-word; white-space: normal; height: 100px;" valign="middle">
                    @if ($p?->bjuridico != null)
                        {{ $p?->bjuridico?->direccion }}
                        <b>
                            Municipio:
                        </b>
                        {{ $p?->bjuridico?->municipios?->municipio ?? 'Sin información' }}
                        <b>
                            Departamento:
                        </b>
                        {{ $p?->bjuridico?->municipios?->departamentos?->departamento ?? 'Sin información' }}
                    @endif
                </td>
            </tr>
            <tr>
                <td>
                    Actividad Económica:
                </td>
                <td>
                    {{ $p?->bjuridico?->actividades->actividad }}
                </td>
            </tr>
            <tr>
                <td>
                    Identificación Tributario:
                </td>
                <td>
                    @if ($p?->bjuridico != null)
                        <ul>
                            <li>
                                <b>NRC: </b>
                                {{ $p?->bjuridico?->detalle?->nrc ?? 'Sin información' }}
                            </li>
                            @forelse ($p?->bjuridico?->identificaciones as $i)
                                <li>
                                    <b>
                                        {{ $i?->identificaciones?->identificacion }}:
                                    </b>
                                    {{ $i?->numero }}
                                </li>
                            @empty
                                <li>No se encontraron mas identificaciones.</li>
                            @endforelse
                        </ul>
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="2"
                    style="background: #ECEFF1; font-weight: bold; text-transform: uppercase; font-size: 12pt; text-align: center;">
                    PARTE II - DETALLE DE LA TRANSACCIÓN EN EFECTIVO U OTRO MEDIO.
                </td>
            </tr>
            <tr>
                <td valign="middle">
                    Punto de Servicio:
                </td>
                <td style="word-wrap: break-word; white-space: normal; height: 60px;" valign="middle">
                    Punto de venta: {{ $p->caja->caja }} ·
                    Sucursal: {{ $p->caja->sucursales->sucursal }}
                </td>
            </tr>
            <tr>
                <td>
                    Municipio:
                </td>
                <td>
                    {{ $p->caja->sucursales->municipios->municipio }}
                </td>
            </tr>
            <tr>
                <td>
                    Departamento:
                </td>
                <td>
                    {{ $p->caja?->sucursales?->municipios?->departamentos?->departamento }}
                </td>
            </tr>
            <tr>
                <td valign="middle">
                    N° de Comprobante:
                </td>
                <td style="word-wrap: break-word; white-space: normal; height: 60px;" valign="middle">
                    Correlativo Interno: {{ $p->comprobante->correlativo }} ·
                    Codigo de generacion:
                    {{ $p->comprobante?->dteOne?->codigo_generacion ?? 'SIN DTE' }}
                </td>
            </tr>
            <tr>
                <td>
                    Clase de Servicio:
                </td>
                <td>
                    {{ $p->clase_servicio }}
                </td>
            </tr>
            <tr>
                <td>
                    Observación de Transacción:
                </td>
                <td>
                    {{ $p->observaciones }}
                </td>
            </tr>
            <tr>
                <td>
                    Monto de la Transacción:
                </td>
                <td>
                    ${{ number_format($p?->comprobante?->total, 2) }}
                </td>
            </tr>
            <tr>
                <td>
                    Valor en Efectivo:

                </td>
                <td>
                    ${{ number_format($p->efectivo, 2) }}
                </td>
            </tr>
            <tr>
                <td>
                    Valor en Cheque:

                </td>
                <td>
                    ${{ number_format($p->cheque, 2) }}
                </td>
            </tr>
            <tr>
                <td>
                    Valor en Tarjeta de Débito o Crédito

                </td>
                <td>
                    ${{ number_format($p->tarjeta, 2) }}
                </td>
            </tr>
            <tr>
                <td>
                    Procedencia del Efectivo:
                </td>
                <td>
                    {{ $p->comprobante?->procedencia }}
                </td>
            </tr>
            <tr>
                <td>
                    Fecha de Transacción:
                </td>
                <td>
                    {{ $p->comprobante?->fecha }}
                </td>
            </tr>
            <tr>
                <td>
                    Cargo del empleado:
                </td>
                <td>
                    {{ $p->cargo }}
                </td>
            </tr>
            <tr>
                <td>
                    Nombre del empleado:
                </td>
                <td>
                    {{ $p->comprobante?->users?->empleadoOne?->nombre_completo ?? 'Usuario sin registro de empleado' }}
                </td>
            </tr>
            <tr>
                <td>
                    Firma del empleado:
                </td>
                <td>
                    ______________________________________
                </td>
            </tr>
            <tr>
                <td>
                    Nombre del jefe de area:
                </td>
                <td>
                    {{ $p?->supervisa?->name ?? 'Al completar la revision del formulario aparecerá aquí el nombre de quien superviso el formulario' }}
                </td>
            </tr>
            <tr>
                <td>
                    Firma del jefe de area:
                </td>
                <td>
                    ______________________________________
                </td>
            </tr>
            <tr>
                <td colspan="2"
                    style="background: #ECEFF1; font-weight: bold; text-transform: uppercase; font-size: 12pt; text-align: center;">
                    PARTE III - IDENTIFICACIÓN DEL SUJETO OBLIGADO
                </td>
            </tr>
            <tr>
                <td colspan="2"
                    style="background: #ECEFF1; font-weight: bold; text-transform: uppercase; font-size: 12pt; text-align: center;"
                    valign="middle">
                    PERSONA JURÍDICA
                </td>
            </tr>
            <tr>
                <td>
                    Nombre o Razón Social:
                </td>
                <td>
                    {{ env('empresa', 'TURISTICAS DE ORIENTE, S.A. DE C.V.') }}
                </td>
            </tr>
            <tr>
                <td valign="middle">
                    Dirección Comercial:
                </td>
                <td style="word-wrap: break-word; white-space: normal; height: 80px;" valign="middle">
                    {{ $p?->caja?->sucursales?->direccion }}
                </td>
            </tr>
            <tr>
                <td>
                    Actividad Económica:
                </td>
                <td>
                    {{ $p?->caja?->sucursales?->giro }}
                </td>
            </tr>
            <tr>
                <td valign="middle">
                    Identificación Tributario:
                </td>
                <td style="word-wrap: break-word; white-space: normal; height: 60px;" valign="middle">
                    <ul>
                        <li>
                            NRC: {{ $p?->caja?->sucursales?->nrc }}
                        </li>
                        <li>
                            NIT: {{ $p?->caja?->sucursales?->nit }}
                        </li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td>
                    Fecha de envió:
                </td>
                <td>
                    {{ $p?->fecha_envio }}
                </td>
            </tr>
            <tr>
                <td colspan="2" style="word-wrap: break-word; white-space: normal; height: 30px; text-align: center;"
                    valign="middle">
                    <b>
                        (ANEXAR COPIA DE CHEQUE, TRANSFERENCIA, ETC. A ESTE FORMULARIO)
                    </b>
                </td>
            </tr>

        </tbody>
    </table>
</body>

</html>
