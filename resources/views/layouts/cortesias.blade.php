<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    {{-- <link rel="stylesheet" href="styles.css"> --}}

    <style>
        /*Estilos para la grilla de 12 columnas*/
        @page {
            margin-top: 105px;
            margin: 0.5cm;
            overflow: hidden;
            box-sizing: border-box;
            font-family: "Lucida Sans", sans-serif;
        }
        header {
            position: fixed;
            margin-top: 0.1cm;
            left: 0px;
            right: 0px;
            height: 60px;
            line-height: 35px;
            color: rgb(88, 88, 88);
        }
        .logo {
            width: 85px;
            height: 65px;
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
        .page-number:after {
            content: "Página " counter(page);
        }
        /*
            27.94cm es el tamaño de la pagina, pero se le restan 2cm por los margenes... 
            Entonces, la longitud de una fila seria: 25.94cm
        */
        /*Definicion de tamaño de cada columna*/
        .col-1  { width: 2.1616cm;  }/*25.94cm / 12 col = 2.1616cm*/
        .col-2  { width: 4.3232cm;  }/*2.1616 * 2  = 4.3232cm*/
        .col-3  { width: 6.4848cm;  }/*2.1616 * 3  = 6.4848cm*/
        .col-4  { width: 8.6464cm;  }/*2.1616 * 4  = 8.6464cm*/
        .col-5  { width: 10.808cm;  }/*2.1616 * 5  = 10.808cm*/
        .col-6  { width: 12.9696cm; }/*2.1616 * 6  = 12.9696cm*/
        .col-7  { width: 15.1312cm; }/*2.1616 * 7  = 15.1312cm*/
        .col-8  { width: 17.2928cm; }/*2.1616 * 8  = 17.2928cm*/
        .col-9  { width: 19.4544cm; }/*2.1616 * 9  = 19.4544cm*/
        .col-10 { width: 21.616cm;  }/*2.1616 * 10 = 21.616cm*/
        .col-11 { width: 23.7776cm; }/*2.1616 * 11 = 23.7776cm*/
        .col-12 { width: 25.9392cm; }/*2.1616 * 12 = 25.9392cm*/

        [class*="col-"] {
            float: left;
            padding: 5px;
            /*border: 1px solid #ccc;*/
        }
        .row {
            margin-bottom: 25px;
            clear: both;
            display: table;
            width: 25.94cm;
        }

        /*La clase .my-sec se agregó para hacer la distincion de colores de las filas*/
        .my-sec:nth-child(even) {
            background-color: #f3f3f3;/*Color de fondo para filas pares*/
            height: 0.55cm;
            padding: 1px;
        }
        .my-sec:nth-child(odd) {
            background-color: #ffffff9f;/*Color de fondo para filas impares*/
            height: 0.55cm;
            padding: 1px;
        }

        /*Propiedades adicionales*/
        .txt-left {
            text-align: left;
        }
        .txt-center {
            text-align: center;
        }
        .txt-right {
            text-align: right;
        }
        .fs-11 {
            font-size: 11px;
        }
        .mb-08 {
            margin-bottom: 0.8cm;
        }
        .mb-1 {
            margin-bottom: 1cm;
        }
        .p-10 {
            padding: 10px;
        }

        /*Div con bordes redondeados*/
        .bordes-redondos{
            width: 27.94cm;
            height: auto;
            border: 1px solid #ccc;
            border-radius: 0.45cm;
        }

        /*Color de fondo de cada encabezado*/
        .bg-header {
            background-color: #b6b6b6;
        }
    </style>
</head>
<body>
    <!--Encabezado-->
    <header class="row" style="margin-bottom: 2.5cm;">
        <div class="col-4 text-left">
            <img src="{{ $logo }}" alt="Logo de empresa" class="logo">
        </div>
        <div class="col-4 txt-center" style="height: 1.71cm; line-height: 1.3cm;">
            <b>REPORTE DE CORTESIAS</b>
        </div>
        <div class="col-4 txt-right" style="height: 1.71cm; line-height: 1.3cm;">
            TURÍSTICAS DE ORIENTE S.A DE C.V
        </div>
    </header>

    <!--Descripcion-->
    <div class="row fs-11">
        <div class="col-6"><b>FECHA:</b> 2024-01-01 - 2024-02-21</div>
        <div class="col-6"><b>TIPO DE CORTESIAS:</b> TODOS LOS TIPOS DE CORTESIAS</div>
    </div>

    <!--Borde redondeado-->
    <div class="bordes-redondos p-10 mb-1">
        <!--Encabezados de columnas-->
        <div class="row fs-11">
            <div class="col-2 bg-header" style="border-top-left-radius: 8px;"><b>CUENTA</b></div>
            <div class="col-2 bg-header"><b>FECHA</b></div>
            <div class="col-3 bg-header txt-left"><b>PRODUCTO</b></div>
            <div class="col-1 bg-header txt-center"><b>CANTIDAD</b></div>
            <div class="col-1 bg-header txt-center"><b>PRECIO</b></div>
            <div class="col-1 bg-header txt-center"><b>P.FAC.</b></div>
            <div class="col-1 bg-header txt-center"><b>TOTAL</b></div>
            <div class="col-1 bg-header txt-center" style=" border-top-right-radius: 8px;"><b>USUARIO</b></div>
        </div>

        <!--Contenido dinamico a ser recorrido por un foreach-->
        <!--Canje-->
        <div class="row fs-11 txt-center"><div class="col-12"><b>CANJE</b></div></div>
        <div class="row fs-11">
            <div class="col-6"><b>VALES DE REGALO</b></div>
            <div class="col-6 txt-right"><b>$ 1,000.00 /MES</b></div>
        </div>

        <div class="my-sec">
            <div class="row fs-11">
                <div class="col-2">ORDEN Nº 16</div>
                <div class="col-2">24-01-2024 01:44:14</div>
                <div class="col-3 txt-left">CENA NAVIDEÑA</div>
                <div class="col-1 txt-center">2</div>
                <div class="col-1 txt-right">$ 25</div>
                <div class="col-1 txt-right">$ 25</div>
                <div class="col-1 txt-right">$ 75.00</div>
                <div class="col-1 txt-center"></div>
            </div>
        </div>
        <div class="my-sec">
            <div class="row fs-11">
                <div class="col-2">ESTADÍA Nº 36</div>
                <div class="col-2">2024-02-08 - 2024-02-10</div>
                <div class="col-3 txt-left">TARIFA 1 DOBLE</div>
                <div class="col-1 txt-center">2 DIA(S)</div>
                <div class="col-1 txt-right">$ 85.18</div>
                <div class="col-1 txt-right">$ 80.00</div>
                <div class="col-1 txt-right">$ 160.00</div>
                <div class="col-1 txt-center">ADMIN</div>
            </div>
        </div>
        <div class="my-sec">
            <div class="row fs-11">
                <div class="col-2">ORDEN Nº 16</div>
                <div class="col-2">24-01-2024 01:44:14</div>
                <div class="col-3 txt-left">CENA NAVIDEÑA</div>
                <div class="col-1 txt-center">20</div>
                <div class="col-1 txt-right">$ 25</div>
                <div class="col-1 txt-right">$ 25</div>
                <div class="col-1 txt-right">$ 100.00</div>
                <div class="col-1 txt-center"></div>
            </div>
        </div>
        <div class="my-sec">
            <div class="row fs-11">
                <div class="col-2">ESTADÍA Nº 36</div>
                <div class="col-2">2024-02-08 - 2024-02-10</div>
                <div class="col-3 txt-left">TARIFA 1 DOBLE</div>
                <div class="col-1 txt-center">2 DIA(S)</div>
                <div class="col-1 txt-right">$ 85.18</div>
                <div class="col-1 txt-right">$ 80.00</div>
                <div class="col-1 txt-right">$ 160.00</div>
                <div class="col-1 txt-center">ADMIN</div>
            </div>
        </div>
        <div class="row fs-11">
            <div class="col-6"><b>TOTAL VALES DE REGALO</b></div>
            <div class="col-6 txt-right"><b>$ 1,235.00</b></div>
        </div>
        <div class="row fs-11">
            <div class="col-6"><b>BEBIDA DE BIENVENIDA BLACK AND WHITE</b></div>
            <div class="col-6 txt-right"><b>$ 300</b></div>
        </div>
        <div class="my-sec">
            <div class="row fs-11">
                <div class="col-2">COMANDA Nº 68</div>
                <div class="col-2">24-01-2024 01:44:14</div>
                <div class="col-3 txt-left">DESAYUNO MIGUELEÑO</div>
                <div class="col-1 txt-center">6</div>
                <div class="col-1 txt-right">$ 5.25</div>
                <div class="col-1 txt-right">$ 5.25</div>
                <div class="col-1 txt-right">$ 31.50</div>
                <div class="col-1 txt-center">ADMIN</div>
            </div>
        </div>
        <div class="my-sec">
            <div class="row fs-11">
                <div class="col-2">COMANDA Nº 68</div>
                <div class="col-2">24-01-2024 01:44:14</div>
                <div class="col-3 txt-left">DESAYUNO MIGUELEÑO</div>
                <div class="col-1 txt-center">6</div>
                <div class="col-1 txt-right">$ 5.25</div>
                <div class="col-1 txt-right">$ 5.25</div>
                <div class="col-1 txt-right">$ 31.50</div>
                <div class="col-1 txt-center">ADMIN</div>
            </div>
        </div>
        <div class="my-sec">
            <div class="row fs-11">
                <div class="col-2">COMANDA Nº 68</div>
                <div class="col-2">24-01-2024 01:44:14</div>
                <div class="col-3 txt-left">DESAYUNO MIGUELEÑO</div>
                <div class="col-1 txt-center">6</div>
                <div class="col-1 txt-right">$ 5.25</div>
                <div class="col-1 txt-right">$ 5.25</div>
                <div class="col-1 txt-right">$ 31.50</div>
                <div class="col-1 txt-center">ADMIN</div>
            </div>
        </div>
        <div class="row fs-11">
            <div class="col-6"><b>TOTAL BEBIDA DE BIENVENIDA BLACK AND WHITE</b></div>
            <div class="col-6 txt-right"><b>$ 1,235.00</b></div>
        </div>
        <div class="row fs-11">
            <div class="col-6"><b>TOTAL CANJE</b></div>
            <div class="col-6 txt-right"><b>$ 300</b></div>
        </div>



        <!--Cortesia de empleados-->
        <div class="row fs-11 txt-center"><div class="col-12"><b>CORTESIA EMPLEADOS</b></div></div>
        <div class="row fs-11">
            <div class="col-6"><b>ALVARO CABALLERO</b></div>
            <div class="col-6 txt-right"><b>$ 1,000.00 /MES</b></div>
        </div>

        <div class="my-sec">
            <div class="row fs-11">
                <div class="col-2">ORDEN Nº 16</div>
                <div class="col-2">24-01-2024 01:44:14</div>
                <div class="col-3 txt-left">CENA NAVIDEÑA</div>
                <div class="col-1 txt-center">2</div>
                <div class="col-1 txt-right">$ 25</div>
                <div class="col-1 txt-right">$ 25</div>
                <div class="col-1 txt-right">$ 75.00</div>
                <div class="col-1 txt-center"></div>
            </div>
        </div>
        <div class="my-sec">
            <div class="row fs-11">
                <div class="col-2">ESTADÍA Nº 36</div>
                <div class="col-2">2024-02-08 - 2024-02-10</div>
                <div class="col-3 txt-left">TARIFA 1 DOBLE</div>
                <div class="col-1 txt-center">2 DIA(S)</div>
                <div class="col-1 txt-right">$ 85.18</div>
                <div class="col-1 txt-right">$ 80.00</div>
                <div class="col-1 txt-right">$ 160.00</div>
                <div class="col-1 txt-center">ADMIN</div>
            </div>
        </div>
        <div class="my-sec">
            <div class="row fs-11">
                <div class="col-2">ORDEN Nº 16</div>
                <div class="col-2">24-01-2024 01:44:14</div>
                <div class="col-3 txt-left">CENA NAVIDEÑA</div>
                <div class="col-1 txt-center">20</div>
                <div class="col-1 txt-right">$ 25</div>
                <div class="col-1 txt-right">$ 25</div>
                <div class="col-1 txt-right">$ 100.00</div>
                <div class="col-1 txt-center"></div>
            </div>
        </div>
        <div class="my-sec">
            <div class="row fs-11">
                <div class="col-2">ESTADÍA Nº 36</div>
                <div class="col-2">2024-02-08 - 2024-02-10</div>
                <div class="col-3 txt-left">TARIFA 1 DOBLE</div>
                <div class="col-1 txt-center">2 DIA(S)</div>
                <div class="col-1 txt-right">$ 85.18</div>
                <div class="col-1 txt-right">$ 80.00</div>
                <div class="col-1 txt-right">$ 160.00</div>
                <div class="col-1 txt-center">ADMIN</div>
            </div>
        </div>
        <div class="row fs-11">
            <div class="col-6"><b>TOTAL ALVARO CABALLERO</b></div>
            <div class="col-6 txt-right"><b>$ 1,235.00</b></div>
        </div>
        <div class="row fs-11">
            <div class="col-6"><b>TOTAL CORTESIA EMPLEADOS</b></div>
            <div class="col-6 txt-right"><b>$ 300</b></div>
        </div>
        <div class="row fs-11">
            <div class="col-12 txt-center">PAQUETES</div>
        </div>
    </div><!--Fin borde redondeado-->



    <footer>
        <small style="float: right;">{{ date('Y-m-d H:i:s') }}</small>
        <div class="page-number"></div>
    </footer>

    <!--Salto de pagina-->
    <div style="page-break-before: always;"></div>



    <!--Borde redondeado-->
    <div class="bordes-redondos p-10" style="margin-top: 85px;">
        <!--Encabezados de columnas-->
        <div class="row fs-11">
            <div class="col-2 bg-header" style="border-top-left-radius: 8px;"><b>CUENTA</b></div>
            <div class="col-2 bg-header"><b>FECHA</b></div>
            <div class="col-3 bg-header txt-left"><b>PRODUCTO</b></div>
            <div class="col-1 bg-header txt-center"><b>CANTIDAD</b></div>
            <div class="col-1 bg-header txt-center"><b>PRECIO</b></div>
            <div class="col-1 bg-header txt-center"><b>P.FAC.</b></div>
            <div class="col-1 bg-header txt-center"><b>TOTAL</b></div>
            <div class="col-1 bg-header txt-center" style=" border-top-right-radius: 8px;"><b>USUARIO</b></div>
        </div>

        <!--Contenido dinamico a ser recorrido por un foreach-->
        <!--Canje-->
        <div class="row fs-11">
            <div class="col-6"><b>DESAYUNO BUFFET HABITACIONES</b></div>
            <div class="col-6 txt-right"><b>$ 1,000.00 /MES</b></div>
        </div>

        <div class="my-sec">
            <div class="row fs-11">
                <div class="col-2">ORDEN Nº 16</div>
                <div class="col-2">24-01-2024 01:44:14</div>
                <div class="col-3 txt-left">CENA NAVIDEÑA</div>
                <div class="col-1 txt-center">2</div>
                <div class="col-1 txt-right">$ 25</div>
                <div class="col-1 txt-right">$ 25</div>
                <div class="col-1 txt-right">$ 75.00</div>
                <div class="col-1 txt-center"></div>
            </div>
        </div>
        <div class="my-sec">
            <div class="row fs-11">
                <div class="col-2">ESTADÍA Nº 36</div>
                <div class="col-2">2024-02-08 - 2024-02-10</div>
                <div class="col-3 txt-left">TARIFA 1 DOBLE</div>
                <div class="col-1 txt-center">2 DIA(S)</div>
                <div class="col-1 txt-right">$ 85.18</div>
                <div class="col-1 txt-right">$ 80.00</div>
                <div class="col-1 txt-right">$ 160.00</div>
                <div class="col-1 txt-center">ADMIN</div>
            </div>
        </div>
        <div class="my-sec">
            <div class="row fs-11">
                <div class="col-2">ORDEN Nº 16</div>
                <div class="col-2">24-01-2024 01:44:14</div>
                <div class="col-3 txt-left">CENA NAVIDEÑA</div>
                <div class="col-1 txt-center">20</div>
                <div class="col-1 txt-right">$ 25</div>
                <div class="col-1 txt-right">$ 25</div>
                <div class="col-1 txt-right">$ 100.00</div>
                <div class="col-1 txt-center"></div>
            </div>
        </div>
        <div class="my-sec">
            <div class="row fs-11">
                <div class="col-2">ESTADÍA Nº 36</div>
                <div class="col-2">2024-02-08 - 2024-02-10</div>
                <div class="col-3 txt-left">TARIFA 1 DOBLE</div>
                <div class="col-1 txt-center">2 DIA(S)</div>
                <div class="col-1 txt-right">$ 85.18</div>
                <div class="col-1 txt-right">$ 80.00</div>
                <div class="col-1 txt-right">$ 160.00</div>
                <div class="col-1 txt-center">ADMIN</div>
            </div>
        </div>
        <div class="row fs-11">
            <div class="col-6"><b>TOTAL DESAYUNO BUFFET HABITACIONES</b></div>
            <div class="col-6 txt-right"><b>$ 1,235.00</b></div>
        </div>
        <div class="row fs-11">
            <div class="col-6"><b>TOTAL PAQUETES</b></div>
            <div class="col-6 txt-right"><b>$ 300</b></div>
        </div>
        <div class="row fs-11">
            <div class="col-6"><b>TOTAL 2024-01-01 - 2024-02-21</b></div>
            <div class="col-6 txt-right"><b>$ 1,235.00</b></div>
        </div>
    </div><!--Fin borde redondeado-->

</body>
</html>