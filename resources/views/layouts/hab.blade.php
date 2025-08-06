@extends('layouts.app')

@section('content')
    <style>
        .message {
            position: fixed;
            top: 20%;
            right: 1%;
            width: 20%;
            z-index: 100;
        }

        #menuPanelHabitaciones {
            visibility: hidden;
            position: fixed;
            width: 240px;
            height: 90vh;
            background: #fefefe;
            overflow-y: auto;
            padding: 8px 0px;

        }

        #btnMenuPanelHabitaciones {
            visibility: hidden;
            position: fixed;
            width: 62px;
            height: 62px;
            border: none;
            border-radius: 50%;
            bottom: 1%;
            left: 1%;
        }

        #menuPanelHabitaciones,
        #btnMenuPanelHabitaciones {
            transition: visibility 40ms ease-in-out;
            z-index: 1009;
        }

        .content {
            z-index: 1000;
        }

        .panel-body {
            min-height: 550px;
        }
    </style>

    <div class="d-md-none d-lg-flex flex-column flex-shrink-0 p-3 shadow sidebarPanel" id="menuPanelHabitaciones">
        <a href="#" class="link-dark text-decoration-none" onclick="setMenu(0)">
            <span class="mdi mdi-minus float-end"></span>
            <span class="fs-4 text-uppercase">

                Menu
            </span>

        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            @canany(['recepciones.reportes', 'huespedes.reportes', 'reservaciones.create',
                'recepciones.recepcion.bitacora_anulaciones', 'recepciones.create'])
                <li>
                    <div class="dropdown">
                        <button class="nav-link text-secondary dropdown-toggle" type="button" id="dropdownMenuButton1"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Reportes
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            @can('recepciones.reportes')
                                <li>
                                    <a href="{{ route('recepciones.reporte_estadia') }}" class="dropdown-item">
                                        <span class="mdi mdi-ticket-confirmation-outline h5"></span> Reporte de estadía
                                    </a>
                                </li>
                            @endcan
                            @can('huespedes.reportes')
                                <li>
                                    <a href="{{ route('recepciones.reporte_huesped') }}" class="dropdown-item">
                                        <span class="mdi mdi-human-male-male-child h5"></span> Reporte de huespedes
                                    </a>
                                </li>
                            @endcan
                            @can('huespedes.datatours')
                                <li>
                                    <a href="{{ route('recepciones.reporte_data_tours') }}" class="dropdown-item">
                                        <span class="mdi mdi-touch-text-outline h5"></span> Reporte data tours
                                    </a>
                                </li>
                            @endcan
                            @can('reservaciones.create')
                                <li>
                                    <a href="{{ route('reservaciones.reporte_habitacion') }}" class="dropdown-item">
                                        <span class="mdi mdi-calendar h5"></span> Calendario por habitación
                                    </a>
                                </li>
                            @endcan
                            @can('reservaciones.create')
                                <li>
                                    <a href="{{ route('reservaciones.reporte_venta') }}" class="dropdown-item">
                                        <span class="mdi mdi-calendar h5"></span> Reporte de reservaciones
                                    </a>
                                </li>
                            @endcan
                            @can('recepciones.create')
                                <li>
                                    <a href="{{ route('reservaciones.reporte_ingreso') }}" class="dropdown-item">
                                        <span class="mdi mdi-calendar h5"></span> Reporte de Ingreso
                                    </a>
                                </li>
                            @endcan
                            @can('reservaciones.create')
                                <li>
                                    <a href="{{ route('reservaciones.detalleHospedajeHuesped') }}" class="dropdown-item">
                                        <span class="mdi mdi-account-details h5"></span> Detalle hospedaje huésped
                                    </a>
                                </li>
                            @endcan
                            @can('recepciones.recepcion.bitacora_anulaciones')
                                <li>
                                    <a href="{{ route('recepciones.bitacoraRecepciones') }}" class="dropdown-item">
                                        <span class="mdi mdi-account-details h5"></span> Bitácora recepciones
                                    </a>
                                </li>
                            @endcan
                            @can('recepciones.create')
                                <li>
                                    <a href="{{ route('recepciones.salidas') }}" class="dropdown-item">
                                        <span class="mdi mdi-account-details h5"></span> Recepciones salidas
                                    </a>
                                </li>
                            @endcan

                            <li>
                                <a href="{{ route('defensoria_index') }}" class="dropdown-item">
                                    <span class="mdi mdi-account-details h5"></span> Reporte Defensoría
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>
            @endcanany
            <li class="nav-item"><b>Creacion</b></li>
            @can('reservaciones.create')
                <li>
                    <a href="{{ route('reservaciones.create') }}" class="nav-link text-secondary">
                        <span class="mdi mdi-calendar-plus h5"></span> Agregar reservas
                    </a>
                </li>
            @endcan
            @can('reservaciones.index')
                <li class="nav-item">
                    <a href="{{ route('reservaciones.index') }}" class="nav-link text-secondary">
                        <span class="mdi mdi-calendar-check h5"></span> Reservaciones
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('reservaciones.history') }}" class="nav-link text-secondary">
                        <span class="mdi mdi-history h5"></span> Historial de reservaciones
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('reservaciones.disponibilidad_habitaciones_view') }}" class="nav-link text-secondary">
                        <span class="mdi mdi-calendar-filter h5"></span> Disponibilidad
                    </a>
                </li>
            @endcan
            @can('recepciones.create')
                <li>
                    <a href="{{ route('recepciones.index') }}" class="nav-link text-secondary">
                        <span class="mdi mdi-check-bold h5"></span> Check In
                    </a>
                </li>
                <li>
                    <a href="{{ route('recepciones.checkout') }}" class="nav-link text-secondary">
                        <span class="mdi mdi-exit-to-app h5"></span> Check Out
                    </a>
                </li>
            @endcan
            @can('clientes.create')
                <li>
                    <a href="{{ route('clientes.create') }}" target="_blank" class="nav-link text-secondary">
                        <span class="mdi mdi-account-plus h5"></span> Agregar clientes
                    </a>
                </li>
            @endcan
            @can('huespedes.create')
                <li>
                    <a href="{{ route('huespedes.create') }}" target="_blank" class="nav-link text-secondary">
                        <span class="mdi mdi-account-multiple-plus h5"></span> Agregar huesped

                    </a>
                </li>
            @endcan
            @can('anticipos.create')
                <li>
                    <a href="{{ route('anticipos.create') }}" target="_blank" class="nav-link text-secondary">
                        <span class="mdi mdi-plus h5"></span> Agregar anticipo

                    </a>
                </li>
            @endcan


            @can('recepciones.ticket')
                <li>
                    <a href="{{ route('recepciones.ticket_container') }}" class="nav-link text-secondary">
                        <span class="mdi mdi-ticket-confirmation-outline h5"></span> Ticket desayuno
                    </a>
                </li>
            @endcan
            @can('habitaciones.cambio_estado')
                <li>
                    <a href="{{ route('habitaciones.cambio_estado') }}" class="nav-link text-secondary">
                        <span class="mdi mdi-bed-queen-outline h5"></span>
                        Solicitar cambio en habitación
                    </a>
                </li>
            @endcan


        </ul>
    </div>
    <button id="btnMenuPanelHabitaciones" class="btn btn-secondary" onclick="setMenu(1)">
        <span class="mdi mdi-menu"></span>
    </button>
    <div class="content">
        @yield('content-reservas')
        @yield('content-hab')
    </div>
@endsection
@section('script')
    <script>
        function menu() {
            const ss = parseInt(localStorage.getItem('showMenu')) == 1,
                menu = document.getElementById("menuPanelHabitaciones"),
                btn = document.getElementById('btnMenuPanelHabitaciones');
            if (ss) {
                menu.style.visibility = "visible";
                btn.style.visibility = 'hidden';
            } else {
                menu.style.visibility = 'hidden';
                btn.style.visibility = 'visible';
            }
        }

        function setMenu(status) {
            localStorage.setItem('showMenu', status)
            menu()
        }
        menu();
    </script>
    @yield('script-hab')
@endsection
