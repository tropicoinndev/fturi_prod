@extends('layouts.dtes')

@section('css-dte')
    <link rel="stylesheet" href="{{ asset('css/json-viewer.css') }}">
@endsection

@section('dte_content')
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="card-title text-uppercase">
                <span class="mdi mdi-format-list-bulleted h3">Contingencias</span>
            </h3>
            <small>Listado de contingencias</small>
            <x-message></x-message>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-6">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalContingencia">
                <span class="mdi mdi-plus"></span> Agregar
            </button>
        </div>
        <div class="col-6">
            <form action="{{ route('dte.contingenciasSearch') }}" method="POST">
                @csrf

                <input type="text" class="form-control" name="txtBusqueda" value="{{ $txtBusqueda ?? '' }}" placeholder="Buscar..." autocomplete="off">
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Acciones</th>
                            <th scope="col">#</th>
                            <th scope="col">Código</th>
                            <th scope="col">Valor</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Fecha creacion</th>
                            <th scope="col">Fecha edicion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($p as $d)
                            <tr>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-light" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="mdi mdi-cog"></span>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            <li><span class="text-mutted p-3">Opciones</span></li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('dte.contingenciasEdit',['id'=>$d->cid]) }}">
                                                    <span class="mdi mdi-pencil"></span> Editar
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('dte.contingenciasStatus',['id'=>$d->cid]) }}">
                                                    <span class="font-bold mdi mdi-{{ $d->estado ? 'toggle-switch text-success' : 'toggle-switch-off text-danger' }}"></span> {{ $d->estado ? 'Desactivar ' : 'Activar' }}
                                                </a>
                                            </li>
                                            <li class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('dte.contingenciasConfirm',['id'=>$d->cid]) }}">
                                                    <span class="mdi mdi-delete text-danger"></span> Eliminar
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <th>{{ $loop->index + 1 }}</th>
                                <td>{{ $d->codigo }}</td>
                                <td>{{ $d->valor }}</td>
                                <td>
                                    @if($d->estado)
                                        <span class="text-success">Activo</span>
                                    @else
                                        <span class="text-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td><small class="text-muted">{{ $d->created_at }}</small></td>
                                <td><small class="text-muted">{{ $d->updated_at }}</small></td>
                            </tr>
                        @empty
                            <tr><td colspan="8">Aun no se han agregado datos.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
  
    <!--Modal agregar-->
    <div class="modal fade" id="modalContingencia" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('dte.contingenciasStore') }}" method="POST">
                @csrf

                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel"><span class="mdi mdi-plus"></span> Crear contingencia</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="codigo" class="form-label">Código:</label>
                            <input type="text" class="form-control" id="codigo" name="codigo" value="{{ old('codigo') ?? '' }}" maxlength="4" required pattern="[0-9]{4}" inputmode="numeric" placeholder="0000" autocomplete="off" aria-describedby="codigoHelp">

                            @error('codigo')
                                <div id="codigoHelp" class="form-text text-danger">
                                    <span class="mdi mdi-alert"></span> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="valor" class="form-label">Valor:</label>
                            <input type="text" class="form-control" id="valor" name="valor" value="{{ old('valor') ?? '' }}" maxlength="100" required pattern="[a-zA-Z]{3,100}" placeholder="Escriba aqui..." autocomplete="off" aria-describedby="valorHelp">

                            @error('valor')
                                <div id="valorHelp" class="form-text text-danger">
                                    <span class="mdi mdi-alert"></span> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><span class="mdi mdi-close"></span> Cerrar</button>
                        <button type="submit" class="btn btn-primary"><span class="mdi mdi-content-save"></span> Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
