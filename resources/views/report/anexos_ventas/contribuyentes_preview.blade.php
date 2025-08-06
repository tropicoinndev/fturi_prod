@extends('layouts.panel_reportes')
@section('panel_reportes')
    <div id="appReporteContribuyentes" class="container">
        <div class="card-body p-2 border-success">
            <div class="row mb-4">
                <div class="col-12 text-uppercase h3">
                    Vista previa
                </div>
                <div class="col-12 text-uppercase h5">
                    F-07 V14: Detalle de ventas a contribuyentes
                </div>
                <div class="col-12 my-2">
                    <a class="btn btn-light" href="{{ route('anexos.contribuyentes') }}" role="button">Volver</a>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-borderless align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Emisión</th>
                                    <th>Clase Doc.</th>
                                    <th>Tipo Doc.</th>
                                    <th>Numero de resolución</th>
                                    <th>Numero de serie</th>
                                    <th>Numero de documento</th>
                                    <th>Nº interno</th>
                                    <th>NIT/NRC</th>
                                    <th>Nombre / Razón social</th>
                                    <th>Ventas Exentas</th>
                                    <th>No Sujetas</th>
                                    <th>Ventas Gravadas Locales</th>
                                    <th>Débito fiscal</th>
                                    <th>Ventas terceros</th>
                                    <th>Débito Ventas terceros</th>
                                    <th>Total Ventas</th>
                                    <th>DUI</th>
                                    <th>Tipo Operación</th>
                                    <th>Tipo Ingreso</th>
                                    <th>Anexo</th>
                                    @if ($anulacion)
                                        <th>Anulaciones</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                @php
                                    $exentas = 0;
                                    $gravadas = 0;
                                    $debito = 0;
                                    $total = 0;
                                @endphp
                                @foreach ($data as $d)
                                    @php
                                        $nrc = $d->identificacion ?? '';
                                        $exentas += $d->exento;
                                        $gravadas += $d->gravado;
                                        $debito += $d->iva;
                                        $total += $d->total_ventas;
                                    @endphp
                                    <tr>
                                        <td>{{ $d->emision }}</td>
                                        <td>{{ $d->clase_documento }}</td>
                                        <td>{{ $d->tipo_documento }}</td>
                                        <td>{{ $d->numero_resolucion }}</td>
                                        <td>{{ $d->numero_serie }}</td>
                                        <td>{{ $d->numero_documento }}</td>
                                        <td>{{ $d->correlativo_interno }}</td>
                                        <td>{{ $nrc }}</td>
                                        <td>{{ $d->nombre }}</td>
                                        <td>${{ number_format($d->exento, 2) }}</td>
                                        <td>${{ number_format($d->no_sujetas, 2) }}</td>
                                        <td>${{ number_format($d->gravado, 2) }}</td>
                                        <td>${{ number_format($d->iva, 2) }}</td>
                                        <td>${{ number_format($d->cuentas_tercero, 2) }}</td>
                                        <td>${{ number_format($d->debito_cuentas_tercero, 2) }}</td>
                                        <td>${{ number_format($d->total_ventas, 2) }}</td>
                                        <td>{{ $nrc == null || $nrc == '' ? $d->dui : '' }}</td>
                                        <td>{{ $d->exento == 0 ? 1 : 2 }}</td>
                                        <td>2</td>
                                        <td>{{ $d->anexo }}</td>
                                        @if ($anulacion)
                                            <td>{{ $d->anulacion }}</td>
                                        @endif
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="9">
                                        Totales
                                    </td>
                                    <td>
                                        ${{ number_format($exentas, 2) }}
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                        ${{ number_format($gravadas, 2) }}

                                    </td>
                                    <td>
                                        ${{ number_format($debito, 2) }}

                                    </td>
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                        ${{ number_format($total, 2) }}
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                    </td>
                                    @if ($anulacion)
                                        <td></td>
                                    @endif
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
