@extends('layouts.panel_reportes')

@section('panel_reportes')
    <div id="appVentaByRubros" class="container">
        <div class="card-body p-2">
            <div class="row mb-2">
                <div class="col-12 text-uppercase h3">
                    Vista previa reporte de créditos a empleados
                </div>
                <div class="col-12 my-2">
                    <a class="btn btn-light" href="{{ route('clientes.reporteCreditoEmpleadosForm') }}" role="button">Volver</a>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Correlativo</th>
                                <th>Cliente</th>
                                <th>Monto crédito</th>
                                <th>Nº de Abono</th>
                                <th>Abonos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalAbonos = 0;
                            @endphp
                            @foreach($clientes as $c)
                                @php
                                    $monto = $data->where('clientes_id', $c->id)->sum('monto');
                                    $total = $data->where('clientes_id', $c->id)->sum('total');
                                    $numeroComprobantes = $data->where('clientes_id', $c->id)->count('id');
                                    $comprobantes = $data->where('clientes_id', $c->id);
                                @endphp

                                @foreach($comprobantes as $i)
                                    <tr class="factura">
                                        <td>{{ $i->fecha }}</td>
                                        <td>{{ $i->correlativo }}</td>
                                        <td>{{ $i->titular }}</td>
                                        <td>${{ number_format($i->monto, 2) }}</td>
                                        <td>{{ $i->abono != null ? $i->abono->abonos_id : '' }}</td>
                                        <td>
                                            @if($i->abono != null && isset($i->abono->abonos_id) && $i->abono->abonos_id)
                                                {{ $numeroComprobantes }} | ${{ number_format($i->total, 2) }} · {{ $i->abono->users->name ?? '' }}
                                                @php
                                                    $totalAbonos += $i->monto;
                                                @endphp
                                            @else
                                                $0.00
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3">TOTAL</td>
                                <td colspan="2">${{ number_format($data->sum('monto'), 2) }}</td>
                                <td>${{ number_format($totalAbonos, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
