@extends('layouts.print_bootstrap')
@section('style')
    <style>
        body,
        .table tr th,
        .table tr td {
            background: #fff;
        }

        .bt {
            border-top: 1px solid #000 !important;
        }
    </style>
@endsection
@section('content')
    <div>
        <div class="row mb-4">
            <div class="col-12 text-uppercase h3">
                {{ env('empresa') }}
            </div>
            <div class="col-12 text-uppercase h5">
                Reporte de ventas de habitaciones
            </div>
            <div class="col-12">
                DETALLADO
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-12 mb-3">
                FECHA: DEL {{ Carbon::parse($inicio)->format('d-m-Y') }} AL {{ Carbon::parse($fin)->format('d-m-Y') }}
            </div>
            <div class="col-12">
                <table class="table table-borderless">
                    <tbody>
                        @php
                            $neto = 0;
                            $venta = 0;
                            $col = 9;
                        @endphp
                        @foreach ($sucursales as $s)
                            @php
                                $sneto = 0;
                                $sventa = 0;
                                $srneto = 0;
                                $srventa = 0;
                                $crneto = 0;
                                $crventa = 0;
                            @endphp
                            <tr>
                                <th scope="row" colspan="{{ $col }}" class="text-center h3 text-uppercase">
                                    {{ $s->sucursal }}
                                </th>
                            </tr>

                            <tr>
                                <td scope="row" colspan="{{ $col }}" class="text-uppercase fw-bold">
                                    Ventas con reservación
                                </td>
                            </tr>
                            @foreach ($vendedores as $v)
                                @php
                                    $dataVendedor = $data
                                        ->where('sucursales_id', $s->id)
                                        ->where('reserva_user_id', $v->id);
                                @endphp
                                @if ($dataVendedor->count() > 0)
                                    <tr>
                                        <td scope="row" colspan="{{ $col }}" class="text-uppercase text-muted">
                                            empleado/a: {{ $v->name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>RESERVACIÓN</th>
                                        <th>RECEPCION</th>
                                        <th>HAB.</th>
                                        <th style="width: 3cm;">FECHA</th>
                                        <th>CLIENTE</th>
                                        <th>CANTIDAD</th>
                                        <th>VENTA NETA</th>
                                        <th>VENTA TOTAL</th>
                                        <th>Factura</th>
                                    </tr>
                                    @php

                                        $vneto = 0;
                                        $vventa = 0;
                                    @endphp
                                    @foreach ($dataVendedor as $d)
                                        @php
                                            $dneto = round($d->tneto, 2);
                                            $dventa = round($d->ttotal, 2);
                                            $vneto += $dneto;
                                            $vventa += $dventa;
                                        @endphp
                                        <tr>
                                            <td>Nº {{ $d->reservacion }}</td>
                                            <td>Nº {{ $d->recepcion }}</td>
                                            <td>{{ $d->numero_habitacion }}</td>
                                            <td>{{ $d->fecha }}</td>
                                            <td>{{ $d->titular }}</td>
                                            <td>{{ $d->cantidad }}</td>
                                            <td class="text-right">${{ number_format($dneto, 2) }}</td>
                                            <td class="text-right">${{ number_format($dventa, 2) }}</td>

                                            <td>{{ $d->correlativo }}</td>
                                        </tr>
                                    @endforeach
                                    @php

                                        $crneto += $vneto;
                                        $crventa += $vventa;
                                    @endphp
                                    <tr>
                                        <td scope="row" colspan="{{ $col - 3 }}" class="text-uppercase text-muted">
                                            Total: {{ $v->name }}
                                        </td>

                                        <td class="text-right">${{ number_format($vneto, 2) }}</td>
                                        <td class="text-right">${{ number_format($vventa, 2) }}</td>
                                        <td></td>
                                    </tr>
                                @endif
                            @endforeach
                            @php

                                $sneto += $crneto;
                                $sventa += $crventa;
                            @endphp
                            <tr>
                                <td scope="row" colspan="{{ $col - 3 }}" class="text-uppercase text-muted fw-bold">
                                    Total {{ $s->sucursal }} con reservaciones
                                </td>

                                <td class="text-right">${{ number_format($crneto, 2) }}</td>
                                <td class="text-right">${{ number_format($crventa, 2) }}</td>
                                <td class="text-right"></td>
                            </tr>

                            <!--Ventas sin reservaciones -->
                            <tr>
                                <td scope="row" colspan="{{ $col }}" class="text-uppercase fw-bold">
                                    Ventas sin reservación
                                </td>
                            </tr>
                            @foreach ($vendedores as $v)
                                @php
                                    $dataVendedorSR = $data
                                        ->where('sucursales_id', $s->id)
                                        ->where('reserva_user_id', null)
                                        ->where('recepcion_user_id', $v->id);
                                @endphp
                                @if ($dataVendedorSR->count() > 0)
                                    <tr>
                                        <td scope="row" colspan="{{ $col }}" class="text-uppercase text-muted">
                                            empleado/a: {{ $v->name }}
                                        </td>
                                    </tr>
                                    <tr>

                                        <th colspan="2">RECEPCION</th>
                                        <th>HAB.</th>
                                        <th style="width: 3cm;">FECHA</th>
                                        <th>CLIENTE</th>
                                        <th>CANTIDAD</th>
                                        <th>VENTA NETA</th>
                                        <th>VENTA TOTAL</th>
                                        <th>Factura</th>
                                    </tr>
                                    @php

                                        $vneto = 0;
                                        $vventa = 0;
                                    @endphp
                                    @foreach ($dataVendedorSR as $d)
                                        @php
                                            $dneto = round($d->tneto, 2);
                                            $dventa = round($d->ttotal, 2);
                                            $vneto += $dneto;
                                            $vventa += $dventa;
                                        @endphp
                                        <tr>

                                            <td colspan="2">Nº {{ $d->recepcion }}</td>
                                            <td>{{ $d->numero_habitacion }}</td>
                                            <td>{{ $d->fecha }}</td>
                                            <td>{{ $d->titular }}</td>
                                            <td>{{ $d->cantidad }}</td>
                                            <td class="text-right">${{ number_format($dneto, 2) }}</td>
                                            <td class="text-right">${{ number_format($dventa, 2) }}</td>

                                            <td>{{ $d->correlativo }}</td>
                                        </tr>
                                    @endforeach
                                    @php

                                        $srneto += $vneto;
                                        $srventa += $vventa;
                                    @endphp
                                    <tr>
                                        <td scope="row" colspan="{{ $col - 3 }}" class="text-uppercase text-muted">
                                            Total: {{ $v->name }}
                                        </td>

                                        <td class="text-right">${{ number_format($vneto, 2) }}</td>
                                        <td class="text-right">${{ number_format($vventa, 2) }}</td>
                                        <td></td>
                                    </tr>
                                @endif
                            @endforeach
                            @php

                                $sneto += $srneto;
                                $sventa += $srventa;

                                $neto += $sneto;
                                $venta += $sventa;
                            @endphp
                            <tr>
                                <td scope="row" colspan="{{ $col - 3 }}" class="text-uppercase text-muted fw-bold">
                                    Total {{ $s->sucursal }} sin reservaciones
                                </td>

                                <td class="text-right">${{ number_format($srneto, 2) }}</td>
                                <td class="text-right">${{ number_format($srventa, 2) }}</td>
                                <td class="text-right"></td>
                            </tr>

                            <tr>
                                <td scope="row" colspan="{{ $col - 3 }}" class="text-uppercase text-muted fw-bold">
                                    Total {{ $s->sucursal }}
                                </td>

                                <td class="text-right">${{ number_format($sneto, 2) }}</td>
                                <td class="text-right">${{ number_format($sventa, 2) }}</td>
                                <td class="text-right"></td>
                            </tr>
                        @endforeach
                        <tr class="bt">
                            <td scope="row" colspan="{{ $col - 3 }}" class="text-uppercase text-muted fw-bold">
                                Total ventas de habitaciones
                            </td>

                            <td class="text-right">${{ number_format($neto, 2) }}</td>
                            <td class="text-right">${{ number_format($venta, 2) }}</td>
                            <td class="text-right"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
@endsection
