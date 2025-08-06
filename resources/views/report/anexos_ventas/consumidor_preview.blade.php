@extends('layouts.panel_reportes')
@section('panel_reportes')
    <div id="appReporteContribuyentes" class="container">
        <div class="card-body p-2 border-success">
            <div class="row mb-4">
                <div class="col-12 text-uppercase h3">
                    Vista previa
                </div>
                <div class="col-12 text-uppercase h5">
                    F-07 V14: Detalle de ventas a consumidor final
                </div>
                <div class="col-12 my-2">
                    <a class="btn btn-light" href="{{ route('anexos.consumidor') }}" role="button">Volver</a>
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
                                    <th>Serie de documento</th>
                                    <th>Numero de control interno DEL</th>
                                    <th>Numero de control interno AL</th>
                                    <th>Numero de documento DEL</th>
                                    <th>Numero de documento AL</th>
                                    <th>Nº Maquina registradora</th>
                                    <th>Ventas Exentas</th>
                                    <th>No Sujetas a proporcionalidad</th>
                                    <th>No Sujetas</th>
                                    <th>Ventas Gravadas Locales</th>
                                    <th>Exportacion en CA</th>
                                    <th>Exportacion fuera CA</th>
                                    <th>Exportacion servicios</th>
                                    <th>Ventas zonas francas/DPA</th>
                                    <th>Ventas Terceros</th>
                                    <th>Total Ventas</th>
                                    <th>Tipo Operación</th>
                                    <th>Tipo Ingreso</th>
                                    <th>Anexo</th>

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
                                        <td>{{ $d->interno_del }}</td>
                                        <td>{{ $d->interno_al }}</td>
                                        <td>{{ $d->numero_documento_del }}</td>
                                        <td>{{ $d->numero_documento_al }}</td>
                                        <td>{{ $d->maquina }}</td>
                                        <td>${{ number_format($d->exento, 2) }}</td>
                                        <td>${{ number_format($d->internas_no_sujetas, 2) }}</td>
                                        <td>${{ number_format($d->no_sujetas, 2) }}</td>
                                        <td>${{ number_format($d->gravado, 2) }}</td>
                                        <td>${{ number_format($d->exportaciones_ca, 2) }}</td>
                                        <td>${{ number_format($d->exportaciones, 2) }}</td>
                                        <td>${{ number_format($d->exportaciones_servicios, 2) }}</td>
                                        <td>${{ number_format($d->ventas_zonas, 2) }}</td>
                                        <td>${{ number_format($d->cuentas_terceros, 2) }}</td>
                                        <td>${{ number_format($d->total_ventas, 2) }}</td>
                                        <td>{{ $d->exento == 0 ? 1 : 4 }}</td>
                                        <td>2</td>
                                        <td>{{ $d->anexo }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="10">
                                        Totales
                                    </td>
                                    <td>
                                        ${{ number_format($exentas, 2) }}
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        ${{ number_format($gravadas, 2) }}

                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>

                                    <td></td>
                                    <td></td>
                                    <td>
                                        ${{ number_format($total, 2) }}
                                    </td>
                                    <td>
                                    </td>


                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
