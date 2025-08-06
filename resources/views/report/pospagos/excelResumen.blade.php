<table>
    <tbody>
        <tr>
            <td colspan="8">
                {{ env('empresa') }}
            </td>
        </tr>
        <tr>
            <td colspan="8">
                Reporte de estadías en pos-pago
            </td>
        </tr>
        <tr>
            <th>Nº</th>
            <th>ESTADÍA</th>
            <th>CLIENTE</th>
            <th>INGRESO</th>
            <th>SALIDA</th>
            <th>Nº DIAS</th>
            <th>TARIFA</th>
            <th>TOTAL</th>
        </tr>

        @foreach ($recepciones as $r)
            @php
                $tarifa = ($r->tarifas->precio / $r->tarifas->numero_dias) * $r->dias;
            @endphp
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $r->id }}</td>
                <td>{{ $r->clientes->nombre }}</td>
                <td>{{ $r->fecha_ingreso }}</td>
                <td>{{ $r->fecha_salida }}</td>
                <td>{{ $r->dias }} {{ $r->dias > 1 ? 'DIAS' : 'DIA' }}</td>
                <td>
                    ${{ number_format($r->tarifas->precio, 2) }} / {{ $r->tarifas->numero_dias }}
                    {{ $r->tarifas->numero_dias > 1 ? 'DIAS' : 'DIA' }}
                </td>
                <td>{{ number_format($tarifa, 2) }}</td>
            </tr>
        @endforeach

    </tbody>
</table>
