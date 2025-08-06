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
            21.6cm es el tamaño de la pagina, pero se le restan 2cm por los margenes... 
            Entonces, la longitud de una fila seria: 19.6cm
        */
        /*Definicion de tamaño de cada columna*/
        .col-1  { width: 1.35cm;  }/*19.6cm / 12 col = 1.6333cm*/
        .col-2  { width: 3.2666cm;  }/*1.6333 * 2  = 3.2666cm*/
        .col-3  { width: 4.8999cm;  }/*1.6333 * 3  = 4.8999cm*/
        .col-4  { width: 6.5332cm;  }/*1.6333 * 4  = 6.5332cm*/
        .col-5  { width: 8.1665cm;  }/*1.6333 * 5  = 8.1665cm*/
        .col-6  { width: 9.7998cm;  }/*1.6333 * 6  = 9.7998cm*/
        .col-7  { width: 11.4331cm; }/*1.6333 * 7  = 11.4331cm*/
        .col-8  { width: 13.0664cm; }/*1.6333 * 8  = 13.0664cm*/
        .col-9  { width: 14.6997cm; }/*1.6333 * 9  = 14.6997cm*/
        .col-10 { width: 16.333cm;  }/*1.6333 * 10 = 16.333cm*/
        .col-11 { width: 17.9663cm; }/*1.6333 * 11 = 17.9663cm*/
        .col-12 { width: 19.1996cm; }/*1.6333 * 12 = 19.5996cm*/

        [class*="col-"] {
            float: left;
            padding: 2px;
            /*border: 1px solid #ccc;*/
        }
        .row {
            margin-bottom: 21px;
            clear: both;
            display: table;
            width: 21.6cm;
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
        .mb-04{
            margin-bottom: 0.4cm;
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
        .pl-08 {
            padding-left: 0.8cm;
        }

        /*Div con bordes redondeados*/
        .bordes-redondos{
            width: 19.3cm;
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
        <div class="col-3 text-left">
            <img src="{{ $logo }}" alt="Logo de empresa" class="logo">
        </div>
        <div class="col-6 txt-center" style="height: 1.71cm; line-height: 1.3cm;">
            <b><small>TURÍSTICAS DE ORIENTE S.A DE C.V</small></b>
        </div>
        <div class="col-3 txt-center" style="height: 1.71cm; line-height: 2.3cm;">
            Nº 16
        </div>
    </header>

    <!--Borde redondeado-->
    <div class="bordes-redondos p-10 mb-04" style="margin-top: 2.5cm;">
        <div class="row fs-11"><div class="col-12"><b>CLIENTE:</b> HOTEL TRÓPICO INN</div></div>
        <div class="row fs-11"><div class="col-12"><b>DIRECCIÓN:</b> 7 AV. ROOSTVELT 3303, SAN MIGUEL</div></div>
        <div class="row fs-11">
            <div class="col-6"><b>MUNICIPIO:</b> SAN MIGUEL</div>
            <div class="col-6"><b>DEPARTAMENTO:</b> SAN MIGUEL</div>
        </div>
        <div class="row fs-11">
            <div class="col-6"><b>PAIS:</b> EL SALVADOR</div>
            <div class="col-6"><b>FORMA DE PAGO:</b> -- AUN SIN PAGOS ANTICIPADOS --</div>
        </div>
        <div class="row fs-11">
            <div class="col-6"><b>CONTACTO:</b> TELÉFONO: 7121-2121</div>
            <div class="col-6"><b>FORMA DE PAGO:</b> NCR: 2345455545</div>
        </div>
    </div><!--Fin borde redondeado-->

    <!--Borde redondeado-->
    <div class="bordes-redondos p-10 mb-04">
        <div class="row fs-11">
            <div class="col-12" style="color: #7e7e7e;">
                INFORMACIÓN DE HUESPEDES
            </div>
        </div>
        
        <div class="row fs-11">
            <div class="col-3"><b>HABITACIÓN</b></div>
            <div class="col-3"><b>NOMBRE</b></div>
            <div class="col-3"><b>NACIONALIDAD</b></div>
            <div class="col-3"><b>TELÉFONO</b></div>
        </div>
        <div style="border-top: solid #ccc 1px;">
            <div class="row">
                <!--code...-->
            </div>
        </div>
    </div><!--Fin borde redondeado-->

    <!--Borde redondeado-->
    <div class="bordes-redondos p-10 mb-04">
        <div class="row fs-11">
            <div class="col-12" style="color: #7e7e7e;">
                INFORMACIÓN DE RESERVACIONES
            </div>
        </div>
        
        <div class="row fs-11">
            <div class="col-2"><b>HABITACIÓN</b></div>
            <div class="col-3"><b>TIPO DE HABITACIÓN</b></div>
            <div class="col-3"><b>FECHAS DE RESERVACIÓN</b></div>
            <div class="col-1"><b>DIAS</b></div>
            <div class="col-2"><b>TARIFA</b></div>
            <div class="col-1"><b>TOTAL</b></div>
        </div>
        <div style="border-top: solid #ccc 1px;"></div>
        <div class="row fs-11">
            <div class="col-2">HABITACIÓN 118</div>
            <div class="col-3">ESTANDAR SENCILLA</div>
            <div class="col-3">07-02-2024 AL 09-02-2024</div>
            <div class="col-1">2 DIAS</div>
            <div class="col-2">$ 72.19</div>
            <div class="col-1">$ 144.37</div>
        </div>
        <div class="row fs-11">
            <div class="col-2">HABITACIÓN 118</div>
            <div class="col-3">ESTANDAR SENCILLA</div>
            <div class="col-3">07-02-2024 AL 09-02-2024</div>
            <div class="col-1">2 DIAS</div>
            <div class="col-2">$ 72.19</div>
            <div class="col-1">$ 144.37</div>
        </div>

        <div class="row fs-11">
            <div class="col-9">

            </div>
            <div class="col-2" style="border-top: solid #ccc 1px;">
                SUB-TOTAL
            </div>
            <div class="col-1" style="border-top: solid #ccc 1px;">
                $ 144.37
            </div>
        </div>
    </div><!--Fin borde redondeado-->

    <footer>
        <small style="float: right;">{{ date('Y-m-d H:i:s') }}</small>
        <div class="page-number"></div>
    </footer>

    <br><br>
    <div class="row fs-11 pl-08">
        <div class="col-6">
            <p>F.: ________________________________________________</p>
            <p>HOTEL TRÓPICO INN</p>
            <b>CLIENTE</b>
        </div>
        <div class="col-6">
            <p>F.: ________________________________________________</p>
            <p>ADMIN</p>
            <b>VENDEDOR</b>
        </div>
    </div>

</body>
</html>