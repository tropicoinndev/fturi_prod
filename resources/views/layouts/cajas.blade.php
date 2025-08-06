@extends('layouts.app')

@section('style')
    @if (!session('caja'))
        <script>
            window.location = "{{ route('cajas.login') }}";
        </script>
        {{ exit() }}
    @endif

    <style>
        .sidebarPanel {
            position: fixed;
            height: 92vh;
            z-index: 1050;
            top: 75px;
            border-radius: 8px;
            width: 280px;
            left: 5px;
            bottom: 5px;
            overflow: auto;
        }

        .panel-body {
            min-height: 91vh;
        }



        .cardinactiva {
            width: 30rem;
        }


        /**aquí es para las cajas que se pueden seleccionar */
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

        .sidebarPanel .nav-link,
        .sidebarPanel hr,
        .sidebarPanel .nav-item,
        .sidebarPanel .dropdown-toggle,
        #setSideBarMenu,
        #btnPanelCaja {
            color: {{ session('caja')->color_texto }};
        }

        .sidebarPanel .nav-item:hover,
        .sidebarPanel .nav-link:hover,
        .nav-link:focus,
        .nav-link.clicked {
            color: {{ session('caja')->color_texto }};
        }

        #btnPanelCaja {
            position: fixed;
            bottom: 2%;
            left: 2%;
            width: 64px;
            height: 64px;
            border-radius: 50%;
            z-index: 1200;
        }
    </style>
    @yield('css-caja')
@endsection

@section('content')
    <button class="btn" style="background-color: {{ session('caja')->color_fondo }};" id="btnPanelCaja"
        onclick="setSideBarMenu(1)"><span class="mdi mdi-menu-open h4"></span></button>

    <div class="d-flex flex-column flex-shrink-0 p-3 shadow sidebarPanel"
        style="background-color: {{ session('caja')->color_fondo }};" id="sidebarPanelCaja">

        <div class="col-12 pt-2 pb-2">
            <div class="dropdown">
                <a href="#" class="text-decoration-none h4" id="setSideBarMenu" onclick="setSideBarMenu(0)">
                    <span class="mdi mdi-menu-open"></span>
                </a>
                <a href="#" class="text-decoration-none dropdown-toggle h4 text-uppercase ms-2" id="menu_cajas"
                    data-bs-toggle="dropdown">
                    {{ session('caja')->caja }}
                </a>
                <ul class="dropdown-menu text-small shadow" aria-labelledby="menu_cajas">
                    @can('correlativos.create')
                        <li>
                            <a class="dropdown-item" href="{{ route('correlativos.create') }}">
                                Agregar bloque
                            </a>
                        </li>
                    @endcan
                    @can('cajas.caja')
                        <li>
                            <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#cambiarPin">
                                Cambiar PIN
                            </button>
                        </li>
                    @endcan
                    @can('turnos.create')
                        <li>
                            <a class="dropdown-item" href="{{ route('cajas.cierre') }}">
                                Cerrar turno
                            </a>
                        </li>
                    @endcan
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('cajas.logout') }}">
                            Salir
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <small class="col-12 text-uppercase" style="color: {{ session('caja')->color_texto }};">
            {{ session('turno')->opcion->turno }}
            {{ session('turno')->fecha }}
        </small>
        <hr>

        <ul class="nav nav-pills flex-column mb-auto">
            <li>
                <a href="{{ route('cajas.menu') }}" class="nav-link">
                    Menu
                </a>
            </li>
            @can('comprobantes.index')
                <li class="nav-item">
                    <a href="{{ route('cajas.my') }}" class="nav-link">
                        Solicitudes de comprobantes
                    </a>
                </li>
            @endcan
            @can('cajas.caja')
                <li class="nav-item">
                    <a type="button" class="nav-link" id="dropdownCreditosCuentas" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Cuentas en crédito
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownCreditosCuentas">
                        <li>
                            <a href="{{ route('cajas.pospago') }}" class="dropdown-item">
                                Estadías
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('cajas.comandas_creditos') }}" class="dropdown-item">
                                Comandas
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan

            @can('cobros.index')
                <li class="nav-item">
                    <a href="{{ route('cobros.index') }}" class="nav-link">
                        Cobros
                    </a>
                </li>
            @endcan
            @can('recepciones.index')
                <li class="nav-item">
                    <a href="{{ route('recepciones.index') }}" class="nav-link">
                        Recepciones
                    </a>
                </li>
            @endcan
            @can('ordenes.index')
                <li>
                    <a href="{{ route('ordenes.index') }}" class="nav-link">
                        Ordenes
                    </a>
                </li>
            @endcan
            @can('comandas.index')
                <li>
                    <a href="{{ route('comandas.index') }}" class="nav-link">
                        Comandas
                    </a>
                </li>
            @endcan
            @can('anulacion_comprobantes.index')
                <!--cSpell:ignore anulacion, opcion, cpin, npin, cardinactiva, cardpin, diseno,  -->
                <li>
                    <a href="{{ route('anulacion_comprobantes.index') }}" class="nav-link">
                        Anular comprobantes
                    </a>
                </li>
                <li>
                    <a href="{{ route('anulacion_comprobantes.list') }}" class="nav-link">
                        Historial anulaciones
                    </a>
                </li>
            @endcan
            <li>
                <a type="button" class="nav-link" id="dropdownReportes" data-bs-toggle="dropdown" aria-expanded="false">
                    Reportes
                </a>
                <ul class="dropdown-menu" aria-labelledby="dropdownReportes">
                    @can('cajas.caja')
                        <li>
                            <a href="{{ route('cajas.turnos_reporte') }}" class="dropdown-item">
                                Reporte de turnos
                            </a>
                        </li>
                    @endcan
                    @can('cajas.ventas_reporte')
                        <li>
                            <a href="{{ route('cajas.ventas_reporte') }}" class="dropdown-item">
                                Reporte de ventas
                            </a>
                        </li>
                    @endcan
                    @can('produccion.cocina')
                        <li>
                            <a href="{{ route('comandas.produccion.view_reporte_cocina') }}" class="dropdown-item">
                                Reporte de pedidos cocina
                            </a>
                        </li>
                    @endcan
                    @can('produccion.bar')
                        <li>
                            <a href="{{ route('comandas.produccion.view_reporte_bar') }}" class="dropdown-item">
                                Reporte de pedidos bar
                            </a>
                        </li>
                    @endcan

                </ul>

            </li>
            <li>
                <a href="{{ route('ros.create') }}" class="nav-link">
                    Crear ROS
                </a>
            </li>
        </ul>
        <hr>

        <ul class="nav nav-pills flex-column mb-4">
            <li class="nav-item"><b style="">Creación</b></li>
            @can('sujeto_excluido.create')
                <li>
                    <a href="{{ route('sujeto_excluido.index') }}" class="nav-link">
                        Sujeto excluido
                    </a>
                </li>
            @endcan
            @can('abonos.create')
                <li>
                    <a href="{{ route('abonos.create') }}" class="nav-link">
                        Ingreso a caja
                    </a>
                </li>
            @endcan
            @can('clientes.create')
                <li>
                    <a href="{{ route('clientes.create') }}" class="nav-link">
                        Agregar cliente
                    </a>
                </li>
            @endcan
            @can('eventos.create')
                <li>
                    <a href="{{ route('eventos.eventos') }}" class="nav-link">
                        Agregar evento
                    </a>
                </li>
            @endcan
            @can('anticipos.create')
                <li>
                    <a href="{{ route('anticipos.create') }}" class="nav-link">
                        Agregar anticipos
                    </a>
                </li>
            @endcan
            @can('correlativos.create')
                <li>
                    <a href="{{ route('correlativos.create') }}" class="nav-link">
                        Agregar bloque
                    </a>
                </li>
            @endcan


        </ul>
    </div>

    {{-- <div id="desktop"> --}}
    <div class="container">
        <div class="card panel-body shadow p-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <h3 class="card-title text-uppercase">{{ $th['title'] ?? '' }}</h3>
                        <p class="text-uppercase text-muted">{{ $th['sub'] ?? '' }}</p>
                    </div>
                    <x-message></x-message>
                </div>
                @yield('panel_caja')<!--Este panel entra en conflicto con los diseños móviles-->
            </div>
        </div>
    </div>
    {{-- </div> --}}

    {{-- <div id="movil" style="margin-top: -24px; width: auto;">
        <!--El diseño movil debe estar fuera de la card de arriba-->
        @yield('diseno_movil_comandas')
    </div> --}}



    <!-- Modal -->
    @yield('modal')
    <div class="modal fade" id="cambiarPin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cambiar PIN</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('cajas_users.pin') }}" method="post">
                    @csrf
                    <input type="hidden" name="caja" value="{{ session('caja')->id }}">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="pin">PIN actual</label>
                            <input type="password" class="form-control" name="pin" id="pin"
                                aria-describedby="helpId" placeholder="Escriba su PIN actual" min="4"
                                max="4" required pattern="[0-9]{4}">
                        </div>
                        <div class="form-group">
                            <label for="npin">Nuevo PIN</label>
                            <input type="password" class="form-control" name="npin" id="npin"
                                aria-describedby="helpId" placeholder="Escriba su nuevo PIN" min="4"
                                max="4" required pattern="[0-9]{4}">
                        </div>
                        <div class="form-group">
                            <label for="cpin">Confirmar nuevo PIN</label>
                            <input type="password" class="form-control" name="cpin" id="cpin"
                                aria-describedby="helpId" placeholder="Confirme su nuevo PIN" min="4"
                                max="4" required pattern="[0-9]{4}">
                        </div>
                        <small>Al cambiar el PIN tendra que volver a iniciar sesion en esta caja. (El PIN solo cambiara en
                            esta caja)</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Cambiar PIN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <main id="notify-caja">
        @if (session('solicitudes'))
            @foreach (session('solicitudes') as $cajas_id)
                <cajas id="Cajas" chanel="cajas.event.{{ $cajas_id }}" listen="CajasEvent"></cajas>
            @endforeach
        @endif

    </main>
    <script type="module">
        var app = appVue();
        app.component('cajas', component.notify);
        app.mount("#notify-caja");
    </script>
    <script>
        var sideBarMenu = 0;
        getMenu()

        function getMenu() {
            if (localStorage.getItem('menuCaja') != null) {
                sideBarMenu = parseInt(localStorage.getItem('menuCaja')) == 1;
            } else localStorage.setItem('menuCaja', sideBarMenu);
            var menu = document.getElementById("sidebarPanelCaja");
            var btn = document.getElementById("btnPanelCaja");

            menu.style.visibility = sideBarMenu == 1 ? 'visible' : 'hidden';
            btn.style.visibility = sideBarMenu == 0 ? 'visible' : 'hidden';
        }

        function setSideBarMenu(o) {
            sideBarMenu = o;
            localStorage.setItem('menuCaja', sideBarMenu)
            getMenu();
        }
    </script>
    @yield('script-caja')
@endsection
