@extends('layouts.print')
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
            width: 6cm;
        }

        .w-10 {
            width: 1.5cm;
        }

        .fp-title {
            font-size: 8.5pt;

            width: {
                    {
                    21.94 / 7
                }
            }cm;
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
            min-width: 1cm;

        }

        .space {
            height: 20px;
        }

        td {
            min-height: 45px;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody+tbody {
            border-top: 2px solid #dee2e6;
        }

        .table-sm th,
        .table-sm td {
            padding: 0.3rem;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }

        .text-center {
            text-align: center;
        }

        .bold-text {
            font-weight: bold;
        }
    </style>
@endsection
@section('titulo')
    <div class="titulo">
        Reporte de descargo de eventos
    </div>
@endsection
@section('content')
    <div>
        <div class="row">
            <div class="col-4">
                <span class="b">Sucursal:</span>
                {{ $caja->sucursales->sucursal }}
            </div>
            <div class="col-4">
                <span class="b"> Caja:</span>
                {{ $caja->caja }}
            </div>
            <div class="col-4">

            </div>
        </div>
            <div class="row">
            <div class="col-4">
                <span class="b">Del:</span>
                {{ $inicio }}
            </div>

            <div class="col-4">
                <span class="b">Al:</span>
                {{ $fin }}
            </div>
            <div class="col-4">
                <span class="b">Cantidad: </span>
                {{ $comandas->count() }} productos comandados
            </div>

        </div>

        @if (isset($eventos))
            @foreach ($vendedor as $v)
                @php
                    $rVendedor = $eventos->where('users_id', $v->id);
                    $total = $rVendedor->sum('total_evento');
                @endphp
                @foreach ($rVendedor as $evento)
                    <div class="row">
                        <div class="col-12">
                            <table
                                class="table table-striped">
                                <thead>
                                    <tr class="text-fold">
                                        <th class="tb-title fp-title">Vendido por:</th>
                                        <th class="tb-title fp-title">Nº</th>
                                        <th class="tb-title fp-title">FECHA</th>
                                        <th class="tb-title fp-title">EVENTO</th>
                                        <th class="tb-title fp-title" colspan="2">CLIENTE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="bold-text">
                                        <td>{{ $v->name }}</td>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $evento->fecha }}</td>
                                        <td>{{ $evento->tipo_eventos->evento }}</td>
                                        <td colspan="2">{{ $evento->clientes->nombre ?? $evento->titular}}</td>
                                    </tr>
                                    @php
                                        $comandas = $evento->comandasTest()->get();
                                        $ordenes = $evento->ordenesTest()->get();
                                        $comanda_detalles = $comandas->flatMap(function ($comanda) {
                                            return $comanda->detalles_comanda;
                                        });
                                        $detalle_ordenes = $ordenes->flatMap(function ($o) {
                                            return $o->detalle_orden;
                                        });
                                        $reservas = $evento->reservaciones()->get();
                                        $detalle_reserva = $reservas->flatMap(function ($r) {
                                            return $r->detalleReservaciones;
                                        });
                                    @endphp
                                    @if (count($comanda_detalles) > 0)
                                        <tr>
                                            <td colspan="6">
                                                <div class="flit">Comandas del evento - {{ count($comanda_detalles) }}
                                                    producto{{ count($comanda_detalles) > 1 ? 's' : '' }}
                                                    comandado{{ count($comanda_detalles) > 1 ? 's' : '' }}</div>
                                                <table class="table table-striped" border="0" cellspacing="0"
                                                    cellpadding="0">
                                                    <thead>
                                                        <tr>
                                                            <th class="tb-title ">Concepto</th>
                                                            <th class="tb-title fp-title">Cantidad</th>
                                                            <th class="tb-title fp-title">Precio</th>
                                                            <th class="tb-title fp-title">Nº Comanda</th>
                                                            <th class="tb-title fp-title">Usuario</th>
                                                            <th class="tb-title fp-title">Fecha</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($comanda_detalles as $detalle)
                                                            <tr>
                                                                <td>{{ $detalle->precios->detalle }}</td>
                                                                <td class="fp-title">{{ $detalle->cantidad }}</td>
                                                                <td class="ft-title">
                                                                    ${{ number_format($detalle->precio, 2) }}</td>
                                                                <td class="ft-title">Nº {{ $detalle->comandas_id }}</td>
                                                                <td class="ft-title">
                                                                    {{ explode('@', $detalle->user_comanda->email)[0] }}
                                                                </td>
                                                                <td class="fp-title">{{ $detalle->created_at }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($ordenes->isNotEmpty())
                                        <tr>
                                            <td colspan="6">
                                                <div class="flit">Órden{{ count($detalle_ordenes) > 1 ? 'es' : '' }} del evento -
                                                    {{ count($detalle_ordenes) }}
                                                    servicio{{ count($detalle_ordenes) > 1 ? 's' : '' }}</div>
                                                <table class="table table-striped" border="0" cellspacing="0"
                                                    cellpadding="0">
                                                    <thead class="title-table">
                                                        <tr>
                                                            <th class="tb-title w-12">Concepto</th>
                                                            <th class="tb-title fp-title">Cantidad</th>
                                                            <th class="tb-title fp-title">Precio</th>
                                                            <th class="tb-title fp-title">Nº Orden</th>
                                                            <th class="tb-title fp-title">Usuario</th>
                                                            <th class="tb-title fp-title">Fecha</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($detalle_ordenes as $orden)
                                                            <tr>
                                                                <td>{{ $orden->servicios->servicio }}</td>
                                                                <td class="fp-title">{{ $orden->cantidad }}</td>
                                                                <td class="fp-title">
                                                                    ${{ number_format($orden->precio_unitario, 2) }}
                                                                </td>
                                                                <td class="fp-title">Nº {{ $orden->ordenes_id }}</td>
                                                                <td class="fp-title">
                                                                    {{ explode('@', $orden->user_detalle->email)[0] }}
                                                                </td>
                                                                <td class="fp-title">{{ $orden->created_at }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($detalle_reserva->isNotEmpty())
                                        <tr>
                                            <td colspan="6">
                                                <div class="flit">Reservacion{{ count($detalle_reserva) > 1 ? 'es' : '' }} del evento -
                                                    {{ count($detalle_reserva) }}
                                                    reserva{{ count($detalle_reserva) > 1 ? 's' : '' }}</div>
                                                <table class="table table-striped" border="0" cellspacing="0"
                                                    cellpadding="0">
                                                    <thead class="tb-title">
                                                        <tr class="tb-title fp-title">
                                                            <th>Fecha ingreso</th>
                                                            <th>Fecha salida</th>
                                                            <th>Habitacion</th>
                                                            <th>Cliente</th>
                                                            <th>Dias</th>
                                                            <th>Tarifa</th>
                                                            <th>Total</th>
                                                            <th>Detalle</th>
                                                            <th>Nº Reserva</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($detalle_reserva as $r)
                                                            <tr>
                                                                <td>{{ $r->fecha_ingreso }}</td>
                                                                <td>{{ $r->fecha_salida }}</td>
                                                                <td>{{ $r->relacionHabitaciones->numero_habitacion }}
                                                                </td>
                                                                <td>{{ $r->relacionReservaciones->relacionClientes->nombre ?? $r->relacionReservaciones->titular }}
                                                                </td>
                                                                <td>{{ $r->dias }}</td>
                                                                <td>{{ $r->relacionTarifas->tarifa }}</td>
                                                                <td>${{ number_format(round($r->relacionTarifas->precio * $r->dias, 2), 2) }}
                                                                </td>
                                                                <td>{{ $r->ingreso ? 'Ingreso registrado' : 'Sin ingreso registrado' }}
                                                                </td>
                                                                <td>Nº {{ $r->reservaciones_id }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
                <div class="row">
                    <div class="col-12 text-bold">
                        <table
                            class="table table-light table-striped table-hover table-bordered table-sm table-responsive-sm">
                            <tbody>
                                <tr>
                                    <td colspan="3" class="text-uppercase text-bold">
                                        Total de eventos autorizados vendidos por {{ $v->name }}
                                    </td>
                                    <td class="text-end"><b>${{ number_format($total, 2) }}</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection
