@extends('layouts.main')

@section('main_content')
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark fixed-top shadow-sm"
            style="background: {{ env('color_menu', '#303F9F;') }}">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/dashboard') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            @can('users.index')
                                @include('layouts.nav.generales')
                            @endcan
                            @include('layouts.nav.clientes')
                            @include('layouts.nav.productos')
                            @include('layouts.nav.servicios')
                            @include('layouts.nav.cajas')
                            @include('layouts.nav.habitaciones')
                            @include('layouts.nav.mantenimientos')
                            @include('layouts.nav.eventos')
                            @include('layouts.nav.cortesias')

                            @can('cajas.my')
                                <li class="nav-item">
                                    {{-- <a class="nav-link {{ request()->routeIs('cajas.my') ? 'active' : '' }}"
                                        href="{{ route('cajas.my') }}">
                                        Cajas
                                    </a> --}}
                                    <a class="nav-link {{ request()->routeIs('cajas.menu') ? 'active' : '' }}"
                                        href="{{ route('cajas.menu') }}">
                                        Cajas
                                    </a>
                                </li>
                            @endcan
                            @can('comandas.index')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('comandas.index') ? 'active' : '' }}"
                                        href="{{ route('comandas.index') }}">
                                        Comandas
                                    </a>
                                </li>
                            @endcan
                            @can('recepciones.index')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('recepciones.index') ? 'active' : '' }}"
                                        href="{{ route('recepciones.index') }}">
                                        Estadías
                                    </a>
                                </li>
                            @endcan
                            @can('bodegas.my')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('bodegas.my') ? 'active' : '' }}"
                                        href="{{ route('bodegas.my') }}">
                                        Bodegas
                                    </a>
                                </li>
                            @endcan
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">Iniciar sesión</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                                    <a class="dropdown-item" href="{{ route('users.showPasswordForm') }}">
                                        Cambiar contraseña
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Cerrar sesión ') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>

                </div>
            </div>
        </nav>

        <main class="py-2" style="margin-top: 65px;">
            @isset($th)
                @if (Breadcrumbs::exists($th['bread'] ?? 'home', $th ?? null, $p ?? null))
                    <div class="container-fluid" style="background: #CFD8DC;">
                        <div class="row mb-3" style="margin-top: -20px;">
                            <div class="col-12 pt-3 m-auto">
                                <div class="container">
                                    {{ Breadcrumbs::render($th['bread'] ?? 'home', $th ?? null, $p ?? null) }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endisset
            @yield('content')
        </main>
    </div>
    @yield('script')
@endsection
