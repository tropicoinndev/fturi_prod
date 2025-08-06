<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
</head>

<body>
    @if (isset($data))
        <table>
            <tr>
                <th>FECHA INGRESO</th>
                <th>FECHA SALIDA</th>
                <th>HABITACIÓN</th>
                <th>Nº HUESPEDES</th>
                <th>ESTADO</th>
                <th>CLIENTE</th>
                <th>DIAS</th>
                <th>TARIFA</th>
                <th>USUARIO</th>
                <th>Facturada</th>
                <th>Anulada</th>
                <th>Observaciones</th>
            </tr>

            @foreach ($data as $r)
                <tr>
                    <td>
                        {{ $r->fecha_ingreso }}
                    </td>
                    <td>
                        {{ $r->fecha_salida }}
                    </td>
                    <td>
                        {{ $r->habitaciones->numero_habitacion }}
                    </td>
                    <td>
                        {{ count($r->huespedes) }}
                    </td>
                    <td>
                        {{ $r->habitaciones->relacionEstadoHabitaciones->estado_habitacion }}
                    </td>
                    <td>
                        {{ $r->clientes_id > 0 ? $r->clientes->nombre : $r->titular }}
                    </td>
                    <td>
                        {{ $r->dias }}
                    </td>
                    <td>
                        ${{ number_format($r->tarifas->precio, 2) }}
                    </td>
                    <td>
                        {{ $r->usuarios->user }}:
                    </td>
                    <td>
                        {{ $r->facturada ? 'Facturada' : 'Sin facturar' }}
                    </td>
                    <td>
                        {{ $r->eliminado ? 'Anulada' : 'Vigente' }}
                    </td>
                    <td>
                        {{ preg_replace('/[^\x20-\x7E\xA0-\xFF]/', '', mb_convert_encoding($r->descripcion, 'UTF-8', 'UTF-8')) }}
                    </td>
                </tr>
            @endforeach
        </table>
    @endif
</body>

</html>
