<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Comandas reporte</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: #333333;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .container {
            width: 90%;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #eaeaea;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .col-12 {
            flex: 0 0 100%;
            max-width: 100%;
        }

        .card {
            background-color: #ffffff;
            border: 1px solid #eaeaea;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-size: 18px;
            font-weight: bold;
            color: #333333;
            margin-bottom: 10px;
        }

        .card-text {
            margin-top: 10px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table th,
        .table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #eaeaea;
        }

        .table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .table tfoot th,
        .table tfoot td {
            font-weight: bold;
            background-color: #e6e6e6;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h5>
                    Notificación automática de comandas activas por crédito. Estas comandas son movidas de forma
                    automática cuando el turno al que pertenecen ya esta cerrado. Ademas informa cuando hay comandas de
                    turnos cerrados que no proceden para créditos (Estas deben cobrarse o anularse si es necesario).
                </h5>
            </div>
            @if ($comandas_creditos != null)
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">REPORTE DE COMANDAS MOVIDAS A CRÉDITO</h5>
                            <div class="card-text">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Comanda</th>
                                            <th scope="col">Caja</th>
                                            <th scope="col">Fecha</th>
                                            <th scope="col">Cliente</th>
                                            <th scope="col">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total = 0;
                                        @endphp
                                        @foreach ($comandas_creditos as $c)
                                            @php
                                                $ctotal = $c->getTotal()->total;
                                                $total += $ctotal;
                                            @endphp
                                            <tr>
                                                <td scope="row">{{ $c->id }}</td>
                                                <td>{{ $c->cajas->caja }}</td>
                                                <td>{{ $c->fecha }}</td>
                                                <td>{{ $c->clientes_id }} - {{ $c->clientes->nombre ?? $c->titular }}
                                                </td>
                                                <td>${{ number_format($ctotal, 2) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td scope="row" colspan="4">Total en comandas movidas a crédito</td>
                                            <td scope="row">${{ number_format($total, 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            @endif
            @if ($reporte_activas != null && $reporte_activas->count() > 0)
                <div class="col-12" style="margin-top: 30px;">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title" style="color: rgba(190, 5, 5, 0.699)">REPORTE DE COMANDAS ACTIVAS -
                                SIN
                                CLIENTES
                                O CLIENTES SIN CRÉDITO PERMITIDO
                            </h5>
                            <div class="card-text">
                                Estas comandas no deben estar activas, deben facturarse o anularse
                            </div>
                            <div class="card-text">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Comanda</th>
                                            <th scope="col">Caja</th>
                                            <th scope="col">Fecha</th>
                                            <th scope="col">Cliente</th>
                                            <th scope="col">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $atotal = 0;
                                        @endphp
                                        @foreach ($reporte_activas as $a)
                                            @php
                                                $actotal = $a->getTotal()->total;
                                                $atotal += $actotal;
                                            @endphp
                                            <tr>
                                                <td scope="row">{{ $a->id }}</td>
                                                <td>{{ $a->cajas->caja }}</td>
                                                <td>{{ $a->fecha }}</td>
                                                <td>
                                                    {{ $a->clientes->nombre ?? ($c->titular ?? 'Sin nombre o titular') }}
                                                </td>
                                                <td>
                                                    ${{ number_format($actotal, 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td scope="row" colspan="4" style="color: rgba(190, 5, 5, 0.699)">
                                                Total
                                            </td>
                                            <td scope="row" style="color: rgba(190, 5, 5, 0.699)">
                                                ${{ number_format($atotal, 2) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>

</html>
