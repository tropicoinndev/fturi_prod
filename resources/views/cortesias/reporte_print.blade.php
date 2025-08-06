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



        .space {
            height: 20px;
        }

        .bg-turno {
            background: #d1d1d1;
            color: #343434;
        }
        tr td {
            padding: 2px 0px;
        }

        .row {
            width: 25cm;
            display: inline-block;
        }

        .col-6 {
            width: 12.5cm;
            float: left;
            margin-bottom: 0.25cm;
        }

        .text-end {
            text-align: right;
        }
    </style>
@endsection
@section('titulo')
    <div class="titulo">
        Reporte de cortesias
    </div>
@endsection
@section('content')
    @if (isset($data))
        <div class="row">
            <div class="col-6">
                Fecha: {{ $fecha }}
            </div>
            <div class="col-6">
                Tipo de cortesias: {{ $tipo_cortesias }}
            </div>
        </div>
        @if (isset($data))
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-striped" border="0" cellspacing="0" cellpadding="0">
                                <thead>
                                    <tr >
                                        <th scope="col">CUENTA</th>
                                        <th scope="col">FECHA</th>
                                        <th scope="col">PRODUCTO</th>
                                        <th scope="col">CANTIDAD</th>
                                        <th scope="col">PRECIO</th>
                                        <th scope="col">P.FAC.</th>
                                        <th scope="col">TOTAL</th>
                                        <th scope="col">USUARIO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalCortesias =0;
                                    @endphp
                                    @foreach ($tipoData as $t)
                                        @php
                                            $cTitular = $titular->where('tipo_cortesias_id', $t->id);
                                            $total = 0;
                                            $countTipo = $data->whereIn('control_cortesias_id', $cTitular->pluck('id'))->count();

                                        @endphp
                                        @if ($countTipo > 0)
                                            <tr>
                                                <th colspan="8" class="text-uppercase text-center">{{ $t->tipo }}</th>
                                            </tr>

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

                                                    <tr>
                                                        <th colspan="6" class="text-uppercase">
                                                            {{ $r->titular }}
                                                        </th>
                                                        <th class="text-end">
                                                            ${{ number_format($r->monto, 2) }}
                                                        </th>
                                                        <th>/mes</th>
                                                    </tr>
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
                                                                            ${{ $orden->servicios->precio_unitario }}
                                                                        </td>
                                                                        <td class="text-end">
                                                                            ${{ $orden->precio_unitario }}
                                                                        </td>
                                                                        <td class="text-end">
                                                                            ${{ number_format($orden->cantidad * $orden->precio_unitario, 2) }}
                                                                        </td>
                                                                        <td>

                                                                        </td>
                                                                    </tr>
                                                                    @php
                                                                        $totalTitular += $orden->cantidad * $orden->precio_unitario;

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
                                                                        ${{ number_format($d->estadia->tarifas->precio, 2) }}
                                                                    </td>
                                                                    <td class="text-end">
                                                                        ${{ number_format($d->estadia->tarifa ?? $d->estadia->tarifas->precio, 2) }}

                                                                    </td>
                                                                    <td class="text-end">
                                                                        ${{ number_format(($d->estadia->tarifa ?? $d->estadia->tarifas->precio) * $d->estadia->dias, 2) }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $d->estadia->usuarios->user }}
                                                                    </td>
                                                                </tr>
                                                                @php

                                                                    $totalTitular += ($d->estadia->tarifa ?? $d->estadia->tarifas->precio) * $d->estadia->dias;

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
                                                                            ${{ number_format($comanda->precios->precio, 2) }}
                                                                        </td>
                                                                        <td class="text-end">
                                                                            ${{ number_format($comanda->precio, 2) }}
                                                                        </td>
                                                                        <td class="text-end">
                                                                            ${{ number_format($comanda->precio * $comanda->cantidad, 2) }}
                                                                        </td>
                                                                        <td>
                                                                            {{ $comanda->user_comanda->user }}
                                                                        </td>
                                                                    </tr>
                                                                    @php
                                                                        $totalTitular += $comanda->precio * $comanda->cantidad;

                                                                    @endphp
                                                                @endforeach
                                                            @break

                                                            @default
                                                        @endswitch
                                                    @endforeach
                                                    @php
                                                        $total += $totalTitular;
                                                    @endphp
                                                    <tr>
                                                        <td colspan="6" class="text-uppercase">
                                                            Total {{ $r->titular }}
                                                        </td>
                                                        <td class="text-end">${{ number_format($totalTitular, 2) }}</td>
                                                        <td></td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            @php
                                                $totalCortesias += $total;
                                            @endphp
                                            <tr class="bg-total">
                                                <td colspan="6" class="text-uppercase">Total {{ $t->tipo }}
                                                </td>
                                                <td class="text-end">
                                                    <b>
                                                        ${{ number_format($total, 2) }}</td>
                                                    </b>
                                                <td></td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    <tr class="bg-total-final">
                                        <td colspan="6" class="text-uppercase">
                                            Total {{ $fecha }}
                                        </td>
                                        <td class="text-end">
                                            <b>
                                                ${{ number_format($totalCortesias, 2) }}</td>
                                            </b>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
    @endif
@endsection
