@extends('layouts.app')

@section('style')
    <style>
        .sidebarPanel {
            position: fixed;
            height: 92vh;
            width: 260px;
            left: 6px;
            bottom: 12px;
            background: #FFF;
            border-radius: 6px;
            overflow-y: scroll;
        }

        .panel-body {
            background: #fff;
            min-height: 91vh;
        }

        .text,
        h5 {
            font-size: 1.2rem;
            font-family: Arial, sans-serif;
        }

        h6 {
            font-size: 1rem;
        }

        .sidebarPanel .nav-link,
        .sidebarPanel hr,
        .sidebarPanel .nav-item,
        .sidebarPanel .dropdown-toggle,
        #setSideBarMenu,
        #btnPanelCaja {
            color: green;
        }

        .sidebarPanel .nav-item:hover,
        .sidebarPanel .nav-link:hover {
            color: green;
            font-weight: bold;
        }

        #btnPanelProduccion {
            position: fixed;
            bottom: 2%;
            left: 2%;
            width: 64px;
            height: 64px;
            border-radius: 50%;
        }
    </style>
    @yield('css-produccion')
@endsection

@section('content')
    <div class="container">
        <div class="card panel-body shadow p-3">
            <div class="card-body">
                <div class="row">
                    <x-message></x-message>
                </div>
                @yield('panel_produccion')
            </div>
        </div>
    </div>
@endsection
