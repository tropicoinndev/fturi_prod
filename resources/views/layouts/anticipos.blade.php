@extends('layouts.app')

@section('style')
    <style>
        :root {
            --sidebar-width: 280px;
            --sidebar-bg-color: #fff;
            --sidebar-text-color: #000;
            --sidebar-btn-color: #007bff;
            --sidebar-border-radius: 8px;
            --sidebar-z-index: 1050;
            --button-size: 64px;
            --button-bg-color: #1e726a;
            --button-hover-bg-color: #0056b3;
            --button-text-color: #fff;
        }

        .sidebarPanel {
            position: fixed;
            width: var(--sidebar-width);
            height: 92vh;
            left: 5px;
            z-index: var(--sidebar-z-index);
            transition: transform 0.3s ease-in-out;
            background: var(--sidebar-bg-color);
            border-radius: var(--sidebar-border-radius);
            overflow: auto;
            transform: translateX(-100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .sidebarPanel.visible {
            transform: translateX(0);
        }

        .sidebarPanel .nav .nav-item .nav-link,
        #setSideBarMenu,
        #btnPanelAnticipo {
            color: var(--sidebar-text-color);
            font-size: 1rem;
        }

        .panel-body {
            min-height: 91vh;
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        #btnPanelAnticipo {
            position: fixed;
            bottom: 4rem;
            left: 5rem;
            width: var(--button-size);
            height: var(--button-size);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1200;
            transition: background 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            cursor: pointer;
        }

        #btnPanelAnticipo,
        #sidebarPanelAnticipo .btn {
            background-color: var(--button-bg-color) !important;
            color: var(--button-text-color) !important;
        }

        .anticipo,
        #sidebarPanelAnticipo .btn.btn-light {
            background-color: var(--button-bg-color) !important;
            color: var(--button-text-color) !important;
        }
    </style>
    @yield('css-anticipos')
@endsection

@section('content')
    <button class="btn btn-light" id="btnPanelAnticipo" onclick="anticipoSiderBarMenu()">
        <span class="mdi mdi-cash-marker h4"></span>
    </button>


    <div class="d-flex flex-column flex-shrink-0 p-3 shadow sidebarPanel" id="sidebarPanelAnticipo">
        <div class="d-flex align-items-center">
            <button class="btn btn-light" id="setSideBarMenu" onclick="anticipoSiderBarMenu()">
                <span class="mdi mdi-cash-marker h4"></span>
            </button>
            <a href="#" class="ms-3 text-decoration-none fw-bold text-dark h4" id="dropdownUser2"
                data-bs-toggle="dropdown" aria-expanded="false">
                Anticipos
            </a>
            <ul class="dropdown-menu text-small shadow " aria-labelledby="dropdownUser2">
                <li><a class="dropdown-item" href="{{ url('/dashboard') }}">Salir</a></li>
            </ul>
        </div>

        <hr>

        <ul class="nav nav-pills flex-column mb-auto">
            @can('anticipos.reporte')
                <li class="nav-item">
                    <a href="{{ route('anticipos.reporte_anticipo') }}" class="nav-link">
                        <span class="mdi mdi-cash-check h3"></span> Anticipos disponibles
                    </a>
                </li>
            @endcan
            @can('anticipos.index')
                <li class="nav-item">
                    <a href="{{ route('anticipos.index') }}" class="nav-link">
                        <span class="mdi mdi-cash-register h3"></span> Anticipos
                    </a>
                </li>
            @endcan
            @can('anticipos.anulados')
                <li class="nav-item">
                    <a href="{{ route('anticipos.anulado_anticipo') }}" class="nav-link">
                        <span class="mdi mdi-cash-off h3"></span> Anticipos anulados
                    </a>
                </li>
            @endcan
            @can('anticipos.nuevo')
                <li class="nav-item">
                    <a href="{{ route('anticipos.create') }}" class="nav-link">
                        <span class="mdi mdi-cash-plus h3"></span> Nuevo anticipo
                    </a>
                </li>
            @endcan

            @can('anticipos.nuevo')
                <li class="nav-item">
                    <a href="{{ route('anticipos.alertaAnticipos') }}" class="nav-link">
                        <span class="mdi mdi-cash-remove h3"></span> Alerta anticipos
                    </a>
                </li>
            @endcan

            @can('anticipos.nuevo')
                <li class="nav-item">
                    <div class="dropdown ms-3 mt-3">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Reporte anticipos activos
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('anticipos.reporteAnticiposForm1') }}">Por fecha de inicio y fin</a></li>
                            <li><a class="dropdown-item" href="{{ route('anticipos.reporteAnticiposForm2') }}">Por fecha de aplicación</a></li>
                        </ul>
                    </div>
                </li>
            @endcan
        </ul>
    </div>
    <div class="container panel-body">
        <div class="row">
            <x-message></x-message>
        </div>
        @yield('panel_anticipo')
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            getAnticipoMenu();
        });

        function anticipoSiderBarMenu() {
            let menu = document.getElementById("sidebarPanelAnticipo");
            menu.classList.toggle('visible');
            let sideBarMenuState = menu.classList.contains('visible') ? 1 : 0;
            localStorage.setItem('menuAnticipo', sideBarMenuState);
            viewAnticipoMenu(sideBarMenuState);
        }

        function getAnticipoMenu() {
            let sideBarMenuState = parseInt(localStorage.getItem('menuAnticipo')) || 0;
            let menu = document.getElementById("sidebarPanelAnticipo");
            if (sideBarMenuState === 1) {
                menu.classList.add('visible');
            }
            viewAnticipoMenu(sideBarMenuState);
        }

        function viewAnticipoMenu(isVisible) {
            let button = document.getElementById("btnPanelAnticipo");
            button.style.display = isVisible ? 'none' : 'flex';
        }
    </script>
@endsection
