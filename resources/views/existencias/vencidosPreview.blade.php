@extends('layouts.bodegas')

@section('panel_bodega')
    <div id="appExistencias" class="p-4">
        <div class="row mb-3">
            <div class="col-12 text-uppercase h3">
                Reporte de vencimientos
            </div>
            <div class="col-12">
                <a class="btn btn-light" href="{{ route('existencias.reporte_vencimiento') }}" role="button">Volver</a>
            </div>

        </div>


        <div class="row mb-4">
            <div class="col-12 text-danger h4">
                PRODUCTOS VENCIDOS ({{ date('Y-m-d') }})
            </div>
            <div class="col-12 ">
                <table class="table table-striped table-inverse">
                    <thead class="thead-inverse">
                        <tr>
                            <th>Lote</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio costo</th>
                            <th>Total</th>
                            <th>Vencimiento</th>
                            <th>Tiempo de vencimiento</th>
                            <th>Bodega</th>
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
                                <td scope="row">{{ $e->id }}</td>
                                <td scope="row">{{ $e->productosExistencias->nombre }}</td>
                                <td scope="row">{{ $e->existencia }}</td>
                                <td scope="row">${{ number_format($e->precio_costo, 2) }}</td>
                                <td scope="row">${{ number_format($total, 2) }}</td>
                                <td scope="row">
                                    {{ $e->vencimiento }}
                                </td>
                                <td scope="row">
                                    {{ $e->ven }}
                                </td>
                                <td scope="row">
                                    {{ $e->bodegasExistencias->bodega }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-uppercase">No se encontraron vencimientos</td>
                            </tr>
                        @endforelse

                        @if ($totalVen > 0)
                            <tr>
                                <td colspan="4">TOTAL</td>
                                <td>${{ number_format($totalVen, 2) }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-warning h4">
                PRODUCTOS PRÓXIMOS A VENCER ({{ $prox }})
            </div>
            <div class="col-12">
                <table class="table table-striped table-inverse">
                    <thead class="thead-inverse">
                        <tr>
                            <th>Lote</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio costo</th>
                            <th>Total</th>
                            <th>Vencimiento</th>
                            <th>Tiempo de vencimiento</th>
                            <th>Bodega</th>
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
                                <td scope="row">{{ $p->id }}</td>
                                <td scope="row">{{ $p->productosExistencias->nombre }}</td>
                                <td scope="row">{{ $p->existencia }}</td>
                                <td scope="row">${{ number_format($p->precio_costo, 2) }}</td>
                                <td scope="row">${{ number_format($total, 2) }}</td>
                                <td scope="row">
                                    {{ $p->vencimiento }}
                                </td>
                                <td scope="row">
                                    {{ $p->ven }}
                                </td>
                                <td scope="row">
                                    {{ $p->bodegasExistencias->bodega }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-uppercase">No se encontraron vencimientos</td>
                            </tr>
                        @endforelse
                        @if ($totalP > 0)
                            <tr>
                                <td colspan="4">TOTAL</td>
                                <td>${{ number_format($totalP, 2) }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
