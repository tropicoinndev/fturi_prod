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
        background-color: #f8f9fa;
    }

    .panel-body {
        min-height: 91vh;
        background-color: #ffffff;
    }

    .text,
    h5 {
        font-size: 1.2rem;
        font-family: Arial, sans-serif;
        color: #333333;
        /* Color de texto minimalista */
    }

    h6 {
        font-size: 1rem;
        color: #555555;
        /* Color de texto minimalista */
    }

    .card1 {
        border-color: green;
    }

    .cardinactiva {
        width: 30rem;
    }

    #btnPanelEvento {
        position: fixed;
        bottom: 2%;
        left: 0.5%;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #6c757d;
        /* Color de fondo del botón minimalista */
        color: #ffffff;
        /* Color de texto del botón */
        z-index: 1200;
    }
</style>

@yield('styles')
@endsection

@section('content')
<button class="btn" id="btnPanelEvento" onclick="setSideBarMenu(1)">
    <span class="mdi mdi-calendar-filter h4"></span>
</button>
<div class="d-flex flex-column flex-shrink-0 p-3 shadow sidebarPanel" id="sidebarPanelEvento">
    <div class="col-12 pt-2">
        <div class="dropdown">
            <a href="#" class="text-decoration-none h4 text-uppercase" id="setSideBarMenu" onclick="setSideBarMenu(0)">
                <span class="mdi mdi-calendar-filter"></span>
            </a>
        </div>
    </div>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto btn-light">
        <li class="nav-item">
            <b>Seccion de reportes </b>
        </li>
        @can('eventos.index')
        <li class="nav-item btn-light">
            <a href="{{ route('eventos.eventos_reporte') }}" class="nav-link text-secondary"><span class="mdi mdi-calendar-plus"></span> Eventos</a>
        </li>
        @endcan
        @can('eventos.sonidos')
        <li class="nav-item btn-light">
            <a href="{{ route('eventos.reporte_sonidos') }}" class="nav-link text-secondary"><span class="mdi mdi-speaker-stop"></span>Sonidos</a>
        </li>
        @endcan
        @can('eventos.montajes')
        <li class="nav-item btn-light">
            <a href="{{ route('eventos.reporte_montajes') }}" class="nav-link text-secondary"><span class="mdi mdi-account-wrench"></span>Montajes</a>
        </li>
        @endcan
        @can('eventos.ventas')
        <li class="nav-item btn-light">
            <a href="{{ route('eventos.reporte_venta') }}" class="nav-link text-secondary"><span class="mdi mdi-account-cash"></span>  Ventas</a>
        </li>
        @endcan
        @can('eventos.descargo')
        <li class="nav-item btn-light">
            <a href="{{ route('eventos.reporte_descargo') }}" class="nav-link text-secondary"><span class="mdi mdi-food-variant"></span>  Descargos</a>
        </li>
        @endcan
        @can('eventos.produccion')
        <li class="nav-item btn-light">
            <a href="{{ route('eventos.cocina') }}" class="nav-link text-secondary"><span class="mdi mdi-food-fork-drink"></span>  Produccion</a>
        </li>
        @endcan
    </ul>
    <hr>
</div>
<div class="container shadow panel-body p-4">
    <div class="row">
        <x-message></x-message>
    </div>
    @yield('panel_reporte_eventos')
</div>
<script>
    var sideBarMenu = 0;
    getMenu();

    function getMenu() {
        if (localStorage.getItem('menuEvento') != null) {
            sideBarMenu = parseInt(localStorage.getItem('menuEvento')) == 1;
        } else localStorage.setItem('menuEvento', sideBarMenu);
        var menu = document.getElementById("sidebarPanelEvento");
        var btn = document.getElementById("btnPanelEvento");

        menu.style.visibility = sideBarMenu == 1 ? 'visible' : 'hidden';
        btn.style.visibility = sideBarMenu == 0 ? 'visible' : 'hidden';
    }

    function setSideBarMenu(o) {
        sideBarMenu = o;
        localStorage.setItem('menuEvento', sideBarMenu)
        getMenu();
    }
</script>
@endsection
