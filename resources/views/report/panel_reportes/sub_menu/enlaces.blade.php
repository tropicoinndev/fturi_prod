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
                        <p>PANEL REPORTES</p>
                    </div>
                </div>

                <div class="row">
                    @can('panel.ventas')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('cajas.panel_reporte_venta') }}"
                                class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-point-of-sale"></span>
                                    </h1>
                                    <p class="card-text">Ventas</p>
                                </div>
                            </a>
                        </div>
                    @endcan

                    @can('panel.turnos')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('cajas.panel_reportes') }}"
                                class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-turnstile"></span>
                                    </h1>
                                    <p class="card-text">Turnos</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('cajas.reporteTurnosDiarios') }}"
                                class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-turnstile"></span>
                                    </h1>
                                    <p class="card-text">Turnos por dia</p>
                                </div>
                            </a>
                        </div>
                    @endcan


                    @can('panel.cocina')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('cajas.panel_cocina') }}" class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-chef-hat"></span>
                                    </h1>
                                    <p class="card-text">Cocina</p>
                                </div>
                            </a>
                        </div>
                    @endcan

                    @can('panel.bar')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('cajas.panel_bar') }}" class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-glass-cocktail"></span>
                                    </h1>
                                    <p class="card-text">Bar</p>
                                </div>
                            </a>
                        </div>
                    @endcan

                    @can('panel.ventaRubros')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('cajas.ventaRubros') }}" class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-point-of-sale"></span>
                                    </h1>
                                    <p class="card-text">Ventas por rubro</p>
                                </div>
                            </a>
                        </div>
                    @endcan

                    @can('libros.contribuyentes')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('libros.contribuyentes') }}"
                                class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-book-check"></span>
                                    </h1>
                                    <p class="card-text">Libro contribuyentes</p>
                                </div>
                            </a>
                        </div>
                    @endcan

                    @can('libros.consumidor')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('libros.consumidor') }}" class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-book-check-outline"></span>
                                    </h1>
                                    <p class="card-text">Libro consumidor final</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('anexos.consumidor')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('anexos.consumidor') }}" class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi  mdi-file-document-outline"></span>
                                    </h1>
                                    <p class="card-text">F-07 Consumidor Final</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('anexos.contribuyentes')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('anexos.contribuyentes') }}"
                                class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-file-document"></span>
                                    </h1>
                                    <p class="card-text">F-07 Contribuyentes</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('anexos.invalidados')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('anexos.invalidados') }}"
                                class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-file-document-edit-outline"></span>
                                    </h1>
                                    <p class="card-text">F-07 Anulaciones / Invalidaciones</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('anexos.sujetos_excluidos')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('anexos.sujetos') }}" class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-file-document-check-outline"></span>
                                    </h1>
                                    <p class="card-text">F-07 Compras a Sujetos Excluidos</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('cajas.comandas_activas')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('cajas.comandasActivas') }}"
                                class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-sticker-text-outline"></span>
                                    </h1>
                                    <p class="card-text">Comandas activas</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('cajas.ventas_habitaciones')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('cajas.ventasHabitaciones') }}"
                                class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-bed"></span>
                                    </h1>
                                    <p class="card-text">Ventas habitaciones</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('recepciones.reporte_pospago')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('recepciones.reporte_pospago') }}"
                                class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-calendar-today"></span>
                                    </h1>
                                    <p class="card-text">Reporte estadías pos pago</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('comandas.reporte_venta')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('comandas.reporteVentas') }}"
                                class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-food-fork-drink"></span>
                                    </h1>
                                    <p class="card-text">Ventas por empleados</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('comandas.reporte_anulaciones')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('comandas.reporteAnulaciones') }}"
                                class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-cart-remove"></span>
                                    </h1>
                                    <p class="card-text">Reporte de anulaciones de comandas</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('clientes.credito')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('clientes.reporteCreditoEmpleadosForm') }}"
                                class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-file-account"></span>
                                    </h1>
                                    <p class="card-text">Reporte de créditos a empleados</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('comandas.reporteCreditos') }}"
                                class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-archive-clock"></span>
                                    </h1>
                                    <p class="card-text">Reporte de comandas en créditos</p>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('ordenes.reportes')
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('ordenes.reporte') }}" class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-toolbox-outline"></span>
                                    </h1>
                                    <p class="card-text">Reporte de ordenes de servicio</p>
                                </div>
                            </a>
                        </div>
                    @endcan

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script></script>
@endsection
