@extends('layouts.panel_reportes')


@section('panel_reportes')
    <div id="appVentaByRubros" class="container">
        <div class="card-body p-2">
            <div class="row mb-2">
                <div class="col-12 text-uppercase h3">
                    Vista previa ventas por rubro
                </div>
                <div class="col-12 my-2">
                    <a class="btn btn-light" href="{{ route('cajas.ventaRubros') }}" role="button">Volver</a>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Nº Cuenta</th>
                                <th scope="col">Tipo Cuenta</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Detalle</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col">Neto</th>
                                <th scope="col">Venta</th>
                                <th scope="col">Propina</th>
                                <th scope="col">IVA</th>
                                <th scope="col">ADV</th>
                                <th scope="col">CET</th>
                                <th scope="col">Total</th>
                                <th scope="col">Factura</th>
                                <th scope="col">MH</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $neto = 0;
                                $venta = 0;
                                $propina = 0;
                                $iva = 0;
                                $cet = 0;
                                $adv = 0;
                                $total = 0;
                            @endphp
                            @foreach ($caja as $c)
                                @php
                                    $cneto = 0;
                                    $cventa = 0;
                                    $cpropina = 0;
                                    $civa = 0;
                                    $ccet = 0;
                                    $ctotal = 0;
                                    $cadv = 0;
                                @endphp
                                <tr>
                                    <th scope="row" colspan="14" class="text-center h3 text-uppercase">
                                        {{ $c->caja }}
                                    </th>
                                </tr>
                                @foreach ($rubro as $r)
                                    @php
                                        $dataFormCaja = [];
                                        $dataFormCaja = $data->where('cajas_id', $c->id)->where('rubros_id', $r->id);
                                    @endphp
                                    @if (count($dataFormCaja) > 0)
                                        @php
                                            $rneto = 0;
                                            $rventa = 0;
                                            $rpropina = 0;
                                            $riva = 0;
                                            $rcet = 0;
                                            $rtotal = 0;
                                            $radv = 0;
                                        @endphp
                                        <tr>
                                            <th scope="row" colspan="14">
                                                {{ $r->rubro }}
                                            </th>
                                        </tr>
                                        @foreach ($dataFormCaja as $d)
                                            @php
                                                $dneto = round($d->neto, 2);
                                                $dventa = round($d->cantidad * $d->neto, 2);
                                                $dpropina = round($d->cantidad * $d->propina, 2);
                                                $diva = round($d->cantidad * $d->iva, 2);
                                                $dcet = round($d->cantidad * $d->cesc, 2);
                                                $dadv = round($d->cantidad * $d->advalorem, 2);
                                                $dtotal = round($d->cantidad * $d->total, 2);

                                                $rneto += $dneto;
                                                $rventa += $dventa;
                                                $rpropina += $dpropina;
                                                $riva += $diva;
                                                $rcet += $dcet;
                                                $radv += $dadv;
                                                $rtotal += $dtotal;
                                            @endphp
                                            <tr>
                                                <td>{{ $d->cuenta }}</td>
                                                <td>
                                                    <span>{{ $d->tipo_registros == 1 ? 'Orden' : ($d->tipo_registros == 2 ? 'Estadía' : ($d->tipo_registros == 3 ? 'Comanda' : 'No registrada')) }}</span>
                                                </td>
                                                <td>{{ $d->fecha }}</td>
                                                <td>{{ $d->concepto }}</td>
                                                <td>{{ $d->cantidad }}</td>
                                                <td>${{ number_format($dneto, 2) }}</td>
                                                <td>${{ number_format($dventa, 2) }}</td>
                                                <td>${{ number_format($dpropina, 2) }}</td>
                                                <td>${{ number_format($diva, 2) }}</td>
                                                <td>${{ number_format($dadv, 2) }}</td>
                                                <td>${{ number_format($dcet, 2) }}</td>
                                                <td>${{ number_format($dtotal, 2) }}</td>
                                                <td>{{ $d->correlativo }}</td>
                                                <td>{{ $d->numero_control }}</td>
                                            </tr>
                                        @endforeach

                                        <tr>
                                            <td class="text-uppercase" colspan="5">TOTAL {{ $r->rubro }}</td>
                                            <td>${{ number_format($rneto, 2) }}</td>
                                            <td>${{ number_format($rventa, 2) }}</td>
                                            <td>${{ number_format($rpropina, 2) }}</td>
                                            <td>${{ number_format($riva, 2) }}</td>
                                            <td>${{ number_format($radv, 2) }}</td>
                                            <td>${{ number_format($rcet, 2) }}</td>
                                            <td>${{ number_format($rtotal, 2) }}</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        @php

                                            $cneto += $rneto;
                                            $cventa += $rventa;
                                            $cpropina += $rpropina;
                                            $civa += $riva;
                                            $ccet += $rcet;
                                            $cadv += $radv;
                                            $ctotal += $rtotal;
                                        @endphp
                                    @endif
                                @endforeach

                                <tr>
                                    <td class="text-uppercase" colspan="5">TOTAL {{ $c->caja }}</td>
                                    <td>${{ number_format($cneto, 2) }}</td>
                                    <td>${{ number_format($cventa, 2) }}</td>
                                    <td>${{ number_format($cpropina, 2) }}</td>
                                    <td>${{ number_format($civa, 2) }}</td>
                                    <td>${{ number_format($cadv, 2) }}</td>
                                    <td>${{ number_format($ccet, 2) }}</td>
                                    <td>${{ number_format($ctotal, 2) }}</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @php

                                    $neto += $cneto;
                                    $venta += $cventa;
                                    $propina += $cpropina;
                                    $iva += $civa;
                                    $cet += $ccet;
                                    $adv += $cadv;
                                    $total += $ctotal;
                                @endphp
                            @endforeach
                            <tr>
                                <td class="text-uppercase" colspan="5">
                                    <b>
                                        TOTAL
                                    </b>
                                </td>
                                <td>
                                    <b>
                                        ${{ number_format($neto, 2) }}
                                    </b>
                                </td>
                                <td>
                                    <b>
                                        ${{ number_format($venta, 2) }}
                                    </b>
                                </td>
                                <td>
                                    <b>
                                        ${{ number_format($propina, 2) }}
                                    </b>
                                </td>
                                <td>
                                    <b>
                                        ${{ number_format($iva, 2) }}
                                    </b>
                                </td>
                                <td>
                                    <b>
                                        ${{ number_format($adv, 2) }}
                                    </b>
                                </td>
                                <td>
                                    <b>
                                        ${{ number_format($cet, 2) }}
                                    </b>
                                </td>

                                <td>
                                    <b>
                                        ${{ number_format($total, 2) }}
                                    </b>
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
