@extends('layouts.clientes_panel')

@section('style-content')
    <style>
        body {
            background: #80DEEA !important;
        }

        .panel-body {
            min-height: 90vh;
        }

        .card-caja {
            background: #455A64;
            color: #37474F;
        }

        .card-menu {
            border-color: #455A64;
            color: #37474F;
            height: 180px;
            overflow: hidden;
        }

        .card-menu .card-title {
            margin-top: 25px;
        }

        .card-menu:hover {
            background: #455A64;
            color: #E8EAF6;
        }
    </style>
@endsection

@section('content_cliente')
    <div class="container">
        <div class="card-body p-2">
            <h5 class="card-title text-uppercase mb-4 fw-bold">
                PANEL DE CLIENTES
            </h5>

            <div class="row mb-2">
                @can('clientes.index')
                    <div class="col-12 col-md-6 col-lg-3 mb-3">
                        <a href="{{ route('clientes.index') }}" class="card text-center text-decoration-none card-menu">
                            <div class="card-body">
                                <h1 class="card-title">
                                    <span class="mdi mdi-point-of-sale"></span>
                                </h1>
                                <p class="card-text">CLIENTES</p>
                            </div>
                        </a>
                    </div>
                @endcan
                @can('clientes.credito')
                    <div class="col-12 col-md-6 col-lg-3 mb-3">
                        <a href="{{ route('clientes.reporte.creditos') }}"
                            class="card text-center text-decoration-none card-menu">
                            <div class="card-body">
                                <h1 class="card-title">
                                    <span class="mdi mdi-point-of-sale"></span>
                                </h1>
                                <p class="card-text">Reporte de créditos</p>
                            </div>
                        </a>
                    </div>
                @endcan
            </div>
        </div>
    </div>
@endsection
