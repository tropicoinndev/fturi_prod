@extends('layouts.excel')

@section('content')
<thead>
    <tr>
        <th colspan="6">
            <div class="titulo">
                Reporte de descargo de eventos
            </div>
            <div>
                <tr>
                    <td>
                        <span class="b">Sucursal:</span> <b>{{ $caja->sucursales->sucursal }}</b>
                    </td>
                    <td>
                        <span class="b">Caja:</span> <b>{{ $caja->caja }}</b>
                    </td>
                    <td>
                        <span class="b">Turno:</span> <b>{{ $turno->opcion->turno ?? '' }} · {{ $turno->fecha }}</b>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="b">Cantidad: </span> <b>{{ $comandas->count() }} productos comandados</b>
                    </td>
                </tr>
            </div>
        </th>
    </tr>
</thead>

@foreach ($vendedor as $v)
    @php
        $rVendedor = $eventos->where('users_id', $v->id);
        $total = $rVendedor->sum('total_evento');
    @endphp

    @foreach ($rVendedor as $evento)
        <table>
            <thead>
                <tr>
                    <th><b>VENDEDOR</b></th>
                    <th><b>Nº</b></th>
                    <th><b>FECHA</b></th>
                    <th><b>EVENTO</b></th>
                    <th><b>CLIENTE</b></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><b>{{ $v->name }}</b></td>
                    <td><b>{{ $loop->index + 1 }}</b></td>
                    <td><b>{{ $evento->fecha }}</b></td>
                    <td><b>{{ $evento->tipo_eventos->evento }}</b></td>
                    <td><b>{{ $evento->clientes->nombre }}</b></td>
                </tr>
            </tbody>
        </table>

        @php
            $comandas = $evento->comandasTest()->get();
            $ordenes = $evento->ordenesTest()->get();
            $comanda_detalles = $comandas->flatMap(function ($comanda) {
                return $comanda->detalles_comanda;
            });
            $detalle_ordenes = $ordenes->flatMap(function ($comanda) {
                return $comanda->detalle_orden;
            });
            $reservas = $evento->reservaciones()->get();
            $detalle_reserva = $reservas->flatMap(function ($r) {
                return $r->detalleReservaciones;
            });
        @endphp

        @if ($comanda_detalles->isNotEmpty())
            <table>
                <thead>
                    <tr>
                        <th colspan="6"><b>Comandas del evento - {{ $comanda_detalles->count() }}
                                producto{{ $comanda_detalles->count() > 1 ? 's' : '' }}
                                comandado{{ $comanda_detalles->count() > 1 ? 's' : '' }}</b></th>
                    </tr>
                    <tr>
                        <th><b>Concepto</b></th>
                        <th><b>Cantidad</b></th>
                        <th><b>Precio</b></th>
                        <th>Nº Comanda</th>
                        <th><b>Usuario</b></th>
                        <th><b>Fecha</b></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($comanda_detalles as $detalle)
                        <tr>
                            <td>{{ $detalle->precios->detalle }}</td>
                            <td>{{ $detalle->cantidad }}</td>
                            <td>${{ number_format($detalle->precio, 2) }}</td>
                            <td>Nº {{ $detalle->comandas_id }}</td>
                            <td>{{ explode('@', $detalle->user_comanda->email)[0] }}</td>
                            <td>{{ $detalle->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        @if ($detalle_ordenes->isNotEmpty())
            <table>
                <thead>
                    <tr>
                        <th colspan="6"><b>Órden{{ $detalle_ordenes->count() > 1 ? 'es' : '' }} del evento - {{ $detalle_ordenes->count() }}
                                servicio{{ $detalle_ordenes->count() > 1 ? 's' : '' }}</b></th>
                    </tr>
                    <tr>
                        <th><b>Concepto</b></th>
                        <th><b>Cantidad</b></th>
                        <th><b>Precio</b></th>
                        <th><b>Nº Orden</b></th>
                        <th><b>Usuario</b></th>
                        <th><b>Fecha</b></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detalle_ordenes as $orden)
                        <tr>
                            <td>{{ $orden->servicios->servicio }}</td>
                            <td>{{ $orden->cantidad }}</td>
                            <td>${{ number_format($orden->precio_unitario, 2) }}</td>
                            <td>Nº {{ $orden->ordenes_id }}</td>
                            <td>{{ explode('@', $orden->user_detalle->email)[0] }}</td>
                            <td>{{ $orden->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        @if ($detalle_reserva->isNotEmpty())
            <table>
                <thead>
                    <tr>
                        <th colspan="6"><b>Reservacion{{ $detalle_reserva->count() > 1 ? 'es' : '' }} del evento - {{ $detalle_reserva->count() }}
                                reserva{{ $detalle_reserva->count() > 1 ? 's' : '' }}</b></th>
                    </tr>
                    <tr>
                        <th><b>Fecha ingreso</b></th>
                        <th><b>Fecha salida</b></th>
                        <th><b>Habitacion</b></th>
                        <th><b>Cliente</b></th>
                        <th><b>Dias</b></th>
                        <th><b>Tarifa</b></th>
                        <th><b>Total</b></th>
                        <th><b>Detalle</b></th>
                        <th><b>Nº Reserva</b></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detalle_reserva as $r)
                        <tr>
                            <td>{{ $r->fecha_ingreso }}</td>
                            <td>{{ $r->fecha_salida }}</td>
                            <td>{{ $r->relacionHabitaciones->numero_habitacion }}</td>
                            <td>{{ $r->relacionReservaciones->relacionClientes->nombre ?? $r->relacionReservaciones->titular }}</td>
                            <td>{{ $r->dias }}</td>
                            <td>{{ $r->relacionTarifas->tarifa }}</td>
                            <td>${{ number_format(round($r->relacionTarifas->precio * $r->dias, 2), 2) }}</td>
                            <td>{{ $r->ingreso ? 'Ingreso registrado' : 'Sin ingreso registrado' }}</td>
                            <td>Nº {{ $r->reservaciones_id }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endforeach

    <table>
        <tbody>
            <tr>
                <td colspan="5" class="bold-text"><b>Total de eventos autorizados vendidos por {{ $v->name }}</b></td>
                <td class="text-end"><b>${{ number_format($total, 2) }}</b></td>
            </tr>
        </tbody>
    </table>
@endforeach
@endsection
