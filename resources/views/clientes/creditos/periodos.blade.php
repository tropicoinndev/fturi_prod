@extends('layouts.clientes_panel')

@section('style-content')
    <style>
        body {
            background: #EF9A9A !important;
        }
    </style>
@endsection

@section('content_cliente')
    <div class="container" id="appClientesEdit">
        <div class="card-body p-2">
            <h5 class="card-title text-uppercase mb-4 fw-bold">
                Clientes sin periodos de créditos
            </h5>

            <div class="row mb-2">

                <div class="col-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Identificación</th>
                                @can('clientes.credito')
                                    <th scope="col" class="text-center">Periodo de credito</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $url_periodo = route('clientes.periodoCreditoUpdate');
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
        app.mount("#appClientesEdit");
    </script>
@endsection
