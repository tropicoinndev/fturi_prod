<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reservación Nº {{ $p->id }}</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <style>
        @page {
            margin: 105px 0.5cm;
            width: 21.59cm;
        }

        header {
            position: fixed;
            top: -80px;
            left: 0px;
            right: 0px;
            height: 60px;
            line-height: 35px;
            color: rgb(88, 88, 88);
        }

        .row .col-sm-1,
        .row .col-sm-2,
        .row .col-sm-6,
        .row .col-sm-12 {
            display: inline-block;
        }

        .col-sm-1 {
            width: 1.79cm;
        }

        .col-sm-2 {
            width: 3.50cm;
        }

        .col-sm-6 {
            width: 45%;
        }

        .col-sm-12 {
            width: 98%;
        }

        .logo {
            width: 90px;
        }

        footer {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
            height: 50px;
            color: rgb(88, 88, 88);
            text-align: center;
            line-height: 35px;
        }

        main {
            padding-left: 0.5cm;
            padding-right: 0.5cm;
            font-size: 8pt;
        }

        .page-number:after {
            content: "Página " counter(page);
        }

        .bt-1 {
            border-top: 1px solid #333 !important;
        }

        .firmas {
            padding-top: 45px;
        }

        .page-break {
            page-break-after: always;
            /* Salto de página después de este elemento */
        }
    </style>
</head>

<body>

    <header class="row">
        <div class="col-sm-2">
            <img src="{{ asset(env('logo', 'images/logo.jpg')) }}" alt="" class="logo">
        </div>
        <div class="col-sm-6 text-center">
            <b>TURISTICAS DE ORIENTE S.A. DE C.V.</b>
            <h5>DETALLE DE RESERVACIÓN</h5>
        </div>
        <div class="col-sm-2 text-right">
            No.
            <b>
                {{ $p->id }}
            </b>
        </div>
    </header>
    <footer>
        <div class="page-number"></div>
    </footer>
    <main>
        <div class="cliente rounded-4 border p-3 text-uppercase fs-4">
            <div class="row">
                <div class="col-sm-12">
                    <p>
                        <b>
                            CLIENTE:
                        </b>
                        {{ $p->clientes_id > 0 ? $p->relacionClientes->nombre : $p->titular }}
                    </p>
                    <p>
                        <b>
                            dirección:
                        </b>
                        {{ $p->clientes_id > 0 ? $p->relacionClientes->direccion : '-- No agregada --' }}

                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <p>
                        <b>
                            municipio:
                        </b>
                        {{ $p->clientes_id > 0 ? $p->relacionClientes?->municipios?->municipio : '-- No agregada --' }}
                    </p>

                </div>
                <div class="col-sm-6">
                    <p>
                        <b>
                            departamento:
                        </b>
                        {{ $p->clientes_id > 0 ? $p->relacionClientes->municipios?->departamentos?->departamento : '-- No agregada --' }}
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <p>
                        <b>
                            Pais:
                        </b>
                        {{ $p->clientes_id > 0 ? $p->relacionClientes?->municipios?->departamentos?->paises?->pais : '-- No agregada --' }}
                    </p>
                </div>
                <div class="col-sm-6">
                    <p>
                        <b>
                            Forma de pago:
                        </b>
                        {{ $p?->forma_pago?->forma ?? 'NO SE CONFIRMO' }}

                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <p>
                        <b>
                            Contacto:
                        </b>
                        {{ $p->clientes_id > 0 && count($p->relacionClientes->contactos) > 0 ? $p->relacionClientes->contactos[0]->contactos->contacto . ': ' . $p->relacionClientes->contactos[0]->valor : '-- No agregado --' }}
                    </p>
                </div>
                <div class="col-sm-6">
                    <p>
                        <b>
                            Identificación:
                        </b>
                        {{ $p->clientes_id > 0 && count($p->relacionClientes->identificaciones) > 0 ? $p->relacionClientes->identificaciones[0]->identificaciones->identificacion . ': ' . $p->relacionClientes->identificaciones[0]->numero : '-- Sin identificaciones --' }}

                    </p>
                </div>
            </div>

        </div>
        <div class="huespedes rounded-3 border p-3 text-uppercase mt-3">
            <div class="row">
                <div class="col-sm-12 text-muted">

                    INFORMACIÓN DE HUESPEDES
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-light fs-4">
                        <thead class="thead-light">
                            <tr>
                                <th>Habitacion</th>
                                <th>Nombre</th>
                                <th>Nacionalidad</th>
                                <th>Pais</th>
                                <th>Telefono</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($p->detalleReservaciones as $dr)
                                @foreach ($dr->huespedes as $h)
                                    <tr>
                                        <td>{{ $dr->relacionHabitaciones->numero_habitacion }}</td>
                                        <td>{{ $h->huesped->nombre }}</td>
                                        <td>{{ $h->huesped->municipios?->departamentos?->paises->nacionalidad ?? $h->huesped->paises?->nacionalidad }}
                                        </td>
                                        <td>{{ $h->huesped->paises->pais ?? $h->huesped->municipios?->departamentos?->paises->pais }}
                                        </td>
                                        <td>{{ $h->huesped->telefono ?? '---' }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <div class="huespedes rounded-3 border p-3 text-uppercase mt-3">
            <div class="row">
                <div class="col-sm-12 text-muted">
                    INFORMACIÓN DE RESERVACIONES
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-light fs-4">
                        <thead class="thead-light">
                            <tr>
                                <th>HABITACION</th>
                                <th>TIPO DE HABITACION</th>
                                <th>FECHAS DE RESERVACION</th>
                                <th>DIAS</th>
                                <th class="text-right">TARIFA</th>
                                <th class="text-right">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $suma = 0;
                            @endphp
                            @foreach ($p->detalleReservaciones as $d)
                                @php
                                    $dias =
                                        (strtotime(\Carbon::parse($d->fecha_salida)->format('Y-m-d')) -
                                            strtotime(\Carbon::parse($d->fecha_ingreso)->format('Y-m-d'))) /
                                        (60 * 60 * 24);
                                    $tarifa = $d->relacionTarifas->monto / (1 + env('iva', 0.13) + env('cesc', 0.05));
                                    $suma += $tarifa * $dias;
                                @endphp
                                <tr>
                                    <td>HABITACION {{ $d->relacionHabitaciones->numero_habitacion }}</td>
                                    <td>
                                        {{ $d->relacionHabitaciones->relacionTipoHabitaciones->tipo_habitacion }}
                                    </td>
                                    <td>{{ \Carbon::parse($d->fecha_ingreso)->format('d/m/Y') }} AL
                                        {{ \Carbon::parse($d->fecha_salida)->format('d/m/Y') }}</td>
                                    <td>{{ $dias }} {{ $dias > 1 ? 'dias' : 'dia' }}</td>
                                    <td class="text-right">${{ number_format($tarifa, 2) }}</td>
                                    <td class="text-right">${{ number_format($tarifa, 2) }}</td>
                                </tr>
                            @endforeach
                            @php
                                $cesc = $suma * env('cesc', 0.05);
                                $iva = $suma * env('iva', 0.13);
                                $total = $suma + $cesc + $iva;
                                $anticipos = count($p->anticipos) > 0 ? $p->anticipos->sum('anticipos_sum_monto') : 0;
                            @endphp
                            <tr>
                                <td colspan="4"></td>
                                <td class="text-right bt-1">
                                    <b>SUB-TOTAL</b>
                                </td>
                                <td class="text-right bt-1">
                                    ${{ number_format($suma, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4"></td>
                                <td class="text-right">
                                    <b>CET</b>
                                </td>
                                <td class="text-right">
                                    ${{ number_format($cesc, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4"></td>
                                <td class="text-right">
                                    <b>IVA</b>
                                </td>
                                <td class="text-right">
                                    ${{ number_format($iva, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4"></td>
                                <td class="text-right bt-1">
                                    <b>Total</b>
                                </td>
                                <td class="text-right bt-1">
                                    ${{ number_format($total, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4"></td>
                                <td class="text-right">
                                    <b>ANTICIPOS</b>
                                </td>
                                <td class="text-right">
                                    ${{ number_format($anticipos, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3"></td>
                                <td colspan="2" class="text-right">
                                    <b>Diferencia</b>
                                </td>
                                <td class="text-right">
                                    ${{ number_format($total - $anticipos, 2) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="text-right fs-6">
                        <small>impresion: {{ date('d/m/Y H:i:s') }}</small>
                    </p>

                </div>
            </div>
        </div>
        <div class="firmas text-uppercase mt-4">
            <div class="row">
                <div class="col-sm-6">
                    <p>
                        F.: __________________________________________________
                    </p>
                    <p>
                        {{ $p->clientes_id > 0 ? $p->relacionClientes->nombre : $p->titular }}
                    </p>
                    <p>
                        <b>
                            Cliente
                        </b>
                    </p>
                </div>
                <div class="col-sm-6">
                    <p>
                        F.: __________________________________________________
                    </p>
                    <p>
                        {{ Auth::user()->name }}
                    </p>
                    <p>
                        <b>
                            Vendedor
                        </b>
                    </p>
                </div>
            </div>
        </div>

        <div class="page-break"></div>

        {{-- --}}
        <ul style="font-size: 13px; line-height: 30px;">
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

    </main>

</body>

</html>
