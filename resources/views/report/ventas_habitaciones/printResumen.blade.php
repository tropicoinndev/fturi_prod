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
                RESUMIDO
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-12 my-3">
                FECHA: DEL {{ Carbon::parse($inicio)->format('d-m-Y') }} AL {{ Carbon::parse($fin)->format('d-m-Y') }}
            </div>
            <div class="col-12">
                <table class="table table-borderless">
                    <tbody>
                        @php
                            $neto = 0;
                            $venta = 0;
                            $cantidad = 0;
                            $col = 4;
                        @endphp
                        @foreach ($sucursales as $s)
                            @php
                                $sneto = 0;
                                $sventa = 0;
                                $scantidad = 0;
                                $srneto = 0;
                                $srventa = 0;
                                $srcantidad = 0;
                                $crneto = 0;
                                $crventa = 0;
                                $crcantidad = 0;
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
                            <tr>
                                <th>EMPLEADO</th>
                                <th>CANTIDAD</th>
                                <th>VENTA NETA</th>
                                <th>VENTA TOTAL</th>
                            </tr>
                            </tr>
                            @foreach ($vendedores as $v)
                                @php
                                    $dataVendedor = $data
                                        ->where('sucursales_id', $s->id)
                                        ->where('reserva_user_id', $v->id);
                                @endphp
                                @if ($dataVendedor->count() > 0)
                                    @php

                                        $vneto = $dataVendedor->SUM('tneto');
                                        $vventa = $dataVendedor->SUM('ttotal');
                                        $vcantidad = $dataVendedor->SUM('cantidad');

                                        $crneto += $vneto;
                                        $crventa += $vventa;
                                        $crcantidad += $vcantidad;
                                    @endphp
                                    <tr>
                                        <td scope="row" class="text-uppercase text-muted">
                                            {{ $v->name }}
                                        </td>

                                        <td class="text-right">{{ $vcantidad }}</td>
                                        <td class="text-right">${{ number_format($vneto, 2) }}</td>
                                        <td class="text-right">${{ number_format($vventa, 2) }}</td>

                                    </tr>
                                @endif
                            @endforeach
                            @php

                                $sneto += $crneto;
                                $sventa += $crventa;
                                $scantidad += $crcantidad;
                            @endphp
                            <tr>
                                <td scope="row" class="text-uppercase text-muted fw-bold">
                                    Total {{ $s->sucursal }} con reservaciones
                                </td>

                                <td class="text-right">{{ $crcantidad }}</td>
                                <td class="text-right">${{ number_format($crneto, 2) }}</td>
                                <td class="text-right">${{ number_format($crventa, 2) }}</td>
                            </tr>


                            <!--Ventas sin reservaciones -->
                            <tr>
                                <td scope="row" colspan="{{ $col }}" class="text-uppercase fw-bold">
                                    Ventas sin reservación
                                </td>
                            <tr>
                                <th>EMPLEADO</th>
                                <th>CANTIDAD</th>
                                <th>VENTA NETA</th>
                                <th>VENTA TOTAL</th>
                            </tr>
                            </tr>
                            @foreach ($vendedores as $v)
                                @php
                                    $dataVendedor = $data
                                        ->where('sucursales_id', $s->id)
                                        ->where('reserva_user_id', null)
                                        ->where('recepcion_user_id', $v->id);
                                @endphp
                                @if ($dataVendedor->count() > 0)
                                    @php

                                        $vneto = $dataVendedor->SUM('tneto');
                                        $vventa = $dataVendedor->SUM('ttotal');
                                        $vcantidad = $dataVendedor->SUM('cantidad');

                                        $srneto += $vneto;
                                        $srventa += $vventa;
                                        $srcantidad += $vcantidad;
                                    @endphp
                                    <tr>
                                        <td scope="row" class="text-uppercase text-muted">
                                            {{ $v->name }}
                                        </td>

                                        <td class="text-right">{{ $vcantidad }}</td>
                                        <td class="text-right">${{ number_format($vneto, 2) }}</td>
                                        <td class="text-right">${{ number_format($vventa, 2) }}</td>

                                    </tr>
                                @endif
                            @endforeach
                            @php

                                $sneto += $srneto;
                                $sventa += $srventa;
                                $scantidad += $srcantidad;
                            @endphp
                            <tr>
                                <td scope="row" class="text-uppercase text-muted fw-bold">
                                    Total {{ $s->sucursal }} sin reservaciones
                                </td>

                                <td class="text-right">{{ $srcantidad }}</td>
                                <td class="text-right">${{ number_format($srneto, 2) }}</td>
                                <td class="text-right">${{ number_format($srventa, 2) }}</td>
                            </tr>

                            <!-- Total de sucursal -->
                            <tr>
                                <td scope="row" class="text-uppercase text-muted fw-bold">
                                    Total {{ $s->sucursal }}
                                </td>

                                <td class="text-right">{{ $scantidad }}</td>
                                <td class="text-right">${{ number_format($sneto, 2) }}</td>
                                <td class="text-right">${{ number_format($sventa, 2) }}</td>

                            </tr>
                            @php
                                $neto += $sneto;
                                $venta += $sventa;
                                $cantidad += $scantidad;
                            @endphp
                        @endforeach

                        <tr class="bt">
                            <td scope="row" colspan="{{ $col - 3 }}" class="text-uppercase text-muted fw-bold">
                                <b>
                                    Total ventas de habitaciones
                                </b>
                            </td>

                            <td class="text-right">{{ $cantidad }}</td>
                            <td class="text-right">${{ number_format($neto, 2) }}</td>
                            <td class="text-right">${{ number_format($venta, 2) }}</td>

                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
