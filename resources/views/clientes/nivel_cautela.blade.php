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
                Edición de nivel de cautela
            </h5>

            <div class="row mb-2">
                <div class="col-12 mb-4">
                    <form action="{{ route('clientes.nivel_cautela_search') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-4">
                                <label for="nombre" class="form-label">Buscar cliente</label>
                                <input type="text" class="form-control" name="nombre" id="nombre"
                                    aria-describedby="helpId" placeholder="Escriba el nombre del cliente"
                                    value="{{ $nombre ?? '' }}" />
                            </div>
                            <div class="col-4">
                                <label for="pais" class="form-label">Nacionalidad</label>
                                <select class="form-select" id="pais" name="pais">
                                    <option selected value="">Todos los países
                                    </option>
                                    @foreach ($paises as $p)
                                        <option value="{{ $p->id }}"
                                            {{ isset($pais) && $pais == $p->id ? 'selected' : '' }}>
                                            {{ $p->pais }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 flex align-content-end">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="etiqueta"
                                        {{ isset($etiqueta) && $etiqueta ? 'checked' : '' }} name="etiqueta">
                                    <label class="form-check-label" for="etiqueta">
                                        Sin nivel de riesgo
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
                                <th scope="col">Nacionalidad</th>
                                <th scope="col">Identificación</th>
                                <th scope="col" class="text-center">Notificaciones</th>
                                <th scope="col" class="text-center">Nivel de riesgo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $url = route('clientes.nivel_cautela_update');
                                $url_notificacion = route('clientes.notificacion_update');
                            @endphp
                            @foreach ($data as $c)
                                <tr>
                                    <th scope="row">
                                        {{ $c->id }}
                                    </th>
                                    <td>
                                        {{ $c->nombre }}
                                    </td>
                                    <td>
                                        @if ($c->extranjero != null)
                                            {{ $c->extranjero->nacionalidad }}
                                        @elseif($c->municipios_id != null)
                                            Salvadoreña
                                        @else
                                            Sin registro.
                                        @endif

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
                                        <status_control actual="{{ $c->notificacion ? 1 : 0 }}"
                                            id="{{ $c->cid }}" url="{{ $url_notificacion }}" />
                                    </td>
                                    <td>
                                        <cautela :data="cautelaList" actual="{{ $c->nivel_cautela }}"
                                            id="{{ $c->cid }}" url="{{ $url }}" />
                                    </td>
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
                    cautelaList: @json($nivelesCautela)
                }
            },
        });
        app.component('cautela', component.cautela);
        app.component('status_control', component.status_control);
        app.mount("#appClientesEdit");
    </script>
@endsection
