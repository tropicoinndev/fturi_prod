@extends('layouts.print_bootstrap')
@section('style')
    <style>
        .table tr th,
        .table tr td,
        body {
            background: #ffffff;
        }
    </style>
@endsection

@section('content')

    @if (isset($data))
        <div class="row">
            <div class="col-12 h3 mb-2">
                {{ env('empresa', 'TURÍSTICAS DE ORIENTE S.A. DE C.V.') }}
            </div>
            <div class="col-12 mb-2 h5">
                REPORTE DE SALIDAS
            </div>
            <div class="col-4 text-uppercase">
                SUCURSAL: {{ $sucursal?->sucursal ?? 'TODAS LAS SUCURSALES' }}
            </div>
            <div class="col-4 text-uppercase">
                fecha: {{ $fecha }}
            </div>
            <div class="col-12 mt-4 table-responsive">
                <table class="table table-light table-hover table-lg table-responsive-lg">
                    <thead>
                        <tr>
                            <th scope="col">Nº Registro</th>
                            <th scope="col">FECHA INGRESO</th>
                            <th scope="col">FECHA SALIDA</th>
                            <th scope="col">HABITACIÓN</th>
                            <th scope="col">CLIENTE</th>
                            <th scope="col">DIAS</th>
                            <th scope="col">TARIFA</th>
                            <th scope="col">TOTAL</th>
                            <th scope="col">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $r)
                            <tr>
                                <td>
                                    {{ $r->id }}
                                </td>
                                <td>
                                    {{ $r->fecha_ingreso }}
                                </td>
                                <td>
                                    {{ $r->fecha_salida }}
                                </td>
                                <td>
                                    {{ $r->habitaciones->numero_habitacion }}
                                </td>
                                <td>
                                    <span class="text-truncate">
                                        {{ $r->clientes_id > 0 ? $r->clientes->nombre : $r->titular }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    {{ $r->dias }}
                                </td>
                                <td class="text-end">
                                    ${{ number_format($r->tarifas->precio, 2) }}
                                </td>
                                <td class="text-end">

                                    ${{ number_format($r->tarifas->monto * $r->dias, 2) }}
                                </td>
                                <td>
                                    <span class="badge h4 {{ $r->facturada ? 'bg-success' : 'bg-danger' }}">
                                        {{ $r->facturada ? 'Facturada' : 'Sin facturar' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
