@extends('layouts.excel')
@section('content')
<table>
    <thead>
        <tr>
            <th>
                <b>
                    FECHA INGRESO
                </b>
            </th>
            <th>
                <b>
                    FECHA SALIDA
                </b>
            </th>
            <th>
                <b>
                    HABITACIÓN
                </b>
            </th>
            <th>
                <b>
                    CLIENTE
                </b>
            </th>
            <th>
                <b>
                    DIAS
                </b>
            </th>
            <th>
                <b>
                    TARIFA
                </b>
            </th>
            <th>
                <b>
                    TOTAL
                </b>
            </th>
            <th>
                <b>
                    Detalle
                </b>
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($vendedor as $v)
        @php
        $rVendedor = $reservaciones->where('users_id', $v->id);
        $reservasVendedor = $reservas->whereIn('reservaciones_id', $rVendedor->pluck('id'));
        $totalIngreso = 0;
        $totalSinIngreso = 0;
        @endphp
        <tr>
            <th colspan="8" class="text-uppercase">{{ $v->name }}</th>
        </tr>

        @foreach ($reservasVendedor as $r)
        @php
        $total = round($r->relacionTarifas->precio * $r->dias, 2);
        if ($r->ingreso) {
        $totalIngreso = $total;
        } else {
        $totalSinIngreso = $total;
        }
        @endphp
        <tr>
            <td>
                {{ $r->fecha_ingreso }}
            </td>
            <td>
                {{ $r->fecha_salida }}
            </td>
            <td>
                {{ $r->relacionHabitaciones->numero_habitacion }}
            </td>
            <td>
                {{ $r->relacionReservaciones->clientes_id > 0 ? $r->relacionReservaciones->relacionClientes->nombre : $r->relacionReservaciones->titular }}
            </td>
            <td class="text-end">
                {{ $r->dias }}
            </td>
            <td class="text-end">
                ${{ number_format($r->relacionTarifas->precio, 2) }}
            </td>
            <td class="text-end">
                ${{ number_format($r->relacionTarifas->precio * $r->dias, 2) }}
            </td>
            <td>
                {{ $r->ingreso ? 'Ingreso registrado' : 'Sin ingreso registrado' }}
            </td>
        </tr>
        @endforeach
        <tr>
            <td colspan="6" class="text-uppercase">{{ $v->name }}: Total sin
                registro de ingreso
            </td>
            <td class="text-end">${{ number_format($totalSinIngreso, 2) }}</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="6" class="text-uppercase">
                {{ $v->name }}: Total con registro de ingreso
            </td>
            <td class="text-end"><b>${{ number_format($totalIngreso, 2) }}</b></td>
            <td></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
