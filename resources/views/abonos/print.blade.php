<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ingreso a caja #{{ $p->id }}</title>

    <link rel="stylesheet" media="print" href="{{ asset('css/b5.css') }}">
    <style>
        @page {
            margin: 0.3cm;
            padding: 0.3cm;
        }

        .logo {
            width: 60px;
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

            font-size: 8pt;
        }

        .bt-1 {
            border-top: 1px solid #333 !important;
        }

        .bb-1 {
            border-bottom: 1px solid #333 !important;
        }


        .z-2 {
            width: 25vh;
        }

        .z-6 {
            width: 5cm;
        }

        .mw-100 {
            min-width: 100%;
        }

        .mw-15 {
            width: 15%;
        }

        .mw-25 {
            width: 25%;

        }

        .mw-50 {
            min-width: 50%;
        }

        .mw-60 {
            min-width: 60%;
        }

        .corte {
            min-width: 90%;
            border: 1px dashed #a7a7a7;
        }

        .nombre-cliente {
            white-space: pre-wrap;
        }

        body {
            text-transform: uppercase;
        }

        .border {
            border: 1px solid #444;
            padding: 6px;
        }

        .rounded-3 {
            border-radius: 8px;
        }

        .m-3 {
            margin: 9px;
        }
    </style>
</head>

<body>
    <main>
        @php
            $copia = [
                ['tipo' => 'original', 'a' => 'cliente'],
                ['tipo' => 'duplicado', 'a' => 'recepcion'],
                ['tipo' => 'triplicado', 'a' => 'contabilidad'],
            ];
        @endphp
        @foreach ($copia as $i)
            <table class="border rounded-3 mw-100 text-uppercase">
                <tr>
                    <td rowspan="3" class="p-1 mw-15">
                        <img src="{{ asset(env('logo', 'images/logo.jpg')) }}" alt="" class="logo">
                    </td>
                    <td class="mw-60 h4">
                        TURISTICAS DE ORIENTE S.A. DE C.V.
                    </td>
                    <td class="mw-25 p-3">
                        <div style="width: 20%; float: left;">
                            <b>No.</b>
                        </div>
                        <div style="width: 80%; float: right; text-align: right; font-size: 11pt;">
                            {{ $p->id }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="h5">
                        <b>
                            RECIBO DE INGRESO A CAJA
                        </b>
                    </td>
                    <td class="p-3">
                        <div style="width: 20%; float: left;">
                            <b>POR:</b>
                        </div>
                        <div style="width: 80%; float: right; text-align: right; font-size: 11pt;">
                            ${{ number_format($p->monto, 2) }}
                        </div>
                    </td>

                </tr>
                <tr>
                    <td>
                        <div style="width: 50%; float: left;">
                            FECHA: <u>{{ date('d-m-Y', strtotime($p->fecha)) }}</u>
                        </div>
                        <div style="width: 50%; float: right;">
                            FORMA DE PAGO: <u>{{ $p->forma_pagos->forma }}</u>

                        </div>
                    </td>

                    <td></td>
                </tr>
                <tr>
                    <td colspan="3" class="px-3 py-3 pt-0 pb-1" style="width: 100%;">
                        <b>
                            Nombre:
                        </b>
                        <span class="nombre-cliente" style="width: 5in;" class="bb-1">
                            {{ $p->clientes->tipo_cliente ? $p->clientes->nombre : $p->clientes->detalle->juridico }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="px-3 py-3 pt-0 pb-1 text-justify">
                        <b>
                            En concepto de:
                        </b>

                        <u>
                            {{ $p->concepto }}
                        </u>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="px-0 py-0 pt-2 pb-2">
                        <small>
                            <ul>
                                <li>
                                    ESTE DOCUMENTO UNICAMENTE TIENE VALIDEZ CON LA PRESENTACION DEL MISMO AL MOMENTO DE
                                    HACER USO DEL SERVICIO; CON FIRMA Y SELLO DE CAJERO QUE RECIBE.
                                </li>
                                <li>
                                    EL DEPOSITO RECIBIDO COMO GARANTIA NO ES REEMBOLSABLE.
                                </li>
                            </ul>
                        </small>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <table class="mw-100 px-3 py-3 pt-0 pb-0">

                            <tr>
                                <td style="width: 33.33%;">
                                    F. ______________________________________
                                </td>
                                <td style="width: 33.33%;">
                                    F. ______________________________________
                                </td>
                                <td style="width: 33.33%;">
                                    F. ______________________________________
                                </td>
                            </tr>
                            <tr>
                                <td class="p-0">
                                    <small>{{ $p->clientes->tipo_cliente ? $p->clientes->nombre : $p->clientes->detalle->juridico }}</small>
                                </td>
                                <td class="p-0">
                                    <small>{{ $p->users->user }}</small>
                                </td>
                                <td class="p-0">
                                    <small>{{ Auth::user()->user }}</small>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-0">
                                    <small>
                                        cliente
                                    </small>
                                </td>
                                <td class="p-0">
                                    <small>
                                        elabora
                                    </small>
                                </td>
                                <td class="p-0">
                                    <small>
                                        recibe
                                    </small>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="3" class="text-right p-1">
                                    <small> {{ date('d-m-Y h:i:s a') }} · <b class="float-right">{{ $i['tipo'] }}:
                                            {{ $i['a'] }}</b></small>
                                </td>
                            </tr>

                        </table>

                    </td>

                </tr>
            </table>
            <div class="corte m-3"></div>
        @endforeach
        <table class="border rounded-3 mw-100 text-uppercase">
            <tr>
                <td rowspan="4" class="p-1 mw-15">
                    <img src="{{ asset(env('logo', 'images/logo.jpg')) }}" alt="" class="logo">
                </td>
                <td class="mw-60">
                    <b>CLIENTE:</b> {{ $p->clientes->nombre }}
                </td>
                <td class="mw-25">
                </td>
            </tr>
            <tr>
                <td>
                    <b>cantidad</b> {{ $letras }}
                </td>
                <td>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="width: 50%; float: left;">
                        <b>Fecha:</b>
                        <u>{{ date('d-m-Y', strtotime($p->fecha)) }}</u>
                    </div>
                    <div style="width: 50%; float: right;">
                        <b>Forma de pago:</b>
                        <u>
                            {{ $p->forma_pagos->forma }}

                        </u>
                    </div>
                </td>
                <td class="p-2">
                    f. ______________________
                </td>
            </tr>
            <tr>
                <td>
                    <b>No.</b>
                    {{ $p->id }}
                    <b class="ms-4">monto:</b>
                    ${{ number_format($p->monto, 2) }}
                    <b class="ms-4">elabora:</b>
                    {{ $p->users->name }}
                </td>
                <td>
                    {{ Auth::user()->name }}
                </td>
            </tr>
            <tr>
                <td colspan="3" class="text-right p-2">
                    <small>{{ date('d-m-Y h:i:s a') }} · <b>cuadruplicado: elabora</b></small>
                </td>
            </tr>
        </table>



    </main>
</body>

</html>
