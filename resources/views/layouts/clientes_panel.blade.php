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

        #btnPanel {
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
    @yield('style-content')
@endsection

@section('content')
    <div class="container ">
        <div class="row justify-content-center">
            <div class="card shadow p-3">
                <div class="card-body app-container">
                    @yield('content_cliente')

                    {{-- Modal Agregar --}}
                    @if (isset($th['btnAdd']) && $th['btnAdd'])
                        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
                            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog" style="max-width: 800px;">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="staticBackdropLabel"><span
                                                class="mdi mdi-plus"></span> Agregar
                                            {{ $th['title'] }}</h1>

                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <x-dynamic-component :component="$th['table'] . '-form'" :table="$th['table']" :data="$data ?? ''" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- --}}

                </div>
            </div>
        </div>
    </div>
    <button class="btn" id="btnPanel" onclick="setSideBarMenu(1)"><span class="mdi mdi-menu-open h4"></span></button>
    <div class="nav-panel shadow" id="sidebarPanel">
        <div class="d-flex flex-column flex-shrink-0 p-3" id="sidebarPanelCaja">

            <div class="col-12 pt-2 pb-2">
                <div class="dropdown">
                    <a href="#" class="text-decoration-none h4" id="setSideBarMenu" onclick="setSideBarMenu(0)">
                        <span class="mdi mdi-menu-open"></span>
                    </a>
                    <a href="{{ route('clientes.panel.creditos') }}" class="text-decoration-none h4 text-uppercase ms-2">
                        Clientes
                    </a>
                </div>
            </div>

            <hr>

            <ul class="nav nav-pills flex-column mb-auto">
                @can('clientes.credito')
                    <li>
                        <a class="nav-link {{ request()->routeIs('clientes.reporte.alertasCredito') ? 'active' : '' }}"
                            href="{{ route('clientes.reporte.alertasCredito') }}">
                            <span class="mdi mdi-bell-badge h3"></span> Alertas de créditos
                        </a>
                    </li>
                    <li>
                        <a class="nav-link {{ request()->routeIs('clientes.reporte.alertasClientes') ? 'active' : '' }}"
                            href="{{ route('clientes.reporte.alertasClientes') }}">
                            <span class="mdi mdi-bell-badge h3"></span> Alertas de clientes
                        </a>
                    </li>
                    <li>
                        <a class="nav-link {{ request()->routeIs('clientes.reporte.creditos', 'clientes.reporte.creditosAcciones') ? 'active' : '' }}"
                            href="{{ route('clientes.reporte.creditos') }}">
                            <span class="mdi mdi-file-document-check h3"></span> Reporte de créditos
                        </a>
                    </li>
                @endcan

                @can('clientes.nivel_cautela')
                    <li>
                        <a class="nav-link {{ request()->routeIs('clientes.nivel_cautela', 'clientes.nivel_cautela_search') ? 'active' : '' }}"
                            href="{{ route('clientes.nivel_cautela') }}">
                            <span class="mdi mdi-account-edit h3"></span> Editar nivel cautela clientes
                        </a>
                    </li>
                @endcan

                @can('clientes.alertas')
                    <li>
                        <a href="#" class="nav-link dropdown-toggle" id="AlertasMovimientos" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <span class="mdi mdi-alert-outline h3"></span> Alertas de transacciones
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="AlertasMovimientos">
                            <a class="dropdown-item" href="{{ route('clientes.alertasEfectivo') }}">
                                <span class="mdi mdi-cash h3"></span> Alertas de efectivo
                            </a>
                            <a class="dropdown-item" href="{{ route('clientes.alertasBanco') }}">
                                <span class="mdi mdi-bank-outline h3"></span> Alertas de movimientos bancarios
                            </a>
                        </div>

                    </li>
                @endcan

                @can('clientes.empleado')
                    <li>
                        <a class="nav-link {{ request()->routeIs('clientes.empleados', 'clientes.empleados_search') ? 'active' : '' }}"
                            href="{{ route('clientes.empleados') }}">
                            <span class="mdi mdi-account-credit-card-outline h3"></span> Créditos a empleados
                        </a>
                    </li>
                @endcan

                @can('clientes.empleado')
                    <li>
                        <a class="nav-link {{ request()->routeIs('clientes.empleado_edit', 'clientes.empleado_search') ? 'active' : '' }}"
                            href="{{ route('clientes.empleado_edit') }}">
                            <span class="mdi mdi-account-cog-outline h3"></span> Configurar clientes
                        </a>
                    </li>
                @endcan

                @can('clientes.index')
                    <li>
                        <a class="nav-link {{ request()->routeIs('clientes.index', 'clientes.search', 'clientes.show') ? 'active' : '' }}"
                            href="{{ route('clientes.index') }}">
                            <span class="mdi mdi-account-group h3"></span> Clientes
                        </a>
                    </li>
                @endcan

                @can('clientes.credito')
                    <li>
                        <a class="nav-link {{ request()->routeIs('clientes.panel.creditos') ? 'active' : '' }}"
                            href="{{ route('clientes.panel.creditos') }}">
                            <span class="mdi mdi-credit-card-clock-outline h3"></span> Control de créditos
                        </a>
                    </li>
                @endcan

                @can('personas_naturales.index')
                    <li>
                        <a class="nav-link {{ request()->routeIs('personas_naturales.index') ? 'active' : '' }}"
                            href="{{ route('personas_naturales.index') }}">
                            <span class="mdi mdi-account-badge h3"></span> Personas naturales
                        </a>
                    </li>
                @endcan

                @can('personas_alertas.index')
                    <li>
                        <a class="nav-link {{ request()->routeIs('personas_alertas.index') ? 'active' : '' }}"
                            href="{{ route('personas_alertas.index') }}">
                            <span class="mdi mdi-target-account h3"></span> Personas alertas
                        </a>
                    </li>
                @endcan

                <li>
                    <a class="nav-link {{ request()->routeIs('operaciones_reguladas.index') ? 'active' : '' }}"
                        href="{{ route('operaciones_reguladas.index') }}">
                        <span class="mdi mdi-form-select h3"></span> Operaciones reguladas
                    </a>
                </li>
                <li>
                    <a class="nav-link {{ request()->routeIs('ros.index', 'ros.create') ? 'active' : '' }}"
                        href="{{ route('ros.index') }}">
                        <span class="mdi mdi-form-select h3"></span> Reporte de Operaciones Sospechosas
                    </a>
                </li>
            </ul>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var sideBarMenu = 0;
        getMenu()

        function getMenu() {
            if (localStorage.getItem('menu') != null) {
                sideBarMenu = parseInt(localStorage.getItem('menu')) == 1;
            } else localStorage.setItem('menu', sideBarMenu);
            var menu = document.getElementById("sidebarPanel");
            var btn = document.getElementById("btnPanel");

            menu.style.visibility = sideBarMenu == 1 ? 'visible' : 'hidden';
            btn.style.visibility = sideBarMenu == 0 ? 'visible' : 'hidden';
        }

        function setSideBarMenu(o) {
            sideBarMenu = o;
            localStorage.setItem('menu', sideBarMenu)
            getMenu();
        }
    </script>
    @yield('script-content')
@endsection
