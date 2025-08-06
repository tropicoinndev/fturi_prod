<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Imprimir HTML</title>

        {{--Todo el ancho disponible del documento se divide entre 12 columnas,
            no importa si es vertical u horizontal, lo tomará en automático--}}
            @php($anchoDeUnaColumna = 100 / 12)

        <style>
            @page {
                /*size: 8.5in 11in;*//*Tamaño carta vertical*/
                size: 11in 8.5in;/*Tamaño carta horizontal*/
                /*1.- Margenes desde donde comenzará el recuadro, 2cm indica el limite inferior hasta donde llegaran de los registros*/
                /*margin: 0.3cm 0.3cm 2cm 0.3cm;*/
                /*margin: 2.8cm 0.3cm 2cm 0.3cm;*/
                /*margin: 0.3cm 0.3cm 1cm 0.3cm;*/
                margin: 0.3cm;
            }
            
            * {
                font-size: 8.3pt;
                font-family: sans-serif;
                color: #546E7A;
                letter-spacing: 0.2px;
            }

            /*---Definicion de tamaño dinamico de cada columna---*/
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
            .mt-15px { margin-top: 15px; }
            .text-uppercase { text-transform: uppercase; }
            /*-----------------------*/

            /*---Header y Footer---*/
            header, footer {/*3.- Contenedores fijos*/
                position: fixed;
                left: 0;/*Nos aseguramos que se extienda a lo ancho*/
                right: 0;/*Nos aseguramos que se extienda a lo ancho*/
            }
            header {
                top: 0;
                margin: 0.2cm;/*Margen de 0.2cm en todas las coordenadas*/
                /*margin-bottom: 25px;*/
            }
            footer {
                bottom: 0;
                margin: 0 0.2cm;/*Margen de 0.2cm izquierdo y derecho*/
            }
            /*---------------------*/

            .logoEmpresa {
                width: 2cm;
                height: 1.4cm;
            }

            /*Salto de pagina*/
            .page-break { page-break-after: always; }

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

            .line {/*2.- altura del recuadro*/
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                border: solid 1px #ccc;
                border-radius: 12px;
                width: 100%;
                /*Para saber a que altura quedara la linea inferior del recuadro segun la orientacion de la pagina*/
                height: {{ $orientacionPagina === 2 ? 99.5 : 104.7 }}%;
            }
        </style>
    </head>
    <body>
        <div class="line"></div>

        <header>
            <div class="row">
                <div class="col-1">
                    <img src="{{ asset(env('logo', '/images/logo.jpg')) }}" alt="Logo" class="logoEmpresa">
                </div>
                <div class="col-7">
                    <p class="text-right text-uppercase"><b class="fs-10pt">REPORTE DE ACTIVIDADES ECONOMICAS</b></p>
                </div>
                <div class="col-4">
                    <p class="text-right text-uppercase mt-15px">TROPICO INN S.A DE C.V</p>
                </div>
            </div>

            <div class="row">
                <div class="col-3"><p class="text-center">Columna 1</p></div>
                <div class="col-3"><p class="text-center">Columna 2</p></div>
                <div class="col-3"><p class="text-center">Columna 3</p></div>
                <div class="col-3"><p class="text-center">Columna 4</p></div>
            </div>
        </header>

        <footer>
            <div class="row">
                <div class="col-4">
                    <p class="text-center">Pie del documento</p>
                </div>
            </div>
        </footer>

        <main>
            <table>
                <thead>
                    <tr>
                        <th scope="col" class="table-border-left-rounded">#</th>
                        <th scope="col">Código</th>
                        <th scope="col">Actividad</th>
                        <th scope="col" class="w-5px">Estado</th>
                        <th scope="col" class="w-111px">Creación</th>
                        <th scope="col" class="w-111px table-border-right-rounded">Edición</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $key => $d)
                        <tr>
                            <th scope="row">{{ $key + 1 }}</th>
                            <td>{{ $d->codigo }}</td>
                            <td>{{ $d->actividad }}</td>
                            <td class="text-center">{{ $d->estado ? 'Activo' : 'Inactivo' }}</td>
                            <td>{{ $d->created_at }}</td>
                            <td>{{ $d->updated_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </main>

        <script>
            function printPage(){
                window.print();
            }
            printPage();
        </script>
    </body>
</html>
