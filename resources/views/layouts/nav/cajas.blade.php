@can('menu.cajas')
    <li class="nav-item dropdown">
        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false" v-pre>
            Admin
        </a>

        <div class="dropdown-menu dropdown-menu" aria-labelledby="navbarDropdown">
            <!--Solo para pruebas, debe eliminarse-->
            @can('formatoDteCcf')
                <a class="dropdown-item {{ request()->routeIs('dte.index') ? 'active' : '' }}" href="{{ route('dte.index') }}">
                    <span class="mdi mdi-home-city"></span> DTEs
                </a>
            @endcan

            @can('sucursales.index')
                <a class="dropdown-item {{ request()->routeIs('sucursales.*') ? 'active' : '' }}"
                    href="{{ route('sucursales.index') }}">
                    <span class="mdi mdi-home-city"></span> Sucursales
                </a>
            @endcan
            @can('forma_pagos.index')
                <a class="dropdown-item {{ request()->routeIs('forma_pagos.*') ? 'active' : '' }}"
                    href="{{ route('forma_pagos.index') }}">
                    <span class="mdi mdi-cash-multiple"></span> Forma de pagos
                </a>
            @endcan
            @can('correlativos.index')
                <a class="dropdown-item {{ request()->routeIs('correlativos.*') ? 'active' : '' }}"
                    href="{{ route('correlativos.index') }}">
                    <span class="mdi mdi-barcode-scan"></span> Correlativos
                </a>
            @endcan
            @can('tipo_comprobantes.index')
                <a class="dropdown-item {{ request()->routeIs('tipo_comprobantes.*') ? 'active' : '' }}"
                    href="{{ route('tipo_comprobantes.index') }}">
                    <span class="mdi mdi-file-multiple"></span> Tipo de comprobantes
                </a>
            @endcan

            @can('anulaciones.index')
                <a class="dropdown-item {{ request()->routeIs('anulaciones.*') ? 'active' : '' }}"
                    href="{{ route('anulaciones.index') }}">
                    <span class="mdi mdi-close-box"></span> Razones de anulaciones
                </a>
            @endcan
            <div class="dropdown-divider"></div>
            @can('opcion_turnos.index')
                <a class="dropdown-item {{ request()->routeIs('opcion_turnos.*') ? 'active' : '' }}"
                    href="{{ route('opcion_turnos.index') }}">
                    <span class="mdi mdi-store-clock"></span> Opción de turnos
                </a>
            @endcan
            @can('cajas.index')
                <a class="dropdown-item {{ request()->routeIs('cajas.*') ? 'active' : '' }}"
                    href="{{ route('cajas.index') }}">
                    <span class="mdi mdi-window-shutter-cog"></span> Configuración de Cajas
                </a>
            @endcan

            @can('produccion.cocina')
                <a class="dropdown-item {{ request()->routeIs('comandas.*') ? 'active' : '' }}"
                    href="{{ route('comandas.produccion.cocina') }}">
                    <span class="mdi mdi-table-chair"></span> Producción cocina
                </a>
            @endcan
            @can('produccion.bar')
                <a class="dropdown-item {{ request()->routeIs('comandas.*') ? 'active' : '' }}"
                    href="{{ route('comandas.produccion.bar') }}">
                    <span class="mdi mdi-table-chair"></span> Producción bar
                </a>
            @endcan
        </div>
    </li>
@endcan
