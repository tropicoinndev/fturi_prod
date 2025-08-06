@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card p-3" style="min-height: 90vh;">
            <div class="card-body">
                <div class="row  mb-4">
                    <div class="col-12">
                        <h3 class="card-title text-uppercase">
                            <span class="mdi mdi-file-document-check text-success h2"></span>
                            Comprobantes
                        </h3>
                        <small>
                            Listado de {{ $fecha ?? 'comprobantes' }}
                        </small>
                        <x-message></x-message>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-12">
                        <form class="row g-3" action="{{ route('comprobantes.search') }}" method="POST">
                            @csrf
                            <div class="col-3">
                                <select class="form-select" name="cajas" required>
                                    <option value="" selected>Seleccione una caja</option>
                                    @foreach ($cajas as $c)
                                        <option value="{{ $c->cid }}" {{ $c->id == $caja ? 'selected' : '' }}>
                                            {{ $c->caja }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="col-3">
                                <input type="date" class="form-control" placeholder="Buscar por fecha" required
                                    value="{{ $fecha ?? '' }}" name="fecha">
                            </div>
                            <div class="col-5">
                                <input type="text" class="form-control"
                                    placeholder="Buscar por titular o correlativo interno" value="{{ $busqueda ?? '' }}"
                                    name="busqueda" required>
                            </div>

                            <div class="col-1">
                                <button class="btn btn-primary" type="submit">
                                    <span class="mdi mdi-magnify"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-12">
                        <table class="table table-light table-sm table-responsive-sm">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Acciones</th>
                                    <th scope="col">Tipo comprobante</th>
                                    <th scope="col">Correlativo</th>
                                    <th scope="col">Titular</th>
                                    <th scope="col">Fecha/hora</th>
                                    <th scope="col">Usuario</th>
                                    <th scope="col">Caja</th>
                                    <th scope="col">DTE</th>
                                    <th scope="col">Estado</th>
                                </tr>
                            </thead>
                            <tbody>

                                @isset($comprobantes)
                                    @foreach ($comprobantes as $d)
                                        <tr>
                                            <th scope="row">{{ $d->id }}</th>
                                            <td class="text-center">
                                                <a class="dropdown-item"
                                                    href="{{ route('comprobantes.detalles', ['id' => $d->cid]) }}"
                                                    target="_blank">
                                                    <span class="mdi mdi-file-document h5"></span>
                                                </a>
                                            </td>
                                            <td>{{ $d->tipoComprobantes?->tipo }}</td>
                                            <td>{{ $d->correlativo }}</td>
                                            <td>{{ $d->titular }}</td>
                                            <td>{{ $d->created_at }}</td>
                                            <td>{{ $d->users->user }}</td>
                                            <td>{{ $d->turnosCajas->cajas->caja }}</td>
                                            <td>{{ $d->dteOne?->codigo_generacion }}</td>
                                            <td>
                                                <button type="button"
                                                    class="btn btn-light btn-sm {{ $d->dteOne?->estado == 'PROCESADO' ? '' : 'text-danger' }}"
                                                    data-bs-toggle="popover" data-bs-placement="right"
                                                    data-bs-custom-class="custom-popover" data-bs-title="Response"
                                                    data-bs-content="{{ json_encode($d->dteOne?->response) }}">
                                                    {{ $d->dteOne?->estado ?? 'RECHAZADO' }}
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
