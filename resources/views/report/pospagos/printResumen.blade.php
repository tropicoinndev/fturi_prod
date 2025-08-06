@extends('layouts.print_bootstrap')
@section('style')
    <style>
        body,
        .table tr th,
        .table tr td {
            background: #fff;

        }

        .table tr th,
        .table tr td {
            padding: 2px;
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
                Reporte de estadías en pos-pago
            </div>

        </div>

        <div class="row mb-2">

            <div class="col-12">
                <table class="table table-borderless">
                    <tbody>
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
                        @php

                            $total = 0;
                        @endphp
                        @foreach ($recepciones as $r)
                            @php
                                $tarifa = ($r->tarifas->precio / $r->tarifas->numero_dias) * $r->dias;
                                $total += $tarifa;
                            @endphp
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $r->id }}</td>
                                <td style="width: 10cm;">{{ $r->clientes->nombre }}</td>
                                <td>{{ $r->fecha_ingreso }}</td>
                                <td>{{ $r->fecha_salida }}</td>
                                <td>{{ $r->dias }} {{ $r->dias > 1 ? 'DIAS' : 'DIA' }}</td>
                                <td>
                                    ${{ number_format($r->tarifas->precio, 2) }} / {{ $r->tarifas->numero_dias }}
                                    {{ $r->tarifas->numero_dias > 1 ? 'DIAS' : 'DIA' }}
                                </td>
                                <td style="text-align: right;">${{ number_format($tarifa, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="7">
                                <b>
                                    TOTAL DE ESTADÍAS EN POS-PAGO
                                </b>
                            </td>
                            <td style="text-align: right;">
                                <b>
                                    ${{ number_format($total, 2) }}
                                </b>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
