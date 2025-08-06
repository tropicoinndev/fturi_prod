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
                                    <th scope="col" class="w-20">Acciones</th>
                                    <th scope="col">#</th>
                                    <th scope="col">Fecha</th>
                                    <th scope="col">Asignacion</th>
                                    <th scope="col">Inicio</th>
                                    <th scope="col">Finalizacion</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Usuario asignado</th>
                                    <th scope="col">Usuario supervisor</th>
                                    <th scope="col">confirmacion de la asignacion</th>
                                    <th scope="col">Tipo de mantenimientos</th>
                                    <th scope="col">Habitaciones</th>
                                    <th scope="col">Observacion</th>
                                    <th scope="col">Bitacora asignado</th>
                                    <th scope="col">solicitante</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($p as $d)
                                    <tr>
                                        <td>
                                            <x-acciones :table="$th['table']" :d="$d" />
                                        </td>
                                        <th>{{ $loop->index + 1 }}</th>
                                        <td>{{ $d->fecha }}</td>
                                        <td>{{ $d->asignacion }}</td>
                                        <td>{{ $d->inicio }}</td>
                                        <td>{{ $d->finalizacion }}</td>
                                        <td>{{ $d->estado }}</td>
                                        <td>{{ $d->asignado ? $d->asignado->name : 'Sin asignar usuario' }}</td>
                                        <td>{{ $d->supervisor ? $d->supervisor->name : 'Sin supervisor' }}</td>
                                        <td>{{ $d->confirmacion_asignacion }}</td>
                                        <td>{{ $d->tipo_mantenimientos->mantenimiento }}</td>
                                        <td>{{ $d->habitaciones->numero_habitacion }}</td>
                                        <td>{{ $d->observacion }}</td>
                                        <td>{{ $d->bitacora_asignado }}</td>
                                        <td>{{ $d->creador->name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8">Aun no se han agregado datos.</td>
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
