@can('menu.servicios')
    <li class="nav-item dropdown">
        <a id="navbarDropdown"
            class="nav-link dropdown-toggle {{ request()->routeIs('tipo_servicios.*', 'servicios.*') ? 'active' : '' }}"
            href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
            Ordenes
        </a>

        <div class="dropdown-menu dropdown-menu" aria-labelledby="navbarDropdown">
            @can('ordenes.index')
                <a class="dropdown-item {{ request()->routeIs('ordenes.*') ? 'active' : '' }}"
                    href="{{ route('ordenes.index') }}">
                    <span class="mdi mdi-clipboard-list"></span> Ordenes
                </a>
            @endcan
            @can('tipo_servicios.index')
                <a class="dropdown-item {{ request()->routeIs('rubros.*') ? 'active' : '' }}"
                    href="{{ route('rubros.index') }}">
                    <span class="mdi mdi-room-service"></span> Rubros
                </a>
            @endcan

            @can('servicios.index')
                <a class="dropdown-item {{ request()->routeIs('servicios.*') ? 'active' : '' }}"
                    href="{{ route('servicios.index') }}">
                    <span class="mdi mdi-food-fork-drink"></span> Servicios
                </a>
            @endcan
        </div>
    </li>
@endcan
