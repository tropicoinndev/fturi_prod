<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>REPORTE DE EVENTOS PRODUCCION</title>

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
            margin-bottom: 3px;
        }
    </style>
</head>

@foreach ($eventos as $evento )
<body>

    <main>

        <div class="row col-12">
            <div class="row">
                <div class="col-11 text-right"><strong>#{{ $evento->id }}</strong></div>
                <div class="col-6 text-center"><strong>REPORTE DE EVENTOS DE PRODUCCION</strong></div>
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
                    style=" width: 655px; border-bottom: 1px solid black;">{{ \Carbon\Carbon::parse($evento->inicio)->format('h:i A') }}
                    a
                    {{ \Carbon\Carbon::parse($evento->finalizacion)->format('h:i A') }}</span>

            </div>
            <div class="row ">

                        <span class="label">SALÓN:</span>
                        <span class="label"
                            style=" width: 650px; border-bottom: 1px solid black;">{{ implode(', ', $evento->salones->pluck('salones.salon')->toArray()) }}</span>
            </div>
            <div class="row">
                <span class="label">FACTURAR A:</span>
                <span class="label" style=" width: 610px; border-bottom: 1px solid black;"><strong
                        class="text-uppercase font-bold">{{ $evento->clientes->nombre ?? $evento->titular }}</strong>
                    @if ($evento->clientes != null)
                        (<span class="text-uppercase"> {{ $evento->clientes->ccf == 0 ? 'comprobante consumidor final' : 'comprobante credito fiscal' }}</span>)
                    @endif
                </span>
            </div>



            <div class="row"></div>

            <section class="row col-12">
                <div class="col-8">
                    <span class="text-uppercase">Menu:</span>
                    <table style="width: 100%; border-collapse: collapse; height:447px;">
                        @php
                            $ordenes =$evento->ordenesTest()->get();
                            $comandas =$evento->comandasTest()->get();
                             $subtotal = 0;
                        @endphp
                        @foreach ($ordenes as $o)
                            @foreach ($o->detalle_orden as $detalle)
                                <tr>
                                    <td style="width: 50%;">
                                        <div style="word-wrap: break-word;">
                                            <span>{{ $detalle->cantidad }}</span> -
                                            <span>{{ $detalle->servicios->servicio }}</span>
                                        </div>
                                    </td>
                                    <td style="width: 10%;visibility: hidden;">..........$</td>
                                    <td></td>
                                </tr>

                            @endforeach
                        @endforeach
                        @foreach ($comandas as $c)
                            @foreach ($c->detalles_comanda as $detalle)
                                <tr>
                                    <td style="width: 50%;">
                                        <div style="word-wrap: break-word;">
                                            <span>{{ $detalle->cantidad }}</span>
                                            <span>{{ $detalle->precios->detalle }} </span>
                                            <span>{{ $detalle->observaciones }}</span>

                                        </div>

                                    </td>
                                    <td style="width: 5%;visibility: hidden;">..........$</td>

                                    <td style="width: 50%;"> </td>
                                </tr>

                            @endforeach
                        @endforeach
                    </table>
                </div>



            </section>

            <section class="row" style="margin-top: 82px;">
                <div class="col-7 float-start" style="width: 55%; margin-right: 50px;">
                    <span class="text-uppercase">OBSERVACIONES</span>
                    <p class="text-uppercase"
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
            </section>




    </main>
</body>

@endforeach

</html>
