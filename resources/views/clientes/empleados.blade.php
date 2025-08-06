@extends('layouts.clientes_panel')

@section('style-content')
    <style>
        body {
            background: #80DEEA !important;
        }
    </style>
@endsection

@section('content_cliente')
    <div class="container" id="appClientesEdit">
        <div class="card-body p-2">
            <h5 class="card-title text-uppercase mb-4 fw-bold">
                Edición clientes empleados y periodos de créditos
            </h5>

            <div class="row mb-2">
                <div class="col-12 mb-4">
                    <form action="{{ route('clientes.empleado_search') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <label for="nombre" class="form-label">Buscar cliente</label>
                                <input type="text" class="form-control" name="nombre" id="nombre"
                                    aria-describedby="helpId" placeholder="Escriba el nombre del cliente"
                                    value="{{ $nombre ?? '' }}" />
                            </div>
                            <div class="col-2 flex align-content-end">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="credito"
                                        {{ isset($credito) && $credito ? 'checked' : '' }} name="credito">
                                    <label class="form-check-label" for="credito">
                                        Con crédito
                                    </label>
                                </div>
                            </div>
                            <div class="col-2 flex align-content-end">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="empleado"
                                        {{ isset($empleado) && $empleado ? 'checked' : '' }} name="empleado">
                                    <label class="form-check-label" for="empleado">
                                        No son empleados
                                    </label>
                                </div>
                            </div>
                            <div class="col-2 flex align-content-end">
                                <button class="btn btn-primary float-end" type="submit">
                                    <span class="mdi mdi-magnify"></span>
                                    Buscar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Identificación</th>
                                <th style="width: 190px;" class="text-center" scope="col">Accionista</th>
                                <th scope="col" class="text-center" width="200px">Empleado</th>
                                @can('clientes.credito')
                                    <th scope="col" class="text-center">Periodo de credito</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $url_periodo = route('clientes.periodoCreditoUpdate');
                                $url_empleado = route('clientes.empleado_update');
                                $url_accionista = route('clientes.accionista_update');
                            @endphp
                            @foreach ($data as $c)
                                <tr>
                                    <th scope="row">
                                        {{ $c->id }}
                                    </th>
                                    <td style="max-width: 300px !important;" class="text-truncate">
                                        {{ $c->nombre }}
                                    </td>
                                    <td>
                                        @if ($c->identificacion != null)
                                            {{ $c->identificacion?->identificaciones->identificacion }}:
                                            {{ $c->identificacion?->numero }}
                                        @else
                                            Sin identificación
                                        @endif
                                    </td>
                                    <td>
                                        <status_control actual="{{ $c->accionista ? 1 : 0 }}" id="{{ $c->cid }}"
                                            url="{{ $url_accionista }}" />
                                    </td>
                                    <td>
                                        <status_control actual="{{ $c->empleado ? 1 : 0 }}" id="{{ $c->cid }}"
                                            url="{{ $url_empleado }}" />
                                    </td>
                                    @can('clientes.credito')
                                        <td>
                                            @if ($c->credito)
                                                <select_control :data="periodos" name="periodo"
                                                    actual="{{ $c->periodos_creditos_id }}" id="{{ $c->cid }}"
                                                    url="{{ $url_periodo }}" />
                                            @else
                                                No tiene habilitado credito
                                            @endif
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if (!(isset($busqueda) && $busqueda))
                    <div class="col-12">
                        {{ $data->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('script-content')
    <script type="module">
        var app = window.appVue({
            data() {
                return {
                    periodos: @json($periodos)
                }
            },
        });
        app.component('select_control', component.select_control);
        app.component('status_control', component.status_control);
        app.mount("#appClientesEdit");
    </script>
@endsection
