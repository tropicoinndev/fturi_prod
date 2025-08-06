@can('menu.mantenimientos')
<li class="nav-item dropdown">
    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false" v-pre>
        Mantenimientos
    </a>

    <div class="dropdown-menu dropdown-menu" aria-labelledby="navbarDropdown">
        @can('tipo_mantenimientos.index')
        <a class="dropdown-item {{ request()->routeIs('tipo_mantenimientos.*') ? 'active' : '' }}"
            href="{{ route('tipo_mantenimientos.index') }}">
           <span class="mdi mdi-account-wrench"></span>  Tipos de mantenimientos
        </a>
        @endcan
        @can('mantenimientos.index')
        <a class="dropdown-item {{ request()->routeIs('mantenimientos.*') ? 'active' : '' }}"
            href="{{ route('mantenimientos.index') }}">
           <span class="mdi mdi-account-wrench-outline"></span> Mantenimientos
        </a>
        @endcan
        @can('administracion/mantenimientos.index')
                <a class="dropdown-item {{ request()->routeIs('administracion_mantenimientos.*') ? 'active' : '' }}"
                    href="{{ route('administracion_mantenimientos.index') }}">
                    <span class="mdi mdi-blinds-horizontal-closed"></span> Administrar mantenimientos
                </a>
            @endcan
    
       
    </div>

</li>
@endcan