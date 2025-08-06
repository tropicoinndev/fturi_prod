@extends('layouts.clientes_panel')

@section('style-content')
    <style>
        body {
            background: #BF360C !important;
        }
    </style>
@endsection

@section('content_cliente')
    <div class="container" id="appClientesEdit">
        <div class="card-body p-2">
            <h5 class="card-title text-uppercase mb-4 fw-bold">
                Clientes Jurídicos sin municipio
            </h5>

            <div class="row mb-2">

                <div class="col-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Identificación</th>
                                <th scope="col" class="text-center">Municipios</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $url_periodo = route('clientes.api_municipios_update');
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

                                    @can('clientes.credito')
                                        <td>
                                            <search_control :data="dataList" name="municipio" id="{{ $c->cid }}"
                                                url="{{ $url_periodo }}" />

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
                    dataList: @json($dataList)
                }
            },
        });
        app.component('search_control', component.search_control);
        app.mount("#appClientesEdit");
    </script>
@endsection
