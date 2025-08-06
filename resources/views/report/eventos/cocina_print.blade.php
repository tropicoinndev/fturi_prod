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
        Reporte de produccion cocina/bar
    </div>
@endsection
@section('content')
    <div>


        <div class="row">
            <div class="col-4">
                <span class="b">Sucursal:</span>
                {{ $caja->sucursales->sucursal }}
            </div>
            <div class="col-4">
                <span class="b"> Caja:</span>
                {{ $caja->caja }}
            </div>
            <div class="col-4">
                <span class="b">Turno:</span>
                {{ $turno->opcion->turno ?? '' }} · {{ $turno->fecha ?? ''}}
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <span class="b">Apertura:</span>
                {{ $turno->apertura ?? ''}} · {{ $turno->uapertura->name ?? '' }}
            </div>

            <div class="col-4">
                <span class="b">Cierre</span>
                {{ $turno->cierre ?? ''}} ·
                @if (isset($turno) && $turno->cierre_users_id > 0)
                    {{ $turno->ucierre->name }}
                @else
                    Aun sin cierre
                @endif
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
                            <td class="tb-title ">CONCEPTO</td>
                            <td class="tb-title fp-title">Producto.</td>
                            <td class="tb-title fp-title">Cantidad.</td>
                            <td class="tb-title fp-title">Precio</td>
                            <td class="tb-title fp-title">CMDA.</td>
                            <td class="tb-title fp-title">Usuario</td>
                            <td class="tb-title fp-title">Fecha</td>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categorias as $cat)
                            <tr>
                                <td colspan="8" class="text-center">
                                    {{ $cat->categoria }}
                                </td>
                            </tr>
                            @foreach ($comandas->where('categorias_precios_id', $cat->id) as $c)
                                <tr>
                                    <td>
                                        {{ $loop->index + 1 }}
                                    </td>
                                    <td>
                                        {{ $c->detalle }}
                                    </td>
                                    <td>
                                        @if($c->precios->detalle_producto->isEmpty())
                                        Produccion
                                    @else
                                        @php $first = true @endphp
                                        @foreach($c->precios->detalle_producto as $p)
                                            @if (!$first)
                                                ,
                                            @else
                                                @php $first = false @endphp
                                            @endif
                                            {{ $p->productos->nombre ?? '' }}
                                        @endforeach
                                    @endif
                                    </td>
                                    <td class="fp-title">
                                        {{ $c->cantidad }}
                                    </td>
                                    <td class="fp-title">
                                        ${{ number_format($c->precio, 2) }}
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
                        @endforeach

                    </tbody>

                </table>
            </div>

        </div>


    </div>
@endsection
