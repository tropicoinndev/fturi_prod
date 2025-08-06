@extends('layouts.app')

@section('style')
    <style>
        .sidebarPanel {
            position: fixed;
            width: 260px;
            min-height: 92vh;
            z-index: 1050;
            border-radius: 8px;
            left: 4px;
            bottom: 5px;
            overflow: auto;
        }

        .panel-body {
            min-height: 91vh;
        }

        /*aqui es para las bodegas que se pueden seleccionar*/
        .text,
        h5 {
            font-size: 1.2rem;
            font-family: Arial, sans-serif;
        }

        h6 {
            font-size: 1rem;
        }

        .card1,
        .card2,
        .card3,
        .cardpin,
        .cardinactiva {
            height: 14rem;
            text-align: center;
            margin-top: 10px;
            margin-right: 10px;
            padding: 3rem;
            border-radius: 10px;
            border: 2px solid grey;
        }

        .card1 {
            border-color: green;
        }

        .cardinactiva {
            width: 30rem;
        }

        #btnPanelBodega {
            position: fixed;
            bottom: 2%;
            left: 0.5%;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            z-index: 1200;
        }
    </style>

    @yield('styles')
@endsection

@section('content')
    <button class="btn" style="background-color: {{ session('bodega')->color_fondo }};" id="btnPanelBodega"
        onclick="setSideBarMenu(1)">
        <span class="mdi mdi-menu-open h4"></span>
    </button>
    <div class="d-flex flex-column flex-shrink-0 p-3 shadow sidebarPanel"
        style="background-color: {{ session('bodega')->color_fondo }};" id="sidebarPanelBodega">
        <div class="col-12 pt-2">
            <div class="dropdown">
                <a href="#" class="text-decoration-none h4 text-upercase" id="setSideBarMenu"
                    onclick="setSideBarMenu(0)">
                    <span class="mdi mdi-menu-open"></span>
                </a>
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="menu_bodegas"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <strong style="color: {{ session('bodega')->color_texto }};">{{ session('bodega')->bodega }}</strong>
                </a>
                <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser2">
                    <li><a class="dropdown-item" href="{{ route('bodegas.logout') }}">Salir</a></li>
                </ul>
            </div>
        </div>

        <hr style="color: {{ session('bodega')->color_texto }};">
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <b style="color: {{ session('bodega')->color_texto }};">Creacion</b>
            </li>
            @can('compras.index')
                <li class="nav-item">
                    <a href="{{ route('compras.index') }}" class="nav-link"
                        style="color: {{ session('bodega')->color_texto }};"><span class="mdi mdi-currency-usd"></span>
                        Compras</a>
                </li>
            @endcan
            @can('compras.index')
                <li class="nav-item">
                    <a href="{{ route('compras.historialCompras') }}" class="nav-link"
                        style="color: {{ session('bodega')->color_texto }};"><span
                            class="mdi mdi-currency-kzt"></span>Historial compras</a>
                </li>
            @endcan
            @can('requisiciones.create')
                <li class="nav-item">
                    <a href="{{ route('bodegas.my') }}" class="nav-link"
                        style="color: {{ session('bodega')->color_texto }};"><span
                            class="mdi mdi-file-arrow-up-down-outline"></span>Nueva requisicion</a>
                </li>
            @endcan
            @can('requisiciones.index')
                <li class="nav-item">
                    <a href="{{ route('requisiciones.historialBodegaRequisiciones') }}" class="nav-link"
                        style="color: {{ session('bodega')->color_texto }};"><span
                            class="mdi mdi-file-arrow-up-down-outline"></span>Historial requisiciones </a>
                </li>
            @endcan
            @can('productos.index')
                <li>
                    <a href="{{ route('productos.index') }}" class="nav-link"
                        style="color: {{ session('bodega')->color_texto }};"><span class="mdi mdi-cart"></span> Productos</a>
                </li>
            @endcan
            @can('requisiciones.index')
                <li>
                    <a href="{{ route('requisiciones.requisicion') }}" class="nav-link"
                        style="color: {{ session('bodega')->color_texto }};"><span class="mdi mdi-file-eye"></span> Mis
                        Requisiciones</a>
                </li>
            @endcan
            @can('requisiciones.create')
                <li>
                    <a href="{{ route('requisiciones.autorizarRequisiciones') }}" class="nav-link"
                        style="color: {{ session('bodega')->color_texto }};">
                        <span class="mdi mdi-file-document-multiple-outline"></span>Autorizacion requisiciones</a>
                </li>
            @endcan
            @can('requisiciones.index')
                <li>
                    <a href="{{ route('requisiciones.requisiciones_reporte') }}" class="nav-link"
                        style="color: {{ session('bodega')->color_texto }};">
                        <span class="mdi mdi-file-chart"></span> Reporte de requisiciones</a>
                </li>
            @endcan
            @can('requisiciones.create')
                <li>
                    <a href="{{ route('requisiciones.historia') }}" class="nav-link"
                        style="color: {{ session('bodega')->color_texto }};">
                        <span class="mdi mdi-file-clock"></span> Requisiciones historia</a>
                </li>
            @endcan
            @can('bodegas.bodega')
                <li>
                    <a href="{{ route('existencias.existencias_reporte') }}" class="nav-link"
                        style="color: {{ session('bodega')->color_texto }};">
                        <span class="mdi mdi-file-table"></span> Existencias por producto</a>
                </li>

                <li>
                    <a href="{{ route('existencias.reporte_existencia_bodega') }}" class="nav-link"
                        style="color: {{ session('bodega')->color_texto }};">
                        <span class="mdi mdi-file-table"></span> Existencias por bodega</a>
                </li>
            @endcan
            @can('existencias.vencimiento')
                <li>
                    <a href="{{ route('existencias.reporte_vencimiento') }}" class="nav-link"
                        style="color: {{ session('bodega')->color_texto }};">
                        <span class="mdi mdi-calendar-alert"></span>
                        Reporte de vencimientos
                    </a>
                </li>
            @endcan
        </ul>
        <hr>

    </div>
    <div class="container shadow panel-body p-4">
        <div class="row">
            <x-message></x-message>
        </div>

        @yield('panel_bodega')

    </div>
    <main id="notify-bodega">
        <bodegas id="Bodegas" chanel="bodegas.event.{{ session('bodega')->id }}" listen="BodegasEvent"></bodegas>
    </main>
    <script type="module">
        var app = appVue();
        app.component('bodegas', component.notify);
        app.mount("#notify-bodega");
    </script>
    <script>
        var sideBarMenu = 0;
        getMenu()

        function getMenu() {
            if (localStorage.getItem('menuBodega') != null) {
                sideBarMenu = parseInt(localStorage.getItem('menuBodega')) == 1;
            } else localStorage.setItem('menuBodega', sideBarMenu);
            var menu = document.getElementById("sidebarPanelBodega");
            var btn = document.getElementById("btnPanelBodega");

            menu.style.visibility = sideBarMenu == 1 ? 'visible' : 'hidden';
            btn.style.visibility = sideBarMenu == 0 ? 'visible' : 'hidden';
        }

        function setSideBarMenu(o) {
            sideBarMenu = o;
            localStorage.setItem('menuBodega', sideBarMenu)
            getMenu();
        }
    </script>
@endsection
