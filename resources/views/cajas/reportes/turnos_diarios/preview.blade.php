@extends('layouts.panel_reportes')


@section('panel_reportes')
    <div id="appVentaByRubros" class="container">
        <div class="card-body p-2">
            <div class="row mb-2">
                <div class="col-12 text-uppercase h3">
                    Vista previa reporte de turnos diarios
                </div>
                <div class="col-12 my-2">
                    <a class="btn btn-light" href="{{ route('cajas.reporteTurnosDiarios') }}" role="button">Volver</a>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12 table-responsive dataPrint">
                    @include('cajas.reportes.turnos_diarios.dataPrint')
                </div>
            </div>
        </div>
    </div>
    <style>
        .dataPrint {
            text-transform: uppercase;
        }

        .table {
            border: 0 !important;
        }

        table tr td {
            border: none;
            background: #fff !important;
        }

        .w-15 {
            width: 30% !important;
        }

        .table-light {
            background: #fff !important;
        }

        .title-table {
            text-align: center;
            font-weight: bolder;
            font-size: 12pt;
        }

        .b {
            font-weight: 600;
        }

        .tb-title {
            font-weight: 500;
            background: #28aced;
        }

        .tb-title {
            background: rgb(203, 203, 203);
            color: rgb(26, 26, 26);
        }

        .totales .tb-title {
            font-size: 11pt;
            width: 6.5cm;
            overflow: hidden;
            background: rgb(255, 255, 255);
        }
    </style>
@endsection
