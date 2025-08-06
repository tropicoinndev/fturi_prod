@extends('layouts.ajustes_inventarios')
{{-- @section('css-dte')
    <link rel="stylesheet" href="{{ asset('css/json-viewer.css') }}">
@endsection --}}

@section('ajustes_inventarios_content')
    <div class="row  mb-4">
        <div class="col-12">
            <h3 class="card-title text-uppercase">
                <span class="mdi mdi-file-document-refresh text-success h2"></span>
                Historial de aprobaciones
            </h3>
            <small>
                Listado de todas las solicitudes autorizadas
            </small>
            <x-message></x-message>
        </div>
    </div>
    {{-- <div class="row mb-4">
        <div class="col-12">
            <form class="row g-3" action="{{ route('dte.procesados_search') }}" method="POST">
                @csrf
                <div class="col-3">
                    <input type="date" class="form-control" placeholder="Buscar por fecha" required
                        value="{{ $fecha ?? '' }}" name="fecha">
                </div>
                <div class="col-8">
                    <input type="text" class="form-control" placeholder="Buscar por titular o numero generación"
                        value="{{ $busqueda ?? '' }}" name="busqueda">
                </div>

                <div class="col-1">
                    <button class="btn btn-primary" type="submit">
                        <span class="mdi mdi-magnify"></span>
                    </button>
                </div>
            </form>
        </div>
    </div> --}}
    <div class="row mb-4">
        <div class="col-12">
            <table class="table table-light table-sm table-responsive-sm">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Acciones</th>
                        <th scope="col">Observación</th>
                        <th scope="col">Fecha proceso</th>
                        <th scope="col">Solicitante</th>
                        <th scope="col">Realizado por</th>
                        <th scope="col">Autorizado por</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Autorizado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($p as $d)
                        <tr>
                            <th scope="row">{{ $d->id }}</th>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-light" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="mdi mdi-cog"></span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        @if ($d->autorizado)
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('ajustes_inventarios.imprimirActa', ['id' => $d->cid]) }}">
                                                    <span class="mdi mdi-printer h4"></span> Imprimir acta
                                                </a>
                                            </li>
                                        @else
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('ajustes_inventarios.edit', ['id' => $d->cid]) }}">
                                                    <span class="mdi mdi-pencil h4"></span> Editar
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                            <td><small class="text-muted d-inline-block text-truncate" tabindex="0"
                                    style="max-width: 80%; cursor: pointer;" data-bs-toggle="popover"
                                    data-bs-trigger="hover focus" data-bs-placement="top" title="Observación"
                                    data-bs-content="{{ $d->observacion ?? 'Sin observación' }}">
                                    {{ Str::limit($d->observacion ?? '---', 30) }}
                                </small>
                            </td>
                            <td>{{ $d->fecha_proceso }}</td>
                            <td>{{ $d->userSolicitante->name }}</td>
                            <td>{{ $d->userRealiza->name }}</td>
                            <td>{{ $d->userAutoriza->name ?? '' }}</td>
                            <td>
                                @if ($d->estado)
                                    <span class="text-success">Activo</span>
                                @else
                                    <span class="text-danger">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                @if ($d->autorizado)
                                    <span class="text-success">Autorizado</span>
                                @else
                                    <span class="text-danger">Negado</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-start mt-4">
            {{ $p->links() }}
        </div>
    </div>
@endsection
