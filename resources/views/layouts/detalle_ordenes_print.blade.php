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
            .mb-15px { margin-bottom: 15px; }
            .mt-2 { margin-top: 2cm; }

            .mr-n06 { margin-right: -0.6cm; }
            .mr-02 { margin-right: 0.2cm; }
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
            thead, th {
                padding: 10px;
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

            .relacionado {
                border: solid 1px #ccc;
                border-radius: 12px;
                padding: 0 0.2cm;
            }
        </style>

        @yield('style'){{--Estilos personalizados por cada comprobante--}}
    </head>
    <body style="margin: 5.3cm 0.5cm {{ $orientacionPagina === 2 ? 0.5 : 0.4 }}cm 0.5cm;">{{--Margenes del contenido--}}

        <div class="line"></div>

        <header>
            <div class="row mr-03 ml-05">
                <div class="col-3">
                    <img src="{{ env('logo') }}" alt="Logo {{ env('empresa') }}" alt="Logo" class="logoEmpresa">
                </div>
                <div class="col-6 text-center">
                    <p>
                        <b class="fs-10pt">DETALLE DE ORDEN Nº #@yield('numero-orden')</b>
                        {{-- <p>ORDEN Nº #{{ $detalleOrdenes[0]->ordenes_id }}</p> --}}
                    </p>
                </div>
                <div class="col-3 text-right">
                    <p style="margin-bottom: -5px;"><b class="fs-10pt">@yield('fecha-orden')</b></p>
                    <p>F. Creación</p>
                </div>
            </div>

            @yield('cliente-orden')
        </header>

        <main>
            @yield('main-orden')
        </main>

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
