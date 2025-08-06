@extends('layouts.app')

@section('style')
    @if (!session('caja'))
        <script>
            window.location = "{{ route('cajas.login') }}";
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

        .card-caja {
            background: {{ session('caja')->color_fondo }};
            color: {{ session('caja')->color_texto }};
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
                        {{ session('caja')->caja }}
                    </div>
                    <div class="col-12">
                        Hola,
                        <span class="text-capitalize">
                            {{ auth()->user()->name }}.
                        </span>
                    </div>
                </div>
                @if (session('turno'))
                    @php
                        $fApertura = \Carbon::parse(session('turno')->fecha . ' ' . session('turno')->opcion->apertura);
                        $cierre = \Carbon::parse(session('turno')->fecha . ' ' . session('turno')->opcion->cierre);
                        if (session('turno')->opcion->cierre < session('turno')->opcion->apertura) {
                            $cierre = $cierre->addDay();
                        }
                        $horas = $fApertura->diffInHours($cierre);
                        $fCierre = $fApertura->addHours($horas);
                    @endphp
                    <div class="row my-4">
                        @if (date('Y-m-d H:i:s') >= $fCierre)
                            <div class="col-12">
                                <div class="card text-bg-warning p-3">
                                    <div class="card-body">
                                        @can('cajas.edit')
                                            <a href="{{ route('cajas.cierre') }}"
                                                class="btn btn-light float-end align-middle">Cerrar
                                                turno</a>
                                        @endcan
                                        <h4 class="card-title text-uppercase">{{ session('turno')->opcion->turno }}</h4>
                                        <p class="card-text">
                                            Debe cerrar este turno:
                                            {{ Carbon::parse(date('Y-m-d H:i:s'))->diffForHumans(session('turno')->apertura) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-12">
                                <div class="card card-caja p-3">
                                    <div class="card-body">
                                        <h4 class="card-title text-uppercase">{{ session('turno')->opcion->turno }}</h4>
                                        <p class="card-text">
                                            <span title="{{ session('turno')->apertura }}">
                                                Apertura {{ Carbon::parse(session('turno')->apertura)->diffForHumans() }}
                                            </span>
                                            ·
                                            <span title="{{ $fCierre }}">
                                                Cierre {{ $fCierre->diffForHumans() }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
                <div class="row">
                    @can('cajas.solicitudes')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('cajas.my') }}" class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-bell-badge"></span>
                                    </h1>
                                    <p class="card-text">Solicitudes de comprobantes</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('comandas.index')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('comandas.index') }}" class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-table-chair"></span>
                                    </h1>
                                    <p class="card-text">Comandas</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('comandas.app_index')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('app.comandas') }}" class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-cellphone"></span>
                                    </h1>
                                    <p class="card-text">Comandas App</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('recepciones.index')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('recepciones.index') }}" class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-bed-outline"></span>
                                    </h1>
                                    <p class="card-text">Recepciones</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('ordenes.index')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('ordenes.index') }}" class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-shopping"></span>
                                    </h1>
                                    <p class="card-text">Ordenes</p>
                                </div>
                            </a>
                        </div>
                    @endcan

                    @can('cobros.index')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('cobros.index') }}" class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-cash-check"></span>
                                    </h1>
                                    <p class="card-text">Cobros</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('eventos.index')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('eventos.eventos') }}" class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi  mdi-calendar-plus-outline"></span>
                                    </h1>
                                    <p class="card-text">Eventos</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('produccion.cocina')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('comandas.produccion.cocina') }}"
                                class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-chef-hat"></span>
                                    </h1>
                                    <p class="card-text">Producción cocina</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('produccion.bar')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('comandas.produccion.bar') }}"
                                class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-glass-cocktail"></span>
                                    </h1>
                                    <p class="card-text">Producción bar</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('clientes.create')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('clientes.create') }}" class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-account-plus"></span>
                                    </h1>
                                    <p class="card-text">Agregar cliente</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('anticipos.create')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('anticipos.create') }}" class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-cash-plus"></span>
                                    </h1>
                                    <p class="card-text">Agregar anticipos</p>
                                </div>
                            </a>
                        </div>
                    @endcan

                    @can('correlativos.create')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('correlativos.create') }}"
                                class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-counter"></span>
                                    </h1>
                                    <p class="card-text">Agregar bloque</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('cajas.edit')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('cajas.turnos_reporte') }}"
                                class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-file-document-check"></span>
                                    </h1>
                                    <p class="card-text">Reporte de turnos</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('cajas.edit')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('cajas.ventas_reporte') }}"
                                class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-clipboard-list-outline"></span>
                                    </h1>
                                    <p class="card-text">Reporte de venta</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('abonos.index')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('abonos.index') }}" class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-cash-clock"></span>
                                    </h1>
                                    <p class="card-text">Abonos</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    <div class="col-12 col-md-6 col-lg-3 mb-3">
                        <a href="{{ route('cajas.logout') }}" class="card text-center text-decoration-none card-menu">
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
