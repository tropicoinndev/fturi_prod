{{--@if($data)--}}{{--La variable data es la que viene desde el controlador--}}
    <!DOCTYPE html>
    <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            
            {{--Metadatos personalizados--}}
            <meta name="author" content="{{ env('empresa') }}">
            <meta name="description" content="TITULO DE REPORTE VACIO">
            <meta name="keywords" content="TITULO DE REPORTE VACIO">

            <title>{{ env('empresa') }}</title>

            {{--Todo el ancho disponible del documento se divide entre 12 columnas,
                no importa si es vertical u horizontal, lo tomará en automático--}}
            @php($anchoDeUnaColumna = 100 / 12)
            @php($orientacionPagina = 1)

            <style>
                @page {
                    /*1.- Margenes desde donde comenzará el recuadro, 0.8cm indica el limite inferior hasta donde llegaran de los registros*/
                    margin: 0.5cm 0.5cm 0.8cm 0.5cm;
                }

                * {
                    font-size: 7pt;
                    font-family: sans-serif;
                    letter-spacing: 0.2px;
                    text-transform: uppercase;
                }

                body {
                    color: #546E7A;
                }

                /*---Definición de tamaño dinámico de cada columna---*/
                .col-1  { width: {{ $anchoDeUnaColumna      }}%; }
                .col-2  { width: {{ $anchoDeUnaColumna * 2  }}%; }
                .col-3  { width: {{ $anchoDeUnaColumna * 3  }}%; }
                .col-4  { width: {{ $anchoDeUnaColumna * 4  }}%; }
                .col-5  { width: {{ $anchoDeUnaColumna * 5  }}%; }
                .col-6  { width: {{ $anchoDeUnaColumna * 6  }}%; }
                .col-7  { width: {{ $anchoDeUnaColumna * 7  }}%; }
                .col-8  { width: {{ $anchoDeUnaColumna * 8  }}%; }
                .col-9  { width: {{ $anchoDeUnaColumna * 9  }}%; }
                .col-10 { width: {{ $anchoDeUnaColumna * 10 }}%; }
                .col-11 { width: {{ $anchoDeUnaColumna * 11 }}%; }
                .col-12 { width: {{ $anchoDeUnaColumna * 12 }}%; }

                [class*="col-"] {
                    float: left;
                    /*margin-bottom: -0.03%;*/
                }
                .row { clear: both; }
                /*---------------------------------------------------*/

                /*---Estilos generales---*/
                .text-center { text-align: center; }
                .text-right { text-align: right; }

                .fs-10pt { font-size: 10pt; }

                .lowerCaseEmail { text-transform: lowercase; }

                .w-05 { width: 0.5cm; }
                .w-1 { width: 1cm; }
                .w-2 { width: 2cm; }
                .w-3 { width: 3cm; }

                .mt-n015 { margin-top: -0.15cm; }
                .mt-15px { margin-top: 15px; }
                .mt-2 { margin-top: 2cm; }

                .mr-n06 { margin-right: -0.6cm; }
                .mr-03 { margin-right: 0.3cm; }

                .ml-02 { margin-left: 0.2cm; }
                .ml-05 { margin-left: 0.5cm; }
                .ml-1 { margin-left: 1cm; }

                /*Marca de agua*/
                .watermark {
                    position: fixed;
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

                .estilo-emisor-receptor {
                    border: solid 1px #ccc;
                    border-radius: 12px;
                    padding: 0 0.2cm;
                    margin-top: -0.2cm;
                }
                .estilo-campos-factura {
                    background: #f2f2f2;
                    padding: 0.15cm 0.1cm;
                }
                /*-----------------------*/

                /*---Header---*/
                header {/*3.- Contenedores fijos*/
                    position: fixed;
                    left: 0;/*Nos aseguramos que se extienda a lo ancho*/
                    right: 0;/*Nos aseguramos que se extienda a lo ancho*/
                }
                header {
                    top: 0;
                    margin: 0.3cm;/*Margen de 0.3cm en todas las coordenadas*/
                }
                /*---------------------*/

                .logoEmpresa {
                    width: 2.5cm;
                    height: 1.7cm;
                }
                .imgQR {
                    width: 2.5cm;
                    height: 2.4cm;
                }

                /*Salto de pagina*/
                /*.page-break { page-break-after: always; }*/
                
                /*---Estilos para la tabla---*/
                table {
                    border-collapse: collapse;
                    width: 100%;
                }
                table thead {
                    background-color: #0f45c3;/*Color para cada comprobante*/
                    color: #ffffff;
                }
                /*thead { background: #dddddd80; }*/
                th, td {
                    padding: 2.98px;
                    /*border-bottom: 1px solid #dddddd80;*/
                }
                td {/*Bordes izquierdos y derechos*/
                    border-left: 1px solid #dddddd;
                    border-right: 1px solid #dddddd;
                }
                table thead th:first-child {
                    border-top-left-radius: 10px;/*Esquina superior izquierda*/
                }
                table thead th:last-child {
                    border-top-right-radius: 10px;/*Esquina superior derecha*/
                }
                /*.w-5px { width: 5px; }
                .w-111px { width: 111px; }*/
                /*.btl-radius { border-top-left-radius: 10px; }*/
                /*.btr-radius { border-top-right-radius: 10px; }*/
                .bbl-radius { border-bottom-left-radius: 10px; }
                .bbr-radius { border-bottom-right-radius: 10px; }

                /*.bt-none { border-bottom: none; }*/
                .borde-t-show { border-top: solid 1px #ccc; }
                /*.bl-show { border-left: solid 1px #ccc; }*/
                .borde-l-none { border-left: none; }
                .borde-r-none { border-right: none; }
                /*.br-show { border-right: solid 1px #ccc; }*/
                .borde-b-show { border-bottom: solid 1px #ccc; }
                /*---------------------------*/

                .line {/*2.- altura del recuadro*/
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    border: solid 1px #ccc;
                    border-radius: 12px;
                    width: 100%;
                    /*Para saber a que altura quedara la linea inferior del recuadro segun la orientacion de la pagina*/
                    height: {{ $orientacionPagina === 2 ? 106.3 : 100 }}%;
                }
            </style>

            @yield('styles'){{--Estilos personalizados por cada reporte--}}
        </head>
        <body style="margin: 10.8cm 0.5cm {{ $orientacionPagina === 2 ? 0.5 : 0.4 }}cm 0.5cm;">{{--Margenes del contenido--}}
            <div class="line"></div>
            
            <header>
                <div class="row mr-03 ml-05">
                    <div class="col-3">
                        <img src="{{ env('logo') }}" alt="Logo {{ env('empresa') }}" alt="Logo" class="logoEmpresa">
                    </div>
                    <div class="col-6 text-center">
                        <p>
                            <b class="fs-10pt">DOCUMENTO TRIBUTARIO ELECTRÓNICO</b>
                            <p>CONSUMIDOR FINAL</p>
                        </p>
                    </div>
                    <div class="col-3">
                        <p class="text-right mt-15px">VERSIÓN 1</p>
                    </div>
                </div>

                {{--@yield('header-content')--}}
                <div class="row ml-02 mr-n06 mt-2">
                    <div style="padding: 0 0.3cm;" class="col-5">
                        <p><b>CÓDIGO DE GENERACIÓN:</b></p>
                        <p class="mt-n015">97631BF6-6183-4A9B-BF3E-872CAEE42B7D</p>
                        <p class="mt-n015"><b>NÚMERO DE CONTROL:</b></p>
                        <p class="mt-n015">DTE-01-M001P001-000000000001414</p>
                        <p class="mt-n015"><b>SELLO DE RECEPCIÓN:</b></p>
                        <p class="mt-n015">20247AAB30FC60F64D7296877662819C8AB1312N</p>
                    </div>
                    <div class="col-1"></div>{{--Columna vacia--}}
                    <div style="padding: 0 0.1cm;" class="col-5">
                        <p><b>MODELO DE FACTURACIÓN:</b></p>
                        <p class="mt-n015">FACTURACIÓN PREVIO</p>
                        <p class="mt-n015"><b>TIPO DE TRANSMISIÓN:</b></p>
                        <p class="mt-n015">TRANSMISIÓN NORMAL</p>
                        <p class="mt-n015"><b>FECHA Y HORA DE GENERACIÓN:</b></p>
                        <p class="mt-n015">2024-10-14 11:25:08</p>
                    </div>
                </div>

                <div class="row text-center mt-2">
                    <div class="col-5">
                        <p><b class="fs-10pt ml-1">EMISOR</b></p>
                    </div>
                    <div class="col-1"></div>{{--Columna vacia--}}
                    <div class="col-5">
                        <p><b style="margin-left: 2.7cm;" class="fs-10pt">RECEPTOR</b></p>
                    </div>
                </div>

                <div class="row ml-02 mr-n06">
                    <div style="height: 4.29cm;" class="col-5 estilo-emisor-receptor">
                        <p><b>NOMBRE:</b> TURISTICAS DE ORIENTE S.A. DE C.V</p>
                        <p class="mt-n015"><b>NOMBRE COMERCIAL:</b> TROPICO INN</p>
                        <p class="mt-n015"><b>NIT:</b> 12170509850014</p>
                        <p class="mt-n015"><b>NRC:</b> 90670</p>
                        <p class="mt-n015"><b>ACTIVIDAD:</b> HOTELES</p>
                        <p class="mt-n015"><b>DIRECCIÓN:</b> AVENIDA ROOSEVELT SUR, 303, SAN MIGUEL, SAN MIGUEL</p>
                        <p class="mt-n015"><b>TELÉFONO:</b> 26821000</p>
                        <p class="mt-n015 lowerCaseEmail"><b>CORREO:</b> clientes@tropicoinn.com.sv</p>
                        <p class="mt-n015"><b>TIPO DE ESTABLECIMIENTO:</b> CASA MATRIZ</p>
                    </div>
                    <div class="col-1"></div>{{--Columna vacia--}}
                    <div style="height: 4.29cm;" class="col-5 estilo-emisor-receptor">
                        <p><b>NOMBRE:</b> LILIAM HASBUN DE BATARSE</p>
                        <p class="mt-n015"><b>IDENTIFICACIÓN:</b> 06140206340025</p>
                        <p class="mt-n015"><b>ACTIVIDAD:</b> ---</p>
                        <p class="mt-n015"><b>DIRECCIÓN:</b> AVENIDA ROOSEVELT SUR, 303, SAN MIGUEL, SAN MIGUEL</p>
                        <p class="mt-n015 lowerCaseEmail"><b>CORREO:</b> clientes@tropicoinn.com.sv</p>
                    </div>
                </div>

                <div class="watermark">INVALIDADO</div>
                <div class="text-center">
                    <p style="color: #ce2424; font-size: 20pt; margin-top: 14.3cm;">INVALIDADO</p>
                    <p>
                        <b class="fs-10pt">REFACTURADO EN EL DTE:</b> <br>
                        <span class="fs-10pt">0-65515615asd-54456465</span>
                    </p>
                    <p>
                        <img src="https://borealtech.com/wp-content/uploads/2018/10/codigo-qr-1024x1024-1.jpg" alt="Codigo QR" class="imgQR">
                    </p>
                </div>
            </header>
            
            <main>
                {{--Sistema de grillas--}}
                {{-- <div class="row">
                    <div style="background: rgba(255, 173, 173, 0.566);" class="col-1"><p class="text-center">col-1</p></div>
                    <div style="background: rgba(173, 255, 174, 0.566);" class="col-1"><p class="text-center">col-1</p></div>
                    <div style="background: rgba(173, 177, 255, 0.566);" class="col-1"><p class="text-center">col-1</p></div>
                    <div style="background: rgba(255, 255, 173, 0.566);" class="col-1"><p class="text-center">col-1</p></div>
                    <div style="background: rgba(255, 173, 244, 0.566);" class="col-1"><p class="text-center">col-1</p></div>
                    <div style="background: rgba(173, 255, 233, 0.566);" class="col-1"><p class="text-center">col-1</p></div>
                    <div style="background: rgba(255, 173, 244, 0.566);" class="col-1"><p class="text-center">col-1</p></div>
                    <div style="background: rgba(173, 255, 233, 0.566);" class="col-1"><p class="text-center">col-1</p></div>
                    <div style="background: rgba(255, 255, 173, 0.566);" class="col-1"><p class="text-center">col-1</p></div>
                    <div style="background: rgba(173, 177, 255, 0.566);" class="col-1"><p class="text-center">col-1</p></div>
                    <div style="background: rgba(173, 255, 174, 0.566);" class="col-1"><p class="text-center">col-1</p></div>
                    <div style="background: rgba(255, 173, 173, 0.566);" class="col-1"><p class="text-center">col-1</p></div>
                </div> --}}
                
                {{-- <div class="row">
                    <div style="background: rgba(255, 173, 173, 0.566);" class="col-2"><p class="text-center">col-2</p></div>
                    <div style="background: rgba(173, 255, 174, 0.566);" class="col-2"><p class="text-center">col-2</p></div>
                    <div style="background: rgba(173, 177, 255, 0.566);" class="col-2"><p class="text-center">col-2</p></div>
                    <div style="background: rgba(255, 255, 173, 0.566);" class="col-2"><p class="text-center">col-2</p></div>
                    <div style="background: rgba(255, 173, 244, 0.566);" class="col-2"><p class="text-center">col-2</p></div>
                    <div style="background: rgba(173, 255, 233, 0.566);" class="col-2"><p class="text-center">col-2</p></div>
                </div> --}}

                {{-- <div class="row">
                    <div style="background: rgba(255, 173, 173, 0.566);" class="col-3"><p class="text-center">col-3</p></div>
                    <div style="background: rgba(173, 255, 174, 0.566);" class="col-3"><p class="text-center">col-3</p></div>
                    <div style="background: rgba(173, 177, 255, 0.566);" class="col-3"><p class="text-center">col-3</p></div>
                    <div style="background: rgba(255, 255, 173, 0.566);" class="col-3"><p class="text-center">col-3</p></div>
                </div> --}}

                {{-- <div class="row">
                    <div style="background: rgba(255, 173, 173, 0.566);" class="col-4"><p class="text-center">col-4</p></div>
                    <div style="background: rgba(173, 255, 174, 0.566);" class="col-4"><p class="text-center">col-4</p></div>
                    <div style="background: rgba(173, 177, 255, 0.566);" class="col-4"><p class="text-center">col-4</p></div>
                </div> --}}

                {{-- <div class="row">
                    <div style="background: rgba(255, 173, 173, 0.566);" class="col-6"><p class="text-center">col-6</p></div>
                    <div style="background: rgba(173, 255, 174, 0.566);" class="col-6"><p class="text-center">col-6</p></div>
                </div> --}}

                {{-- <div class="row">
                    <div style="background: rgba(255, 173, 173, 0.566);" class="col-12"><p class="text-center">col-12</p></div>
                </div> --}}
                
                {{--@yield('content')--}}{{--Contenido dinamico--}}
                <table>
                    <thead>
                        <tr>
                            <th scope="col" class="borde-b-show w-05">#</th>
                            <th scope="col" class="borde-b-show w-05">CANT.</th>
                            <th scope="col" class="borde-b-show w-1">MEDIDA</th>
                            <th scope="col" class="borde-b-show">DESCRIPCIÓN</th>
                            <th scope="col" class="borde-b-show w-2">PRECIO UNITARIO</th>
                            <th scope="col" class="borde-b-show w-2">VENTAS NO GRAVADAS</th>
                            <th scope="col" class="borde-b-show" style="width: 3.5cm;">VENTAS EXENTAS</th>
                            <th scope="col" class="borde-b-show w-3">VENTAS GRAVADAS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $key => $d)
                            <tr>
                                <td class="text-center bbl-radius" scope="row">{{ $d['id'] }}</td>
                                <td class="text-center">1</td>
                                <td class="text-center">OTROS</td>
                                <td>DESCRIPCIÓN DE PRUEBA</td>
                                <td class="text-right">$000,000.00</td>
                                <td class="text-right">$000,000.00</td>
                                <td class="text-right">$000,000.00</td>
                                <td class="text-right">$000,000.00</td>
                            </tr>
                        @endforeach
                        {{--Campos de factura--}}
                        <tr>
                            <th colspan="4" class="borde-t-show"></th>
                            <th class="text-right estilo-campos-factura borde-t-show bbl-radius">SUMAS</th>
                            <th class="text-right estilo-campos-factura borde-t-show">$000,000.00</th>
                            <th class="text-right estilo-campos-factura borde-t-show">$000,000.00</th>
                            <th class="text-right estilo-campos-factura borde-t-show">$000,000.00</th>
                        </tr>
                        <tr>
                            <td colspan="6" rowspan="8" class="borde-l-none borde-r-none">
                                <p><b>VALOR EN LETRAS:</b> SETECIENTOS VEINTICUATRO DOLARES (USD) CON CINCUENTA CENTAVOS</p>
                                <p><b>CONDICIÓN DE LA OPERACIÓN:</b> CONTADO</p>
                                <p>CONSULTA DE COMPROBANTE ELECTRÓNICO</p>
                                <p>
                                    <img src="https://borealtech.com/wp-content/uploads/2018/10/codigo-qr-1024x1024-1.jpg" alt="Codigo QR" class="imgQR">
                                </p>
                            </td>
                            <th class="text-right estilo-campos-factura">(+) 5% TURISMO</th>
                            <th class="text-right estilo-campos-factura">$000,000.00</th>
                        </tr>
                        <tr>
                            <th class="text-right estilo-campos-factura">SUB-TOTAL</th>
                            <th class="text-right estilo-campos-factura">$000,000.00</th>
                        </tr>
                        <tr>
                            <th class="text-right estilo-campos-factura">VENTAS EXENTAS</th>
                            <th class="text-right estilo-campos-factura">$000,000.00</th>
                        </tr>
                        <tr>
                            <th class="text-right estilo-campos-factura">VENTAS NO GRAVADAS</th>
                            <th class="text-right estilo-campos-factura">$000,000.00</th>
                        </tr>
                        <tr>
                            <th class="text-right estilo-campos-factura">VENTAS NO SUJETAS</th>
                            <th class="text-right estilo-campos-factura">$000,000.00</th>
                        </tr>
                        <tr>
                            <th class="text-right estilo-campos-factura">(-) 1% IVA</th>
                            <th class="text-right estilo-campos-factura">$000,000.00</th>
                        </tr>
                        <tr>
                            <th class="text-right estilo-campos-factura fs-10pt">TOTAL</th>
                            <th style="background: #f2f2f2; padding: 2.98px;" class="text-right fs-10pt">$000,000.00</th>
                        </tr>
                        <tr>
                            <th class="text-right estilo-campos-factura borde-t-show bbl-radius">(I) 5% AD-VALOREM</th>
                            <th class="text-right estilo-campos-factura borde-t-show bbr-radius">$000,000.00</th>
                        </tr>
                        {{--Extension--}}
                        <tr>
                            <td colspan="6" class="borde-l-none">
                                <b>EXTENSIÓN</b>
                                <p>
                                    <b>ENTREGA:</b><br>
                                    NOMBRE: Juan Perez<br>
                                    DOCUMENTO: 00000000
                                </p>
                                <p>
                                    <b>RECIBE:</b><br>
                                    NOMBRE: Bartolomeo Estanislao<br>
                                    DOCUMENTO: 00000000
                                </p>
                                <p>
                                    <b>OBSERVACIONES: </b>
                                    ---
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </main>

            {{--<div class="page-break"></div>--}}{{--Se agrega un salto de pagina--}}

            {{--Este script de PHP se ejecuta por cada pagina que contenga el documento y queda afuera de todo--}}
            <script type="text/php">
                if(isset($pdf)){
                    $pdf->page_script('
                        #Fuente y tamaño elegidos
                            $font = $fontMetrics->get_font("sans-serif", "normal");
                            $size = 9;
                        #Coordernadas para colocar el texto en el pie de pagina
                            if({{ $orientacionPagina }} === 2){#Landscape
                                $horizontal = 270;#Entre mas alto el numero, el texto se desplaza hacia la derecha
                                $vertical   = 595;#Entre mas alto el numero, el texto se desplaza hacia abajo
                            }
                            else{#Portrait
                                $horizontal = 230;
                                $vertical   = 775;
                            }
                        #Impresion del texto
                            $pdf->text($horizontal,$vertical,"{{ date('Y-m-d H:i:s') }} | Página $PAGE_NUM de $PAGE_COUNT",$font,$size);
                    ');
                }
            </script>
        </body>
    </html>
{{--@else
    NO HAY DATOS PARA MOSTRAR!!!.
@endif--}}
