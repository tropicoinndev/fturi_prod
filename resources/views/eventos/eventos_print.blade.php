<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>REPORTE DE EVENTOS</title>

    <!-- Enlaces a las hojas de estilos de Bootstrap no son necesarios para Dompdf -->
    <style>
            * {
            font-family: 'Roboto', sans-serif;
        }

        @page {
            margin: 35px 0.5cm;
            size: A4;
            font-family: "Lucida Sans", sans-serif;
        }

        .logo {
            width: 60px;
            float: left;
            margin-right: 10px;
            margin-bottom: 10px;
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

        footer {
            position: fixed;
            bottom: 1cm;
            left: 0px;
            right: 0px;
            height: 25px;
            color: rgb(88, 88, 88);
            text-align: center;
            line-height: 35px;
            font-size: 13px;
        }

        main {
            font-size: 9pt;
            border: 1px solid #333;
            padding: 0px;
            width: 19.98cm;
            height: auto;
            border: 1px solid #ccc;
            border-radius: 0.34cm;
        }

        .page-number:after {
            content: "Página " counter(page);
        }

        .col-1 {
            width: 1.799166667cm;
        }


        .col-2 {
            width: 3.598333333cm;
        }


        .col-3 {
            width: 5.397500001cm;
        }


        .col-4 {
            width: 7.196666668cm;
        }


        .col-5 {
            width: 8.995833335cm;
        }


        .col-6 {
            width: 10.795000002cm;
        }


        .col-7 {
            width: 12.594166669cm;
        }


        .col-8 {
            width: 14.393333336cm;
        }

        .col-9 {
            width: 16.192500003cm;
        }

        .col-10 {
            width: 17.99166667cm;
        }


        .col-11 {
            width: 19.790833337cm;
        }

        .col-12 {
            width: 21.590000004cm;
        }



        /* Clases de estilo personalizadas */
        .row {
            clear: both;
            width: 21.59cm;
            margin-bottom: 5px;
            text-align: justify;


        }

        [class*="col-"] {
            float: left;
            padding: 5px;
            /*border: 1px solid #ccc;*/
        }

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-start {
            text-align: start;
        }

        .text-right {
            text-align: right;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .float-end {
            float: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .col-1 {
            width: 2.1616cm;
            float: left;
        }


        .test {
            width: 90%;
            /* Ancho del 70% */
            float: left;
            border-bottom: 1px solid #000;

        }

        .test1 {
            width: 30%;
            /* Ancho del 30% */
            float: left;
            margin: left 0px;


        }

        .mb {
            margin-bottom: 5px;

        }

        .underline {
            text-decoration: underline;
        }

        .float-end {
            float: right;
        }

        .float-start {
            float: left;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .text-start {
            text-align: start;
        }

        .label {
            display: inline-block;
        }

        .margin-bottom {
            margin-bottom: 2px;
        }

        .card {
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card img {
            width: 85%;
            height: 10vh;

        }

        .center {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /**table reservas */
        .table-container {
            margin: 10px 0;
        }

        .table-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
            text-align: left;
        }

        .reservas-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ddd;
        }

        .reservas-table th,
        .reservas-table td {
            padding: 4px 6px;
            border-bottom: 1px solid #000;
            text-align: left;
        }

        .reservas-table tr:nth-child(even) {
            background-color: #f2f2f2;
            border-bottom: 1px solid #000;
        }


        .reservas-table th {
            color: 000;
            font-weight: bold;
        }

        .reservas-table td:first-child {
            font-weight: bold;
        }

        .text-danger {
            color: #dc3545;
        }

        .text-success {
            color: #28a745;
        }

        .card-img-top {
            width: 100%;
            height: 5vh;
            object-fit: cover;
        }
    </style>
</head>

@foreach ($eventos as $evento )
<body>
    <main>

        <div class="row col-12">
            <div class="row">
                <div class="col-11 text-right "><strong>#{{ $evento->id }}</strong></div>
            </div>
            <div class="row">
                <div class="col-11 center " style="width: 55%; margin-left: 98px;">

                    <p style=" padding: 2px; height: 40px; overflow-y: auto; word-wrap: break-word;">
                        <strong>{{ $evento->observaciones_factura }}</strong>
                    </p>
                </div>
            </div>

            <div class="row col-12 mb">

                <div>
                    <img src="{{ asset(env('logo', '/images/logo.jpg')) }}" alt="Logo de empresa" class="logo">
                </div>
                <div class="row"></div>
                <div class="row"></div>
                <div class="row"></div>
                <div class="row"></div>
                <div class="row"></div>
                <div class="row"></div>
                <div class="row"></div>
                <div class="row"></div>
                <div class="row"></div>
                <div class="row"></div>

                <div class="font-bold text-left"></div>
                <div class="row mb ">PBX:2682-1000</div>

                <div class="mb row">
                    <div class="test1 ">
                        <div>FAX:2682-1000</div>
                    </div>
                    <div class="test mb font-bold text-center">CONTRATO DE EVENTO</div>
                </div>

            </div>
            <div class="row"></div>

            <div class="row ">
                <span class="label">FECHA DE EVENTO:</span>
                <span class="label text-uppercase"
                    style=" width: 250px; border-bottom: 1px solid black;">{{ \Carbon\Carbon::parse($evento->fecha)->isoFormat('dddd D [de] MMMM [de] YYYY') }}</span>
                <span class="label">TIPO DE EVENTO:</span>
                <span class="label"
                    style=" width: 225px; border-bottom: 1px solid black;">{{ $evento->tipo_eventos->evento }}</span>

            </div>
            <div class="row ">
                <span class="label">HORA:</span>
                <span class="label"
                    style=" width: 350px; border-bottom: 1px solid black;">{{ \Carbon\Carbon::parse($evento->inicio)->format('h:i A') }}
                    a
                    {{ \Carbon\Carbon::parse($evento->finalizacion)->format('h:i A') }}</span>
                <span class="label">ANTICIPO:</span>
                <span class="label" style=" width: 238px; border-bottom: 1px solid black;">
                    @if ($evento->getAnticipos->isNotEmpty())
                        <strong>${{ number_format($evento->getAnticipos->sum('anticipos_sum_monto'), 2) }}
                            #{{ implode(', ', $evento->getAnticipos->pluck('anticipos.id')->toArray()) }} </strong>
                    @endif
                </span>
            </div>
            <div class="row ">
                <div class="row-group">
                    <div class="row-item">
                        <span class="label">SALÓN:</span>
                        <span class="label"
                            style=" width: 268px; border-bottom: 1px solid black;">{{ implode(', ', $evento->salones->pluck('salones.salon')->toArray()) }},
                            @if (count($evento->salones) > 1)
                                @if ($evento->salones->pluck('separado')->contains(true))
                                    Separados
                                @else
                                    Unidos
                                @endif

                            @endif
                        </span>
                        <span class="label">Efectivo:</span>
                        <span class="label" style=" width:26px;font-size: 10px; ">(
                            @if ($evento->getAnticipos->isNotEmpty())
                                @php
                                    $efectivo = $evento->getAnticipos
                                        ->pluck('anticipos')
                                        ->flatten()
                                        ->filter(function ($anticipos) {
                                            return $anticipos->forma_pagos && $anticipos->forma_pagos->token == 6001;
                                        })
                                        ->count();
                                @endphp

                                @if ($efectivo > 0)
                                    {{ $efectivo }}#
                                @endif
                            @endif
                            )

                        </span>
                        <span class="label">Cheque No:</span>
                        <span class="label" style=" width: 108px; border-bottom: 1px solid black;">
                            @if ($evento->getAnticipos->isNotEmpty())
                                @php
                                    $cheque = $evento->getAnticipos
                                        ->pluck('anticipos')
                                        ->flatten()
                                        ->filter(function ($anticipos) {
                                            return $anticipos->forma_pagos && $anticipos->forma_pagos->token == 6005;
                                        })
                                        ->count();
                                @endphp

                                @if ($cheque > 0)
                                    {{ $cheque }}#
                                @endif
                            @endif

                        </span>
                        <span class="label">Bco:</span>
                        <span class="label" style=" width: 99px; border-bottom: 1px solid black;">
                            @if ($evento->getAnticipos->isNotEmpty())

                                @php
                                    $bcoCount = $evento->getAnticipos
                                        ->pluck('anticipos')
                                        ->flatten()
                                        ->filter(function ($anticipos) {
                                            return $anticipos->forma_pagos && $anticipos->forma_pagos->token == 6003;
                                        })
                                        ->count();
                                @endphp

                                @if ($bcoCount > 0)
                                    {{ $bcoCount }}#
                                @endif
                            @endif
                        </span>
                    </div>
                    <div class="row-item text-center">
                        <span class="label" style=" width:70px; "></span>
                        <span class="label">Tarjeta de credito Tipo:</span>
                        <span class="label" style=" width: 150px; border-bottom: 1px solid black;">
                            @if ($evento->getAnticipos->isNotEmpty())
                                @php
                                    $tarjeta = $evento->getAnticipos
                                        ->pluck('anticipos')
                                        ->flatten()
                                        ->filter(function ($anticipos) {
                                            return $anticipos->forma_pagos && $anticipos->forma_pagos->token == 6002;
                                        })
                                        ->count();
                                @endphp

                                @if ($tarjeta > 0)
                                    {{ $tarjeta }}#
                                @endif
                            @endif
                        </span>
                        <span class="label">No</span>
                        <span class="label" style=" width: 249px; border-bottom: 1px solid black;"></span>
                    </div>
                </div>
            </div>
            <div class="row">

                <span class="label">PEDIDO POR:</span>
                <span class="label text-uppercase"
                    style=" width: 300px; border-bottom: 1px solid black;">{{ $evento->clientes->nombre ?? $evento->titular }}</span>


                <span class="label">TELÉFONO:</span>
                <span class="label text-uppercase"
                    style=" width: 242px; border-bottom: 1px solid black;">{{ $evento->clientes->contactos[0]->valor ?? '' }}</span>


            </div>
            <div class="row">
                <span class="label">DIRECCIÓN:</span>
                <span class="label text-uppercase"
                    style=" width: 619px; border-bottom: 1px solid black;">{{ $evento->clientes->direccion ?? 'No se ha asignado cliente aun' }}</span>
            </div>
            <div class="row">
                <span class="label">FACTURAR A:</span>
                <span class="label text-uppercase" style=" width: 610px; border-bottom: 1px solid black;"><strong
                        class="text-uppercase font-bold">{{ $evento->clientes->nombre ?? $evento->titular }}</strong>
                    @if ($evento->clientes != null)
                        ({{ $evento->clientes->ccf == 0 ? 'comprobante consumidor final' : 'comprobante credito fiscal' }})
                    @endif
                </span>
            </div>
            <div class="row">
                <span class=" label text-uppercase">Forma de pago:</span>
                <span class="label" style=" width: 200px; ">{{ $evento->forma_pagos->forma }}</span>
                <span class="label">MÍNIMO GARANTIZADO:</span>
                <span class="label"
                    style=" width: 101px; border-bottom: 1px solid black;">{{ $evento->minimo_personas }}</span>
                <span class="label">MÁXIMO:</span>
                <span class="label"
                    style="width: 84px; border-bottom: 1px solid black;">{{ $evento->maximo_personas }}</span>
            </div>

            <div class="test row margin-bottom  font-bold text-center"></div>
            <div class="row"></div>
            <div class="row text-end">
                <span class="text-uppercase text-start " style="margin-right:230px;">Menu:</span>
                <span style="margin-right:178px;">DOLARES</span>
                <span class="label">TIPO DE MONTAJE:</span>


            </div>
            <section class="row col-12">
                <div class="col-8">

                    <table style="width: 100%; border-collapse: collapse; height:447px;">
                        @php
                            $subtotal = 0;
                            $total = 0;
                            $iva = 0;
                            $propina = 0;
                            $monto = 0;
                            $tipo_comanda = 0;
                            $ordenes = $evento->ordenesTest()->get();
                            $comandas = $evento->comandasTest()->get();
                            $reservas = $evento->reservaciones()->get();
                            $detalle_reserva = $reservas->flatMap(function ($r) {
                                return $r->detalleReservaciones;
                            });
                        @endphp
                        @foreach ($ordenes as $o)
                            @php
                                $monto += $o->sum_orden;
                            @endphp
                            @foreach ($o->detalle_orden as $detalle)
                                <tr>

                                    <td style="width: 50%;">
                                        <div style="word-wrap: break-word;">
                                            <span>{{ $detalle->cantidad }}</span> -
                                            <span>{{ $detalle->servicios->servicio }}</span>
                                        </div>
                                    </td>
                                    <td style="width: 10%;visibility: hidden;">..........$</td>
                                    <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                                    <td>${{ number_format($detalle->total, 2) }}</td>
                                </tr>
                                @php

                                    $subtotal += $detalle->total;
                                    $iva += $detalle->iva;
                                    $propina += $detalle->propina;
                                @endphp
                            @endforeach
                        @endforeach
                        @foreach ($comandas as $c)
                            @foreach ($c->detalles_comanda as $detalle)
                                <tr>
                                    <td style="width: 50%;">
                                        <div style="word-wrap: break-word;">
                                            <span>{{ $detalle->cantidad }}</span> -
                                            <span>{{ $detalle->precios->detalle }} @if ($c->tipo_comanda == 3)
                                                    (CORTESIA)
                                                @endif </span>
                                            <span>{{ $detalle->observaciones }} </span>

                                        </div>

                                    </td>
                                    <td style="width: 5%;visibility: hidden;">..........$</td>

                                    <td style="width: 50%;">${{ number_format($detalle->precio, 2) }} </td>
                                    <td style="width: 50%;">
                                        @if ($c->tipo_comanda == 3)
                                            ${{ number_format(0, 2) }}
                                        @else
                                            ${{ number_format($detalle->total, 2) }}
                                        @endif
                                    </td>
                                </tr>
                                @php
                                    if ($evento->getAnticipos && $evento->getAnticipos->isNotEmpty()) {
                                        // Calcular el total de anticipos
                                        $totalAnticipos = $evento->getAnticipos->sum('anticipos_sum_monto');
                                    } else {
                                        // Si no hay anticipos, el total de anticipos es cero
                                        $totalAnticipos = 0;
                                    }
                                    $subtotal += $detalle->total;
                                    $total = 0;
                                    $total =  $subtotal - $totalAnticipos ;
                                    if ($detalle->iva) {
                                        $iva += env('iva', 0.13) * $detalle->precio;
                                    }
                                    if ($detalle->propina) {
                                        $propina += env('propina', 0.1) * $detalle->precio;
                                    }

                                @endphp
                            @endforeach
                        @endforeach
                        @if (isset($detalle_reserva) && count($detalle_reserva) > 0)
                        @foreach ($detalle_reserva as $r)
                                <tr>
                                    <td style="width: 50%;">
                                        <div style="word-wrap: break-word;">
                                            <span>{{ $r->dias}} días</span> -
                                            <span><b class="text-uppercase">{{ $r->relacionTarifas->tarifa }}</b> ${{ number_format($r->relacionTarifas->precio,2) }} </span>


                                        </div>

                                    </td>
                                    <td style="width: 5%;visibility: hidden;">..........$</td>

                                    <td style="width: 50%;">${{ number_format($r->total,2) }} </td>
                                    <td style="width: 50%;">

                                    </td>
                                </tr>
                            @php
                                $subtotal += $r->total;
                            @endphp

                            @endforeach
                        @endif

                        <tr class="font-monospace">

                            <td colspan="3"><span class="float-end">Sub-total $</span></td>


                            <td style="width: 50%;"><span class=" text-secondary">
                                    {{ number_format($subtotal, 2) }}</span>
                            </td>

                        </tr>

                        <tr class="font-monospace">

                            <td colspan="3"><span class="float-end">Propina $</span></td>


                            <td><span class=" text-secondary">{{ number_format($propina, 2) }}</span>
                            </td>

                        </tr>
                        <tr class="font-monospace">

                            <td colspan="3"><span class="float-end">IVA $</span></td>


                            <td><span class=" text-secondary">{{ number_format($iva, 2) }}</span>
                            </td>

                        </tr>
                        <tr class="table-light font-monospace">

                            <td colspan="3"><b class="float-end">TOTAL $</b></td>

                            <td><span>
                                    {{ number_format($subtotal, 2) }}</span></td>

                        </tr>
                        @if ($evento->getAnticipos && $evento->getAnticipos->isNotEmpty())
                        <tr class="font-monospace">

                            <td colspan="3"><span class="float-end"><b>Total anticipos $</b></span></td>


                            <td style="width: 50%;"><span class=" text-secondary">
                                    {{ number_format($totalAnticipos, 2) }}</span>
                            </td>

                        </tr>
                        @if ($totalAnticipos > $subtotal )
                        <tr class="font-monospace">

                            <td colspan="3"><span class="float-end  ">Anticipo a fovor del cliente $</span></td>


                            <td style="width: 50%;"><span class=" text-secondary">
                                    {{ number_format($total, 2) }}</span>
                            </td>

                        </tr>
                        @elseif ($totalAnticipos < $subtotal )
                            <tr class="font-monospace">

                            <td colspan="3"><span class="float-end text-danger">Pendiente de pago $</span></td>


                            <td style="width: 50%;"><span class=" text-secondary">
                                    {{ number_format($subtotal - $totalAnticipos, 2) }}</span>
                            </td>

                        </tr>
                        @endif

                        @endif
                    </table>
                </div>

                <div class="col-5 float-end" style="margin-top:0%; position:relative;  ">

                    <div class="float-start">
                        <table style="height:30px;">
                            <tr>
                                <td style="width: 50%;">
                                    <span class="label" style="margin-left:24px;">Tipo de mesas:</span>
                                    <span class="label" style="width: 130px; border-bottom: 1px solid black;">
                                        {{ $evento->montajes->montaje ?? '' }}
                                    </span>

                                </td>
                                <td>

                                </td>
                            </tr>


                            <tr>
                                <td style="width: 50%;">
                                    <span class="label" style="margin-left:24px;">Pista de baile:</span>
                                    <span class="label" style="width: 133px; border-bottom:1px solid black;">
                                        @if ($evento->detalle_montaje->isNotEmpty())
                                            @php $pista = $evento->detalle_montaje->pluck('pista_baile')->filter()->implode(', '); @endphp
                                            @if ($pista)
                                                {{ $pista }}
                                            @endif
                                        @endif
                                    </span>

                                </td>
                                <td>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">
                                    <span class="label" style="margin-left:24px;">Podium:</span>
                                    <span class="label" style="width: 164px; border-bottom:1px solid black;">
                                        @if ($evento->detalle_montaje->isNotEmpty())
                                            @php
                                                $podium = $evento->detalle_montaje
                                                    ->pluck('podium')
                                                    ->filter()
                                                    ->implode(',');
                                            @endphp
                                            @if ($podium)
                                                {{ $podium }}
                                            @endif
                                        @endif
                                    </span>

                                </td>
                                <td>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">
                                    <span class="label" style="margin-left:24px;">Rotafolio y plumon:</span>
                                    <span class="label" style="width: 109px; border-bottom:1px solid black;">
                                        @if ($evento->detalle_montaje->isNotEmpty())
                                            @php
                                                $rotafolio = $evento->detalle_montaje
                                                    ->pluck('rotafolio_plumon')
                                                    ->filter()
                                                    ->implode(',');
                                            @endphp
                                            @if ($rotafolio)
                                                {{ $rotafolio }}
                                            @endif
                                        @endif
                                    </span>

                                </td>
                                <td>
                                </td>
                            </tr>

                            <tr>
                                <td style="width: 52%;">
                                    <span class="label" style="margin-left:24px; margin-top: 4px;">Banderas:</span>
                                    <span class="label"
                                        style="width: 149px;  border-bottom:1px solid black;margin-top: 4px;">
                                        @if ($evento->detalle_montaje->isNotEmpty())
                                            @php
                                                $banderas = $evento->detalle_montaje
                                                    ->pluck('bandera')
                                                    ->filter()
                                                    ->implode(',');
                                            @endphp
                                            @if ($banderas)
                                                {{ $banderas }}
                                            @endif
                                        @endif
                                    </span>

                                </td>
                                <td>
                                </td>
                            </tr>

                            <tr>
                                <td style="width: 50%;">
                                    <span class="label" style="margin-left:24px;">Eq. de Amplif. y
                                        Microf.:</span>
                                    <span class="label"
                                        style="width: 89px;  border-bottom:1px solid black;margin-top: 4px;">
                                        {{ $evento->sonidos->sonido ?? '' ? 'XXX' : 'No es requerido' }}

                                    </span>

                                </td>
                                <td>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">
                                    <span class="label" style="margin-left:24px;">Otros:</span>
                                    <span class="label"
                                        style="width:178px; border-bottom:1px solid black; white-space: nowrap;">
                                        @if ($evento->detalle_montaje->isNotEmpty())
                                            @php
                                                $otros = $evento->detalle_montaje
                                                    ->pluck('otros')
                                                    ->filter()
                                                    ->implode(',');
                                            @endphp
                                            @if ($otros)
                                                {{ $otros }}
                                            @endif
                                        @endif
                                    </span>

                                </td>
                                <td>
                                </td>
                            </tr>
                        </table>

                    </div>


                </div>

            </section>

            <div class="row col-12 mb-5">
                <div class="col-7 float-start margin-bottom" style="margin-top:25%; position:absolute;">

                </div>
                <div class="col-5 float-end margin-bottom" style="margin-top:25%; position:absolute;">
                    <table class="table table-bordered">
                        <div class="float-start">

                            <h5 style="margin-left:28px;"><strong>CONTRATACION DEL EVENTO</strong></h5>
                            <p style="margin-left:28px; width:53%; text-align: justify;">
                                Solicitamos verificar, firmar y enviar a nuestras oficinas este convenio a más tardar 24
                                horas
                                de
                                haberlo recibido. Los detalles para el evento, como menú, montaje, etc., deberán ser
                                enviados a
                                más
                                tardar 15 días previos al evento.
                                Bajo ninguna circunstancia se cobrará menos del número de personas mínimo garantizado.
                            </p>
                            <h5 class="underline" style="margin-left:28px;"><b>SUSPENSION DEL CONTRATO:</b></h5>
                            <p style="margin-left:28px; width:53%;  text-align: justify;">
                                3 días antes de realizar el evento, el cliente se compromete a pagar el 100% del total
                                de lo
                                contratado, excepto el 10% de servicio.
                                Cualquier cambio o modificación al convenio deberá ser comunicado por escrito al hotel.
                                <b>El
                                    depósito recibido como garantía no es reembolsable.</b>
                            </p>


                        </div>
                    </table>
                </div>
            </div>


            <div class="row" style="margin-top: 11%;">
                <div class="col-7 float-start" style="width: 55%; margin-right: 50px;">
                    <span class="text-uppercase">OBSERVACIONES</span>
                    <p
                        style="border: 1px solid #000; padding: 2px; height: 51px; overflow-y: auto; word-wrap: break-word;">
                        {{ $evento->observaciones }}
                    </p>
                </div>

                <div class="float-end col-5" style="margin-top:13px;width: 50%;">
                    <div style="text-align: center; width: 90%;">
                        <div style="margin-right: px; font-size: 9px;">
                            <span class="label margin-bottom">Fecha:</span>
                            <span class="label"
                                style="width: 125px; border-bottom:1px solid black;"><strong>{{ \Carbon\Carbon::parse($evento->created_at)->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</strong></span>
                        </div>
                        <hr style="border: none; border-top: 1px solid black; margin-bottom: 0px; width: 50%;">

                        <div style="margin-right: 5px; font-size: 8px;">Firma cliente</div>

                        <div class="text-uppercase" style="margin-bottom: 1px;">{{ $evento->usuarios->name }}
                        </div>

                        <hr style="border: none; border-top: 1px solid black; margin-bottom: 0px; width: 50%;">

                        <div style="margin-right: 5px; font-size: 9px;">Ejecutiv@ de eventos</div>

                        <hr style="border: none; border-top: 1px solid black; margin-bottom: 0px; width: 50%;">

                        @if (isset($evento->autoriza))
                            <span class="text-uppercase font-bold" style="font-size: 8px;">Autorizado</span>
                        @else
                            <span class="text-uppercase font-bold " style="color:red;font-size: 8px;">Sin
                                autorización</span>
                        @endif
                    </div>
                </div>
                <div class="row"></div>
            <div class="row"></div>
            <div class="row"></div>
            <div class="row"></div>
            <div class="row"></div>
            <div class="row"></div>
            <div class="row"></div>
            <div class="row"></div>
            <div class="row"></div>
            <div class="row"></div>
            <div class="row"></div>
            <div class="row"></div>
            <div class="row"></div>
            <div class="row"></div>
            <div class="row"></div>
            <div class="row"></div>
            <div class="row"></div>
            <div class="row"></div>

            @if (count($evento->galerias_evento) > 0)
                <div class="row">
                    <p>GALERÍA DE EVENTOS</p>
                    <div class="container">
                        @foreach ($evento->galerias_evento as $eg)
                            @php
                                $imagePath = asset('img/' . $eg->galerias->foto);
                            @endphp

                            <div class="card grid"> <!-- Contenedor de la tarjeta -->
                                <img src="{{ $imagePath }}" class="card-img-top">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            </div>
    </main>
</body>

@endforeach

</html>
