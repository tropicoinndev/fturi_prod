@extends('layouts.clientes_panel')

@section('style-content')
    <style>
        body {
            background: #FF6E40 !important;
        }
    </style>
@endsection

@section('content_cliente')
    <div class="container" id="appClientesEdit">
        <div class="card-body p-2">
            <h5 class="card-title text-uppercase mb-4 fw-bold">
                Clientes extranjeros sin identificaciones
            </h5>

            <div class="row mb-2">

                <div class="col-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Tipo</th>
                                <th scope="col" class="text-center">Identificaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $url = route('clientes.api_identificacion_update');
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
                                        <span
                                            class="badge rounded-pill {{ $c->tipo_cliente ? 'text-bg-warning' : 'text-bg-danger' }}">


                                            @if ($c->tipo_cliente)
                                                Cliente natural
                                            @else
                                                Cliente juridico
                                            @endif
                                        </span>
                                    </td>

                                    @can('clientes.credito')
                                        <td>
                                            <identificaciones :list="dataList" id="{{ $c->cid }}"
                                                url="{{ $url }}" tipo="{{ $c->tipo_cliente }}" />

                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script-content')
    <script type="module">
        var app = window.appVue({
            data() {
                return {
                    dataList: @json($identificaciones)
                }
            },
        });
        app.component('identificaciones', component.identificaciones);
        app.mount("#appClientesEdit");
    </script>
@endsection
