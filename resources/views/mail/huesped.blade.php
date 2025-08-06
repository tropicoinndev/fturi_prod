<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Huéspedes - TURISTICAS DE ORIENTE S.A. DE C.V.</title>
    <style>
        /* Reset de estilos */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f1f1f1;
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 1000px;
            margin: 10px auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        header,
        footer {
            background-color: #37474F;
            color: #FAFAFA;
            text-align: center;
            padding: 15px;
        }

        header img {
            max-width: 80px;
            margin-bottom: 10px;
        }

        h1 {
            font-size: 1.5rem;
            margin-bottom: 5px;
        }

        h2 {
            font-size: 1.25rem;
            color: #455A64;
            margin: 15px 0;
        }

        main {
            padding: 20px;
        }

        .table-container {
            overflow-x: auto;
            margin-top: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #80CBC4;
            color: #333;
            font-weight: 600;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #e0f2f1;
        }

        .contact a {
            color: #80CBC4;
            text-decoration: none;
        }

        .contact a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="container">
        <header>
            <h1>{{ env('empresa', 'TURISTICAS DE ORIENTE S.A. DE C.V.') }}</h1>
        </header>

        <main>
            <h2>Listado de Huéspedes del día {{ $fecha }}</h2>
            <p>Estimado, se le enviará el reporte de huéspedes con la siguiente información.</p>

            @if (isset($huespedes))
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Nº</th>
                                <th>Nombre</th>
                                <th>Documento</th>
                                <th>Nacionalidad</th>
                                <th>Habitación</th>
                                <th>Entrada</th>
                                <th>Salida</th>
                                <th>Noches</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($huespedes as $r)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $r->nombre }}</td>
                                    <td>{{ $r->identificacion }} {{ $r->documento }}</td>
                                    <td>{{ $r->p ?? 'Salvadoreña' }}</td>
                                    <td>{{ $r->n_h ?? 'Nacional' }}</td>
                                    <td>{{ $r->fecha_ingreso }}</td>
                                    <td>{{ $r->fecha_salida }}</td>
                                    <td>{{ (new DateTime($r->fecha_ingreso))->diff(new DateTime($r->fecha_salida))->days }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </main>
    </div>
</body>

</html>
