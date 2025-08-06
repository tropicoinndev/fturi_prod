@extends('layouts.app')

@section('content')
<style>
    .column,
    .dia {
        position: relative;
        width: 14.28%;
    }

    .dia {

        max-height: 100px;
        height: 100px;
        cursor: pointer;
    }

    .dia-content {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
    }

    .ocupado {
        background: #455A64;
        color: #fafafa;
    }

    .dia .tooltip {
        visibility: hidden;
        width: 180px;
        background-color: #2979FF;
        color: #fff;
        text-align: center;
        padding: 8px;
        border-radius: 5px;
        position: absolute;
        z-index: 1;
        bottom: 110%;
        left: 50%;
        margin-left: -75px;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .ocupado:hover .tooltip {
        visibility: visible;
        opacity: 1;
    }

    .ocupado:hover {
        background: #009688;
        color: #fafafa;
    }

    .flecha {
        position: absolute;
        width: 0px;
        height: 0px;
        margin-left: 20px;
        border-top: 15px solid #2979FF;
        border-right: 15px solid transparent;
        border-bottom: 15px solid transparent;
        border-left: 15px solid transparent;
    }

    .head {
        background: #CFD8DC;
        padding: 10px;
    }

    .panelCalendar {
        min-height: 85vh;
    }

    body {
        background: #E1F5FE;
    }

</style>
<div id="appReporteHab" class="container">
    <div class="card panelCalendar">
        <div class="card-body p-5">
            <form action="{{ route('cortesias.reporte_opcion') }}" method="post">
                @csrf
                <div class="row mb-2">
                    <div class="card-title col-12 h5 text-uppercase mb-4">REPORTE DE CORTESIAS</div>
                    <div class="col-3">
                        <div class="mb-3">
                            <label for="" class="form-label">
                                Tipo de cortesía
                            </label>
                            <select class="form-select" name="tipo" required>
                                <option selected value="0" class="text-uppercase">TODOS</option>
                                @foreach ($tipos_cortesias as $t)
                                <option value="{{ $t->id }}" {{ isset($tipo) && $tipo == $t->id ? 'selected' : '' }} class="text-uppercase">
                                    {{ $t->tipo }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="mb-3">
                            <label for="" class="form-label">
                                Inicio
                            </label>
                            <input type="date" name="inicio" class="form-control" value="{{ $inicio ?? '' }}" required />
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="mb-3">
                            <label for="" class="form-label">
                                Finalización
                            </label>
                            <input type="date" name="fin" class="form-control" value="{{ $fin ?? '' }}" required />
                        </div>
                    </div>
                    <div class="col-3 row align-items-center">
                        <div class="col ">
                            <button class="btn btn-light" type="submit" role="button" value="1" name="opcion">
                                <span class="mdi mdi-magnify h5"></span>
                                Buscar
                            </button>
                            <button class="btn btn-light" type="submit" role="button" value="2" name="opcion">
                                <span class="mdi mdi-file-pdf-box h5"></span>
                                PDF
                            </button>
                            <button class="btn btn-light" type="submit" role="button" value="3" name="opcion">
                                <span class="mdi mdi-file-excel h5"></span>
                                XLS
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            @if (isset($data))
            <div class="row">
                <div class="col-12">
                    <table class="table table-light table-striped table-hover table-bordered table-sm table-responsive-sm">
                        <thead>
                            <tr>
                                <th scope="col">CUENTA</th>
                                <th scope="col">FECHA</th>
                                <th scope="col">PRODUCTO</th>
                                <th scope="col">CANTIDAD</th>
                                <th scope="col">PRECIO</th>
                                <th scope="col">P.FAC.</th>
                                <th scope="col">TOTAL</th>
                                <th scope="col">USUARIO</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $totalCortesias = 0;
                            @endphp
                            @foreach ($tipoData as $t)
                            @php
                            $cTitular = $titular->where('tipo_cortesias_id', $t->id);
                            $total = 0;
                            $countTipo = $data->whereIn('control_cortesias_id', $cTitular->pluck('id'))->count();

                            @endphp
                            @if ($countTipo > 0)
                            <tr>
                                <th colspan="8" class="text-uppercase text-center">{{ $t->tipo }}</th>
                            </tr>

                            @foreach ($cTitular as $r)
                            @php
                            $dataTitular = [];
                            $countTitular = $data->where('control_cortesias_id', $r->id)->count();
                            $totalTitular = 0;
                            @endphp


                            @if ($countTitular > 0)
                            @php
                            $dataTitular = $data->where('control_cortesias_id', $r->id);
                            @endphp
                            <tr>
                                <th colspan="6" class="text-uppercase">
                                    {{ $r->titular }}
                                </th>
                                <th class="text-end">
                                    ${{ number_format($r->monto, 2) }}
                                </th>
                                <th>/mes</th>
                            </tr>
                            @foreach ($dataTitular as $d)
                            @switch($d->origen)
                            @case(1)
                            @foreach ($d->detalle->detalle_orden as $orden)
                            <tr>
                                <td>
                                    {{ $d->cuenta }} Nº {{ $d->origen_id }}
                                </td>
                                <td>
                                    {{ Carbon::parse($orden->created_at)->format('d-m-Y h:i:s') }}
                                </td>
                                <td>
                                    {{ $orden->servicios->servicio }}
                                </td>
                                <td>
                                    {{ $orden->cantidad }}
                                </td>
                                <td class="text-end">
                                    ${{ $orden->servicios->precio_unitario }}
                                </td>
                                <td class="text-end">
                                    ${{ $orden->precio_unitario }}
                                </td>
                                <td class="text-end">
                                    ${{ number_format($orden->cantidad * $orden->precio_unitario, 2) }}
                                </td>
                                <td>

                                </td>
                            </tr>
                            @php
                            $totalTitular += $orden->cantidad * $orden->precio_unitario;

                            @endphp
                            @endforeach
                            @break

                            @case(2)
                            <tr>
                                <td>
                                    {{ $d->cuenta }} Nº {{ $d->origen_id }}
                                </td>
                                <td>
                                    {{ $d->estadia->fecha_ingreso }} -
                                    {{ $d->estadia->fecha_salida }}
                                </td>
                                <td>
                                    {{ $d->estadia->tarifas->tarifa }}
                                </td>
                                <td>
                                    {{ $d->estadia->dias }} dia(s)
                                </td>
                                <td class="text-end">
                                    ${{ number_format($d->estadia->tarifas->precio, 2) }}
                                </td>
                                <td class="text-end">
                                    ${{ number_format($d->estadia->tarifa ?? $d->estadia->tarifas->precio, 2) }}

                                </td>
                                <td class="text-end">
                                    ${{ number_format(($d->estadia->tarifa ?? $d->estadia->tarifas->precio) * $d->estadia->dias, 2) }}
                                </td>
                                <td>
                                    {{ $d->estadia->usuarios->user }}
                                </td>
                            </tr>
                            @php

                            $totalTitular += ($d->estadia->tarifa ?? $d->estadia->tarifas->precio) * $d->estadia->dias;

                            @endphp
                            @break

                            @case(3)
                            @foreach ($d->detalle->detalles_comanda as $comanda)
                            <tr>
                                <td>
                                    {{ $d->cuenta }} Nº {{ $d->origen_id }}
                                </td>
                                <td>
                                    {{ Carbon::parse($comanda->created_at)->format('d-m-Y h:i:s') }}
                                </td>
                                <td>
                                    {{ $comanda->precios->detalle }}
                                </td>
                                <td>
                                    {{ $comanda->cantidad }}
                                </td>
                                <td class="text-end">
                                    ${{ number_format($comanda->precios->precio, 2) }}
                                </td>
                                <td class="text-end">
                                    ${{ number_format($comanda->precio, 2) }}
                                </td>
                                <td class="text-end">
                                    ${{ number_format($comanda->precio * $comanda->cantidad, 2) }}
                                </td>
                                <td>
                                    {{ $comanda->user_comanda->user }}
                                </td>
                            </tr>
                            @php
                            $totalTitular += $comanda->precio * $comanda->cantidad;

                            @endphp
                            @endforeach
                            @break

                            @default
                            @endswitch
                            @endforeach
                            @php
                            $total += $totalTitular;
                            @endphp
                            <tr>
                                <td colspan="6" class="text-uppercase">
                                    Total {{ $r->titular }}
                                </td>
                                <td class="text-end">${{ number_format($totalTitular, 2) }}</td>
                                <td></td>
                            </tr>
                            @endif
                            @endforeach
                            @php
                            $totalCortesias += $total;
                            @endphp
                            <tr>
                                <td colspan="6" class="text-uppercase">Total {{ $t->tipo }}
                                </td>
                                <td class="text-end">${{ number_format($total, 2) }}</td>
                                <td></td>
                            </tr>
                            @endif
                            @endforeach
                            <tr class="bg-total-final">
                                <td colspan="6" class="text-uppercase">
                                    Total periodo
                                </td>
                                <td class="text-end">
                                    <b>
                                        ${{ number_format($totalCortesias, 2) }}</td>
                                </b>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
