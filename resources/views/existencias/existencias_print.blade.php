@extends('layouts.bodegaprint')

@section('style')
    <style>
        /* Estilos generales... */
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
            text-align: left;
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

        .table th,
        .table td {
            padding: 8px;
        }

        .producto {
            background-color: #343a40;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 14px;
        }

        .totals-row {
            font-weight: bold;

        }

        .table th:first-child,
        .table td:first-child {
            width: 10%;
        }

        .table th,
        .table td {
            width: 14%;
        }

        .totals-table {
            border-collapse: collapse;
            width: 100%;
        }

        .totals-row {
            display: table-row;
            border: 1px solid black;
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

        .proximovencer {
            color: #bd4e32;
        }

        .estado-lote {
            word-wrap: break-word;
            white-space: normal;
        }
    </style>
@endsection

@section('titulo')
    <div class="titulo">
        REPORTE DE EXISTENCIA DE PRODUCTO
    </div>
@endsection

@section('content')
    <div class="table-responsive">
        @php
            $Producto = null;
            $uniqueLotes = [];
        @endphp

        @foreach ($reporteExistencias as $e)
            @if ($Producto != $e->productosExistencias->nombre)
                @if ($Producto !== null)
                    <tr>
                        <td colspan="2"><b>Totales</b></td>
                        <td>{{ count($uniqueLotes) }}</td>
                        <td>{{ array_sum($uniqueLotes) }}</td>
                        <td>{{ number_format(array_sum($e['unitario']) / count($e['unitario']), 2) }}</td>
                        <td>{{ array_sum($e['total']) }}</td>
                    </tr>
                    </tbody>
                    </table>
                @endif

                @php
                    $Producto = $e->productosExistencias->nombre;
                    $uniqueLotes = [];
                    $unitario = [];
                    $total = [];
                @endphp

                <table class="table table-inverse totals-table">
                    <thead>
                        <tr colspan="12">
                            <th colspan="2">
                                Codigo: 00000{{ $e->productosExistencias->id }}
                            </th>
                            <th>
                                {{ $Producto }}
                            </th>
                            <th>
                                {{ $e->productosExistencias->categoria->categoria }}
                            </th>
                            <th>Minimos:{{ $e->productosExistencias->minimos }}</th>
                            <th>Maximos:{{ $e->productosExistencias->maximos }}</th>
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
            @endif

            <tr>
                <td>{{ $e->lote }}</td>
                <td>{{ $e->existencia }}</td>
                <td>${{ $e->precio_costo }}</td>
                <td>${{ $e->existencia * $e->precio_costo }}</td>
                @php
                    $fechaIngreso = $e->ingreso;
                    $fechaVencimiento = $e->vencimiento;
                    $diasParaVencer = Carbon\Carbon::parse($fechaVencimiento)->diffInDays(Carbon\Carbon::now());

                @endphp

                <td
                    class="{{ is_null($fechaVencimiento) ? 'lote-disponible' : ($fechaIngreso >= $fechaVencimiento ? 'lote-vencido' : ($diasParaVencer <= 15 ? 'proximovencer' : 'fecha-vigente')) }}">
                    {{ $fechaVencimiento ? $fechaVencimiento : 'No vence' }}
                </td>

                <td>{{ $e->ingreso }}</td>
                <td>{{ $e->user_crea }}</td>

                @php
                    $fechaIngreso = $e->ingreso;
                    $fechaVencimiento = $e->vencimiento;
                    $diasParaVencer = now()->diffInDays($fechaVencimiento);
                    $claseFecha =
                        $diasParaVencer <= 15
                            ? 'proximovencer'
                            : ($fechaIngreso >= $fechaVencimiento
                                ? 'lote-vencido'
                                : 'fecha-vigente');
                @endphp

                <td
                    class="{{ is_null($fechaVencimiento) ? 'lote-disponible' : ($diasParaVencer <= 15 ? 'proximovencer' : ($fechaIngreso >= $fechaVencimiento ? 'lote-vencido' : 'fecha-vigente')) }}">
                    <span
                        class="{{ is_null($fechaVencimiento) ? 'No vence' : ($fechaIngreso >= $fechaVencimiento ? 'lote-vencido' : 'No vence') }}">
                        <span class="estado-lote">
                            {{ is_null($fechaVencimiento) ? 'No vence' : ($fechaIngreso >= $fechaVencimiento ? 'Vencido' : ($diasParaVencer <= 15 ? 'Próximo a vencer' : 'Vigente')) }}
                        </span>
                    </span>
                </td>


            </tr>

            @php
                $uniqueLotes[] = $e->lote;
                $uniqueExistencia[] = $e->existencia;
                $unitario[] = $e->precio_costo;
                $total[] = $e->existencia * $e->precio_costo;
            @endphp

            @if ($loop->last)
                <tr class="totals-row">

                    <td class="totals-cell">{{ count($uniqueLotes) }}</td>
                    <td class="totals-cell">{{ array_sum($uniqueExistencia) }}</td>
                    <td class="totals-cell">${{ number_format(array_sum($unitario) / count($unitario), 2) }}</td>
                    <td class="totals-cell">${{ array_sum($total) }}</td>
                    <td class="totals-cell"></td>
                    <td class="totals-cell"></td>
                    <td class="totals-cell"></td>
                    <td class="totals-cell"><b>Totales</b></td>

                </tr>
                </tbody>
                </table>
            @endif
        @endforeach

    </div>
@endsection
