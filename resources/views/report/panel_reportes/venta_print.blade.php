@extends('layouts.print')
@section('style')
    <style>
        * {
            text-transform: uppercase;
            font-size: 9pt;
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
        Reporte de venta
    </div>
@endsection
@section('content')
    <div>
        <div class="row">
            <div class="col-4">
                <span class="b">Sucursal:</span>
                {{ $caja->sucursales->sucursal ?? 'TODAS LAS SUCURSALES' }}
            </div>
            <div class="col-4">
                <span class="b"> Caja:</span>
                {{ $caja->caja ?? 'TODAS LAS CAJAS' }}
            </div>
            <div class="col-4">

            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <span class="b">Del:</span>
                {{ $inicio }}
            </div>

            <div class="col-4">
                <span class="b">Al:</span>
                {{ $fin }}
            </div>
            <div class="col-4">
                <span class="b">Cantidad: </span>
                {{ $comandas->count() }} productos comandados
            </div>

        </div>

        <div class="row">
            <div class="col-12">
                <table class="table table-striped" border="0" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <td class="tb-title">#</td>
                            <td class="tb-title w-15">CONCEPTO</td>
                            <td class="tb-title fp-title">Cantidad.</td>
                            <td class="tb-title fp-title">TOTAL</td>
                            <td class="tb-title fp-title">CMDA.</td>
                            <td class="tb-title fp-title">Usuario</td>
                            <td class="tb-title fp-title">Fecha</td>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($turno as $t)
                            <tr>
                                <td colspan="7">

                                    <span style="font-weight: bold;">
                                        {{ $t->opcion->turno }}
                                    </span>
                                    desde: {{ $t->apertura }}
                                    hasta: {{ $t->cierre ?? 'Aun abierto' }}
                                    <span style="font-weight: bold;">
                                        caja: {{ $t->cajas->caja }}
                                    </span>
                                </td>
                            </tr>
                            @foreach ($categorias as $cat)
                                @if ($comandas->where('categorias_precios_id', $cat->id)->where('turnos_id', $t->id)->count() > 0)
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            {{ $cat->categoria }}
                                        </td>
                                    </tr>
                                    @foreach ($comandas->where('categorias_precios_id', $cat->id)->where('turnos_id', $t->id) as $c)
                                        <tr>
                                            <td>
                                                {{ $loop->index + 1 }}
                                            </td>
                                            <td>
                                                {{ $c->detalle }} @if($c->observaciones) <i>· {{ $c->observaciones }}</i> @endif
                                            </td>
                                            <td class="fp-title">
                                                {{ $c->cantidad }}
                                            </td>
                                            <td class="fp-title">
                                                ${{ number_format($c->total, 2) }}
                                            </td>
                                            <td class="fp-title">
                                                Nº {{ $c->comandas_id }}
                                            </td>
                                            <td class="fp-title">
                                                {{ explode('@', $c->user_comanda->email)[0] }}
                                            </td>
                                            <td class="fp-title">
                                                {{ $c->created_at }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>
        <div class="row" style="margin-top: 25px;">
            <div class="col-12" style="font-size: 12pt;">
                Resumen venta por precios
            </div>
            <div class="col-12">

                <table class="table table-striped" border="0" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <td class="tb-title w-10">#</td>
                            <td class="tb-title w-15">CONCEPTO</td>
                            <td class="tb-title fp-title">Cantidad.</td>
                            <td class="tb-title fp-title">TOTAL</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($resumen as $rm)
                            <tr>
                                <td class="">
                                    {{ $loop->index + 1 }}
                                </td>
                                <td class="">
                                    {{ $rm->detalle }}
                                </td>
                                <td class="fp-title">
                                    {{ $rm->cantidad }}
                                </td>
                                <td class="fp-title">
                                    ${{ number_format($rm->total_precio, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
            </div>
            <div class="col-12">
                Este reporte no incluye los descuentos aplicados al momento de facturar.
            </div>
        </div>
        <div class="row" style="margin-top: 25px;">
            <div class="col-12" style="font-size: 12pt;">
                REPORTE DE DESCARGO DE INVENTARIO
            </div>
            <div class="col-12">

                <table class="table table-striped" border="0" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <td class="tb-title w-10">#</td>
                            <td class="tb-title w-15">PRODUCTOS</td>
                            <td class="tb-title fp-title">CANTIDAD</td>
                            <td class="tb-title fp-title">Origen</td>
                            <td class="tb-title fp-title">Lote</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($existencias as $e)
                            <tr>
                                <td class="">
                                    {{ $loop->index + 1 }}
                                </td>
                                <td class="">
                                    {{ $e->productos->nombre }}
                                </td>
                                <td class="fp-title">
                                    {{ $e->cantidad }}
                                </td>
                                <td class="fp-title">
                                    {{ $e->existencias->bodegas->bodega }}
                                </td>
                                <td class="fp-title">
                                    Nº {{ $e->existencias_id }} - V.{{ $e->existencias->vencimiento }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
            </div>
        </div>
        @if(count($ordenes) > 0 )
        <div class="row">
            <div class="col-12" style="font-size: 12pt;">
                REPORTE DE ORDENES DE SERVICIOS
            </div>
            <div class="col-12">

                <table class="table table-striped" border="0" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <td class="tb-title ">#</td>
                            <td class="tb-title fp-title w-10">Concepto</td>
                            <td class="tb-title fp-title">Cantidad</td>
                            <td class="tb-title fp-title">Precio</td>
                            <td class="tb-title fp-title">Nº Orden</td>
                            <td class="tb-title fp-title">Usuario</td>
                            <td class="tb-title fp-title">Fecha</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ordenes as $orden)
                            <tr>
                                <td class="">
                                    {{ $loop->index + 1 }}
                                </td>
                                <td class="">{{ $orden->servicios->servicio ?? '' }}</td>
                                <td class="fp-title">{{ $orden->cantidad }}</td>
                                <td class="fp-title">${{ number_format($orden->precio_unitario, 2) }}
                                </td>
                                <td class="fp-title">Nº {{ $orden->ordenes_id }}</td>
                                <td class="fp-title">{{ explode('@', $orden->user_detalle->email ?? '')[0] }}
                                </td>
                                <td class="fp-title">{{ $orden->created_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>

            </div>
        </div>
        @endif

    </div>
@endsection
