@extends('layouts.anticipos')

@section('panel_anticipo')
    <div class="col-md-12">
        <div class="card shadow p-4">
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
                </div>

                <!-- Botones y caja de búsqueda -->
                <div class="row mb-3">
                    <div class="col-md-4 mb-2">
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
                    <div class="col-md-8">
                        <form action="{{ route($th['table'] . '.search') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="activos"
                                            name="activos" checked>
                                        <label class="form-check-label" for="activos">
                                            Solo activos
                                        </label>
                                    </div>
                                </div>
                                <div class="col-10">

                                    <input type="text" class="form-control"
                                        placeholder="Buscar por nombre de cliente o por número de anticipo (mínimo tres dígitos)"
                                        id="txtBusqueda" name="txtBusqueda" value="{{ $txtBusqueda ?? '' }}"
                                        autocomplete="off">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tabla de anticipos -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Acciones</th>

                                <th scope="col">Codigo</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Cliente</th>
                                <th scope="col">Monto actual</th>
                                <th scope="col">Monto creacion</th>
                                <th scope="col">Fecha Aplicacion</th>
                                <th scope="col">Forma Pago</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Anulada</th>
                                <th scope="col">Separacion</th>
                                <th scope="col">Usuario</th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse($p as $d)
                                <tr>
                                    <td>
                                        <x-acciones :table="$th['table']" :d="$d" />

                                    </td>
                                    <td>{{ $d->id }}</td>
                                    <td>{{ $d->fecha }}</td>
                                    <td>
                                        <div class="row">
                                            <div class="col-12 fw-bold">
                                                {{ $d->clientes->nombre }}
                                            </div>
                                            <div class="col-12 text-muted">
                                                <i>
                                                    {{ $d->concepto }}
                                                </i>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${{ number_format($d->monto, 2) }}
                                        @if ($d->estado)
                                            @can('anticipos.admin')
                                                <a class="btn btn-light btn-sm"
                                                    href="{{ route('anticipos.separar', ['id' => $d->cid]) }}" role="button">
                                                    <span class="mdi mdi-pencil h5"></span>
                                                </a>
                                            @endcan
                                        @endif
                                    </td>
                                    <td>${{ number_format($d->monto_historico, 2) }}</td>
                                    <td>
                                        <small>{{ $d->fecha_aplicacion }}</small>
                                        @if ($d->estado)
                                            @can('anticipos.cambiar_aplicacion')
                                                <a class="btn btn-light btn-sm"
                                                    href="{{ route('anticipos.cambiarAplicacion', ['id' => $d->cid]) }}"
                                                    role="button">
                                                    <span class="mdi mdi-pencil h5"></span>
                                                </a>
                                            @endcan
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $d->forma_pagos->forma }}</small>
                                        <br>
                                        @can('anticipos.cambiar_forma_pago')
                                            <a class="btn btn-light btn-sm"
                                                href="{{ route('anticipos.cambiarFormaPago', ['id' => $d->cid]) }}"
                                                role="button">
                                                <span class="mdi mdi-pencil h5"></span>
                                            </a>
                                        @endcan
                                    </td>
                                    <td>
                                        @if ($d->estado)
                                            <span class="text-success">Anticipo sin aplicar</span>
                                        @else
                                            <span class="text-info">Anticipo aplicado</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($d->anulado)
                                            <span class="text-danger">Anticipo anulado</span>
                                        @else
                                            <span class="text-success">Anticipo activo</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($d->separado)
                                            <span class="text-danger">Este anticipo fue separado</span>
                                        @else
                                            <span class="text-success">Anticipo normal</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $d->users->name }}</span>
                                    </td>

                                </tr>

                            @empty
                                <tr>
                                    <td colspan="13">Aún no se han agregado datos.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-start mt-4">
                    {{ $p->links() }}
                </div>

            </div>
        </div>

        @if (isset($th['btnAdd']) && $th['btnAdd'])
            <!-- Modal de agregar -->
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
    </div>
@endsection
