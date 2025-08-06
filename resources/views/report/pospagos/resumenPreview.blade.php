@extends('layouts.panel_reportes')
@section('css-panel_reportes')
    <style>
        body {
            background: #FF8A65 !important;
        }
    </style>
@endsection

@section('panel_reportes')
    <div id="appVentaByRubros" class="container">
        <div class="card-body p-2">
            <div class="row mb-2">
                <div class="col-12 text-uppercase h3">
                    Vista previa reporte de estadías en pos-pago
                </div>

                <div class="col-12 my-2">
                    <a class="btn btn-light" href="{{ route('recepciones.reporte_pospago') }}" role="button">Volver</a>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12">
                    <table class="table">
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
                                    <td style="width: 15cm;">{{ $r->clientes->nombre }}</td>
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
                                <td colspan="7">TOTAL DE ESTADÍAS EN POS-PAGO</td>
                                <td style="text-align: right;">${{ number_format($total, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
