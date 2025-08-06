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
    <script>
        var sideBarMenu = 0;
        getMenu()

        function getMenu() {
            if (localStorage.getItem('menuDTE') != null) {
                sideBarMenu = parseInt(localStorage.getItem('menuDTE')) == 1;
            } else localStorage.setItem('menuDTE', sideBarMenu);
            var menu = document.getElementById("sidebarPanelDTE");
            var btn = document.getElementById("btnPanelDTE");

            menu.style.visibility = sideBarMenu == 1 ? 'visible' : 'hidden';
            btn.style.visibility = sideBarMenu == 0 ? 'visible' : 'hidden';
        }

        function setSideBarMenu(o) {
            sideBarMenu = o;
            localStorage.setItem('menuDTE', sideBarMenu)
            getMenu();
        }
    </script>
    @yield('script-dte')
@endsection
@section('content')
    <div class="container ">
        <div class="row justify-content-center">
            <div class="card shadow p-5">
                <div class="card-body app-container" id="appDte">
                    @yield('dte_content')
                </div>
            </div>
        </div>
    </div>
    <button class="btn" id="btnPanelDTE" onclick="setSideBarMenu(1)"><span class="mdi mdi-menu-open h4"></span></button>
    <div class="nav-panel shadow" id="sidebarPanelDTE">
        <div class="d-flex flex-column flex-shrink-0 p-3" id="sidebarPanelCaja">

            <div class="col-12 pt-2 pb-2">
                <div class="dropdown">
                    <a href="#" class="text-decoration-none h4" id="setSideBarMenu" onclick="setSideBarMenu(0)">
                        <span class="mdi mdi-menu-open"></span>
                    </a>
                    <a href="{{ route('dte.index') }}" class="text-decoration-none h4 text-uppercase ms-2">
                        Dashboard
                    </a>
                </div>
            </div>

            <hr>

            <ul class="nav nav-pills flex-column mb-auto">
                <li>
                    <a href="{{ route('dte.comprobantes') }}" class="nav-link">
                        <span class="mdi mdi-file-document-check h3"></span> Comprobantes
                    </a>
                </li>
                <li>
                    <a href="{{ route('dte.procesados') }}" class="nav-link">
                        <span class="mdi mdi-file-document-check h3"></span> DTES Procesados
                    </a>
                </li>
                <li>
                    <a href="{{ route('dte.fallidos') }}" class="nav-link">
                        <span class="mdi mdi-file-document-remove h3"></span>
                        DTES Fallidos
                    </a>
                </li>
                <li>
                    <a href="{{ route('dte.observaciones') }}" class="nav-link">
                        <span class="mdi mdi-file-document-alert h3"></span>
                        DTES con observaciones
                    </a>

                </li>
                <li class="dropdown">
                    <button class="nav-link dropdown-toggle" type="button" id="btnInvalidaciones" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <span class="mdi mdi-file-document-alert h3"></span>Invalidaciones de DTE
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="btnInvalidaciones">
                        <li>
                            <a href="{{ route('dte_anulaciones.solicitudes') }}" class="dropdown-item">
                                <span class="mdi mdi-file-export h3"></span>
                                Solicitudes de anulaciones
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('dte_anulaciones.historia') }}" class="dropdown-item">
                                <span class="mdi mdi-file-document-alert h3"></span>
                                DTE invalidados
                            </a>
                        </li>
                    </ul>
                </li>


                <li class="dropdown">
                    <button class="nav-link dropdown-toggle" type="button" id="btnContingencias" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <span class="mdi mdi-alert h3"></span> Contingencias
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="btnContingencias">
                        <li>
                            <a href="{{ route('dte.contingencias_config') }}" class="dropdown-item">
                                <span class="mdi mdi-clipboard-alert-outline h3"></span>
                                Configuración de contingencias
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('contingencias_mh.index') }}" class="dropdown-item">
                                <span class="mdi mdi-clipboard-alert-outline h3"></span>
                                Contingencias MH
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('dte.lotes_index') }}" class="dropdown-item">
                                <span class="mdi mdi-file-document-multiple h3"></span>
                                Lotes enviados
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('dte.contingenciasIndex') }}" class="dropdown-item">
                                <span class="mdi mdi-format-list-bulleted-type h3"></span>
                                Tipos de contingencias
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="dropdown">
                    <button class="nav-link dropdown-toggle" type="button" id="btnInvalidaciones" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <span class="mdi mdi-invoice-edit-outline h3"></span> Cajas
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="btnInvalidaciones">
                        <li>
                            <a href="{{ route('cajas.index') }}" class="dropdown-item">
                                <span class="mdi mdi-file-document-edit h3"></span>
                                Cajas
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('comprobantes.cambioTurnos') }}" class="dropdown-item">
                                <span class="mdi mdi-file-document-edit h3"></span>
                                Editar turno
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('comprobantes.AperturaTurnos') }}" class="dropdown-item">
                                <span class="mdi mdi-file-document-edit h3"></span>
                                Apertura de turno
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('cajas.buscar_cuentas') }}" class="dropdown-item">
                                <span class="mdi mdi-file-document-edit h3"></span>
                                Buscar cuentas
                            </a>
                        </li>

                    </ul>
                </li>

                <li>
                    <a href="{{ route('anticiposVisual.index') }}" class="nav-link">
                        <span class="mdi mdi-currency-usd-off h3"></span>
                        Anticipos visual
                    </a>
                </li>


            </ul>
        </div>
    </div>
@endsection
