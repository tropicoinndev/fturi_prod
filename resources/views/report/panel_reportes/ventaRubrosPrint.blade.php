@extends('layouts.print_html_optimized')

@section('title_report','REPORTE DE VENTAS POR RUBRO')

@section('header')
    <div class="row">
        <div class="col-6"><p class="text-center">DEL: {{ $inicio }}</p></div>
        <div class="col-6"><p class="text-center">AL: {{ $fin }}</p></div>
    </div>
@endsection

@section('content')
    @php
        $contadorRegistros = 0;
        $currentPage = 1;
        $pageCount = 1; // Contador de páginas

        #Cantidad de registros a mostrar por cada página según su orientación
        #Horizontal: 10 registros por página
        #Vertical:   43 registros por página
        $orientacionPagina = 2;
        $cantRegistros = $orientacionPagina === 2 ? 22 : 43;
        $totalPages = ceil(count($data) / $cantRegistros);

        #Definir encabezado de la tabla, se renderizara como HTML, no se escaparán carácteres
        $header = '
            <thead>
                <tr>
                    <th scope="col" class="table-border-left-rounded">Nº Cuenta</th>
                    <th scope="col" class="w-111px">Tipo Cuenta</th>
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
                    <th scope="col" class="table-border-right-rounded">MH</th>
                </tr>
            </thead>';
    @endphp

    <table>
        {!! $header !!}{{--Mostrar encabezado--}}
        <tbody>
            @php
                $neto = 0;
                $venta = 0;
                $propina = 0;
                $iva = 0;
                $adv = 0;
                $cet = 0;
                $total = 0;
            @endphp
            @foreach ($caja as $c)
                @php
                    $cneto = 0;
                    $cventa = 0;
                    $cpropina = 0;
                    $civa = 0;
                    $cadv = 0;
                    $ccet = 0;
                    $ctotal = 0;
                @endphp
                
                @php $contadorRegistros += 1; @endphp
                <tr>
                    <th scope="row" colspan="14" class="text-center">
                        -- {{ $c->caja }} --
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
                            $radv = 0;
                            $rcet = 0;
                            $rtotal = 0;
                        @endphp

                        @php $contadorRegistros += 1; @endphp
                        <tr>
                            <th scope="row" colspan="14">
                                {{ $r->rubro }}
                            </th>
                        </tr>
                        @foreach ($dataFormCaja as $d)
                            {{--La validación de la cantidad de registros a mostrar por cada página, se debe realizar en el foreach más interno--}}
                            @if(intval($contadorRegistros % $cantRegistros) === 0 && $contadorRegistros != 0){{--Si ya pasaron los registros necesarios y no es el primer registro con indice 0--}}
                                
                                {{--Se comienza cerrando etiquetas de abajo hacia arriba--}}
                                </tbody>{{--Se cierra el tbody---}}
                                </table>{{--Se cierra la tabla---}}
                                </main>{{--Se cierra el contenedor actual---}}
                                <div class="page-number">Página {{ $pageCount }} de {{ $totalPages }}</div> {{-- Mostrar el número de página --}}
                                <div class="page-break"></div>{{--Se agrega un salto de pagina--}}
                                <main>{{--Y se abre un contenedor nuevo--}}

                                <table>{{--Se abre una nueva tabla con todo y sus encabezados---}}
                                    {!! $header !!}{{--Mostrar encabezado--}}
                                <tbody>{{--Se abre el tbody y se continua con los tr de abajo---}}

                                @php $pageCount++; @endphp {{-- Incrementar contador de páginas --}}
                            @endif
                            
                            @php
                                $dneto = round($d->neto, 2);
                                $dventa = round($d->cantidad * $d->neto, 2);
                                $dpropina = round($d->cantidad * $d->propina, 2);
                                $diva = round($d->cantidad * $d->iva, 2);
                                $dadv = round($d->cantidad * $d->advalorem, 2);
                                $dcet = round($d->cantidad * $d->cesc, 2);
                                $dtotal = round($d->cantidad * $d->total, 2);

                                $rneto += $dneto;
                                $rventa += $dventa;
                                $rpropina += $dpropina;
                                $riva += $diva;
                                $radv += $dadv;
                                $rcet += $dcet;
                                $rtotal += $dtotal;
                            @endphp
                            <tr>
                                <td class="text-center">{{ $d->cuenta }}</td>
                                <td class="text-center">
                                    <span>{{ $d->tipo_registros == 1 ? 'Orden' : ($d->tipo_registros == 2 ? 'Estadía' : ($d->tipo_registros == 3 ? 'Comanda' : 'No registrada')) }}</span>
                                    <br><small>Nº {{ $d->numero_control }}</small>
                                </td>
                                <td class="text-center">{{ $d->fecha }}</td>
                                <td class="text-truncate">{{ $d->concepto }}</td>
                                <td class="text-center">{{ $d->cantidad }}</td>
                                <td class="text-right">${{ number_format($dneto, 2) }}</td>
                                <td class="text-right">${{ number_format($dventa, 2) }}</td>
                                <td class="text-right">${{ number_format($dpropina, 2) }}</td>
                                <td class="text-right">${{ number_format($diva, 2) }}</td>
                                <td class="text-right">${{ number_format($dadv, 2) }}</td>
                                <td class="text-right">${{ number_format($dcet, 2) }}</td>
                                <td class="text-right">${{ number_format($dtotal, 2) }}</td>
                                <td class="text-center">{{ $d->correlativo }}</td>
                                <td class="text-center">{{ $d->numero_control }}</td>
                            </tr>

                            @php $contadorRegistros++; @endphp
                        @endforeach

                        <tr>
                            <td colspan="5">TOTAL {{ $r->rubro }}</td>
                            <td class="text-right">${{ number_format($rneto, 2) }}</td>
                            <td class="text-right">${{ number_format($rventa, 2) }}</td>
                            <td class="text-right">${{ number_format($rpropina, 2) }}</td>
                            <td class="text-right">${{ number_format($riva, 2) }}</td>
                            <td class="text-right">${{ number_format($radv, 2) }}</td>
                            <td class="text-right">${{ number_format($rcet, 2) }}</td>
                            <td class="text-right">${{ number_format($rtotal, 2) }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                        @php
                            $cneto += $rneto;
                            $cventa += $rventa;
                            $cpropina += $rpropina;
                            $civa += $riva;
                            $cadv += $radv;
                            $ccet += $rcet;
                            $ctotal += $rtotal;
                        @endphp
                    @endif
                @endforeach

                <tr>
                    <th colspan="5">TOTAL {{ $c->caja }}</th>
                    <td class="text-right">${{ number_format($cneto, 2) }}</td>
                    <td class="text-right">${{ number_format($cventa, 2) }}</td>
                    <td class="text-right">${{ number_format($cpropina, 2) }}</td>
                    <td class="text-right">${{ number_format($civa, 2) }}</td>
                    <td class="text-right">${{ number_format($cadv, 2) }}</td>
                    <td class="text-right">${{ number_format($ccet, 2) }}</td>
                    <td class="text-right">${{ number_format($ctotal, 2) }}</td>
                    <td></td>
                    <td></td>
                </tr>
                @php
                    $neto += $cneto;
                    $venta += $cventa;
                    $propina += $cpropina;
                    $iva += $civa;
                    $adv += $cadv;
                    $cet += $ccet;
                    $total += $ctotal;
                @endphp
            @endforeach

            <tr class="text-right">
                <td colspan="5">
                    <b>TOTAL</b>
                </td>
                <td>
                    <b>${{ number_format($neto, 2) }}</b>
                </td>
                <td>
                    <b>${{ number_format($venta, 2) }}</b>
                </td>
                <td>
                    <b>${{ number_format($propina, 2) }}</b>
                </td>
                <td>
                    <b>${{ number_format($iva, 2) }}</b>
                </td>
                <td>
                    <b>${{ number_format($adv, 2) }}</b>
                </td>
                <td>
                    <b>${{ number_format($cet, 2) }}</b>
                </td>
                <td>
                    <b>${{ number_format($total, 2) }}</b>
                </td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    
    <div class="page-number" style="margin-top: 35px;">Página {{ $pageCount }} de {{ $totalPages }}</div> {{-- Mostrar el número de página --}}
@endsection
