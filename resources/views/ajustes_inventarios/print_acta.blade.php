<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>{{ config('app.name', 'Reporte de acta') }}</title>

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
            $altoDisponible  = $alto  - ($marginTop  + $marginBottom) - 0.10;#27.94 - (5.08) = 22.86cm

            #Sistema de grillas para las filas y columnas: dinamico
            $anchoDeUnaColumna = $anchoDisponible / 12;#15.59cm / 12cols = 1.299166667cm cada columna tendra un ancho dinamico
            #-----

            $valorPixel = 0.23;/*1px = 0.0264583cm*/

            #---Fecha---
            $fecha = Carbon::now();

            #Formatea la fecha en el formato deseado
            $fechaFormateada = $fecha->locale('es')->isoFormat('D [de] MMMM [de] YYYY');#30 de agosto de 2024
            #-----------
        @endphp

        <style>
            @page {
                /*width: 8.5in;
                height: 11in;
                margin: 22px;*/
                margin: {{ $marginTop }}cm {{ $marginRight }}cm {{ $marginBottom }}cm {{ $marginLeft }}cm;
            }

            * {
                font-family: sans-serif;
                color: #546E7A;
                letter-spacing: 0.5px;
                font-size: 11pt;
            }

            /*Definicion de tamaño dinamico de cada columna*/
            .col-1  { width: {{ ($anchoDeUnaColumna )}}cm; }/*15.59cm / 12 cols = 1.299166667cm*/
            .col-2  { width: {{ ($anchoDeUnaColumna * 2)  }}cm; }/*1.299166667 * 2  = 2.598333334cm*/
            .col-3  { width: {{ ($anchoDeUnaColumna * 3)  }}cm; }/*1.299166667 * 3  = 3.897500001cm*/
            .col-4  { width: {{ ($anchoDeUnaColumna * 4)  }}cm; }/*1.299166667 * 4  = 5.196666668cm*/
            .col-5  { width: {{ ($anchoDeUnaColumna * 5)  }}cm; }/*1.299166667 * 5  = 6.495833335cm*/
            .col-6  { width: {{ ($anchoDeUnaColumna * 6)  }}cm; }/*1.299166667 * 6  = 7.795000002cm*/
            .col-7  { width: {{ ($anchoDeUnaColumna * 7)  }}cm; }/*1.299166667 * 7  = 9.094166669cm*/
            .col-8  { width: {{ ($anchoDeUnaColumna * 8)  }}cm; }/*1.299166667 * 8  = 10.393333336cm*/
            .col-9  { width: {{ ($anchoDeUnaColumna * 9)  }}cm; }/*1.299166667 * 9  = 11.692500003cm*/
            .col-10 { width: {{ ($anchoDeUnaColumna * 10) }}cm; }/*1.299166667 * 10 = 12.99166667cm*/
            .col-11 { width: {{ ($anchoDeUnaColumna * 11) }}cm; }/*1.299166667 * 11 = 14.290833337cm*/
            .col-12 { width: {{ ($anchoDeUnaColumna * 12) }}cm; }/*1.299166667 * 12 = 15.590000004cm*/

            [class*="col-"] {
                float: left;
                margin-bottom: -0.03cm;
            }
            .row { clear: both; }

            /*Salto de pagina*/
            .page-break { page-break-after: always; }

            /*Estilos para las imagenes*/
            .imgLogo {
                width: 2.8cm;
                height: 1.82cm;
            }

            /*Alineacion de texto*/
            .text-center { text-align: center; }
            .text-right { text-align: right; }
            .text-left { text-align: left; }
            .text-uppercase { text-transform: uppercase; }

            /*Margenes*/
            .mt-1 { margin-top: 1cm; }
            .ml-8 { margin-left: 8px; }
        </style>
    </head>
    <body>
        @isset($ajusteInv)
            <div style="padding: 30px;">
                <!--Fila 1: Logo y titulo-->
                <div class="row">
                    <div class="col-6 text-center">
                        <p style="margin-left: -1.5cm;" class="text-uppercase"><b>Turisticas de Oriente S.A. de C.V.</b></p>
                    </div>

                    <div class="col-4 text-right">
                        <p style="margin-top: -12px; margin-right: -60px;"><img src="{{ env('logo') }}" class="logo" alt="Logo {{ env('empresa') }}" class="imgLogo"></p>
                    </div>
                </div>

                <p style="margin-top: 2.3cm;" class="ml-8"><b>ACTA #{{ $ajusteInv->id }}</b></p>
                <p class="ml-8">{{ $fechaFormateada }}</p>
                <p class="ml-8">En sucursal</p>
                
                @foreach ($ajusteExi as $ae)
                    <p style="line-height: 0.6cm;" class="ml-8">* Se realiza el <b>{{ $ae->accion === 1 ? 'AUMENTO' : 'DESCARTE' }}</b> del producto/insumo 
                        <b class="text-uppercase">{{ $ae->existencias->productosExistencias->nombre }}</b> 
                        perteneciente a <b class="text-uppercase">{{ $ae->existencias->bodegas->bodega }}</b> 
                        ingresada en el <b>LOTE #{{ $ae->existencias_id }}</b>, {{ $ae->accion === 1 ? 'aumentando' : 'descartando' }} <b>{{ number_format($ae->cantidad, 2) }}</b> unidades.
                    </p>
                @endforeach

                <p class="ml-8">Razón del descarte:</p>
                <p style="line-height: 0.8cm;" class="ml-8 text-uppercase">{{ $ajusteInv->observacion }}</p>

                <p style="margin-top: 0.8cm;" class="ml-8">Realizando el cambio para mantener el inventario acorde a lo físico, firmamos todos.</p>

                <p class="mt-1 ml-8">F. ____________________</p>
                <p class="ml-8 text-uppercase">{{ $ajusteInv->userSolicitante->name }}</p>
                <p class="ml-8">SOLICITANTE</p>

                <p class="mt-1 ml-8">F. ____________________</p>
                <p class="ml-8 text-uppercase">{{ $ajusteInv->userRealiza->name }}</p>
                <p class="ml-8">REALIZA</p>

                <p class="mt-1 ml-8">F. ____________________</p>
                <p class="ml-8 text-uppercase">{{ $ajusteInv->userAutoriza->name }}</p>
                <p class="ml-8">AUTORIZA</p>
            </div>

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
        @endisset
    </body>
</html>