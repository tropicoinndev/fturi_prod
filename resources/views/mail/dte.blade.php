<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DTE - TURISTICAS DE ORIENTE S.A. DE C.V.</title>
    <style>
        body {
            background: #80CBC4;
        }

        .container {
            width: 90vw;
            margin: auto;
            margin-top: 22px;
            margin-bottom: 0;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
            background: white;
            padding: 22px;
        }

        .footer {
            background: #37474F;
            color: #FAFAFA;
            width: 90vw;
            margin: auto;
            padding: 22px;
        }

        .footer a {
            color: #FAFAFA;
        }

        .titulo {
            color: #455A64;
            font-size: 18pt;
            font-weight: 600;
        }

        .logo {
            margin: auto;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <p class="text-center">
            <img src="https://tropicoinn.com.sv/images/logo_mail.jpg" class="logo" alt="Logo {{ env('empresa') }}">
        </p>
        <p class="titulo text-center">
            {{ env('empresa', 'TURISTICAS DE ORIENTE S.A. DE C.V.') }}
        </p>
        <p>
            Estimado(a) cliente {{ $dte->comprobante?->titular }} {{ $dte->sujeto?->titular }}, se
            @if ($dte->invalidado && $dte->invalidado != null)
                <span style="color: red;">
                    invalido
                </span>
            @else
                emitió
            @endif
            el comprobante con el siguiente detalle.
        </p>
        @if ($dte->invalidado && $dte->invalidado != null)
            <p>
            <h2 style="color: red;">
                DTE INVALIDADO
            </h2>
            </p>
            @if (strlen($dte->invalidado->codigo_generacion_r) > 30)
                <p>
                    <b>REFACTURADO EN DTE: </b>
                    {{ $dte->invalidado->codigo_generacion_r }}
                </p>
            @endif
        @endif
        <p>
        <h2>
            INFORMACIÓN DEL DTE
        </h2>
        </p>
        <P>
            <b>
                CÓDIGO DE GENERACIÓN:
            </b>
            {{ $dte->codigo_generacion }}
        </P>
        <p>
            <b>
                SELLO DE RECEPCION:
            </b>
            {{ $dte->sello_recibido }}

        </p>
        <p>
            <b>
                FECHA DE PROCESAMIENTO:
            </b>
            {{ $dte->fecha_procesamiento }}
        </p>
        <p>
            <b>
                AMBIENTE:
            </b>
            {{ env('ambiente') }} - {{ env('ambiente_mh') }}
        </p>

    </div>
    <div class="footer">
        POR FAVOR NO RESPONDA A ESTE CORREO, SI TIENE ALGUNA CONSULTA PUEDE CONSULTAR AL CORREO
        <a href="mailto:clientes.dte@tropicoinn.com.sv?subject={{ $dte->codigo_generacion }}">
            clientes.dte@tropicoinn.com.sv
        </a>
        <br>
        CONTACTOS
        <a href="tel:+50326821000">
            +503 2682 1000
        </a>
    </div>
</body>

</html>
