@can('menu.habitaciones')
    <li class="nav-item dropdown">
        <a id="navbarDropdown" class="nav-link dropdown-toggle {{ request()->routeIs('habitaciones.*') ? 'active' : '' }}"
            href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
            Habitaciones
        </a>

        <div class="dropdown-menu dropdown-menu" aria-labelledby="navbarDropdown">
            @can('reservaciones.index')
                <a class="dropdown-item {{ request()->routeIs('reservaciones.*') ? 'active' : '' }}"
                    href="{{ route('reservaciones.index') }}">
                    <span class="mdi mdi-calendar-check-outline"></span> Reservaciones
                </a>
            @endcan
            @can('reservaciones.index')
                <a class="dropdown-item {{ request()->routeIs('recepciones.index') ? 'active' : '' }}"
                    href="{{ route('recepciones.index') }}">
                    <span class="mdi mdi-calendar-check-outline"></span> Recepciones
                </a>
            @endcan
            <div class="dropdown-divider"></div>
            @can('temporadas.index')
                <a class="dropdown-item {{ request()->routeIs('temporadas.*') ? 'active' : '' }}"
                    href="{{ route('temporadas.index') }}">
                    <span class="mdi mdi-bell"></span> Temporadas
                </a>
            @endcan

            @can('tarifas.index')
                <a class="dropdown-item {{ request()->routeIs('tarifas.*') ? 'active' : '' }}"
                    href="{{ route('tarifas.index') }}">
                    <span class="mdi mdi-currency-usd"></span> Tarifas
                </a>
            @endcan

            @can('administrar/habitaciones.index')
                <a class="dropdown-item {{ request()->routeIs('administrar_habitaciones.*') ? 'active' : '' }}"
                    href="{{ route('administrar_habitaciones.index') }}">
                    <span class="mdi mdi-bell"></span> Administrar Habitaciones
                </a>
            @endcan
            <div class="dropdown-divider"></div>
            @can('tipo_habitaciones.index')
                <a class="dropdown-item {{ request()->routeIs('tipo_habitaciones.*') ? 'active' : '' }}"
                    href="{{ route('tipo_habitaciones.index') }}">
                    <span class="mdi mdi-door"></span> Tipo de habitaciones
                </a>
            @endcan

            @can('forma_habitaciones.index')
                <a class="dropdown-item {{ request()->routeIs('forma_habitaciones.*') ? 'active' : '' }}"
                    href="{{ route('forma_habitaciones.index') }}">
                    <span class="mdi mdi-bunk-bed"></span> Forma de habitaciones
                </a>
            @endcan

            @can('estado_habitaciones.index')
                <a class="dropdown-item {{ request()->routeIs('estado_habitaciones.*') ? 'active' : '' }}"
                    href="{{ route('estado_habitaciones.index') }}">
                    <span class="mdi mdi-paper-roll"></span> Estado de habitaciones
                </a>
            @endcan

            @can('tipo_camas.index')
                <a class="dropdown-item {{ request()->routeIs('tipo_camas.*') ? 'active' : '' }}"
                    href="{{ route('tipo_camas.index') }}">
                    <span class="mdi mdi-bed"></span> Tipo de camas
                </a>
            @endcan

            @can('tipo_reservaciones.index')
                <a class="dropdown-item {{ request()->routeIs('tipo_reservaciones.*') ? 'active' : '' }}"
                    href="{{ route('tipo_reservaciones.index') }}">
                    <span class="mdi mdi-calendar-check-outline"></span> Tipo de Reservaciones
                </a>
            @endcan

            @can('ubicacion_habitaciones.index')
                <a class="dropdown-item {{ request()->routeIs('ubicacion_habitaciones.*') ? 'active' : '' }}"
                    href="{{ route('ubicacion_habitaciones.index') }}">
                    <span class="mdi mdi-map-marker"></span> Ubicación de habitaciones
                </a>
            @endcan
        </div>
    </li>
@endcan
