<table>
    <thead>
        <tr>
            <th colspan="6" style="text-align: center; text-transform: uppercase;">
                {{ env('empresa') }}
            </th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center; text-transform: uppercase;">
                Reporte de ordenes de servicio
            </th>
        </tr>
        <tr>
            <th colspan="6" style="text-transform: uppercase;">
                @php
                    $cj = $cajas->pluck('caja');
                    $all = $cj->join(', ', ' y ');
                @endphp
                <strong>
                    Cajas:
                </strong>
                {{ $all }}
            </th>
        </tr>
        <tr>
            <th colspan="6"></th>
        </tr>
        <tr>
            <th scope="col">Caja</th>
            <th scope="col">Fecha</th>
            <th scope="col">Orden</th>
            <th scope="col">cliente</th>
            <th scope="col">Estado</th>
            <th scope="col" class="text-end">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $d)
            <tr class="{{ $detalle ? 'bt' : '' }}">
                <td>{{ $d->cajas->caja }}</td>
                <td>{{ $d->fecha }}</td>
                <td>{{ $d->orden }}</td>
                <td class="text-uppercase titular">
                    {{ $d->clientes?->nombre ?? $d->titular }}
                </td>
                <td>
                    @if ($d->facturada)
                        Facturada
                    @elseif ($d->estado)
                        Activa
                    @elseif ($d->anulada)
                        Anulada
                    @elseif (!$d->estado)
                        Sin facturar
                    @endif
                </td>
                <td class="text-end">${{ number_format($d->sum_orden, 2) }}</td>
            </tr>
            @if ($detalle)
                <tr>
                    <td></td>
                    <td scope="col">Empleado</td>
                    <td scope="col">Cantidad</td>
                    <td scope="col">Servicio</td>
                    <td scope="col" class="text-end">Unitario</td>
                    <td></td>
                </tr>
                @forelse ($d->detalle_orden as $o)
                    <tr>
                        <td></td>
                        <td>{{ $o->user_detalle?->user }}</td>
                        <td>{{ $o->cantidad }}</td>
                        <td>{{ $o->servicios->servicio }}</td>
                        <td class="text-end">${{ number_format($o->precio_unitario, 2) }}</td>
                        <td></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            Sin servicios agregados
                        </td>
                    </tr>
                @endforelse
            @endif
        @endforeach
    </tbody>
</table>
