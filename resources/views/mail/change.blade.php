<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MENSAJE AUTOMÁTICO</title>
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
        <h4>Mensaje automático</h4>
        {{ $body ?? '' }}
        </p>

    </div>
    <div class="footer">
        POR FAVOR NO RESPONDA A ESTE CORREO
    </div>
</body>

</html>
