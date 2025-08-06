@extends('layouts.mantenimientos')
@section('panel_mantenimiento')
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-12 col-lg-6">
                        <h3 class="card-title text-capitalize">
                            {{ $th['title'] ?? 'Listado' }}
                        </h3>
                        <p class="text-uppercase text-muted">
                            {{ $th['sub'] ?? '' }}
                        </p>
                    </div>
                    <!--Boton agregar y caja de busqueda-->
                    <div class="row mb-3">
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mb-2">
                            @if (isset($th['btnAdd']) && $th['btnAdd'])
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#staticBackdrop">
                                    <span class="mdi mdi-plus"></span> Agregar
                                </button>
                            @elseif (Route::has($th['table'] . '.create'))
                                <a href="{{ route($th['table'] . '.create') }}" class="btn btn-outline-primary">
                                    <span class="mdi mdi-plus"></span> Agregar
                                </a>
                            @endif
                        </div>

                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <form action="{{ route($th['table'] . '.search') }}" method="post">
                                @csrf
                                <input type="text" class="form-control" placeholder="Buscar..." id="txtBusqueda"
                                    name="txtBusqueda" value="{{ $txtBusqueda ?? '' }}" autocomplete="off">
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Acciones</th>
                                    <th scope="col">#</th>
                                    <th scope="col">Mantenimiento</th>
                                    <th scope="col">Id del grupo telegram</th>
                                    <th scope="col">Duracion promedio</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Estado iniciado</th>
                                    <th scope="col">Estado completado</th>
                                    <th scope="col">Notificacion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($p as $d)
                                    <tr>
                                        <td>
                                            <x-acciones :table="$th['table']" :d="$d" />
                                        </td>

                                        <th>{{ $loop->index + 1 }}</th>
                                        <td>{{ $d->mantenimiento }}</td>
                                        <td>{{ isset($d->id_grupo_telegram )? $d->id_grupo_telegram : 'No tiene grupo de telegram '}}</td>
                                        <td>{{ $d->duracion }}</td>
                                        <td>
                                            @if ($d->estado)
                                                <span class="text-success">Activo</span>
                                            @else
                                                <span class="text-danger">Inactivo</span>
                                            @endif
                                        </td>
                                        <td>{{ isset($d->inicio_habitaciones) ? $d->inicio_habitaciones->estado_habitacion : 'No inicio con estado' }}</td>
                                        <td>{{ isset($d->completado_habitaciones) ? $d->completado_habitaciones->estado_habitacion : 'No se completo con estado' }}</td>
                                        <td>
                                            @if ($d->notificacion)
                                                <span class="text-success">Si</span>
                                            @else
                                                <span class="text-danger">No</span>
                                            @endif
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3">Aun no se han agregado datos.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (isset($th['btnAdd']) && $th['btnAdd'])
        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel"><span class="mdi mdi-plus"></span> Agregar
                            {{ $th['title'] }}</h1>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <x-dynamic-component :component="$th['table'] . '-form'" :table="$th['table']" :data="$data ?? ''" />
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
