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

        <style>
            @page {
                /*size: 8.5in 11in;*//*Tamaño carta vertical*/
                size: 11in 8.5in;/*Tamaño carta horizontal*/
                margin: 0 0 0.5cm 0;/*Márgenes propios de la página*/
            }

            * {
                font-size: 8pt;
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

            /*---Header y Footer---*/
            header, footer {
                position: fixed;
                right: 0;
                left: 0;
            }
            header {
                top: 0;
                margin: 1cm;/*El header tendrá un márgen de 0.3cm en todos las direcciones, debe de coincidir con el márgen izquierdo y derecho del body*/
            }
            footer {
                bottom: 0;
                text-align: center;
            }
            /*---------------------*/

            .logoEmpresa {
                width: 2.2cm;
                height: 1.5cm;
            }

            /*---Números de página---*/
            .page-number:before { content: "Página " counter(page); }
            /*.total-pages:before { content: " de " counter(pages); }*/
            /*.page-break { page-break-after: always; }*/
            /*-----------------------*/

            /*---Estilos generales---*/
            .text-center { text-align: center; }
            .text-right { text-align: right; }

            .fs-9pt { font-size: 9pt; }
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
            /*-----------------------*/

            /*---Tabla---*/
            table {
                border-collapse: collapse;
                width: 100%;
            }
            table thead th:first-child { border-top-left-radius: 10px; }/*Borde redondo esquina superior izquierda*/
            table thead th:last-child { border-top-right-radius: 10px; }/*Borde redondo esquina superior derecha*/
            thead { background: #dddddd80; }/*Color de fondo del thead*/
            thead tr th { padding: 3px; }/*Padding únicamente del thead de la tabla*/
            tr td {/*Padding únicamente para cada fila|registro|td de la tabla*/
                padding: 1px;
                /*border-bottom: 1px solid #dddddd80;*/
            }
            /*-----------*/
        </style>
    </head>
    <body style="margin: 3.7cm 1cm 0.8cm 1cm;">{{--Estos márgenes son los que se usan para colocar|mover la tabla--}}
        <header>
            {{--Fila 1 de información--}}
            <div class="row">
                <div class="col-2">
                    <img src="{{ env('logo') }}" alt="Logo {{ env('empresa') }}" alt="Logo" class="logoEmpresa">
                </div>
                <div class="col-8 text-center">
                    <b class="fs-10pt">Turisticas de Oriente S.A. de C.V.</b>
                    <p style="margin-top: 3px;">
                        <b>Requisición:</b> {{ $p->solicitud }}
                    </p>
                </div>
                <div class="col-2">
                    <b class="fs-10pt">Nº #{{ $p->id }}</b>
                    <br>
                    <b>Fecha: </b>{{ date('d-m-Y', strtotime($p->fecha)) }}
                    <br>
                    <b>Tiempo: </b>{{ $resolver }}
                </div>
            </div>

            {{--Fila 2 de información--}}
            <div class="row">
                <div class="col-2"></div>{{--Columna vacia para hacer espacio a la izquierda--}}

                <div class="col-5">
                    {{-- <p> --}}
                        <b>Bodega de entrada: </b>{{ $p->relacionBodegasEntrada->bodega }}
                        <br>
                        <b>Bodega de salida: </b>{{ $p->relacionBodegasSalida->bodega }}
                    {{-- </p> --}}
                </div>
                <div class="col-5">
                    {{-- <p> --}}
                        <b>Responsable que autoriza: </b>{{ optional($p->relacionUserAutorizacion)->name ?? 'Requisicion sin autorizar' }}
                        <br>
                        <b>Usuario que solicita: </b>{{ $p->relacionUserCreacion->name }}
                    {{-- </p> --}}
                </div>
            </div>
        </header>

        <footer>
            <div class="row page-number"> · {{ date('Y-m-d H:i:s') }}</div>
        </footer>

        <main>
            <table>
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Lote BE</th>
                        <th scope="col">Lote BS</th>
                        <th scope="col">Producto</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Precio costo</th>
                        <th scope="col">Total</th>
                        <th scope="col">Fecha vecimiento</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($d as $dr)
                        <tr>
                            <td class="text-center" scope="row">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $dr->id }}</td>
                            <td class="text-center">{{ $dr->lote_origen }}</td>
                            <td>{{ $dr->relacionProductos->nombre }}</td>
                            <td class="text-center">{{ $dr->cantidad }}</td>
                            <td class="text-right">${{ number_format($dr->relacionExistencias->precio_costo, 2) }}</td>
                            <td class="text-right">${{ number_format($dr->relacionExistencias->precio_costo * $dr->cantidad, 2) }}</td>
                            <td class="text-center">{{ $dr->relacionExistencias->vencimiento ?? 'No vence' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <br><br><br><br><br>{{--En lugar de usar style="margin-top: 50px !important;", se usan <br> ya que así permite mostrar más registros en las páginas--}}

            {{--Fila 3 de firmas--}}
            <div class="row text-center">
                <div class="col-6">
                    F. ______________________________
                    <br><br>
                    Entrega
                </div>
                <div class="col-6">
                    F. ______________________________
                    <br><br>
                    Recibe
                </div>
            </div>
        </main>
    </body>
</html>
