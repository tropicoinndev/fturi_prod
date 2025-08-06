@can('menu.eventos')
<li class="nav-item dropdown">
    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false" v-pre>
        Eventos
    </a>

    <div class="dropdown-menu dropdown-menu" aria-labelledby="navbarDropdown">
        @can('montajes.index')
        <a class="dropdown-item {{ request()->routeIs('montajes.*') ? 'active' : '' }}"
            href="{{ route('montajes.index') }}">
           <span class="mdi mdi-account-wrench"></span> Montajes
        </a>
        @endcan
        @can('galerias.index')
        <a class="dropdown-item {{ request()->routeIs('galerias.*') ? 'active' : '' }}"
            href="{{ route('galerias.index') }}">
           <span class="mdi mdi-camera"></span> Galerias
        </a>
        @endcan
        @can('categoria_fotos.index')
        <a class="dropdown-item {{ request()->routeIs('categoria_fotos.*') ? 'active' : '' }}"
            href="{{ route('categoria_fotos.index') }}">
           <span class="mdi mdi-camera"></span> Categorias fotos
        </a>
        @endcan
        @can('sonidos.index')
        <a class="dropdown-item {{ request()->routeIs('sonidos.*') ? 'active' : '' }}"
            href="{{ route('sonidos.index') }}">
           <span class="mdi mdi-soundcloud"></span> Sonidos
        </a>
        @endcan
        @can('tipo_eventos.index')
        <a class="dropdown-item {{ request()->routeIs('tipo_eventos.*') ? 'active' : '' }}"
            href="{{ route('tipo_eventos.index') }}">
           <span class="mdi mdi-calendar-multiple-check"></span> Tipo de eventos
        </a>
        @endcan
        @can('salones.index')
        <a class="dropdown-item {{ request()->routeIs('salones.*') ? 'active' : '' }}"
            href="{{ route('salones.index') }}">
           <span class="mdi mdi-google-classroom"></span> Salones
        </a>
        @endcan
        @can('eventos.index')
        <a class="dropdown-item {{ request()->routeIs('eventos.*') ? 'active' : '' }}"
            href="{{ route('eventos.eventos') }}">
           <span class="mdi mdi-calendar-multiple-check"></span> Eventos
        </a>
        @endcan
        @can('eventos.reporte')
        <a class="dropdown-item {{ request()->routeIs('eventos.*') ? 'active' : '' }}"
            href="{{ route('eventos.eventos_reporte') }}">
           <span class="mdi mdi-file-chart-outline"></span> Reporte eventos
        </a>
        @endcan
    </div>

</li>
@endcan
