<li class="nav-item dropdown">
    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false" v-pre>
        Usuarios
    </a>

    <div class="dropdown-menu dropdown-menu" aria-labelledby="navbarDropdown">
       
        @can('menu.generales')
            @can('users.index')
                <a class="dropdown-item {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <span class="mdi mdi-account-cog"></span> Usuarios
                </a>
            @endcan
            @can('roles.index')
                <a class="dropdown-item {{ request()->routeIs('roles.*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                    <span class="mdi mdi-shield-account"></span> Roles
                </a>
            @endcan
            @can('roles.index')
                <a class="dropdown-item {{ request()->routeIs('empleados.*') ? 'active' : '' }}" href="{{ route('empleados.index') }}">
                    <span class="mdi mdi-account-tag-outline"></span> Empleados
                </a>
            @endcan

        @endcan
    </div>
</li>
