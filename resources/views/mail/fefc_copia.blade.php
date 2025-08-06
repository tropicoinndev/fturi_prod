<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>DTE - TURISTICAS DE ORIENTE S.A. DE C.V.</title>

        @php
            $orientacionPagina = 1;#1 = portrait, 2 = landscape
            $ancho = 21.59;
            $alto  = 27.94;

            #Margenes: dinamico
            $marginTop    = 0.5;
            $marginBottom = 0.8;#0.8, Si es positivo (el margen se desplaza hacia arriba), si es negativo (el margen se desplaza hacia abajo)
            $marginLeft   = 0.5;
            $marginRight  = 0.5;

            #Espacio de trabajo disponible: dinamico
            $anchoDisponible = $ancho - ($marginLeft + $marginRight); #21.59 - (6)    = 15.59cm
            $altoDisponible  = $alto  - ($marginTop  + $marginBottom);#27.94 - (5.08) = 22.86cm

            #Sistema de grillas para las filas y columnas: dinamico
            $anchoDeUnaColumna = $anchoDisponible / 12;#15.59cm / 12cols = 1.299166667cm cada columna tendra un ancho dinamico
            #-----

            $valorPixel = 0.23;/*1px = 0.0264583cm*/
        @endphp

        <style>
            @page {
                /*width: 8.5in;
                height: 11in;
                margin: 22px;*/
                margin: {{ $marginTop }}cm {{ $marginRight }}cm {{ $marginBottom }}cm {{ $marginLeft }}cm;
            }

            body {
                text-transform: uppercase;
                font-size: 7pt;
                font-family: sans-serif;
                color: #546E7A;
                letter-spacing: 0.3px;
            }

            .watermark {
                position: absolute;
                top: 40%;
                left: 55%;
                width: 100%;
                transform: translate(-50%, -50%) rotate(-50deg);
                color: #546E7A;
                opacity: 0.2;
                font-size: 80pt;
                pointer-events: none;
                z-index: 1000;
            }

            /*Definicion de tamaño dinamico de cada columna*/
            .col-1  { width: {{ ($anchoDeUnaColumna       - $valorPixel)}}cm; }/*15.59cm / 12 cols = 1.299166667cm*/
            .col-2  { width: {{ ($anchoDeUnaColumna * 2)  - $valorPixel }}cm; }/*1.299166667 * 2  = 2.598333334cm*/
            .col-3  { width: {{ ($anchoDeUnaColumna * 3)  - $valorPixel }}cm; }/*1.299166667 * 3  = 3.897500001cm*/
            .col-4  { width: {{ ($anchoDeUnaColumna * 4)  - $valorPixel }}cm; }/*1.299166667 * 4  = 5.196666668cm*/
            .col-5  { width: {{ ($anchoDeUnaColumna * 5)  - $valorPixel }}cm; }/*1.299166667 * 5  = 6.495833335cm*/
            .col-6  { width: {{ ($anchoDeUnaColumna * 6)  - $valorPixel }}cm; }/*1.299166667 * 6  = 7.795000002cm*/
            .col-7  { width: {{ ($anchoDeUnaColumna * 7)  - $valorPixel }}cm; }/*1.299166667 * 7  = 9.094166669cm*/
            .col-8  { width: {{ ($anchoDeUnaColumna * 8)  - $valorPixel }}cm; }/*1.299166667 * 8  = 10.393333336cm*/
            .col-9  { width: {{ ($anchoDeUnaColumna * 9)  - $valorPixel }}cm; }/*1.299166667 * 9  = 11.692500003cm*/
            .col-10 { width: {{ ($anchoDeUnaColumna * 10) - $valorPixel }}cm; }/*1.299166667 * 10 = 12.99166667cm*/
            .col-11 { width: {{ ($anchoDeUnaColumna * 11) - $valorPixel }}cm; }/*1.299166667 * 11 = 14.290833337cm*/
            .col-12 { width: {{ ($anchoDeUnaColumna * 12) - $valorPixel }}cm; }/*1.299166667 * 12 = 15.590000004cm*/

            [class*="col-"] {
                float: left;
                margin-bottom: -0.03cm;
            }
            .row { clear: both; }

            /*Estilos para encabezado y pie de pagina*/
            header, footer {
                position: fixed;
                left: 0;/*Nos aseguramos que se extienda a lo ancho*/
                right: 0;/*Nos aseguramos que se extienda a lo ancho*/
                /* height: 50px;
                background-color: #f8f9fa;
                color: #6c757d;
                text-align: center;
                line-height: 35px; */
            }
            header { top: 0; }
            footer {
                bottom: 2cm;
                padding: 1.3%;
            }

            /*Salto de pagina*/
            .page-break { page-break-after: always; }

            /*Estilos para las imagenes*/
            .imgLogo {
                width: 3cm;
                height: 2cm;
            }
            .imgQR {
                width: 2.5cm;
                height: 2.5cm;
            }

            /*Alineacion de texto*/
            .text-center { text-align: center; }
            .text-right { text-align: right; }
            .text-left { text-align: left; }

            /*Margenes*/
            .mt-n015 { margin-top: -0.15cm; }
            .mt-n02 { margin-top: -0.2cm; }
            .mt-n025 { margin-top: -0.25cm; }
            .mt-n04 { margin-top: -0.4cm; }
            .mb-06 { margin-bottom: 0.6cm; }

            /*Tamaños*/
            .fs-9 { font-size: 9pt; }
            .fs-10 { font-size: 10pt; }
            .loweCaseEmail {
                text-transform: lowercase;
                font-size: 8.5pt;
            }

            /*Contenedor principal*/
            .content {
                border-radius: 15px;
                border: 1px solid #ccc;
                padding: 10px;
                box-sizing: border-box;
                height: 21cm;
                margin-top: 60px;/*Espacio para el header*/
                margin-bottom: 60px;/*Espacio para el footer*/
            }
            
            /*Bordes y color*/
            .gris-oscuro { background: #f2f2f2; }
            .borde-gris { border: solid 1px #ccc; }
            .borde-t-gris { border-top: solid 1px #ccc; }
            .borde-r-gris { border-right: solid 1px #ccc; }
            .borde-b-gris { border-bottom: solid 1px #ccc; }
            .borde-left { border-left: solid 1px #ccc; }
            .estilo {
                border-radius: 15px;
                border: 1px solid #ccc;/*0.01px*/
            }
            .borde-t-none { border-top: none; }
            .borde-r-none { border-right: none; }
            .borde-b-none { border-bottom: none; }
            .borde-l-none { border-left: none; }

            /*Estilos de tabla*/
            .table {
                /*border-collapse: separate;*/
                margin-left: 2.5%;
                margin-top: 4.4cm;
                border-spacing: 0;
                /*border: 1px solid #ccc;*//*Grosor y color del borde*/
                border-radius: 8px;/*Radio de los bordes redondeados*/
            }
            .table th {
                background-color: #f2f2f2;/*Color de fondo del encabezado*/
                /*border-right: 0.5px solid #ccc;*//*Bordes derechos para todos los th del encabezado*/
            }
            .table thead th:first-child {
                border-top-left-radius: 8px;/*Esquina superior izquierda*/
            }
            .table thead th:last-child {
                border-top-right-radius: 8px;/*Esquina superior derecha*/
            }
            .table th, .table td {
                /*border-top: 1px solid #ccc;*//*Borde superior para encabezados*/
                /*border-right: 1px solid #ccc;*//*Borde derecho*/
                /*border-bottom: 1px solid #ccc;*//*Borde inferior*/
                padding: 4px;/*Espaciado interno*/
            }
        </style>
    </head>
    <body>
        {{-- <header>
            <img src="{{ env('logo') }}" class="logo" alt="Logo {{ env('empresa') }}">
            <p>Encabezado del Documento</p>
        </header>

        <footer>
            <p>Pie de Página del Documento</p>
        </footer> --}}

        <div class="watermark">Inválidado</div>

        <!--Contenedor principal-->
        <div style="width: {{ $anchoDisponible }}cm; height: {{ ($altoDisponible - 0.10) }}cm;" class="estilo">



            <!--Fila 1: Logo y titulo-->
            <div class="row">
                <div class="col-3 text-center">
                    <p><img src="{{ env('logo') }}" class="logo" alt="Logo {{ env('empresa') }}" class="imgLogo"></p>
                </div>

                <div class="col-6 text-center">
                    <p class="fs-9"><b>Documento Tributario Electrónico</b></p>
                    <p>Comprobante de Crédito Fiscal</p>
                </div>

                <div class="col-3 text-right">
                    <p>V 0.1</p>
                </div>
            </div>



            <!--Fila 2: Informacion del sellador y codigo QR-->
            <div style="padding: 8px; margin-left: 2.5%;" class="row">
                <div class="col-4 mt-n04">
                    <p><b>Código de generación:</b></p>
                    <p class="mt-n025">C6DCC977-293F-4064-AA78-AF23335B532B</p>

                    <p class="mt-n015"><b>Número de control:</b></p>
                    <p class="mt-n025">DTE-03-S032P001-000000000023253</p>

                    <p class="mt-n015"><b>Sello de recepción:</b></p>
                    <p class="mt-n025">2024B0D1A0D1B0D844DD8AD78CADFCBB36D9NPCA</p>
                </div>

                <div style="margin-left: 1.2cm;" class="col-4 mt-n04">
                    <span>
                        <p><b>Modelo de facturación:</b></p>
                        <p class="mt-n025">Modelo de facturación previo</p>

                        <p class="mt-n015"><b>Tipo de transmisión:</b></p>
                        <p class="mt-n025">Transmisión normal</p>

                        <p class="mt-n015"><b>Fecha y hora de generación:</b></p>
                        <p class="mt-n025">{{ date("Y-m-d H:i:s") }}</p>
                    </span>
                </div>

                <div class="col-4 mt-n04">
                    <p><b>Tipo de documento:</b></p>
                    <p class="mt-n025">Comprobante de crédito fiscal</p>

                    <p class="mt-n015"><b>Tipo de generación:</b></p>
                    <p class="mt-n025">---</p>

                    <p class="mt-n015"><b>Nº Documento:</b></p>
                    <p class="mt-n025">---</p>

                    <p class="mt-n015"><b>Fecha de emisión:</b></p>
                    <p class="mt-n025">{{ date('Y-m-d H:i:s') }}</p>
                </div>
            </div>



            <!--Fila 3: Titulo de emisor y receptor-->
            <div class="row text-center">
                <div class="col-6"><b class="fs-9">Emisor</b></div>
                <div class="col-6"><b class="fs-9">Receptor</b></div>
            </div>



            <!--Fila 4: Informacion de emisor y receptor-->
            <div style="padding: 5px 2.7%;" class="row"><!--Este padding es el margen entre la linea del documento y la linea de la columna-->
                <div style="padding: 0px 10px;" class="col-5 estilo">
                    <p><b>Nombre:</b> Turisticas de Oriente S.A de C.V</p>
                    <p class="mt-n02"><b>Nombre comercial:</b> Hotel Trópico Inn</p>
                    <p class="mt-n02"><b>NIT:</b> 06143112690013</p>
                    <p class="mt-n02"><b>NRC:</b> 5738</p>
                    <p class="mt-n02"><b>Actividad:</b> Hosteleria y turismo</p>
                    <p class="mt-n02"><b>Dirección:</b> Av. Rossevelt Sur, 3301, San Miguel, El Salvador</p>
                    <p class="mt-n02"><b>Teléfono:</b> +(503) 2682-1000</p>
                    <p class="mt-n02"><b>Correo:</b><span class="lowerCaseEmail"> info@tropicoinn.com.sv</span></p>
                    <p class="mt-n02"><b>Tipo de establecimiento:</b> Casa matriz</p>
                </div>

                <div class="col-1"><p></p></div><!--Columna vacia-->

                <div style="padding: 0px 10px;" class="col-5 estilo">
                    <p><b>Nombre:</b> Turisticas de Oriente S.A de C.V</p>
                    <p class="mt-n02"><b>Nombre comercial:</b> Hotel Trópico Inn</p>
                    <p class="mt-n02"><b>NIT:</b> 06143112690013</p>
                    <p class="mt-n02"><b>NRC:</b> 5738</p>
                    <p class="mt-n02"><b>Actividad:</b> Hosteleria y turismo</p>
                    <p class="mt-n02"><b>Dirección:</b> Av. Rossevelt Sur, 3301, San Miguel, El Salvador</p>
                    <p class="mt-n02"><b>Teléfono:</b> +(503) 2682-1000</p>
                    <p class="mt-n02 mb-06"><b>Correo:</b><span class="lowerCaseEmail"> info@tropicoinn.com.sv</span></p>
                </div>
            </div>



            <!--Fila 5: Tabla-->
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Cod.</th>
                        <th scope="col">Cant.</th>
                        <th scope="col">Unidad</th>
                        <th style="width: 5.4cm;" scope="col">Descripción</th>
                        <th style="width: 1.5cm;" scope="col">Precio Unitario</th>
                        <th style="width: 1.5cm;" scope="col">Otros Montos</th>
                        <th style="width: 1.6cm;" scope="col">Ventas No Sujetas</th>
                        <th scope="col">Ventas Exentas</th>
                        <th style="width: 1.7cm;"scope="col">Ventas Gravadas</th>
                    </tr>
                </thead>
                <tbody>
                    {{--Datos de prueba--}}
                    @php
                        $data = [
                            ['id'=>1], ['id'=>2], ['id'=>3], ['id'=>4], ['id'=>5],
                            ['id'=>6], ['id'=>7], ['id'=>8], ['id'=>9], ['id'=>10],
                            ['id'=>11],['id'=>12],['id'=>13],['id'=>14],['id'=>15],
                            ['id'=>16],['id'=>17],['id'=>18],['id'=>19],['id'=>20],
                            ['id'=>21],['id'=>22],['id'=>23],['id'=>24],['id'=>25],
                            ['id'=>30],['id'=>31],['id'=>32],['id'=>33],['id'=>34],
                            ['id'=>35],['id'=>36],['id'=>37],['id'=>38],['id'=>39],
                        ];
                    @endphp

                    @foreach($data as $key => $item)
                        <tr>
                            <td scope="row" class="text-right borde-left borde-r-gris borde-t-none borde-b-none">{{ $item['id'] }}</td>
                            <td class="text-right borde-r-gris">6258</td>
                            <td class="text-right borde-r-gris">12</td>
                            <td class="borde-r-gris">Unidad</td>
                            <td class="borde-r-gris">Sixpack Cerveza Golden</td>
                            <td class="text-right borde-r-gris">$ 0000.00</td>
                            <td class="text-right borde-r-gris">$ 0000.00</td>
                            <td class="text-right borde-r-gris">$ 0000.00</td>
                            <td class="text-right borde-r-gris">$ 0000.00</td>
                            <td class="text-right borde-r-gris">$ 0000.00</td>
                        </tr>
                    @endforeach
                    <!--Campos de factura-->
                    <tr class="text-right">
                        <td style="border-radius: 0 0 0 12px;" colspan="6" class="borde-gris borde-r-none borde-l-none borde-b-none"></td>
                        <td style="border-radius: 0 0 0 12px;" class="gris-oscuro borde-gris borde-r-none"><b>Sumas:</b></td>
                        <td class="gris-oscuro borde-t-gris borde-b-gris"><b>$ 0000.00</b></td>
                        <td class="gris-oscuro borde-t-gris borde-b-gris"><b>$ 0000.00</b></td>
                        <td class="gris-oscuro borde-gris borde-l-none"><b>$ 0000.00</b></td>
                    </tr>
                    <tr class="text-right">
                        <td class="text-left" colspan="8">
                            <b>Valor en letras:</b> CUARENTA Y SIETE DÓLARES CON CUARENTA Y SEIS CENTAVOS
                        </td>
                        <td class="gris-oscuro borde-left"><b>IVA</b></td>
                        <td class="gris-oscuro borde-r-gris"><b>$ 0000.00</b></td>
                    </tr>
                    <tr class="text-right">
                        <td class="text-left" colspan="8">
                            <b>Condición de la operación:</b> Contado
                        </td>
                        <td class="gris-oscuro borde-left"><b>(+) CET 5% TURISMO</b></td>
                        <td class="gris-oscuro borde-r-gris"><b>$ 0000.00</b></td>
                    </tr>
                    <tr class="text-right">
                        <td colspan="8">
                            <p style="position: absolute;">
                                <img style="margin-top: 0.5cm;" src="https://borealtech.com/wp-content/uploads/2018/10/codigo-qr-1024x1024-1.jpg" alt="Codigo QR" class="imgQR">
                            </p>
                            <span style="position: absolute; margin-top: 0.4cm;" class="text-left">Consulta de comprobante electrónico</span>
                        </td>
                        <td class="gris-oscuro borde-left"><b>(+) IMP AD-VALOREM</b></td>
                        <td class="gris-oscuro borde-r-gris"><b>$ 0000.00</b></td>
                    </tr>
                    <tr class="text-right">
                        <td colspan="8"></td>
                        <td class="gris-oscuro borde-left"><b>(+) PROPINA</b></td>
                        <td class="gris-oscuro borde-r-gris"><b>$ 0000.00</b></td>
                    </tr>
                    <tr class="text-right">
                        <td colspan="8"></td>
                        <td class="gris-oscuro borde-left"><b>SUB - TOTAL</b></td>
                        <td class="gris-oscuro borde-r-gris"><b>$ 0000.00</b></td>
                    </tr>
                    <tr class="text-right">
                        <td colspan="8"></td>
                        <td class="gris-oscuro borde-left"><b>VENTAS EXENTAS</b></td>
                        <td class="gris-oscuro borde-r-gris"><b>$ 0000.00</b></td>
                    </tr>
                    <tr class="text-right">
                        <td colspan="8"></td>
                        <td class="gris-oscuro borde-left"><b>VENTAS NO SUJETAS</b></td>
                        <td class="gris-oscuro borde-r-gris"><b>$ 0000.00</b></td>
                    </tr>
                    <tr class="text-right">
                        <td colspan="8"></td>
                        <td class="gris-oscuro borde-left"><b>(-) 1% IVA RETENIDO</b></td>
                        <td class="gris-oscuro borde-r-gris"><b>$ 0000.00</b></td>
                    </tr>
                    <tr class="text-right">
                        <td colspan="8"></td>
                        <td style="border-radius: 0 0 0 12px;" class="gris-oscuro borde-gris borde-t-none borde-r-none"><b class="fs-10">VENTA TOTAL</b></td>
                        <td style="border-radius: 0 0 12px 0;" class="gris-oscuro borde-gris borde-b-gris borde-t-none borde-l-none"><b class="fs-10">$ 0000.00</b></td>
                    </tr>
                </tbody>
            </table>

            <!--Título de inválido-->
            <div>
                <p style="color: #ccc; font-size: 20pt; text-align: center;">Inválidado</p>
            </div>



            <!--Fila 6: Codigo QR-->
            {{-- <div class="row">
                <div class="col-12">
                    <p><img src="https://borealtech.com/wp-content/uploads/2018/10/codigo-qr-1024x1024-1.jpg" alt="Codigo QR" class="imgQR"></p>
                </div>
            </div> --}}
            
        </div><!--End contenedor principal-->



        <!--Este script de PHP se ejecuta por cada pagina que contenga el documento y queda afuera de todo-->
        <script type="text/php">
            if(isset($pdf)){
                $pdf->page_script('
                    #Fuente y tamaño elegidos
                        $font = $fontMetrics->get_font("sans-serif", "normal");
                        $size = 10;
                    #Coordernadas para colocar el texto en el pie de pagina
                        if({{ $orientacionPagina }} === 2){#Landscape
                            $horizontal = 285;#Entre mas alto el numero, el texto se desplaza hacia la derecha
                            $vertical   = 592;#Entre mas alto el numero, el texto se desplaza hacia abajo
                        }
                        else{#Portrait
                            $horizontal = 200;
                            $vertical   = 772;
                        }
                    #Impresion del texto
                        $pdf->text($horizontal,$vertical,"Trópico Inn | {{ date("Y-m-d H:i:s") }} | Página $PAGE_NUM de $PAGE_COUNT",$font,$size);
                ');
            }
        </script>
    </body>
</html>
