@extends('layouts.clientes_panel')

@section('style-content')
    <style>
        body {
            background: #80DEEA !important;
        }

        .empleado td {
            background: #B2DFDB !important;
        }

        .factura td {
            background: #E0F2F1 !important;
        }
    </style>
@endsection

@section('content_cliente')
    <div class="container">
        <div class="card-body p-2">
            <h5 class="card-title text-uppercase mb-4 fw-bold">
                Empleados: VENTAS AL CRÉDITO
            </h5>

            <div class="row">
                <div class="col-12 mb-4">
                    <form action="{{ route('clientes.empleados_search') }}" method="post">
                        @csrf
                        <div class="row align-items-end">
                            <div class="col-4">
                                <label for="fecha" class="form-label">Mes</label>
                                <input type="month" class="form-control" name="fecha" id="fecha"
                                    placeholder="Seleccione un mes" value="{{ $fecha }}" />
                            </div>
                            <div class="col-6">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" name="nombre" id="nombre" value="{{ $nombre ?? '' }}"
                                    placeholder="Buscar por nombre" />
                            </div>
                            <div class="col-2">
                                <button type="submit" class="btn btn-primary justify-self-end">Buscar</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Correlativo</th>
                                <th>Cliente</th>
                                <th>Monto crédito</th>
                                <th>Comprobantes</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clientes as $c)
                                <tr class="empleado">
                                    <td></td>
                                    <td>{{ $c->nombre }}</td>
                                    @php
                                        $monto = $data->where('clientes_id', $c->id)->sum('monto');
                                        $total = $data->where('clientes_id', $c->id)->sum('total');
                                        $numeroComprobantes = $data->where('clientes_id', $c->id)->count('id');
                                        $comprobantes = $data->where('clientes_id', $c->id);
                                    @endphp
                                    <td>${{ number_format($monto, 2) }} </td>
                                    <td>{{ $numeroComprobantes }} | ${{ number_format($total, 2) }}</td>
                                    <td>
                                        <a class="btn btn-light"
                                            href="{{ route('clientes.comprobantes_empleado_mes', ['id' => $c->cid, 'mes' => $fecha]) }}"
                                            role="button" target="_blank">
                                            Cliente
                                        </a>
                                    </td>
                                </tr>
                                @foreach ($comprobantes as $i)
                                    <tr class="factura">
                                        <td>{{ $i->correlativo }}</td>
                                        <td>{{ $i->titular }}</td>
                                        <td>${{ number_format($i->monto, 2) }}</td>
                                        <td>
                                            {{ $i->fecha }}
                                        </td>
                                        <td>
                                            @if ($i->abono != null)
                                                ${{ number_format($i->abono->monto, 2) }}
                                                <a class="btn btn-light btn-sm ms-2"
                                                    href="{{ route('abonos.container', ['id' => \Crypt::encryptString($i->abono->abonos_id)]) }}"
                                                    role="button" title="Imprimir abono" target="_blank">
                                                    <span class="mdi mdi-printer"></span> Imprimir
                                                </a>
                                            @else
                                                Sin abono registrado
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
