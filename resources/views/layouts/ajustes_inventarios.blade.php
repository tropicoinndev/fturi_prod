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

        #btnPanelAjustesInventarios {
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
    @yield('style-ajustes-inventarios')
@endsection

@section('script')
    <script>
        var sideBarMenu = 0;
        getMenu()

        function getMenu() {
            if (localStorage.getItem('menuAjustesInventarios') != null) {
                sideBarMenu = parseInt(localStorage.getItem('menuAjustesInventarios')) == 1;
            } else localStorage.setItem('menuAjustesInventarios', sideBarMenu);
            var menu = document.getElementById("sidebarPanelAjustesInventarios");
            var btn = document.getElementById("btnPanelAjustesInventarios");

            menu.style.visibility = sideBarMenu == 1 ? 'visible' : 'hidden';
            btn.style.visibility = sideBarMenu == 0 ? 'visible' : 'hidden';
        }

        function setSideBarMenu(o) {
            sideBarMenu = o;
            localStorage.setItem('menuAjustesInventarios', sideBarMenu)
            getMenu();
        }
    </script>
    @yield('script-ajustes-inventarios')
@endsection
@section('content')
    <div class="container ">
        <div class="row justify-content-center">
            <div class="card shadow p-5">
                <div class="card-body app-container" id="appAjustesInventarios">
                    @yield('ajustes_inventarios_content')
                </div>
            </div>
        </div>
    </div>
    <button class="btn" id="btnPanelAjustesInventarios" onclick="setSideBarMenu(1)"><span class="mdi mdi-menu-open h4"></span></button>
    <div class="nav-panel shadow" id="sidebarPanelAjustesInventarios">
        <div class="d-flex flex-column flex-shrink-0 p-3" id="sidebarPanelCaja">

            <div class="col-12 pt-2 pb-2">
                <div class="dropdown">
                    <a href="#" class="text-decoration-none h4" id="setSideBarMenu" onclick="setSideBarMenu(0)">
                        <span class="mdi mdi-menu-open"></span>
                    </a>
                    @can('ajustes_inventarios.index')
                        <a href="{{ route('ajustes_inventarios.index') }}" class="text-decoration-none h4 text-uppercase ms-2">
                            Dashboard
                        </a>
                    @endcan
                </div>
            </div>

            <hr>

            <ul class="nav nav-pills flex-column mb-auto">
                @can('ajustes_inventarios.create')
                    <li>
                        <a href="{{ route('ajustes_inventarios.create') }}" class="nav-link">
                            <span class="mdi mdi-file-document-plus h3"></span> Crear solicitud de ajuste
                        </a>
                    </li>
                @endcan
                @can('ajustes_inventarios.solicitados')
                    <li>
                        <a href="{{ route('ajustes_inventarios.solicitados') }}" class="nav-link">
                            <span class="mdi mdi-file-document-check h3"></span> Ajustes sin completar
                        </a>
                    </li>
                @endcan
                @can('ajustes_inventarios.negados')
                    <li>
                        <a href="{{ route('ajustes_inventarios.negados') }}" class="nav-link">
                            <span class="mdi mdi-file-document-minus h3"></span> Solicitudes negadas
                        </a>
                    </li>
                @endcan
                @can('ajustes_inventarios.autorizar')
                    <li>
                        <a href="{{ route('ajustes_inventarios.autorizar') }}" class="nav-link">
                            <span class="mdi mdi-file-clock h3"></span> Autorizar solicitudes
                        </a>
                    </li>
                @endcan
                @can('ajustes_inventarios.historial')
                    <li>
                        <a href="{{ route('ajustes_inventarios.historial') }}" class="nav-link">
                            <span class="mdi mdi-file-document-refresh h3"></span> Historial de aprobaciones
                        </a>
                    </li>
                @endcan
                @can('ajustes_inventarios.reporte')
                    <li>
                        <a href="{{ route('ajustes_inventarios.reporte') }}" class="nav-link">
                            <span class="mdi mdi-file-chart h3"></span> Reporte de ajustes
                        </a>
                    </li>
                @endcan
                @can('ajustes_inventarios.reporte')
                    <li>
                        <a href="{{ route('ajustes_inventarios.reporteExistenciasByBodega') }}" class="nav-link">
                            <span class="mdi mdi-file-chart h3"></span> Reporte de existencias por bodega
                        </a>
                    </li>
                @endcan
                @can('ajustes_inventarios.reporte')
                    <li>
                        <a href="{{ route('ajustes_inventarios.reporteExistenciasByProducto') }}" class="nav-link">
                            <span class="mdi mdi-file-chart h3"></span> Reporte de existencias por producto
                        </a>
                    </li>
                @endcan
            </ul>
        </div>
    </div>
@endsection
