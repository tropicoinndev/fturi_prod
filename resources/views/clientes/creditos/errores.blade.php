@extends('layouts.clientes_panel')
@section('style-content')
    <style>
        body {
            background: #80DEEA !important;
        }
    </style>
@endsection
@section('content_cliente')
    <div class="container" id="appContent">
        <div class="card-body p-2">
            <div class="row mb-4">
                <div class="col-12 text-uppercase h3">
                    Alertas de clientes
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-12 mb-4">
                    <div class="card {{ $periodo > 0 ? 'text-bg-danger' : '' }} text-center">
                        <div class="card-body">
                            <h2 class="card-title"> {{ $periodo }}</h2>
                            <p class="card-text">
                                Clientes con crédito habilitado, pero sin periodos de créditos configurados.
                            </p>
                            <p class="card-text">
                                <a class="btn btn-light" href="{{ route('clientes.reporte.alertasClientesPeriodos') }}"
                                    role="button">
                                    Ver todos
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-6 mb-4 text-center">
                    <div class="card {{ $jlocalidad > 0 ? 'text-bg-danger' : '' }}">
                        <div class="card-body">
                            <h2 class="card-title"> {{ $jlocalidad }}</h2>
                            <p class="card-text">
                                Clientes jurídicos sin municipio
                            </p>
                            <p class="card-text">
                                <a class="btn btn-light" href="{{ route('clientes.reporte.alertasClientesMunicipios') }}"
                                    role="button">
                                    Ver todos
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-3 mb-4">
                    <div class="card {{ $locales > 0 ? 'text-bg-warning' : '' }}">
                        <div class="card-body">
                            <h2 class="card-title"> {{ $locales }}</h2>
                            <p class="card-text">
                                Clientes locales sin identificaciones
                            </p>
                            <p class="card-text">
                                <a name="" id="" class="btn btn-light"
                                    href="{{ route('clientes.reporte.alertasClientesIdentificaciones') }}" role="button">
                                    Ver todos
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-3 mb-4">
                    <div class="card {{ $extranjeros > 0 ? 'text-bg-warning' : '' }}">
                        <div class="card-body">
                            <h2 class="card-title"> {{ $extranjeros }}</h2>
                            <p class="card-text">
                                <small>
                                    Clientes extranjeros sin identificaciones
                                </small>
                            </p>
                            <p class="card-text">
                                <a class="btn btn-light"
                                    href="{{ route('clientes.reporte.alertasClientesIdentificacionesExtranjeros') }}"
                                    role="button">
                                    Ver todos
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-4 mb-4">
                    <div class="card {{ $nlocalidad > 0 ? 'text-bg-warning' : '' }}">
                        <div class="card-body">
                            <h2 class="card-title"> {{ $nlocalidad }}</h2>
                            <p class="card-text">
                                Clientes naturales sin municipio o país (extranjeros)
                            </p>
                            <p class="card-text">
                                <a class="btn btn-light"
                                    href="{{ route('clientes.reporte.alertasClientesMunicipiosPais') }}" role="button">
                                    Ver todos
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-6 mb-4 text-center">
                    <div class="card {{ $jactividad > 0 ? 'text-bg-danger' : '' }}">
                        <div class="card-body">
                            <h2 class="card-title"> {{ $jactividad }}</h2>
                            <p class="card-text">
                                Clientes jurídicos sin actividad económica
                            </p>
                            <p class="card-text">
                                <a class="btn btn-light" href="{{ route('clientes.reporte.alertasClientesActividades') }}"
                                    role="button">
                                    Ver todos
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
