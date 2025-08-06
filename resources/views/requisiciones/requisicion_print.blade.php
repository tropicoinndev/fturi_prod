<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>REPORTE DE REQUISICIONES</title>

    <link rel="stylesheet" media="print" href="{{ asset('css/bootstrap.min.css') }}">
    <style>
        @page {
            margin: 0.6cm;
            padding: 0.6cm;
        }

        .logo {
            width: 90px;
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

        .center {
            text-align: center;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        /* Estilos para la tabla de detalles */
        table.details-table {
            border-collapse: collapse;
            width: 100%;
            margin-right: 5%;
            margin-top: 10px;

        }

        table.details-table th,
        table.details-table td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }

        table.details-table th {
            background-color: #f2f2f2;
            border-bottom: 2px solid #333;
        }

        table.details-table td:last-child {
            border-right: 1px solid #333;
        }

        body {
            text-transform: uppercase;
        }

        main {
            font-size: 8pt;
        }

        .group-container {
            margin-bottom: 30px;
            padding: 10px;
            border: 1px solid #333;
            border-radius: 5px;
            max-height: 800px;
            /* Ajusta según sea necesario */
            overflow: hidden;
            page-break-inside: avoid;
        }
    </style>
</head>

<body>
    <main>
        @foreach ($reporte as $p)
            <main>
                <div class="group-container">
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
                                    {{ $p['requisicion']->id }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="h6 text-uppercase">
                                <b>
                                    REQUISICION: <small>{{ $p['requisicion']->solicitud }}</small>
                                </b>
                            </td>
                            <td class="p-3">
                                <div style="width: 20%; float: left;">

                                </div>
                                <div style="width: 80%; float: right; text-align: right; font-size: 11pt;">

                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="width: 50%; float: left;">
                                    FECHA: <u>{{ date('d-m-Y', strtotime($p['requisicion']->fecha)) }}</u>
                                </div>
                                <div style="width: 60%; float: right; margin-top: 0%;">
                        tiempo en resolver: <u class="autoriza"> @php
                                                $t1 = Carbon::parse($p['requisicion']->created_at);
                                                $t2 = Carbon::parse($p['requisicion']->updated_at);
                                                $tiempoTardado = $t2->diffForHumans($t1);
                                            @endphp
                                            {{ $tiempoTardado }}</u>
                    </div>
                            </td>

                            <td></td>
                        </tr>
                        <tr>

                        </tr>
                        <tr>
                            <td colspan="3">
                                <div class="container">
                                    <!-- Contenedor para Bodegas -->
                                    <div class="left-container" style="width: 50%; float: left;">
                                        <!-- Celda para Bodega de entrada -->
                                        <div class="cell">

                                            Bodega de entrada:<u>
                                                {{ $p['requisicion']->relacionBodegasEntrada->bodega }}
                                            </u>
                                        </div>

                                        <!-- Celda para Bodega de salida -->
                                        <div class="cell">

                                            Bodega de salida:


                                            <u>
                                                {{ $p['requisicion']->relacionBodegasSalida->bodega }}
                                            </u>
                                        </div>
                                    </div>

                                    <!-- Contenedor para Autoriza y Solicita -->
                                    <div class="right-container" style="width: 50%; float: right;">
                                        <!-- Celda para Responsable que autoriza -->
                                        <div class="cell">
                                            Responsable que autoriza: <u>
                                                @if ($p['requisicion']->relacionUserAutorizacion)
                                                    {{ $p['requisicion']->relacionUserAutorizacion->name }}
                                                @else
                                                    Sin responsable
                                                @endif
                                            </u>
                                        </div>

                                        <!-- Celda para Usuario que solicita -->
                                        <div class="cell">
                                            Usuario que solicita:


                                            <u>
                                                {{ $p['requisicion']->relacionUserCreacion->name }}
                                            </u>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"
                                class="table table-striped table-inverse border rounded-3 mw-100 text-uppercase">
                                <small>
                                    <table class="mw-100 details-table">
                                        <thead class="thead-inverse">
                                            <tr>
                                                <th>Lote BE</th>
                                                <th>Lote BS</th>
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Precio costo</th>
                                                <th>total</th>
                                                <th>Fecha de vencimiento</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($p['detalles'] as $dr)

                                                <tr>
                                                    <td scope="row">{{ $dr->id }}</td>
                                                    <td scope="row">{{ $dr->lote_origen }}</td>
                                                    <td scope="row">{{ $dr->relacionProductos->nombre }}</td>
                                                    <td scope="row">{{ $dr->cantidad }}</td>
                                                    <td scope="row">$ {{ $dr->relacionExistencias->precio_costo }}
                                                    <td scope="row">
                                                        $ {{ number_format($dr->relacionExistencias->precio_costo * $dr->cantidad, 2) }}
                                                    </td>
                                                    <td scope="row">{{ $dr->relacionExistencias->vencimiento ?? 'No vence' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </small>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <table class="mw-100 px-3 py-3 pt-0 pb-0 center">

                                    <tr>

                                        <td style="width: 33.33%;text-align: center;">
                                            F. ______________________________________
                                        </td>
                                        <td style="width: 33.33%;text-align:center;">
                                            F. ______________________________________
                                        </td>
                                    </tr>

                                    <tr>

                                        <td class="p-0 center">
                                            <small>
                                                entrega
                                            </small>
                                        </td>
                                        <td class="p-0 center">
                                            <small>
                                                recibe
                                            </small>
                                        </td>
                                        <td class="p-0 center">
                                            <small>
                                                impresion: <u>{{ date('d-m-Y h:i:s a') }}</u>

                                            </small>
                                        </td>
                                    </tr>

                                </table>

                            </td>
                        </tr>
                    </table>
                </div>
            </main>
        @endforeach
    </main>
</body>

</html>
