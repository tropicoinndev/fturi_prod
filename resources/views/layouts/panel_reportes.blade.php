@extends('layouts.app')

@section('style')
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-bg-color: #f8f9fa;
            --panel-bg-color: #ffffff;
            --text-color-primary: #333333;
            --text-color-secondary: #555555;
            --btn-bg-color: #6c757d;
            --btn-text-color: #ffffff;
        }

        body {
            background: #FFCCBC;
        }

        .sidebar-panel {
            position: fixed;
            width: var(--sidebar-width);
            min-height: 92vh;
            z-index: 1050;
            border-radius: 8px;
            left: 4px;
            bottom: 5px;
            overflow: auto;
            background-color: var(--sidebar-bg-color);
        }

        .panel-body {
            min-height: 91vh;
            background-color: var(--panel-bg-color);
        }

        .text-primary,
        h5 {
            font-size: 1.2rem;
            font-family: Arial, sans-serif;
            color: var(--text-color-primary);
        }

        h6 {
            font-size: 1rem;
            color: var(--text-color-secondary);
        }

        .card-active {
            border-color: green;
        }

        .card-inactive {
            width: 30rem;
        }

        #btnPanelEvento {
            position: fixed;
            bottom: 4rem;
            left: 5rem;
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background-color: var(--btn-bg-color);
            color: var(--btn-text-color);
            z-index: 1200;
        }

        .table-white,
        .table-white tr th,
        .table-white tr td {
            background: white;
        }

        @media print {

            #btnPanelEvento,
            #sidebarPanelReporte {
                display: none !important;
            }
        }
    </style>
    @yield('css-panel_reportes')
@endsection

@section('content')
    <button class="btn" id="btnPanelEvento" onclick="toggleSidebarMenu()">
        <span class="mdi mdi-chart-bar h4"></span>
    </button>
    <div class="d-flex flex-column flex-shrink-0 p-3 shadow sidebar-panel" id="sidebarPanelReporte">
        <div class="col-12 pt-2">
            <div class="dropdown">
                <a href="#" class="text-decoration-none h4 text-uppercase" id="toggleSidebar"
                    onclick="toggleSidebarMenu()">
                    <span class="mdi mdi-chart-bar"></span>
                </a>
                <a href="#" class="ms-3 text-decoration-none fw-bold text-dark h4" id="dropdownUser2"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    Panel de reportes
                </a>
                <ul class="dropdown-menu text-small shadow " aria-labelledby="dropdownUser2">
                    <li><a class="dropdown-item" href="{{ url('/dashboard') }}">Salir</a></li>
                </ul>
            </div>
        </div>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto btn-light">
            <li class="nav-item">
                <a class="btn btn-light" href="{{ route('cajas.panel_reportes_sub_menu') }}">Inicio</a>
            </li>
            <li class="nav-item">
                <b>Reportes </b>
            </li>
            @canany(['cajas.ventas_habitaciones', 'cajas.ventaRubros', 'panel.turnos'])
                <div class="nav-item">
                    <a class="btn btn-light dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="mdi mdi-file-export"></span>
                        Ventas
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        @can('cajas.ventas_habitaciones')
                            <li>
                                <a class="dropdown-item" href="{{ route('cajas.ventasHabitaciones') }}">
                                    Ventas habitaciones
                                </a>
                            </li>
                        @endcan
                        @can('cajas.ventaRubros')
                            <li>
                                <a class="dropdown-item" href="{{ route('cajas.ventaRubros') }}">
                                    Ventas por rubro
                                </a>
                            </li>
                        @endcan
                        @can('panel.turnos')
                            <li>
                                <a class="dropdown-item" href="{{ route('cajas.panel_reportes') }}">
                                    Reporte de turnos
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('cajas.reporteTurnosDiarios') }}">
                                    Reporte de turnos diarios
                                </a>
                            </li>
                        @endcan
                        @can('panel.reporte')
                            <li>
                                <a class="dropdown-item" href="{{ route('cajas.reporteTurnosDiarios') }}">
                                    Reporte de turnos diarios
                                </a>
                            </li>
                        @endcan
                        @can('clientes.credito')
                            <li>
                                <a class="dropdown-item" href="{{ route('clientes.reporteCreditoEmpleadosForm') }}">
                                    Reporte de créditos a empleados
                                </a>
                            </li>
                        @endcan
                        @can('recepciones.reporte_pospago')
                            <li>
                                <a class="dropdown-item" href="{{ route('recepciones.reporte_pospago') }}">
                                    Reporte de estadías en pos-pago
                                </a>
                            </li>
                        @endcan


                    </ul>
                </div>
            @endcanany




            @canany(['libros.contribuyentes', 'libros.consumidor'])
                <div class="nav-item">
                    <a class="btn btn-light dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="mdi mdi-file-export"></span>
                        Libros de IVA
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        @can('libros.contribuyentes')
                            <li>
                                <a class="dropdown-item" href="{{ route('libros.contribuyentes') }}">
                                    Libro contribuyentes
                                </a>
                            </li>
                        @endcan
                        @can('libros.consumidor')
                            <li>
                                <a class="dropdown-item" href="{{ route('libros.consumidor') }}">
                                    Libro consumidor final
                                </a>
                            </li>
                        @endcan

                    </ul>
                </div>
            @endcanany

            @canany(['anexos.contribuyentes', 'anexos.consumidor', 'anexos.invalidados', 'anexos.sujetos_excluidos'])
                <div class="nav-item">
                    <a class="btn btn-light dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="mdi mdi-file-export"></span>
                        Anexos F07
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">

                        @can('anexos.contribuyentes')
                            <li>
                                <a class="dropdown-item" href="{{ route('anexos.contribuyentes') }}">
                                    Ventas a contribuyentes
                                </a>
                            </li>
                        @endcan
                        @can('anexos.consumidor')
                            <li>
                                <a class="dropdown-item" href="{{ route('anexos.consumidor') }}">
                                    Ventas a consumidor final
                                </a>
                            </li>
                        @endcan
                        @can('anexos.invalidados')
                            <li>
                                <a class="dropdown-item" href="{{ route('anexos.invalidados') }}">
                                    Anulados / Invalidados
                                </a>
                            </li>
                        @endcan
                        @can('anexos.sujetos_excluidos')
                            <li>
                                <a class="dropdown-item" href="{{ route('anexos.sujetos') }}">
                                    Sujetos Excluidos
                                </a>
                            </li>
                        @endcan
                    </ul>
                </div>
            @endcanany

            @canany(['cajas.comandas_activas', 'cajas.ventas', 'panel.cocina', 'panel.bar'])
                <div class="nav-item">
                    <a class="btn btn-light dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="mdi mdi-file-export"></span>
                        Comandas
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        @can('cajas.comandas_activas')
                            <li>
                                <a class="dropdown-item" href="{{ route('cajas.comandasActivas') }}">
                                    Comandas Activas
                                </a>
                            </li>
                        @endcan
                        @can('cajas.ventas')
                            <li>
                                <a class="dropdown-item" href="{{ route('cajas.panel_reporte_venta') }}">
                                    Ventas
                                </a>
                            </li>
                        @endcan
                        @can('panel.cocina')
                            <li>
                                <a class="dropdown-item" href="{{ route('cajas.panel_cocina') }}">
                                    Cocina
                                </a>
                            </li>
                        @endcan
                        @can('panel.bar')
                            <li>
                                <a class="dropdown-item" href="{{ route('cajas.panel_bar') }}">
                                    Bar
                                </a>
                            </li>
                        @endcan
                        @can('cajas.reporte_venta')
                            <li>
                                <a class="dropdown-item" href="{{ route('comandas.reporteVentas') }}">
                                    Ventas por empleado
                                </a>
                            </li>
                        @endcan
                        @can('comandas.reporte_anulaciones')
                            <li>
                                <a class="dropdown-item" href="{{ route('comandas.reporteAnulaciones') }}">
                                    Reporte de anulaciones
                                </a>
                            </li>
                        @endcan
                        @can('clientes.credito')
                            <li>
                                <a class="dropdown-item" href="{{ route('comandas.reporteCreditos') }}">
                                    Reporte de créditos
                                </a>
                            </li>
                        @endcan
                    </ul>
                </div>
            @endcanany

            @can('ordenes.reportes')
                <li class="nav-item">
                    <a class="btn btn-light" href="{{ route('ordenes.reporte') }}">Ordenes de servicio</a>
                </li>
            @endcan

        </ul>
        <hr>
    </div>
    <div class="container shadow panel-body p-4">
        <div class="row">
            <x-message></x-message>
        </div>
        @yield('panel_reportes')
    </div>
    <script>
        const sidebarPanel = document.getElementById('sidebarPanelReporte');
        const btnPanelEvento = document.getElementById('btnPanelEvento');

        function getMenuState() {
            return localStorage.getItem('menuPanelReporte') === '1';
        }

        function setMenuState(state) {
            localStorage.setItem('menuPanelReporte', state ? '1' : '0');
        }

        function toggleSidebarMenu() {
            const isVisible = getMenuState();
            setMenuState(!isVisible);
            updateMenuVisibility();
        }

        function updateMenuVisibility() {
            const isVisible = getMenuState();
            sidebarPanel.style.visibility = isVisible ? 'visible' : 'hidden';
            btnPanelEvento.style.visibility = isVisible ? 'hidden' : 'visible';
        }

        updateMenuVisibility();
    </script>
@endsection
