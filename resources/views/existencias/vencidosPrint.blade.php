@extends('layouts.print_bootstrap')

@section('content')
    <style>
        body,
        .table tr th,
        .table tr td {
            background: white;
        }
    </style>
    <div id="appExistencias" class="p-4">
        <div class="row mb-3">
            <div class="col-12 text-uppercase h3 text-center">
                {{ env('empresa') }}
            </div>
            <div class="col-12 text-uppercase h3 text-center fw-bolder">
                Reporte de vencimientos
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12">
                PRODUCTOS VENCIDOS ({{ date('Y-m-d') }})
            </div>
            <div class="col-12 ">
                <table class="table">
                    <thead class="thead-inverse">
                        <tr>
                            <th>BODEGA</th>
                            <th>LOTE</th>
                            <th>FECHA VEN.</th>
                            <th>TIEMPO VEN.</th>
                            <th>PRODUCTO</th>
                            <th>CANTIDAD</th>
                            <th>PRECIO COSTO</th>
                            <th>TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalVen = 0;
                        @endphp
                        @forelse ($vencidos as $e)
                            @php
                                $total = round($e->existencia * $e->precio_costo, 2);
                                $totalVen += $total;
                            @endphp
                            <tr>
                                <td scope="row" class="text-uppercase">
                                    {{ $e->bodegasExistencias->bodega }}
                                </td>
                                <td scope="row">
                                    {{ $e->id }}
                                </td>
                                <td scope="row">
                                    {{ $e->vencimiento }}
                                </td>
                                <td scope="row">
                                    <small>
                                        {{ $e->ven }}
                                    </small>
                                </td>
                                <td scope="row">
                                    {{ $e->productosExistencias->nombre }}
                                </td>
                                <td scope="row">
                                    {{ $e->existencia }}
                                </td>
                                <td scope="row">
                                    ${{ number_format($e->precio_costo, 2) }}
                                </td>
                                <td scope="row">
                                    ${{ number_format($total, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-uppercase">No se encontraron vencimientos</td>
                            </tr>
                        @endforelse

                        @if ($totalVen > 0)
                            <tr>
                                <td colspan="7">TOTAL</td>
                                <td>${{ number_format($totalVen, 2) }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                PRODUCTOS PRÓXIMOS A VENCER ({{ $prox }})
            </div>
            <div class="col-12">
                <table class="table ">
                    <thead class="thead-inverse">
                        <tr>
                            <th>BODEGA</th>
                            <th>LOTE</th>
                            <th>FECHA VEN.</th>
                            <th>TIEMPO VEN.</th>
                            <th>PRODUCTO</th>
                            <th>CANTIDAD</th>
                            <th>PRECIO COSTO</th>
                            <th>TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalP = 0;
                        @endphp
                        @forelse ($proximos as $p)
                            @php
                                $total = round($p->existencia * $p->precio_costo, 2);
                                $totalP += $total;
                            @endphp
                            <tr>
                                <td scope="row" class="text-uppercase">
                                    {{ $p->bodegasExistencias->bodega }}
                                </td>
                                <td scope="row">
                                    {{ $p->id }}
                                </td>
                                <td scope="row">
                                    {{ $p->vencimiento }}
                                </td>
                                <td scope="row">
                                    <small>
                                        {{ $p->ven }}
                                    </small>
                                </td>
                                <td scope="row">
                                    {{ $p->productosExistencias->nombre }}
                                </td>
                                <td scope="row">
                                    {{ $p->existencia }}
                                </td>
                                <td scope="row">
                                    ${{ number_format($p->precio_costo, 2) }}
                                </td>
                                <td scope="row">
                                    ${{ number_format($total, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-uppercase">No se encontraron vencimientos</td>
                            </tr>
                        @endforelse
                        @if ($totalP > 0)
                            <tr>
                                <td colspan="7">TOTAL</td>
                                <td>${{ number_format($totalP, 2) }}</td>

                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
