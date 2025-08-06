<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <table style="text-transform: uppercase;">
        <tbody>
            <tr>
                <td colspan="7" valign="middle"
                    style="font-weight: bold; text-transform: uppercase; font-size: 13pt; text-align: center; height: 70px;">
                    {{ env('empresa') }}

                    <p>Reporte de anticipos activos</p>

                    <p>
                        Cajas:
                        @foreach($cajas as $caja)
                            {{ $caja->caja }},
                        @endforeach

                        Fecha de aplicación: {{ $fecha_aplicacion }}
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="col">Anticipo Nº</th>
                <th scope="col" class="text-truncate">Titular</th>
                <th scope="col">Monto</th>
                {{-- <th scope="col">Monto histórico</th> --}}
                <th scope="col">Caja</th>
                <th scope="col">Usuario realiza</th>
                <th scope="col">Fecha</th>
                <th scope="col">Fecha aplicación</th>
            </tr>

            @foreach($anticipos as $a)
                <tr>
                    <td style="text-align: center;" scope="row">{{ $a->id }}</td>
                    <td>
                        <b class="mb-0">{{ $a->clientes->nombre }}</b>
                        <p class="mb-0">
                            <i style="color: #ccc;">{{ $a->concepto }}</i>
                        </p>
                    </td>
                    <td style="text-align: right;">${{ number_format($a->monto, 2) }}</td>
                    {{-- <td style="text-align: right;">${{ number_format($a->monto_historico, 2) }}</td> --}}
                    <td>{{ $a->turnos->cajas->caja }}</td>
                    <td>{{ $a->users->name }}</td>
                    <td>{{ $a->fecha }}</td>
                    <td>{{ $a->fecha_aplicacion }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
