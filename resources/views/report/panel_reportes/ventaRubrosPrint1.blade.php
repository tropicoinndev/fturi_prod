@extends('layouts.print')

@section('style')
    <style>
        * {
            /*text-transform: uppercase;*/
            font-size: 9pt;
        }

        .table {
            text-transform: uppercase;
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
            width: {{ 21.94 / 7 }}cm;
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
    </style>
@endsection

@section('titulo')
    <div class="titulo">
        Reporte de Ventas por Rubro
    </div>
@endsection

@section('content')
    <div>
        <div class="row">
            <div class="col-4">
                <span class="b">Del:</span> {{ $inicio }}
            </div>
            <div class="col-4">
                <span class="b">Al:</span> {{ $fin }}
            </div>
            <div class="col-4">
                <span class="b">Turno(s):</span>
                @switch($turno_selected)
                    @case(1)
                        Turno Mañana
                    @break
                    @case(2)
                        Turno Tarde
                    @break
                    @case(3)
                        Turno Noche
                    @break
                    @default
                        Todos Los Turnos
                @endswitch
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <table class="table table-striped" border="0" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <td>Com/Ord</td>
                            <td>Fecha</td>
                            <td>Producto</td>
                            <td>Cantidad</td>
                            <td>Precio</td>
                            <td>Venta</td>
                            <td>Propina</td>
                            <td>IVA</td>
                            <td>CET</td>
                            <td>Total</td>
                            <td>Factura</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detalleComprobantes as $dc)
                            <tr>
                                <td>{{ $dc->comprobantes_id }}</td>
                                <td>{{ $dc->comprobantes->fecha }}</td>
                                <td>{{ $dc->concepto }}</td>
                                <td>{{ $dc->cantidad }}</td>
                                <td>{{ $dc->neto }}</td>
                                <td>{{ $dc->neto * $dc->cantidad }}</td>
                                <td>{{ $dc->propina }}</td>
                                <td>{{ $dc->iva }}</td>
                                <td>{{ $dc->cesc }}</td>
                                <td>{{ $dc->total }}</td>
                                <td>{{ $dc->comprobantes->tipoComprobantes->token === 7002 ? 'F' : 'C' }} {{ $dc->comprobantes->correlativo }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <th colspan="5" class="text-center">Subtotal de 1 - {{ $rubro->rubro ?? 'Todos los Rubros' }} -{{ $caja->caja ?? 'Todas las Cajas' }}-</th>
                            <th>{{ collect($detalleComprobantes)->sum('neto') * collect($detalleComprobantes)->sum('cantidad') }}</th>
                            <th>{{ collect($detalleComprobantes)->sum('propina') }}</th>
                            <th>{{ collect($detalleComprobantes)->sum('iva') }}</th>
                            <th>{{ collect($detalleComprobantes)->sum('cesc') }}</th>
                            <th>{{ collect($detalleComprobantes)->sum('total') }}</th>
                            <th></th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
