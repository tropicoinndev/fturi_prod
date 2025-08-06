<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>
    <body>
        <div>
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="5" valign="middle" style="font-weight: bold; font-size: 13pt; text-align: center; height: 60px;">
                            <p>REPORTE DE CRÉDITO A EMPLEADOS</p>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="2">
                            <b>DEL: </b> <p>{{ $inicio }}</p>
                        </th>
                        <th colspan="3">
                            <b>AL: </b> <p>{{ $fin }}</p>
                        </th>
                    </tr>
                    <tr></tr>
                    <tr>
                        <th>Fecha</th>
                        <th>Correlativo</th>
                        <th>Cliente</th>
                        <th>Monto crédito</th>
                        <th>Nº de Abono</th>
                        <th>Abonos</th>
                        <th>Realiza abono</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clientes as $c)
                        @php
                            $monto = $data->where('clientes_id', $c->id)->sum('monto');
                            $total = $data->where('clientes_id', $c->id)->sum('total');
                            $numeroComprobantes = $data->where('clientes_id', $c->id)->count('id');
                            $comprobantes = $data->where('clientes_id', $c->id);
                        @endphp

                        @foreach($comprobantes as $i)
                            <tr class="factura">
                                <td>{{ $i->fecha }}</td>
                                <td>{{ $i->correlativo }}</td>
                                <td>{{ $i->titular }}</td>
                                <td>{{ round($i->monto, 2) }}</td>
                                <td>{{ $i->abono != null ? $i->abono->abonos_id : '' }}</td>
                                <td>
                                    @if($i->abono != null && isset($i->abono->abonos_id) && $i->abono->abonos_id)
                                        {{ round($i->total, 2) }}
                                    @else
                                        0.00
                                    @endif
                                </td>
                                <td>{{ $i->abono->users->name ?? '' }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </body>
</html>
