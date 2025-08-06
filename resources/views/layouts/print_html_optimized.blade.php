<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Imprimir HTML</title>

        {{--Todo el ancho disponible de la página se divide entre 12 columnas,
            no importa si es vertical u horizontal, los cálculos se realizarán en automático--}}
            @php($anchoDeUnaColumna = 100 / 12)

        <style>
            @page {
                /*La página cambiará de tamaño según la orientación de la misma*/
                /*La variable $orientacionPagina viene desde el controlador*/
                size: {{ $orientacionPagina === 2 ? '11in 8.5in' : '8.5in 11in' }};

                /*La página tendrá un margen de 0.3cm en todas sus coordenadas: top, right, bottom, left*/
                /*Se deja ese margen ya que por defecto la impresora también le agrega sus propios margenes de impresión*/
                margin: 0.5cm;
            }

            * {
                font-size: 8.3pt;
                text-transform: uppercase;
                font-family: sans-serif;
                color: #546E7A;
                letter-spacing: 0.2px;
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
            .text-left { text-align: left; }
            .text-center { text-align: center; }
            .text-right { text-align: right; }
            .fs-10pt { font-size: 10pt; }
            .mt-12px { margin-top: 12px; }
            .text-uppercase { text-transform: uppercase; }
            .text-truncate {
                max-width: 200px; /* Ajusta el ancho máximo según tus necesidades */
                overflow: hidden;
                text-overflow: ellipsis; /* Muestra "..." al final si el texto es demasiado largo */
                white-space: nowrap; /* Evita que el texto se rompa en varias líneas */
            }
            /*-----------------------*/

            /*---Header y Footer---*/
            header, footer {/*3.- Contenedores fijos*/
                position: fixed;
                left: 0;/*Nos aseguramos que se extienda a todo lo ancho*/
                right: 0;/*Nos aseguramos que se extienda a todo lo ancho*/
            }
            header {
                top: 0;
                margin: 0.1cm;/*Margen de 0.1cm en todas las coordenadas, para dar un poco de espacio al logo y al titulo*/
            }
            footer {
                bottom: 0.3cm;
                margin: 0 0.1cm;/*Margen de 0.1cm izquierdo y derecho*/
            }

            main {
                /*El contenido dinámico de la tabla comenzará a patir de los 2.8cm del margen superior principal de la página*/
                /*Y finalizará 1cm antes del margen inferior*/
                margin: 2.8cm 0 1cm 0;
            }
            /*---------------------*/

            .logoEmpresa {
                width: 2cm;
                height: 1.4cm;
            }

            /*Contador de página*/
            /*.page-number:after {
                content: "Página " counter(page);
            }*/
            /*Salto de página*/
            .page-break { page-break-after: always; }

            .page-number {
                /*content: "Página " counter(page);*/
                position: relative;
                bottom: 0;
                left: 0;
                right: 0;
                text-align: center;
            }

            /*---Estilos para la tabla---*/
            table {
                border-collapse: collapse;
                width: 100%;
            }
            thead { background: #dddddd80; }
            th, td {
                padding: 2.98px;
                border-bottom: 1px solid #dddddd80;
            }
            .w-5px { width: 5px; }
            .w-111px { width: 111px; }
            .table-border-left-rounded { border-top-left-radius: 10px; }
            .table-border-right-rounded { border-top-right-radius: 10px; }
            /*---------------------------*/

            /*.line {*//*2.- altura del recuadro*/
                /*position: fixed;
                top: 0;
                left: 0;
                right: 0;
                border: solid 1px #ccc;
                border-radius: 12px;
                width: 100%;
                /*Para saber a que altura quedara la linea inferior del recuadro segun la orientacion de la pagina*/
                /*height: {{ $orientacionPagina === 2 ? 99.5 : 104.7 }}%;
            }*/
        </style>
    </head>
    <body>
        {{-- <div class="line"></div> --}}

        <header>
            <div class="row">
                <div class="col-1">
                    <img src="{{ asset(env('logo', '/images/logo.jpg')) }}" alt="Logo" class="logoEmpresa">
                </div>
                <div class="col-7">
                    <p class="text-right text-uppercase"><b class="fs-10pt">@yield('title_report')</b></p>
                </div>
                <div class="col-4">
                    <p class="text-right text-uppercase mt-12px">TURÍSTICAS DE ORIENTE S.A. DE C.V.</p>
                </div>
            </div>

            @yield('header')
        </header>

        {{-- <footer>
            <div class="row">
                <div class="col-4">
                    <p></p>
                </div>
                <div class="col-4 text-center">
                    <div class="page-number"><p></p><!--Página {{ $currentPage }} de {{ $totalPages }}--></div>
                </div>
                <div class="col-4 text-right">
                    {{ date('Y-m-d H:i:s') }}
                </div>
            </div>
        </footer> --}}

        <main>            
            @yield('content')
        </main>

        <script>
            function printPage(){
                window.print();
            }
            printPage();
        </script>
    </body>
</html>
