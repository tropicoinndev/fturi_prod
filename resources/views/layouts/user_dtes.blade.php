@extends('layouts.app')

@section('style')
    <style>
        .nav-panel {
            position: fixed;
            height: 92vh;
            z-index: 1050;
            top: 75px;
            border-radius: 8px;
            width: 280px;
            left: 5px;
            bottom: 5px;
            overflow: auto;
            background-color: #37474F;
            color: #FAFAFA;
        }

        #btnPanelDTE {
            position: fixed;
            bottom: 2%;
            left: 2%;
            width: 64px;
            height: 64px;
            border-radius: 50%;
            z-index: 1200;
            background-color: #37474F;
            color: #FAFAFA;
        }

        .nav-panel .nav .nav-link {
            color: #FAFAFA;
        }

        .app-container {
            min-height: 80vh;
            margin-bottom: 22px;
        }

        .card-indicador {
            padding: 12px;
            border: 0px !important;
            min-height: 160px !important;
        }

        .card-indicador .card-header {
            background: none !important;
            border: 0px !important;
            font-size: 22pt;
        }

        .card-indicador .icon {
            margin: auto;
            text-align: center;
            font-size: 42pt;
        }

        .card-gray {
            background-color: #37474F !important;
            color: #FAFAFA !important;
        }

        .card-green {
            background-color: #26A69A !important;
            color: #FAFAFA !important;
        }

        .card-orange {

            background-color: #FF5722 !important;
            color: #FAFAFA !important;
        }

        .card-contingencia {
            background-color: #F57C00 !important;
            color: #FAFAFA !important;
        }

        .card-red {
            background-color: #E57373 !important;
            color: #FAFAFA !important;
        }

        .card-light-green {
            background-color: #B2DFDB !important;
        }

        .card-disable {

            background-color: #90A4AE !important;
            color: #FAFAFA !important;
        }

        .card-sucursal-1 {
            background: #FFF9C4 !important;
        }

        .card-sucursal-2 {
            background: #C8E6C9 !important;

        }

        .card-table {
            background-color: #FFCCBC !important;
        }

        .card-table-1 {
            background-color: #FFECB3 !important;

        }

        .card-purple {

            background-color: #7746ec !important;
            color: #FFF !important;
        }

        .card-telescope {
            background: #4040c8 !important;
            color: #FFF !important;
        }

        body {
            background: #D7CCC8 !important;
        }

        .btn-accion {
            background: #CFD8DC !important;
        }
    </style>
    @yield('style-dte')
@endsection

@section('script')
    @yield('script-dte')
@endsection
@section('content')
    <div class="container ">
        <div class="row justify-content-center">
            <div class="card shadow p-5">
                <div class="card-body app-container" id="appDte">
                    @yield('user_content')
                </div>
            </div>
        </div>
    </div>
@endsection
