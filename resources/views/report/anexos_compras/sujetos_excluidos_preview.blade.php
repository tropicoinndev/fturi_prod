@extends('layouts.panel_reportes')
@section('panel_reportes')
    <div id="appReporteContribuyentes" class="container">
        <div class="card-body p-2 border-success">
            <div class="row mb-4">
                <div class="col-12 text-uppercase h3">
                    Vista previa
                </div>
                <div class="col-12 text-uppercase h5">
                    F-07 V14: Compras a sujetos excluidos
                </div>
                <div class="col-12 my-2">
                    <a class="btn btn-light" href="{{ route('anexos.sujetos') }}" role="button">Volver</a>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-borderless align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Tipo Doc.</th>
                                    <th>Documento</th>
                                    <th>Nombre / Razón social</th>
                                    <th>Emisión</th>
                                    <th>Numero serie</th>
                                    <th>Numero documento</th>
                                    <th>Monto operación</th>
                                    <th>Retención IVA13%</th>
                                    <th>Tipo operación</th>
                                    <th>Clasificación</th>
                                    <th>Sector</th>
                                    <th>Tipo Costo / Gasto</th>
                                    <th>Anexo</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                @php

                                    $total = 0;
                                @endphp
                                @foreach ($data as $d)
                                    @php
                                        $total += $d->monto;
                                    @endphp
                                    <tr>
                                        <td>{{ $d->tipo_documento }}</td>
                                        <td>{{ $d->documento }}</td>
                                        <td>{{ $d->nombre }}</td>
                                        <td>{{ $d->emision }}</td>
                                        <td>{{ $d->numero_serie }}</td>
                                        <td>{{ $d->numero_documento }}</td>
                                        <td>${{ number_format($d->monto, 2) }}</td>
                                        <td>{{ $d->retencion }}</td>
                                        <td>{{ $d->tipo_operacion }}</td>
                                        <td>{{ $d->clasificacion }}</td>
                                        <td>{{ $d->sector }}</td>
                                        <td>{{ $d->tipo_clasificacion }}</td>
                                        <td>{{ $d->anexo }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="6">
                                        Totales
                                    </td>
                                    <td>
                                        ${{ number_format($total, 2) }}
                                    </td>
                                    <td colspan="6"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
