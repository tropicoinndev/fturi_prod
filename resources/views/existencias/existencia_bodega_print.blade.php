@extends('layouts.bodegaprint')

@section('style')
    <style>
        /* Estilos optimizados */
        * {
            text-transform: uppercase;
            font-size: 10pt;
        }

        table.main-table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
        }

        table.main-table th,
        table.main-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: right;
        }

        table.main-table th {
            background-color: #f5f5f5;
        }

        table.main-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            border-radius: 5px;
            overflow: hidden;
        }

        .producto {
            background-color: #343a40;
            color: white;
            padding: 10px;
        }

        .table thead th {
            text-align: left;
        }

        .table tbody td {
            text-align: left;
        }

        .producto {
            background-color: #343a40;
            color: white;
            padding: 15px;
            text-align: center;
        }

        .table th:first-child,
        .table td:first-child {
            width: 10%;
        }

        .table th,
        .table td {
            width: 14%;
            padding: 8px;
        }

        .totals-table {
            border-collapse: collapse;
            width: 100%;
        }

        .totals-row {
            display: table-row;
            border-top: 1px solid black;
        }

        .totals-cell {
            display: table-cell;
            padding: 8px;
        }

        .lote-vencido {
            color: red;
        }

        .lote-disponible {
            color: blue;
        }

        .fecha-vigente {
            color: green;
        }

        .totals-row .totals-cell {
            text-align: left;
        }

        .table th,
        .table td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .titulo {
            font-size: 14pt;
        }

        .subtitulo {
            font-size: 12pt;
        }

        .table th.normal-cell {
            font-weight: normal;
        }

        .proximovencer {
            color: #bd4e32;
        }

        .estado-lote {
            word-wrap: break-word;
            white-space: normal;
        }

        .nombre {
            width: 2cm;
        }
    </style>
@endsection

@section('titulo')
    <div class="titulo">
        TURISTICAS DE ORIENTE S.A. DE C.V.
    </div>
@endsection

@section('subtitulo')
    <div class="subtitulo">
        REPORTE DE EXISTENCIAS EN BODEGA
    </div>
@endsection

@section('content')
    <div class="table-responsive">
        @php
            $productos = [];
        @endphp

        @foreach ($reporteExistenciasBodega as $e)
            @php
                $productoId = $e->productosExistencias->id;
                if (!isset($productos[$productoId])) {
                    $productos[$productoId] = [
                        'producto' => $e->productosExistencias,
                        'bodega' => $e->bodega_existe,
                        'lotes' => [],
                        'totalExistencia' => 0,
                        'totalUnitario' => 0,
                        'total' => 0,
                    ];
                }

                // Agregamos el lote a los lotes del producto
                $productos[$productoId]['lotes'][] = [
                    'existencia' => $e->existencia,
                    'precio_costo' => $e->precio_costo,
                    'lote' => $e->lote,
                    'vencimiento' => $e->vencimiento,
                    'ingreso' => $e->ingreso,
                    'usuario_ingreso' => $e->user_crea,
                    'bodega' => $e->bodega_existe,
                ];

                // Sumamos las existencias y el costo total del producto
                $productos[$productoId]['totalExistencia'] += $e->existencia;
                $productos[$productoId]['totalUnitario'] += $e->precio_costo;
                $productos[$productoId]['total'] += $e->existencia * $e->precio_costo;
            @endphp
        @endforeach

        @foreach ($productos as $producto)
            <table class="table table-inverse totals-table">
                <thead>
                    <tr colspan="12">
                        <th colspan="2">
                            Codigo: 00000{{ $producto['producto']->id }}
                        </th>
                        <th class="nombre" colspan="3">
                            {{ $producto['producto']->nombre }}
                        </th>
                        <th class="normal-cell">
                            {{ $producto['producto']->categoria->categoria }}
                        </th>

                        <th>Bodega:{{ $producto['bodega'] }}</th>
                    </tr>
                    <tr>
                        <th>Lote</th>
                        <th>Existencia</th>
                        <th>Unitario</th>
                        <th>Total</th>
                        <th>Vencimiento</th>
                        <th>Fecha de ingreso</th>
                        <th>Usuario ingreso</th>
                        <th>Estado lote</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($producto['lotes'] as $lote)
                        @php
                            $fechaIngreso = $lote['ingreso'];
                            $fechaVencimiento = $lote['vencimiento'];
                            $diasParaVencer = Carbon\Carbon::parse($fechaVencimiento)->diffInDays(Carbon\Carbon::now());
                        @endphp

                        <tr>
                            <td>{{ $lote['lote'] }}</td>
                            <td>{{ $lote['existencia'] }}</td>
                            <td>${{ number_format($lote['precio_costo'], 2) }}</td>
                            <td>${{ number_format($lote['existencia'] * $lote['precio_costo'], 2) }}</td>
                            <td
                                class="{{ is_null($fechaVencimiento) ? 'lote-disponible' : ($diasParaVencer <= 15 ? 'proximovencer' : ($fechaIngreso >= $fechaVencimiento ? 'lote-vencido' : 'fecha-vigente')) }}">
                                {{ $fechaVencimiento ? $fechaVencimiento : 'No vence' }}
                            </td>

                            <td>{{ $lote['ingreso'] }}</td>
                            <td>{{ $lote['usuario_ingreso'] }}</td>
                            <td
                                class="{{ is_null($fechaVencimiento) ? 'lote-disponible' : ($diasParaVencer <= 15 ? 'proximovencer' : ($fechaIngreso >= $fechaVencimiento ? 'lote-vencido' : 'fecha-vigente')) }}">
                                <span class="estado-lote">
                                    {{ is_null($fechaVencimiento) ? 'No vence' : ($diasParaVencer <= 15 ? 'Próximo a vencer' : ($fechaIngreso >= $fechaVencimiento ? 'Vencido' : 'Vigente')) }}
                                </span>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
                <tr class="totals-row">
                    <td class="totals-cell">{{ count($producto['lotes']) }}</td>
                    <td class="totals-cell">{{ $producto['totalExistencia'] }}</td>
                    <td class="totals-cell">${{ number_format($producto['totalUnitario'] / count($producto['lotes']), 2) }}
                    </td>
                    <td class="totals-cell">${{ number_format($producto['total'], 2) }}</td>
                    <td class="totals-cell"></td>
                    <td class="totals-cell"></td>
                    <td class="totals-cell"></td>
                    <td class="totals-cell"><b>Totales</b></td>
                </tr>
            </table>
        @endforeach
    </div>
@endsection
