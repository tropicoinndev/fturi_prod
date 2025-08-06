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
            width: 6.5cm;
        }

        .fp-title {
            font-size: 8.5pt;
            width: {{ 21.94 / ($nforma + 8) }}cm;
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

        .bt-2 {
            border-top: 2px #2c2c2c solid;
        }

        .bb-1 {
            border-bottom: 1px #000 solid;
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

        .space {
            height: 20px;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
@endsection
@section('titulo')
    <div class="titulo">
        Reporte de turno
    </div>
@endsection
@section('content')
    @include('cajas.reportes.turnos_diarios.dataPrint')
@endsection
