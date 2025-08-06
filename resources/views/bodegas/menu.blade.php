@extends('layouts.app')

@section('style')
    @if (!session('bodega'))
        <script>
            window.location = "{{ route('bodegas.login') }}";
        </script>
        {{ exit() }}
    @endif
    <style>
        body {
            background: #EDE7F6;
        }

        .panel-body {
            min-height: 90vh;
        }

        .card-bodega {
            background: {{ session('bodega')->color_fondo }};
            color: {{ session('bodega')->color_texto }};
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
@section('content')
    <div class="container">
        <div class="card panel-body shadow p-4">
            <div class="card-body">
                <div class="row">
                    <x-message></x-message>
                </div>
                <div class="row">
                    <div class="col-12 h3">
                        {{ session('bodega')->bodega }}
                    </div>
                    <div class="col-12 mb-2">
                        Hola,
                        <span class="text-capitalize">
                            {{ auth()->user()->name }}.
                        </span>
                    </div>
                </div>
            
                
                <div class="row">
                    @can('bodegas.my')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('bodegas.my') }}" class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-file-edit-outline"></span>
                                    </h1>
                                    <p class="card-text">Creacion de requisiciones</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('compras.index')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('compras.index') }}" class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-cart-plus"></span>
                                    </h1>
                                    <p class="card-text">Compras</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('compras.index')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('compras.historialCompras') }}" class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-shopping"></span>
                                    </h1>
                                    <p class="card-text">Historial de compras</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('requisiciones.index')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('requisiciones.historialBodegaRequisiciones') }}" class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-clipboard-text-clock"></span>
                                    </h1>
                                    <p class="card-text">Historial de requisiciones</p>
                                </div>
                            </a>
                        </div>
                    @endcan

                    @can('cobros.index')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('requisiciones.requisicion') }}" class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-file-multiple"></span>
                                    </h1>
                                    <p class="card-text">Mis requisiciones</p>
                                </div>
                            </a>
                        </div>
                    @endcan

                    @can('requisiciones.create')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('requisiciones.autorizarRequisiciones') }}" class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-text-box-check"></span>
                                    </h1>
                                    <p class="card-text">Autorizacion de requisiciones</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('requisiciones.index')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('requisiciones.requisiciones_reporte') }}" class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-chart-box-plus-outline"></span>
                                    </h1>
                                    <p class="card-text">Reporte de requisiciones</p>
                                </div>
                            </a>
                        </div>
                    @endcan

                    @can('bodegas.bodega')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('existencias.existencias_reporte') }}"
                                class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-tag-check"></span>
                                    </h1>
                                    <p class="card-text">Existencias por producto</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('cajas.create')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('existencias.reporte_existencia_bodega') }}"
                                class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-file-document-check"></span>
                                    </h1>
                                    <p class="card-text">Existencias por bodegas</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    <div class="col-12 col-md-6 col-lg-3 mb-3">
                        <a href="{{ route('bodegas.logout') }}" class="card text-center text-decoration-none card-menu">
                            <div class="card-body">
                                <h1 class="card-title">
                                    <span class="mdi mdi-exit-to-app"></span>
                                </h1>
                                <p class="card-text">Salir</p>
                            </div>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
