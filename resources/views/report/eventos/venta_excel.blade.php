@extends('layouts.excel')
@section('content')
    <table>
        <thead>
            <tr>
                <th>
                    <b>
                        FECHA
                    </b>
                </th>
                <th>
                    <b>
                        HORARIO
                    </b>
                </th>
                <th>
                    <b>
                        EVENTO
                    </b>
                </th>
                <th>
                    <b>
                        CLIENTE
                    </b>
                </th>
                <th>
                    <b>
                        FORMA DE PAGO
                    </b>
                </th>
                <th>
                    <b>
                        MONTO
                    </b>
                </th>
                <th>
                    <b>
                        ESTADO
                    </b>
                </th>

            </tr>
        </thead>
        <tbody>
            @foreach ($vendedor as $v)
                @php
                    $rVendedor = $eventos->where('users_id', $v->id);
                    $total = $rVendedor->sum('total_evento');
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
                        <td class="text-end">
                            {{ $r->forma_pagos->forma }}
                        </td>
                        <td class="text-end">
                            {{ number_format($r->montofacturado, 2) }}
                        </td>
                        <td class="text-end">
                            @if ($r->autoriza)
                                <span class="text-uppercase">autorizado</span>
                            @else
                                <span class="text-uppercase">sin autorizar</span>
                            @endif
                        </td>

                    </tr>
                @endforeach
                <tr>
                    <td colspan="5" class="text-uppercase">{{ $v->name }}: Total de eventos autorizados
                    </td>
                    <td class="text-end">${{ number_format($total, 2) }}</td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
