@extends('layouts.print_bootstrap')

@section('style')
    <style>
        body,
        .table tr,
        .table td,
        .table th {
            background: #FFF;
        }

        .bt {
            border-top: 1px solid #000 !important;
        }

        .bb {
            border-bottom: 1px solid #000 !important;
        }

        .bt-2 {
            border-top: 2.5px solid #000 !important;
        }
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="card-body p-2">
            <div class="row mb-2">
                <div class="col-12 text-uppercase h3 text-center">
                    {{ env('empresa') }}
                </div>
                <div class="col-12 text-uppercase h3 text-center">
                    Reporte de comandas en crédito
                </div>

                <div class="col-12 my-1 text-uppercase">
                    @php
                        $cj = $cajas->pluck('caja');
                        $all = $cj->join(', ', ' y ');
                    @endphp
                    <strong>
                        Cajas:
                    </strong>
                    <br>
                    {{ $all }}
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12">
                    <table class="table table-borderless table-white">
                        <thead>
                            <tr>
                                <th scope="col">Caja</th>
                                <th scope="col">Empleados</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Comanda</th>
                                <th scope="col">cliente</th>
                                <th scope="col">Segmento</th>
                                <th scope="col">Periodo de crédito</th>
                                <th scope="col">Transcurrido</th>
                                <th scope="col">Estado</th>
                                <th scope="col" class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                            @endphp


                            @foreach ($data as $d)
                                @php

                                    $total += $d->sumComanda;
                                @endphp
                                <tr>
                                    <td>{{ $d->cajas->caja }}</td>
                                    <td>{{ $d->usuarios->user }}</td>
                                    <td>{{ $d->fecha }}</td>
                                    <td>{{ $d->id }}</td>
                                    <td style="width: 9cm;">
                                        {{ $d->clientes?->nombre ?? $d->titular }}
                                    </td>
                                    <td>
                                        @if ($d->clientes?->accionista)
                                            ACCIONISTA
                                        @elseif ($d->clientes?->empleado)
                                            EMPLEADO
                                        @elseif ($d->clientes?->credito)
                                            CLIENTE
                                        @endif
                                    </td>
                                    <td>{{ $d->clientes?->periodosCreditos?->periodo }}</td>
                                    <td>{{ $d->created_at->diffInDays(now()) }} (dias)</td>
                                    @php
                                        $vencido =
                                            $d->created_at->diffInDays(now()) > $d->clientes?->periodosCreditos?->dias;
                                    @endphp
                                    <td>
                                        {{ $vencido ? 'VENCIDO' : 'A TIEMPO' }}
                                    </td>
                                    <td class="text-end">${{ number_format($d->sumComanda, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-uppercase fw-bold bt-2" colspan="9">
                                    TOTAL:
                                </td>
                                <td class="text-end fw-bold bt-2">${{ number_format($total, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
