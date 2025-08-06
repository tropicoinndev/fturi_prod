<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>REPORTE DE MONTAJE DE SONIDOS DE EVENTOS</title>

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

        .label {
            display: inline-block;
        }

        .margin-bottom {
            margin-bottom: 1px;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            border-collapse: collapse;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .title-table th {
            font-weight: bold;
            text-align: left;
            padding: 3px;
            background-color: #f2f2f2;
            border-bottom: 1px solid #dee2e6;
        }

        .table td,
        .table th {
            padding: 8px;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }



    </style>
</head>

@foreach ($data as $evento)
    @php
        $salonesSeparados = $evento->salones->pluck('separado')->contains(true);
        $ordenes = $evento->ordenesTest()->get();

        $detalle_ordenes = $ordenes->flatMap(function ($comanda) {
            return $comanda->detalle_orden;
        });

        $ordenados = count($detalle_ordenes);

    @endphp

    <body>

        <main>

            <div class="row col-12">
                <div class="row">
                    <div class="col-11 text-right"><strong>#{{ $evento->id }}</strong></div>
                    <div class="col-6 text-center"><strong>REPORTE DE MONTAJE DE SONIDOS</strong></div>
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
                    <div class="row">
                    </div>
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
                        style=" width: 650px; border-bottom: 1px solid black;">{{ \Carbon\Carbon::parse($evento->inicio)->format('h:i A') }}
                        a
                        {{ \Carbon\Carbon::parse($evento->finalizacion)->format('h:i A') }}</span>

                </div>
                <div class="row ">
                    <div class="row-group">
                        <div class="row-item">
                            <span class="label">SALÓN:</span>
                            <span class="label"
                                style=" width: 645px; border-bottom: 1px solid black;">{{ implode(', ', $evento->salones->pluck('salones.salon')->toArray()) }}</span>

                        </div>

                    </div>
                </div>
                <div class="row">

                    <span class="label">ESTADO SALONES:</span>
                    <span class="label text-uppercase" style=" width: 583px; border-bottom: 1px solid black;">
                        @if ($salonesSeparados)
                            Separados
                        @else
                            Unidos
                        @endif
                    </span>
                </div>

                <div class="row">
                    <span class="label">FACTURAR A:</span>
                    <span class="label text-uppercase" style=" width: 610px; ">{{ $evento->clientes->nombre ?? $evento->titular }}

                    </span>
                </div>




                <div class="test row margin-bottom  font-bold text-center"></div>

                <div class="row col-8">
                    <h4>ORDENES DE SERVICIOS DEL EVENTO</h4>
                    <table class="table table-striped" style="width: 680px; height: 200px;" border="0" cellspacing="0" cellpadding="0">
                        <thead class="title-table">
                            <tr>
                                <th>Concepto</th>
                                <th>Cantidad</th>
                                <th>Nº Orden</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($detalle_ordenes->isEmpty())
                                <tr>
                                    <td colspan="3" style="text-align: center;">No se han agregado órdenes aún.</td>
                                </tr>
                            @else
                            @foreach ($detalle_ordenes as $orden)
                                <tr>
                                    <td>{{ $orden->servicios->servicio }}</td>
                                    <td>{{ $orden->cantidad }}</td>
                                    <td>Nº {{ $orden->ordenes_id }}</td>
                                </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>



                <div class="row"></div>
                <div class="row text-center">

                    <span class="label">TIPO DE MONTAJE:</span>


                </div>
                <div class="row col-12 text-uppercase">

                    <div class="row">
                        <span >Tipo de mesas:</span>
                        <span  style="width: 585px;">
                            {{ $evento->montajes->montaje ?? 'No es requerido' }}
                        </span>
                    </div>

                    <div class="row">
                        <span >Sonido:</span>
                        <span style="width: 626px;">
                            {{ $evento->sonidos->sonido ?? 'No es requerido' }}
                        </span>
                    </div>
                    <div class="row ">
                        <span class="label">Eq. de Amplif. y Microf.:</span>
                        <p style="width: 680px;  text-align: justify;">
                            {{ $evento->observaciones_sonidos ?? 'No es requerido' }}
                        </p>
                    </div>

                        <div class="row">
                        <span class="label">observaciones del montaje:</span>
                        <p
                            style="width: 680px; text-align: justify;">
                        {{ $evento->montaje ?? 'No es requerido' }}
                        </p>
                    </div>

                    <div class="row">
                        <span >Otros:</span>
                        <span
                            style="width: 690px;">

                                @if ($evento->detalle_montaje->isNotEmpty())
                                    @php $otros = $evento->detalle_montaje->pluck('otros')->filter()->implode(', '); @endphp
                                    @if ($otros)
                                        {{ $otros }}
                                    @endif
                                @endif

                        </span>
                    </div>

                </div>

                <section class="row" style="margin-top: 0px;">
                    <div class="col-7 float-start" style="width: 55%; margin-right: 50px;">
                        <span class="text-uppercase">OBSERVACIONES</span>
                        <p class="text-uppercase"
                            style="border: 1px solid #000; padding: 2px; height: 51px; overflow-y: auto; word-wrap: break-word;">
                            <b>{{ $evento->observaciones }}</b>
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
                </section>




        </main>
    </body>
@endforeach

</html>
