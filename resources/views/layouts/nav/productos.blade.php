@can('menu.productos')
    <li class="nav-item dropdown">
        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false" v-pre>
            Productos
        </a>

        <div class="dropdown-menu dropdown-menu" aria-labelledby="navbarDropdown">
            @can('bodegas.index')
                <a class="dropdown-item {{ request()->routeIs('bodegas.*') ? 'active' : '' }}"
                    href="{{ route('bodegas.index') }}">
                    <span class="mdi mdi-store"></span> Bodegas
                </a>
            @endcan
            @can('proveedores.index')
                <a class="dropdown-item {{ request()->routeIs('proveedores.*') ? 'active' : '' }}"
                    href="{{ route('proveedores.index') }}">
                    <span class="mdi mdi-truck"></span> Proveedores
                </a>
            @endcan
            @can('tipo_pagos.index')
                <a class="dropdown-item {{ request()->routeIs('tipo_pagos.*') ? 'active' : '' }}"
                    href="{{ route('tipo_pagos.index') }}">
                    <span class="mdi mdi-credit-card"></span> Tipo de pagos
                </a>
            @endcan
            @can('compras.index')
                <a class="dropdown-item {{ request()->routeIs('compras.*') ? 'active' : '' }}"
                    href="{{ route('compras.index') }}">
                    <span class="mdi mdi-clipboard-list"></span> Compras
                </a>
            @endcan
            @can('requisiciones.index')
                <a class="dropdown-item {{ request()->routeIs('requisiciones.*') ? 'active' : '' }}"
                    href="{{ route('bodegas.my') }}">
                    <span class="mdi mdi-clipboard-list"></span> Requisiciones
                </a>
            @endcan
            <div class="dropdown-divider"></div>

            @can('categorias.index')
                <a class="dropdown-item {{ request()->routeIs('categorias.*') ? 'active' : '' }}"
                    href="{{ route('categorias.index') }}">
                    <span class="mdi mdi-window-shutter-cog"></span> Categorías
                </a>
            @endcan
            @can('productos.index')
                <a class="dropdown-item {{ request()->routeIs('productos.*') ? 'active' : '' }}"
                    href="{{ route('productos.index') }}">
                    <span class="mdi mdi-window-shutter-cog"></span> Productos
                </a>
            @endcan
            <div class="dropdown-divider"></div>
            @can('rubros.index')
                <a class="dropdown-item {{ request()->routeIs('rubros.*') ? 'active' : '' }}"
                    href="{{ route('rubros.index') }}">
                    <span class="mdi mdi-cash-register"></span> Rubros
                </a>
            @endcan
            @can('categorias_precios.index')
                <a class="dropdown-item {{ request()->routeIs('categorias_precios.*') ? 'active' : '' }}"
                    href="{{ route('categorias_precios.index') }}">
                    <span class="mdi mdi-cash-sync"></span> Categorías Precios
                </a>
            @endcan
            @can('precios.index')
                <a class="dropdown-item {{ request()->routeIs('precios.*') ? 'active' : '' }}"
                    href="{{ route('precios.index') }}">
                    <span class="mdi mdi-cash-multiple"></span> Precios
                </a>
            @endcan
            @can('cargos.index')
                <a class="dropdown-item {{ request()->routeIs('cargos.index') ? 'active' : '' }}"
                    href="{{ route('cargos.index') }}">
                    <span class="mdi mdi-cash-multiple"></span> Cargos de habitaciones
                </a>
            @endcan

            <div class="dropdown-divider"></div>

            @can('ajustes_inventarios.index')
                <a class="dropdown-item {{ request()->routeIs('ajustes_inventarios.*') ? 'active' : '' }}"
                    href="{{ route('ajustes_inventarios.index') }}">
                    <span class="mdi mdi-cash-register"></span> Ajustes de inventarios
                </a>
            @endcan
        </div>
    </li>
@endcan
