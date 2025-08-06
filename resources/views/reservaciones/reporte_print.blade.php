@extends('layouts.print_b')
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
            /*width: 200px;*/
            width: 6cm;
        }

        .fp-title {
            font-size: 8.5pt;
            width: 2cm;
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
        }

        .space {
            height: 20px;
        }

        .bg-turno {
            background: #d1d1d1;
            color: #343434;
        }

        .bg-total {
            background: #d8ffde;
            color: #1c1c1c;
            font-weight: 600;
        }

        tr td {
            padding: 2px 0px;
        }
    </style>
@endsection
@section('titulo')
    <div class="titulo">
        Reporte de reservaciones
    </div>
@endsection
@section('content')
    @if (isset($reservaciones))
        <div class="row">
            <div class="col-12">
                <table class="table table-light table-striped table-hover table-bordered table-sm table-responsive-sm">
                    <thead>
                        <tr>
                            <th scope="col">FECHA INGRESO</th>
                            <th scope="col">FECHA SALIDA</th>
                            <th scope="col">HABITACIÓN</th>
                            <th scope="col">CLIENTE</th>
                            <th scope="col">DIAS</th>
                            <th scope="col">TARIFA</th>
                            <th scope="col">TOTAL</th>
                            <th scope="col">Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vendedor as $v)
                            @php
                                $rVendedor = $reservaciones->where('users_id', $v->id);
                                $reservasVendedor = $reservas->whereIn('reservaciones_id', $rVendedor->pluck('id'));
                                $totalIngreso = 0;
                                $totalSinIngreso = 0;
                            @endphp
                            <tr>
                                <th colspan="8" class="text-uppercase">{{ $v->name }}</th>
                            </tr>

                            @foreach ($reservasVendedor as $r)
                                @php
                                    $total = round($r->relacionTarifas->precio * $r->dias, 2);
                                    if ($r->ingreso) {
                                        $totalIngreso += $total;
                                    } else {
                                        $totalSinIngreso += $total;
                                    }
                                @endphp
                                <tr>
                                    <td>
                                        {{ $r->fecha_ingreso }}
                                    </td>
                                    <td>
                                        {{ $r->fecha_salida }}
                                    </td>
                                    <td>
                                        {{ $r->relacionHabitaciones->numero_habitacion }}
                                    </td>
                                    <td>
                                        {{ $r->relacionReservaciones->clientes_id > 0 ? $r->relacionReservaciones->relacionClientes->nombre : $r->relacionReservaciones->titular }}
                                    </td>
                                    <td class="text-end">
                                        {{ $r->dias }}
                                    </td>
                                    <td class="text-end">
                                        ${{ number_format($r->relacionTarifas->precio, 2) }}
                                    </td>
                                    <td class="text-end">
                                        ${{ number_format($r->relacionTarifas->precio * $r->dias, 2) }}
                                    </td>
                                    <td>
                                        {{ $r->ingreso ? 'Ingreso registrado' : 'Sin ingreso registrado' }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="6" class="text-uppercase">{{ $v->name }}: Total sin registro de ingreso</td>
                                <td class="text-end">${{ number_format($totalSinIngreso, 2) }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-uppercase">{{ $v->name }}: Total con registro de ingreso</td>
                                <td class="text-end"><b>${{ number_format($totalIngreso, 2) }}</b></td>
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
