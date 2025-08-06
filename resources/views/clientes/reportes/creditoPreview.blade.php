@extends('layouts.clientes_panel')
@section('style-content')
    <style>
        body {
            background: #80DEEA !important;
        }

        .table th {
            white-space: nowrap;
        }

        .border-b {
            border-bottom: 2px solid #000;
        }
    </style>
@endsection
@section('content_cliente')
    <div class="container">
        <div class="card-body p-2">
            <div class="row mb-4">
                <div class="col-12 text-uppercase h3">
                    Reporte de créditos
                </div>
                <div class="col-12">
                    <span class="text-muted text-uppercase">Vista previa</span>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-4">
                    DEPARTAMENTO: {{ $depto }}
                </div>
                <div class="col-4">
                    HASTA: {{ $fecha }}
                </div>
                <div class="col-4">
                    SEGMENTO: {{ $segmento }}
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-12 table-responsive">
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless table-light align-middle">
                            <thead>
                                <tr class="border-b">
                                    <th>Codigo</th>
                                    <th>NOMBRE</th>
                                    <th class="text-end">TOTAL</th>
                                    <th class="text-end">NORMAL</th>
                                    <th class="text-end">VENCIDOS</th>
                                    <th class="text-end">DE 1 A 30</th>
                                    <th class="text-end">DE 31 A 60</th>
                                    <th class="text-end">DE 61 A 90</th>
                                    <th class="text-end">DE 91 A 120</th>
                                    <th class="text-end">MAS DE 120</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                @foreach ($data as $d)
                                    <tr class="table-light">
                                        <td scope="row">{{ $d->clientes_id }}</td>
                                        <td scope="row">{{ $d->cliente_nombre }}</td>
                                        <td class="text-end">${{ number_format($d->total_monto, 2) }}</td>
                                        <td class="text-end">${{ number_format($d->monto_tiempo, 2) }}</td>
                                        <td class="text-end">${{ number_format($d->monto_vencido, 2) }}</td>
                                        <td class="text-end">${{ number_format($d->monto_30, 2) }}</td>
                                        <td class="text-end">${{ number_format($d->monto_60, 2) }}</td>
                                        <td class="text-end">${{ number_format($d->monto_90, 2) }}</td>
                                        <td class="text-end">${{ number_format($d->monto_120, 2) }}</td>
                                        <td class="text-end">${{ number_format($d->monto_superior, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <th scope="row" colspan="2">TOTAL</th>

                                <th class="text-end">${{ number_format($data->sum('total_monto'), 2) }}</th>
                                <th class="text-end">${{ number_format($data->sum('monto_tiempo'), 2) }}</th>
                                <th class="text-end">${{ number_format($data->sum('monto_vencido'), 2) }}</th>
                                <th class="text-end">${{ number_format($data->sum('monto_30'), 2) }}</th>
                                <th class="text-end">${{ number_format($data->sum('monto_60'), 2) }}</th>
                                <th class="text-end">${{ number_format($data->sum('monto_90'), 2) }}</th>
                                <th class="text-end">${{ number_format($data->sum('monto_120'), 2) }}</th>
                                <th class="text-end">${{ number_format($data->sum('monto_superior'), 2) }}</th>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
