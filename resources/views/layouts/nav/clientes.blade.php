@can('menu.clientes')
    <li class="nav-item dropdown">
        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false" v-pre>
            Clientes
        </a>

        <div class="dropdown-menu dropdown-menu" aria-labelledby="navbarDropdown">

            @can('clientes.nivel_cautela')
                <a class="dropdown-item {{ request()->routeIs('clientes.nivel_cautela') ? 'active' : '' }}"
                    href="{{ route('clientes.nivel_cautela') }}">
                    <span class="mdi mdi-account-edit"></span> Editar nivel cautela clientes
                </a>
            @endcan

            @can('clientes.alertas')
                <a class="dropdown-item {{ request()->routeIs('clientes.alertasEfectivo') ? 'active' : '' }}"
                    href="{{ route('clientes.alertasEfectivo') }}">
                    <span class="mdi mdi-alert"></span> Alertas de efectivo
                </a>
            @endcan
            @can('clientes.empleado')
                <a class="dropdown-item {{ request()->routeIs('clientes.empleados') ? 'active' : '' }}"
                    href="{{ route('clientes.empleados') }}">
                    <span class="mdi mdi-account-credit-card-outline"></span> Créditos a empleados
                </a>
            @endcan
            @can('clientes.empleado')
                <a class="dropdown-item {{ request()->routeIs('clientes.empleado_edit') ? 'active' : '' }}"
                    href="{{ route('clientes.empleado_edit') }}">
                    <span class="mdi mdi-account-cog-outline"></span> Configurar clientes
                </a>
            @endcan
            @can('clientes.index')
                <a class="dropdown-item {{ request()->routeIs('clientes.index') ? 'active' : '' }}"
                    href="{{ route('clientes.index') }}">
                    <span class="mdi mdi-account-group"></span> Clientes
                </a>
            @endcan
            @can('identificaciones.index')
                <a class="dropdown-item {{ request()->routeIs('identificaciones.*') ? 'active' : '' }}"
                    href="{{ route('identificaciones.index') }}">
                    <span class="mdi mdi-card-account-details"></span> Identificaciones
                </a>
            @endcan
            @can('contactos.index')
                <a class="dropdown-item {{ request()->routeIs('contactos.*') ? 'active' : '' }}"
                    href="{{ route('contactos.index') }}">
                    <span class="mdi mdi-phone"></span> Contactos
                </a>
            @endcan
            @can('giros.index')
                <a class="dropdown-item {{ request()->routeIs('giros.*') ? 'active' : '' }}"
                    href="{{ route('giros.index') }}">
                    <span class="mdi mdi-cart"></span> Giros
                </a>
            @endcan
            @can('descuentos.index')
                <a class="dropdown-item {{ request()->routeIs('descuentos.*') ? 'active' : '' }}"
                    href="{{ route('descuentos.index') }}">
                    <span class="mdi mdi-tag-multiple"></span> Descuentos
                </a>
            @endcan
            <div class="dropdown-divider"></div>
            @can('paises.index')
                <a class="dropdown-item {{ request()->routeIs('paises.*') ? 'active' : '' }}"
                    href="{{ route('paises.index') }}">
                    <span class="mdi mdi-earth"></span> Países
                </a>
            @endcan

            @can('departamentos.index')
                <a class="dropdown-item {{ request()->routeIs('departamentos.*') ? 'active' : '' }}"
                    href="{{ route('departamentos.index') }}">
                    <span class="mdi mdi-city"></span> Departamentos
                </a>
            @endcan

            @can('municipios.index')
                <a class="dropdown-item {{ request()->routeIs('municipios.*') ? 'active' : '' }}"
                    href="{{ route('municipios.index') }}">
                    <span class="mdi mdi-map-marker"></span> Municipios
                </a>
            @endcan
            @can('actividades_economicas.index')
                <a class="dropdown-item {{ request()->routeIs('actividades_economicas.*') ? 'active' : '' }}"
                    href="{{ route('actividades_economicas.index') }}">
                    <span class="mdi mdi-text-box-multiple"></span> Actividades económicas
                </a>
            @endcan
            @can('solicitantes.index')
                <a class="dropdown-item {{ request()->routeIs('solicitantes.*') ? 'active' : '' }}"
                    href="{{ route('solicitantes.index') }}">
                    <span class="mdi mdi-account-switch-outline"></span> Solicitantes
                </a>
            @endcan
            @can('periodos_creditos.index')
                <a class="dropdown-item {{ request()->routeIs('periodos_creditos.*') ? 'active' : '' }}"
                    href="{{ route('periodos_creditos.index') }}">
                    <span class="mdi mdi-credit-card-clock-outline"></span> Periodos créditos
                </a>
            @endcan

            @can('clientes.credito')
                <a class="dropdown-item {{ request()->routeIs('clientes.panel.creditos') ? 'active' : '' }}"
                    href="{{ route('clientes.panel.creditos') }}">
                    <span class="mdi mdi-credit-card-clock-outline"></span> Control de créditos
                </a>
            @endcan

            {{-- @can('personas_naturales.index')
                <a class="dropdown-item {{ request()->routeIs('personas_naturales.index') ? 'active' : '' }}"
                    href="{{ route('personas_naturales.index') }}">
                    <span class="mdi mdi-account-badge"></span> Personas naturales
                </a>
            @endcan --}}

        </div>
    </li>
@endcan
