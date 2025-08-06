@can('menu.cortesias')
    <li class="nav-item dropdown">
        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false" v-pre>
            Cortesias
        </a>

        <div class="dropdown-menu dropdown-menu" aria-labelledby="navbarDropdown">



            @can('cortesias.autorizar')
                <a class="dropdown-item {{ request()->routeIs('cortesias.autorizar') ? 'active' : '' }}"
                    href="{{ route('cortesias.autorizar') }}">
                    <span class="mdi mdi-food-turkey"></span> Autorizar cortesias
                </a>
            @endcan
            @can('cortesias.precios')
                <a class="dropdown-item {{ request()->routeIs('cortesias.precios') ? 'active' : '' }}"
                    href="{{ route('cortesias.precios') }}">
                    <span class="mdi mdi-food-turkey"></span> Editar precios en cortesias
                </a>
            @endcan
            @can('cortesias.comprobantes')
                <a class="dropdown-item {{ request()->routeIs('cortesias.comprobante') ? 'active' : '' }}"
                    href="{{ route('cortesias.comprobante') }}">
                    <span class="mdi mdi-food-turkey"></span> Comprobante cortesias
                </a>
            @endcan
            @can('cortesias.reporte')
                <a class="dropdown-item {{ request()->routeIs('cortesias.reporte') ? 'active' : '' }}"
                    href="{{ route('cortesias.reporte') }}">
                    <span class="mdi mdi-food-turkey"></span> Reportes de cortesias
                </a>
            @endcan
            @can('control_cortesias.index')
                <a class="dropdown-item {{ request()->routeIs('control_cortesias.*') ? 'active' : '' }}"
                    href="{{ route('control_cortesias.index') }}">
                    <span class="mdi mdi-turkey"></span> Control cortesias
                </a>
            @endcan
            @can('tipo_cortesia.index')
                <a class="dropdown-item {{ request()->routeIs('tipo_cortesia.*') ? 'active' : '' }}"
                    href="{{ route('tipo_cortesia.index') }}">
                    <span class="mdi mdi-turkey"></span> Tipo cortesia
                </a>
            @endcan
        </div>

    </li>
@endcan
