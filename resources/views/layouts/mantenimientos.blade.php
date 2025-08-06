@extends('layouts.app')

@section('style')
    <style>
        .sidebarPanel {
            position: fixed;
            width: 280px;
            height: 92vh;
            z-index: 1050;
            transition: width 0.3s ease-in-out;
            background: #E0F2F1;
            border-radius: 8px;
            overflow: auto;


        }

        .sidebarPanel .nav .nav-item .nav-link,
        #setSideBarMenu,
        #btnPanelMantenimiento {
            color: #37474F;
            font-size: 12pt;
        }

        .panel-body {
            min-height: 91vh;
        }


        #btnPanelMantenimiento {
            position: fixed;
            bottom: 9%;
            left: 5%;
            width: 64px;
            height: 64px;
            border-radius: 50%;
            z-index: 1200;

        }
    </style>

    @yield('css-mantenimiento')
@endsection

@section('content')
    <button class="btn btn-secondary" id="btnPanelMantenimiento" onclick="setSideBarMenu(1)"><span
            class="mdi mdi-menu h4"></span></button>
    <div class="d-flex flex-column flex-shrink-0 p-3 shadow sidebarPanel " id="siderbarPanelMantenimiento">
        <div class="col-12 fs-4 d-flex align-items-center">
            <button  class="btn btn-secondarytext-decoration-none h1 mr-3" id="setSideBarMenu" onclick="setSideBarMenu(0)">
                <span class="mdi mdi-menu-open" ></span>
            </button>
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle ml-3" id="dropdownUser2"
                data-bs-toggle="dropdown" aria-expanded="false">
                <strong>Mantenimiento</strong>
            </a>
            <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser2">
                <li><a class="dropdown-item" href="{{ url('/dashboard') }}">Salir</a></li>
            </ul>
        </div>

        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item h4 text-muted fw-bold">
                Panel
            </li>
            @can('mantenimientos.habitaciones')
                <li class="nav-item">


                    <a href=" {{ route('mantenimientos.habitaciones') }}" class="nav-link "><span
                            class="mdi mdi-account-plus"></span> Asignar habitaciones</a>

                </li>
            @endcan
            @can('mantenimientos.index')
                <li class="nav-item">


                    <a href="{{ route('mantenimientos.index') }}" class="nav-link ">
                        <span class="mdi mdi-account-wrench"></span>
                        Mantenimientos</a>

                </li>
            @endcan
            @can('tipo_mantenimientos.index')
                <li class="nav-item">

                    <a href=" {{ route('tipo_mantenimientos.index') }}" class="nav-link "><span
                            class="mdi mdi-account-wrench-outline"></span> Tipos de mantenimientos</a>
                </li>
            @endcan

            @can('mantenimientos.asignacion')
                <li class="nav-item">

                    <a href=" {{ route('mantenimientos.asignacionMantenimiento') }}" class="nav-link "><span
                            class="mdi mdi-account-clock"></span> Asignar mantenimientos</a>
                </li>
            @endcan
            @can('mantenimientos.completar')
                <li class="nav-item">

                    <a href=" {{ route('mantenimientos.confirmacionMantenimiento') }}" class="nav-link "><span
                            class="mdi mdi-account-key"></span> Mis asignaciones</a>
                </li>
            @endcan
            @can('mantenimientos.supervisor')
                <li class="nav-item">

                    <a href=" {{ route('mantenimientos.supervisionMantenimiento') }}" class="nav-link "><span
                class="mdi mdi-account-supervisor-circle-outline"></span> Supervisar
                        mant.</a>

                </li>
            @endcan
            @can('mantenimientos.supervisor')
                <li class="nav-item">

                    <a href=" {{ route('mantenimientos.mantenimientos_reporte') }}" class="nav-link "><span
                            class="mdi mdi-file-chart-outline"></span> Reporte de mant.</a>

                </li>
            @endcan

        </ul>


    </div>
    <div class="container shadow panel-body p-4">
        <div class="row">
            <x-message></x-message>
        </div>

        @yield('panel_mantenimiento')

    </div>
    <script>
        var sideBarMenu = 0;
        getMenu()

        function getMenu() {
            if (localStorage.getItem('menuMantenimiento') != null) {
                sideBarMenu = parseInt(localStorage.getItem('menuMantenimiento')) == 1;
            } else localStorage.setItem('menuMantenimiento', sideBarMenu);
            var menu = document.getElementById("siderbarPanelMantenimiento");
            var btn = document.getElementById("btnPanelMantenimiento");

            menu.style.visibility = sideBarMenu == 1 ? 'visible' : 'hidden';
            btn.style.visibility = sideBarMenu == 0 ? 'visible' : 'hidden';
        }

        function setSideBarMenu(o) {
            sideBarMenu = o;
            localStorage.setItem('menuMantenimiento', sideBarMenu)
            getMenu();
        }
    </script>
@endsection
