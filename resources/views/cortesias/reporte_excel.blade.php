@extends('layouts.excel')
@section('content')
@if (isset($data))
<table>
    <thead>
        <tr>
            <th colspan="10">
                <center>
                    <b>
                        REPORTE DE CORTESIAS
                    </b>
                </center>
            </th>
        </tr>
        <tr>
            <th colspan="10" class="text-center">
                <center>
                    FECHA: {{ $fecha }}
                </center>
            </th>
        </tr>
        <tr>
            <th>
                CUENTA
            </th>
            <th>
                FECHA
            </th>
            <th>
                PRODUCTO
            </th>
            <th>
                CANTIDAD
            </th>
            <th>
                PRECIO $
            </th>
            <th>
                P.FAC. $
            </th>
            <th>
                TOTAL $
            </th>
            <th>
                USUARIO
            </th>
            <th>
                Tipo cortesias
            </th>
            <th>
                Titular
            </th>


        </tr>
    </thead>
    <tbody>
        @php
        $totalCortesias =0;
        $filas = 3;
        @endphp
        @foreach ($tipoData as $t)
        @php
        $cTitular = $titular->where('tipo_cortesias_id', $t->id);
        $total = 0;
        $countTipo = $data->whereIn('control_cortesias_id', $cTitular->pluck('id'))->count();

        @endphp
        @if ($countTipo > 0)


        @foreach ($cTitular as $r)
        @php
        $dataTitular = [];
        $countTitular = $data->where('control_cortesias_id', $r->id)->count();
        $totalTitular = 0;
        @endphp


        @if ($countTitular > 0)
        @php
        $dataTitular = $data->where('control_cortesias_id', $r->id);
        @endphp


        @foreach ($dataTitular as $d)
        @switch($d->origen)
        @case(1)
        @foreach ($d->detalle->detalle_orden as $orden)
        <tr>
            <td>
                {{ $d->cuenta }} Nº {{ $d->origen_id }}
            </td>
            <td>
                {{ Carbon::parse($orden->created_at)->format('d-m-Y h:i:s') }}
            </td>
            <td>
                {{ $orden->servicios->servicio }}
            </td>
            <td>
                {{ $orden->cantidad }}
            </td>
            <td class="text-end">
                {{ $orden->servicios->precio_unitario }}
            </td>
            <td class="text-end">
                {{ $orden->precio_unitario }}
            </td>
            <td class="text-end">
                {{ number_format($orden->cantidad * $orden->precio_unitario, 2) }}
            </td>
            <td>
            </td>
            <td>
                {{ $t->tipo }}
            </td>
            <td>
                {{ $r->titular }} ${{ number_format($r->monto, 2) }} /mes
            </td>
        </tr>
        @php
        $totalTitular += $orden->cantidad * $orden->precio_unitario;
        $filas++;
        @endphp
        @endforeach
        @break

        @case(2)
        <tr>
            <td>
                {{ $d->cuenta }} Nº {{ $d->origen_id }}
            </td>
            <td>
                {{ $d->estadia->fecha_ingreso }} -
                {{ $d->estadia->fecha_salida }}
            </td>
            <td>
                {{ $d->estadia->tarifas->tarifa }}
            </td>
            <td>
                {{ $d->estadia->dias }} dia(s)
            </td>
            <td class="text-end">
                {{ number_format($d->estadia->tarifas->precio, 2) }}
            </td>
            <td class="text-end">
                {{ number_format($d->estadia->tarifa ?? $d->estadia->tarifas->precio, 2) }}

            </td>
            <td class="text-end">
                {{ number_format(($d->estadia->tarifa ?? $d->estadia->tarifas->precio) * $d->estadia->dias, 2) }}
            </td>
            <td>
                {{ $d->estadia->usuarios->user }}
            </td>
            <td>
                {{ $t->tipo }}
            </td>
            <td>
                {{ $r->titular }} ${{ number_format($r->monto, 2) }} /mes
            </td>

        </tr>
        @php
        $totalTitular += ($d->estadia->tarifa ?? $d->estadia->tarifas->precio) * $d->estadia->dias;
        $filas++;
        @endphp
        @break

        @case(3)
        @foreach ($d->detalle->detalles_comanda as $comanda)
        <tr>
            <td>
                {{ $d->cuenta }} Nº {{ $d->origen_id }}
            </td>
            <td>
                {{ Carbon::parse($comanda->created_at)->format('d-m-Y h:i:s') }}
            </td>
            <td>
                {{ $comanda->precios->detalle }}
            </td>
            <td>
                {{ $comanda->cantidad }}
            </td>
            <td class="text-end">
                {{ number_format($comanda->precios->precio, 2) }}
            </td>
            <td class="text-end">
                {{ number_format($comanda->precio, 2) }}
            </td>
            <td class="text-end">
                {{ number_format($comanda->precio * $comanda->cantidad, 2) }}
            </td>
            <td>
                {{ $comanda->user_comanda->user }}
            </td>
            <td>
                {{ $t->tipo }}
            </td>
            <td>
                {{ $r->titular }} ${{ number_format($r->monto, 2) }} /mes
            </td>
        </tr>
        @php
        $totalTitular += $comanda->precio * $comanda->cantidad;
        $filas++;
        @endphp
        @endforeach
        @break

        @default
        @endswitch
        @endforeach
        @php
        $total += $totalTitular;
        @endphp
        @endif
        @endforeach
        @php
        $totalCortesias += $total;
        @endphp
        @endif
        @endforeach
        <tr class="bg-total-final">
            <td colspan="6" class="text-uppercase">
                Total {{ $fecha }}
            </td>
            <td class="text-end">
                <b>
                    =SUM(G4:G{{ $filas }})
                </b>
            </td>
            <td></td>
        </tr>
    </tbody>
</table>
@endif
@endsection
