@extends('layouts.app')

@section('style')
    <style>
        body {
            background: #EDE7F6;
        }

        .panel-body {
            min-height: 90vh;
        }

        .card-caja {
            background: #455A64;
            color: #455A64;
        }

        .card-menu {
            border-color: #455A64;
            color: #37474F;
            height: 180px;
            overflow: hidden;
        }

        .card-menu .card-title {
            margin-top: 25px;
        }

        .card-menu:hover {
            background: #455A64;
            color: #E8EAF6;
        }

        .progress-bar-container {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 10px;
            background-color: #E0E0E0;
            border-radius: 0 0 5px 5px;
        }

        .progress-bar {
            height: 100%;
            background-color: #76c7c0;

            transition: width 0.3s ease;
        }
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="card panel-body shadow p-4">
            <div class="card-body">
                <div class="row">
                    <div class="row aling-items-center">
                        <div class="col-9 col-md-9 mb-4">
                        <p>Hola, <span class="text-capitalize">{{ auth()->user()->name }}</span>.</p>
                    </div>
                    <div class="col-3 text-md-end  mb-3">
                        <a href="/dashboard" class="btn btn-light w-60 text-end">
                            <span class="mdi mdi-exit-to-app"></span>
                            Salir
                        </a>
                    </div>
                    </div>
                </div>

                <div class="row">
                    <section>
                        @can('users.index')
                            <div class=" col-12 d-flex justify-content-between">

                                <!-- usuarios -->
                                <div class="card col-12  mb-3 text-uppercase">
                                    <div class="card-header fw-bolder d-flex justify-content-between align-items-center">
                                        <span>Detalle de usuarios</span>
                                        <a class="btn btn-light m-1 text-uppercase" href="{{ route('users.index') }}"
                                            role="button">
                                            <span class="mdi mdi-account"></span>
                                            Ver Usuarios ({{ count($users) }})
                                        </a>
                                        <a class="btn btn-light m-1 text-uppercase" href="{{ route('users.create') }}"
                                            role="button">
                                            <span class="mdi mdi-account-multiple-plus"></span>
                                            Agregar Usuario
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="scrollable-container2">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">#</th>
                                                            <th scope="col">Nombre</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($users as $u)
                                                            <tr>
                                                                <th>{{ $loop->index + 1 }}</th>
                                                                <td>{{ $u->name }}</td>

                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="4" class="text-center">No hay usuarios
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        @endcan
                    </section>

                </div>
                <div class="row">
                    <section>
                        @can('empleados.index')
                            <div class=" col-12 d-flex justify-content-between">


                                <div class="card col-12  mb-3 text-uppercase">
                                    <div class="card-header fw-bolder d-flex justify-content-between align-items-center">
                                        <span> detalle empleados</span>
                                        <a class="btn btn-light m-1 text-uppercase" href="{{ route('empleados.index') }}"
                                            role="button">
                                            <span class="mdi mdi-account"></span>
                                            Ver empleados ({{ count($empleados) }})
                                        </a>

                                    </div>
                                    <section>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="scrollable-container2">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">#</th>
                                                                <th scope="col">Nombre</th>
                                                                <th scope="col">Estado</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse ($empleados as $e)
                                                                <tr>
                                                                    <th>{{ $loop->index + 1 }}</th>
                                                                    <td>{{ $e->nombre_completo }}</td>
                                                                    <td>
                                                                        @if ($e->estado)
                                                                            <span class="text-success">Activo</span>
                                                                        @else
                                                                            <span class="text-danger">Inactivo</span>
                                                                        @endif
                                                                    </td>

                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="4" class="text-center">No hay usuarios
                                                                    </td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>


                                </div>
                        </section>
                    @endcan
                    </section>

                </div>
                <div class="row">
                    <section>
                        @can('roles.index')
                            <div class=" col-12 d-flex justify-content-between">


                                <div class="card col-12  mb-3 text-uppercase">
                                    <div class="card-header fw-bolder d-flex justify-content-between align-items-center">
                                        <span> detalle de roles</span>
                                        <a class="btn btn-light m-1 text-uppercase" href="{{ route('roles.create') }}"
                                            role="button">
                                            <span class="mdi mdi-account-tag-outline"></span>
                                            agregar rol
                                        </a>
                                        <a class="btn btn-light m-1 text-uppercase" href="{{ route('roles.index') }}"
                                            role="button">
                                            <span class="mdi mdi-account-tag-outline"></span>
                                            Ver roles ({{ count($roles) }})
                                        </a>

                                    </div>
                                    <section>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="scrollable-container2">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">#</th>
                                                                <th scope="col">Rol</th>
                                                                <th scope="col">asignado a</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse ($roles as $r)
                                                                <tr>
                                                                    <th>{{ $loop->index + 1 }}</th>
                                                                    <td>{{ $r->name }}</td>
                                                                    <td>{{ $r->users->pluck('name')->implode(', ') }}</td>


                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="4" class="text-center">No hay usuarios
                                                                    </td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>


                                </div>
                        </section>
                    @endcan
                    </section>

                </div>
            </div>
        </div>
    </div>
@endsection
