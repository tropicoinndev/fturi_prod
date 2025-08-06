@extends('layouts.panel_reportes')
@section('css-panel_reportes')
    <style>
        body {
            background: #FFCC80;
        }
    </style>
@endsection

@section('panel_reportes')
    <div id="appVentaByRubros" class="container">
        <div class="card-body p-2">
            <div class="row mb-2">
                <div class="col-12 text-uppercase h3">
                    Vista previa reporte de ventas de habitaciones
                </div>
                <div class="col-12">
                    RESUMIDO
                </div>
                <div class="col-12 my-2">
                    <a class="btn btn-light" href="{{ route('cajas.ventasHabitaciones') }}" role="button">Volver</a>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12 my-3">
                    Fecha: {{ Carbon::parse($inicio)->format('d-m-Y') }} {{ Carbon::parse($fin)->format('d-m-Y') }}
                </div>
                <div class="col-12">
                    <table class="table">
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

                            <tr>
                                <td scope="row" colspan="{{ $col - 3 }}" class="text-uppercase text-muted fw-bold">
                                    Total ventas de habitaciones
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
    </div>
@endsection
