@extends('layouts.panel_reportes')
@section('css-panel_reportes')
    <style>
        .bt-1 {
            border-top: 1px solid #000;
        }

        .table-white,
        .table-white tr,
        .table-white tr td,
        .table-white tr th {
            background: #FFF;
        }

        @media print {
            .botones {
                display: none;
            }

            #comandasActivas {
                width: 100%;
                margin: 0px;
                padding: 0px;
            }
        }
    </style>
@endsection

@section('panel_reportes')
    <div id="comandasActivas" class="container-fluid">
        <div class="card-body p-1">
            <div class="row mb-2">
                <div class="col-12 text-uppercase h3">
                    Vista previa comandas activas
                </div>
                <div class="col-12 my-2 botones">
                    <a class="btn btn-light" href="{{ route('cajas.comandasActivas') }}" role="button">Volver</a>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12">
                    <table class="table table-borderless table-hover table-white">

                        <tbody>

                            @foreach ($caja as $c)
                                @php
                                    $total = 0;
                                    $dataCaja = $data->where('cajas_id', $c->id);
                                    $comandas = $dataCaja->count();
                                @endphp
                                <tr>
                                    <th colspan="5" class="h4 text-center text-uppercase">
                                        {{ $c->caja }}
                                    </th>
                                </tr>
                                @forelse ($dataCaja as $d)
                                    @php
                                        $ctotal = 0;
                                    @endphp
                                    <tr class="bt-1">
                                        <th scope="col">{{ $d->fecha }}</th>
                                        <th scope="col">{{ $d->id }}</th>
                                        <th scope="col" colspan="3">
                                            {{ $d->clientes_id != null ? $d->clientes->nombre : $d->titular ?? 'No se agrego cliente o titular' }}
                                            @if ($d->clientes_id != null && $d->clientes->credito)
                                                <span class="badge bg-secondary">Cliente con crédito</span>
                                            @endif

                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Usuario</th>
                                        <th class="text-muted">Cantidad</th>
                                        <th class="text-muted">Concepto</th>
                                        <th class="text-muted">Precio</th>
                                        <th class="text-muted">Total</th>
                                    </tr>

                                    @foreach ($d->detalles_comandas as $dc)
                                        @php
                                            $totalConcepto = round($dc->precio * $dc->cantidad, 2);
                                            $ctotal += $totalConcepto;
                                        @endphp
                                        <tr>
                                            <td>
                                                {{ $dc->user_comanda->user }}
                                            </td>
                                            <td>
                                                {{ $dc->cantidad }}
                                            </td>
                                            <td>
                                                {{ $dc->precios->detalle }}
                                            </td>
                                            <td>
                                                ${{ number_format($dc->precio, 2) }}
                                            </td>
                                            <td>
                                                ${{ number_format($totalConcepto, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    @php
                                        $total += $ctotal;
                                    @endphp
                                    <tr>
                                        <th scope="col" colspan="4">Total comanda Nº {{ $d->id }}</th>
                                        <th scope="col">${{ number_format($ctotal, 2) }}</th>
                                    </tr>
                                @empty
                                    <tr>
                                        <td scope="col" colspan="5" class="text-center">
                                            No se encontraron comandas activas
                                        </td>
                                    </tr>
                                @endforelse
                                @if ($comandas > 0)
                                    <tr>
                                        <th scope="col" colspan="4" class="text-uppercase text-bg-light">
                                            Total {{ $c->caja }}
                                        </th>
                                        <th scope="col" class="text-bg-light">
                                            ${{ number_format($total, 2) }}
                                        </th>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
