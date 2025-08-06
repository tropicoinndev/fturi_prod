@extends('layouts.app')

@section('style')
    <style>
        body {
            background: #91c0da;
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
@section('content')
    <div class="container">
        <div class="card panel-body shadow p-4">
            <div class="card-body">

                <div class="row mb-3">

                    <div class="col-12">
                        Bienvenid@,
                        <span class="text-capitalize">
                            {{ auth()->user()->name }}.
                        </span>
                    </div>
                </div>

                <div class="row">
                    @can('users.menu')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('users.menu') }}" class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-account"></span>
                                    </h1>
                                    <p class="card-text">Usuarios</p>
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
                            <a href="{{ route('cajas.my') }}" class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-archive-cog"></span>
                                    </h1>
                                    <p class="card-text">Cajas</p>
                                </div>
                            </a>
                        </div>
                    @endcan

                    @can('cobros.index')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('productos.index') }}" class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-store-plus-outline"></span>
                                    </h1>
                                    <p class="card-text">Productos</p>
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
                    @can('clientes.index')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('clientes.index') }}" class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-account-group-outline"></span>
                                    </h1>
                                    <p class="card-text">Clientes</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('habitaciones.index')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('administrar_habitaciones.index') }}"
                                class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-bell-cog"></span>
                                    </h1>
                                    <p class="card-text">Habitaciones</p>
                                </div>
                            </a>
                        </div>
                    @endcan


                    @can('servicios.index')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('servicios.index') }}" class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-toolbox-outline"></span>
                                    </h1>
                                    <p class="card-text">Servicios</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('cajas.index')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('cajas.index') }}" class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-account-cog-outline"></span>
                                    </h1>
                                    <p class="card-text">Admin</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('mantenimientos.index')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('mantenimientos.index') }}"
                                class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-blinds-horizontal-closed"></span>
                                    </h1>
                                    <p class="card-text">Mantenimientos</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('bodegas.index')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('bodegas.index') }}" class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-store"></span>
                                    </h1>
                                    <p class="card-text">Bodegas</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('cortesias.autorizar')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('cortesias.autorizar') }}"
                                class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi  mdi-wallet-giftcard"></span>
                                    </h1>
                                    <p class="card-text">Cortesias</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('anticipos.admin')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('anticipos.index') }}" class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi  mdi-account-cash"></span>
                                    </h1>
                                    <p class="card-text">Anticipos</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('dte.anulaciones')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('dte_anulaciones.cajas.solicitudes') }}"
                                class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi  mdi-file-document-edit"></span>
                                    </h1>
                                    <p class="card-text">Invalidaciones de DTE</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('reportes.admin')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('cajas.panel_reportes_sub_menu') }}"
                                class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-file-table-outline"></span>
                                    </h1>
                                    <p class="card-text">Reportes</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('ordenes.index')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('ordenes.index') }}" class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi  mdi-room-service-outline"></span>
                                    </h1>
                                    <p class="card-text">Ordenes</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('reservaciones.index')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('reservaciones.index') }}"
                                class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-calendar-plus"></span>
                                    </h1>
                                    <p class="card-text">Reservaciones</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('comprobantes.consulta')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('comprobantes.index') }}"
                                class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-file-document"></span>
                                    </h1>
                                    <p class="card-text">Comprobantes</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    <div class="col-12 col-md-6 col-lg-3 mb-3">
                        <a href="{{ route('ros.create') }}" class="card text-center text-decoration-none card-menu">
                            <div class="card-body">
                                <h1 class="card-title">
                                    <span class="mdi mdi-account-alert"></span>
                                </h1>
                                <p class="card-text">Crear ROS</p>
                            </div>
                        </a>
                    </div>

                    @can('app.logout')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">

                            <a class="card text-center text-decoration-none card-menu" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">

                                <div class="card-body">

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                    <h1 class="card-title">
                                        <span class="mdi mdi-exit-to-app"></span>
                                    </h1>
                                    <p class="card-text">Salir</p>
                                </div>
                            </a>
                        </div>
                    @endcan

                </div>
            </div>
        </div>
    </div>
@endsection
