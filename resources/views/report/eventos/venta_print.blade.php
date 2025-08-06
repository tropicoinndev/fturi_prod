@extends('layouts.print_b')
@section('style')
    <style>
        * {
            text-transform: uppercase;
            font-size: 9pt;
        }

        .title-table {
            background: rgb(255, 255, 255);
            font-size: 11pt;
            text-align: center;
            color: rgb(26, 26, 26);
            font-weight: 400;
        }

        .w-15 {
            /*width: 200px;*/
            width: 6cm;
        }

        .fp-title {
            font-size: 8.5pt;
            width: 2cm;
            overflow: hidden;
            text-align: right;
        }

        .dollar {
            font-size: 9.5pt;
            text-align: right;
        }

        .titulo {
            font-size: 12pt;
            color: rgb(26, 26, 26);
            text-align: center;
            font-weight: 600;
            width: 100vh;
        }

        .b {
            font-weight: 500;
            color: #555;
        }

        .b1 {
            font-weight: 100;

        }

        .bt-1 {
            border-top: 1px #000 solid;
        }

        .tb-title {
            background: rgb(203, 203, 203);
            color: rgb(26, 26, 26);
        }

        .space {
            height: 20px;
        }

        .bg-turno {
            background: #d1d1d1;
            color: #343434;
        }

        .bg-total {
            background: #d8ffde;
            color: #1c1c1c;
            font-weight: 600;
        }

        tr td {
            padding: 2px 0px;
        }
    </style>
@endsection
@section('titulo')
    <div class="titulo">
        Reporte de ventas de eventos
    </div>
@endsection
@section('content')
    @if (isset($eventos))
        <div class="row">
            <div class="col-12">
                <table class="table table-light table-striped table-hover table-bordered table-sm table-responsive-sm">
                    <thead>
                        <tr>
                            <th scope="col">FECHA </th>
                            <th scope="col">HORARIO</th>
                            <th scope="col">EVENTO</th>
                            <th scope="col">CLIENTE</th>
                            <th scope="col">FORMA DE PAGO</th>
                            <th scope="col">TOTAL</th>
                            <th scope="col">ESTADO</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vendedor as $v)
                            @php
                                $rVendedor = $eventos->where('users_id', $v->id);
                                $total = $rVendedor->sum('montofacturado');

                            @endphp
                            <tr>
                                <th colspan="8" class="text-uppercase">{{ $v->name }}</th>
                            </tr>

                            @foreach ($rVendedor as $r)
                                <tr>
                                    <td>
                                        {{ $r->fecha }}
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($r->inicio)->format('h:i A') }}-{{ \Carbon\Carbon::parse($r->finalizacion)->format('h:i A') }}
                                    </td>
                                    <td>
                                        {{ $r->tipo_eventos->evento }}
                                    </td>
                                    <td>
                                        {{ $r->clientes->nombre ?? $r->titular }}
                                    </td>
                                    <td>
                                        {{ $r->forma_pagos->forma }}

                                    </td>
                                    <td>
                                        {{ number_format($r->montofacturado, 2) }}

                                    </td>
                                    <td>
                                             @if ($r->autoriza)
                                                <span class="text-uppercase" >autorizado</span>
                                            @else
                                                <span class="text-uppercase">sin autorizar</span>
                                            @endif
                                    </td>

                                </tr>
                            @endforeach

                            <tr>
                                <td colspan="5" class="text-uppercase">
                                    {{ $v->name }}: Total de eventos autorizados
                                </td>
                                <td class="text-end"><b>${{ number_format($total, 2) }}</b></td>
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
