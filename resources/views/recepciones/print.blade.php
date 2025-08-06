@extends('layouts.print_b_vertical')
@section('style')
    <style>
        * {
            text-transform: uppercase;
            font-size: 9pt;
        }

        .title-table {
            background: rgb(255, 255, 255);
            font-size: 11pt;
            text-align: center;
            color: rgb(26, 26, 26);
            font-weight: 400;
        }

        .w-15 {
            /*width: 200px;*/
            width: 6cm;
        }

        .fp-title {
            font-size: 8.5pt;
            width: 2cm;
            overflow: hidden;
            text-align: right;
        }

        .dollar {
            font-size: 9.5pt;
            text-align: right;
        }

        .b {
            font-weight: 500;
            color: #555;
        }

        .b1 {
            font-weight: 100;

        }

        .bt-1 {
            border-top: 1px #000 solid;
        }

        .tb-title {
            background: rgb(203, 203, 203);
            color: rgb(26, 26, 26);
        }

        .space {
            height: 20px;
        }

        .bg-turno {
            background: #d1d1d1;
            color: #343434;
        }

        .bg-total {
            background: #d8ffde;
            color: #1c1c1c;
            font-weight: 600;
        }

        tr td {
            padding: 2px 0px;
        }

        .row {
            width: 25cm;
            display: inline-block;
        }

        .col-6 {
            width: 12.5cm;
            float: left;
            margin-bottom: 0.25cm;
        }

        .text-end {
            text-align: right;
        }

        .border {
            border: 1px #737373 solid !important;
        }

        .page_break {
            page-break-before: always;
        }

        .float-right {
            float: right;
        }
    </style>
@endsection
@section('titulo')
    <div class="titulo">
        TARJETA DE REGISTRO
    </div>
    <p style="position: fixed; top: -72px; left: 8cm; right: 0; font-size: 10pt; letter-spacing: 0.5px;">REG.
        #{{ $p->id }}</p>
@endsection
@section('content')
    <main>
        <div class="row cliente rounded-3 p-2 border text-uppercase fs-4">
            <div class="col-12">
                <b>
                    CLIENTE:
                </b>
                {{ $p->clientes_id > 0 ? $p->clientes->nombre : $p->titular }}
            </div>
            <div class="col-12">
                <b>
                    dirección:
                </b>
                {{ $p->clientes_id > 0 ? $p->clientes->direccion : '-- No agregada --' }}
            </div>
            <div class="col-5">
                <b>
                    municipio:
                </b>
                {{ $p->clientes_id > 0 ? $p->clientes?->municipios?->municipio ?? '' : '-- No agregada --' }}
            </div>
            <div class="col-5">
                <b>
                    departamento:
                </b>
                {{ $p->clientes_id > 0 ? $p->clientes?->municipios?->departamentos?->departamento ?? '' : '-- No agregada --' }}
            </div>
            <div class="col-5">
                <b>
                    Pais:
                </b>
                {{ $p->clientes_id > 0 ? $p->clientes?->municipios?->departamentos->paises->pais ?? '' : '-- No agregada --' }}
            </div>
            <div class="col-5">
                <b>
                    Forma de pago:
                </b>
                {{ count($p->getAnticipos) > 0 ? $p->getAnticipos[0]->anticipos->forma_pagos->forma : '-- Aun sin pagos --' }}
            </div>
            <div class="col-12">
                @if ($p->clientes_id > 0)
                    @foreach ($p->clientes->contactos as $c)
                        <b>{{ $c->contactos->contacto }}:</b> {{ $c->valor }}
                        @if ($loop->last)
                        @else
                            <b>·</b>
                        @endif
                    @endforeach
                @endif

            </div>

        </div>
        <div class="row huespedes rounded-3 border p-2 text-uppercase mt-2">
            <div class="col-12 text-muted">
                INFORMACIÓN DE HUESPEDES
            </div>
            <div class="col-12">
                <table class="table table-light fs-6">
                    <thead class="thead-light">
                        <tr>
                            <th>Habitacion</th>
                            <th>Nombre</th>
                            <th>Nacionalidad</th>
                            <th>Telefono</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($p->huespedes as $h)
                            <tr>
                                <td>{{ $p->habitaciones->numero_habitacion }}</td>
                                <td>{{ $h->huesped->nombre }}</td>
                                <td>{{ $h->huesped->paises->nacionalidad ?? $h->huesped?->municipios?->departamentos?->paises?->nacionalidad }}
                                </td>
                                <td>{{ $h->huesped->telefono ?? '---' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row huespedes rounded-3 border p-2 text-uppercase mt-2">

            <div class="col-12 text-muted">
                INFORMACIÓN DE LA ESTADÍA
                <span class="float-right"><b>Realiza:</b>{{ $p->usuarios->name }}</span>
            </div>

            <div class="col-12">
                <table class="table table-light fs-4">
                    <thead class="thead-light">
                        <tr>
                            <th>HABITACIÓN</th>
                            <th>TIPO DE HABITACIÓN</th>
                            <th>FECHAS DE ESTADÍA</th>
                            <th>CANT.</th>
                            <th class="text-right">MONTO</th>
                            <th class="text-right">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $suma = 0;
                        @endphp

                        @php
                            $dias =
                                (strtotime(\Carbon::parse($p->fecha_salida)->format('Y-m-d')) -
                                    strtotime(\Carbon::parse($p->fecha_ingreso)->format('Y-m-d'))) /
                                (60 * 60 * 24);

                            $tarifa = $p->tarifas->monto / (1 + env('iva', 0.13) + env('cesc', 0.05));
                            $suma += $ttarifa = $tarifa * $dias;
                        @endphp
                        <tr>
                            <td>HABITACION {{ $p->habitaciones->numero_habitacion }}</td>
                            <td>
                                {{ $p->habitaciones->relacionFormaHabitaciones->forma_habitacion }}
                                {{ $p->habitaciones->relacionTipoHabitaciones->tipo_habitacion }}

                            </td>
                            <td>{{ \Carbon::parse($p->fecha_ingreso)->format('d/m/Y') }} AL
                                {{ \Carbon::parse($p->fecha_salida)->format('d/m/Y') }}</td>
                            <td style="min-width: 45px;">{{ $dias }} {{ $dias > 1 ? 'dias' : 'dia' }}
                            </td>
                            <td class="text-right">${{ number_format($tarifa, 2) }}</td>
                            <td class="text-right">${{ number_format($ttarifa, 2) }}</td>
                        </tr>
                        @php
                            $cIva = 0;
                            $cCesc = 0;
                            $cPropina = 0;
                            $cTotal = 0;
                        @endphp
                        @foreach ($p->cargos as $cargo)
                            @php
                                $cIva += $cargo->tiva;
                                $cCesc += $cargo->tcesc;
                                $cPropina += $cargo->tpropina;
                                $cTotal += $cargo->tNeto;
                            @endphp
                            <tr>
                                <td colspan="3">
                                    {{ $cargo->cargos->cargo }}
                                </td>
                                <td>
                                    {{ $cargo->cantidad }}
                                </td>
                                <td class="text-right">
                                    ${{ number_format($cargo->neto, 2) }}
                                </td>
                                <td class="text-right">
                                    ${{ number_format($cargo->tneto, 2) }}
                                </td>
                            </tr>
                        @endforeach

                        @php
                            $cesc = $suma * env('cesc', 0.05);
                            $cesc += $cCesc;
                            $iva = $suma * env('iva', 0.13);
                            $iva += $cIva;
                            $suma += $cTotal;
                            $total = $suma + $cesc + $iva;
                            $anticipos = count($p->getAnticipos) > 0 ? $p->getAnticipos->sum('anticipos_sum_monto') : 0;
                        @endphp
                        <tr>
                            <td colspan="3" rowspan="6" class="text-danger text-uppercase text-justify p-3">
                                ESTIMADO CLIENTE, LE ROGAMOS VERIFIQUE TODA LA INFORMACIÓN (TELÉFONOS, EMAIL,
                                ETC.) DE SU EMPRESA E INFORMACIÓN PERSONAL.
                            </td>
                            <td rowspan="6"></td>
                            <td class="text-right bt-1">
                                <b>SUB-TOTAL</b>
                            </td>
                            <td class="text-right bt-1">
                                ${{ number_format($suma, 2) }}
                            </td>
                        </tr>
                        <tr>

                            <td class="text-right">
                                <b>CESC</b>
                            </td>
                            <td class="text-right">
                                ${{ number_format($cesc, 2) }}
                            </td>
                        </tr>
                        <tr>

                            <td class="text-right">
                                <b>IVA</b>
                            </td>
                            <td class="text-right">
                                ${{ number_format($iva, 2) }}
                            </td>
                        </tr>
                        <tr>

                            <td class="text-right bt-1">
                                <b>Total</b>
                            </td>
                            <td class="text-right bt-1">
                                ${{ number_format($total, 2) }}
                            </td>
                        </tr>
                        <tr>

                            <td class="text-right">
                                <b>ANTICIPOS</b>
                            </td>
                            <td class="text-right">
                                ${{ number_format($anticipos, 2) }}
                            </td>
                        </tr>
                        <tr>

                            <td class="text-right">
                                <b>Diferencia</b>
                            </td>
                            <td class="text-right">
                                ${{ number_format($total - $anticipos, 2) }}
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
        <div class="page_break"></div>

        <div class="terminos">
            <div class="row">

                <div class="col-12 text-center">
                    Estoy de acuerdo en desocupar mi habitación a la 1:00PM. <br>
                    I agree to vacate my room by 1:00PM in this date.
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-justify">
                    <b>ESTIMADO HUESPED:</b> Sirvase tomar nota que el hotel no se hace responsable por los objetos
                    de valor que se dejen en su habitación. El hotel cuenta con cajas de seguridad en las habitaciones,
                    Standar Suite, Superior Suite, Junior Suite y Master Suite para uso de sus
                    huespedes; libres de cargo alguno. Por favor solicitela a su llegada en recepción. Quedando la
                    única llave de sus cajas de seguridad bajo su custodia y si es extraviada por usted o algun
                    miembro de sus acompañantes tendra que pagar al hotel el importe de $60.00 U.S al tipo de cambio
                    que se encuentre vigente.
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-justify">
                    <b>DEAR GUEST:</b> Please read the following carefully. The hotel will not take any
                    responsability if you leave any valueble objects in your room. The hotel has a safe for the
                    guests free of charge. Please ask for it at the reception when you arrive. There is only one key
                    which will be given to you, if it's lost by you or your companion there is a $60.00 U.S change
                    at the actual exchange rate.
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-justify">
                    <b>Observaciones:</b> {{ $p->descripcion ?? '--Sin observaciones' }}
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12 text-justify text-uppercase">
                    Recibo __________ llaves de habitacion y me comprometo a devolverlas en buen estado al realizar
                    el check out de lo contrario cancelare a hotel tropico inn la cantidad de $10.00 por cada llave
                    electronica extraviada.
                </div>
            </div>
            <div class="row mt-2 mb-2">
                <div class="col-12 text-justify text-uppercase">
                    @if ($p->detalle_reservas_id > 0)
                        <b>Ventas:</b>
                        {{ $p->reservaciones->users->name }} ·
                    @endif
                    <b>Tipo de facturacion:</b>
                    {{ $p->clientes->tipo_cliente ? 'Comprobante de Consumidor Final' : 'Comprobante de Credito Fiscal' }}
                </div>
            </div>
            <div class="firmas text-uppercase mt-2">
                <div class="row mt-4">
                    <div class="col-6">

                        F.: __________________________________________________
                        <br>
                        @if (isset($p->huespedes[0]))
                            {{ $p->huespedes[0]->huesped->nombre }}
                        @else
                            {{ $p->clientes_id > 0 ? $p->clientes->nombre : $p->titular }}
                        @endif
                        <br>
                        <b>
                            HUESPED
                        </b>

                    </div>
                    <div class="col-6">
                        <p>
                            ACEPTO LAS CONDICIONES DE INGRESO Y MEDIDAS DE BIOSEGURIDAD A SEGUIR EN LAS INSTALACIONES
                            DEL HOTEL.
                        </p>
                    </div>
                </div>
            </div>

        </div>

        <div class="page_break"></div>

        {{-- Politicas --}}
        <div>
            <ul style="font-size: 13px; line-height: 25px;">
                <li>Toda persona que entre al hotel en calidad de huésped, tiene la obligación de registrarse en recepción
                    por cada habitación.</li>
                <li>El cobro de la habitación se genera a partir de la entrega de la misma.</li>
                <li>La hora de entrega de la habitación en el hotel (CHECK IN) es a las 03:00 P.M.</li>
                <li>La hora de la salida de la habitación en el hotel (CHECK OUT) es a la 01:00 M.D.</li>
                En caso de hacer un CHECK OUT posterior al acordado, se cobrará una tarifa de $15.00 dólares adicionales; Si
                el huésped decide salir después de las 05:00 pm, estará obligado a pagar tarifa de la noche completa.
                <li>En caso de escándalos o actos que pongan en riesgo la integridad de los demás huéspedes, ya sea en el
                    interior de las habitaciones o áreas de uso común propiedad del hotel, se solicitará desalojar
                    inmediatamente las habitaciones y cubrir el costo totoal de la habitación.</li>
                <li>Los daños causados a las instalaciones a causa del mal uso por parte del huésped, serán cargados a la
                    cuenta del mismo, como son: extravío o daño a la llave de habitación, control del televisor u objetos
                    que se encuentren dentro de las habitaciones como toallas, sábanas, decoración, etc.</li>
                <li>En caso de cancelacion o salida anticipada, se deberá cubrir el costo de la primera noche de tarifa
                    RACK. En caso de reservaciones de 3 noches o 3 habitaciones o más, se deberá cubrir el 50% del total de
                    la estancia.</li>
                <li>El hotel no se hace responsable de cualquier robo y daño parcial o total de sus posesiones dentro de la
                    habitación, instalaciones del hotel y/o automóvil, excepto bajo previa declaración.</li>
                <li>El hotel no se hace responsable del costo por cualquier accidente o percance dentro de sus
                    instalaciones. (Se proporcionarán los recursos necesarios que estén en nuestras manos). Siendos estos
                    ocasionados por negligencia del huésped.</li>
                <li>El fumar dentro de la habitación está estrictamente prohibido.</li>
                <li>La empresa no se hace responsable de cualquier tipo de incidentes causados por nuestro personal o ajeno
                    y de objetos olvidados en el interior de su automóvil.</li>
                <li>No se permiten mascotas de ninguna clase dentro de las instalaciones del hotel.</li>
            </ul>
        </div>
    </main>
@endsection
