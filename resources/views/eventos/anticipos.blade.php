@extends('layouts.app')
@section('style')
    <style>
        body {
            background-color: #E0F2F1;
        }

        .panel-body {
            min-height: 91vh;
            color: #FAFAFA;
            top: 0;
        }

        .btn-light {
            background: #DAE0E5;
        }

        .card-eventos {
            background: #E3F2FD;
            border: none;
            box-shadow: 1px 5px 2px #BBDEFB;
            E0F7FA
        }

        .text-eventos {
            color: #37474F;
        }

        .text-cliente {
            color: #37474F;
        }

        .titulo {
            color: #263238;
        }

        .contactos {
            color: #37474F;
            font-size: 9pt;
        }

        .tipo-evento {
            color: #546E7A;
            font-size: 9pt;
        }

        .text-observacion {
            color: #455A64;
        }

        .total {
            color: #37474F;
        }

        .dpl {
            background: #E3F2FD;
        }
    </style>
@endsection
@section('content')
    <div class="container" id="anticipoEvento">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-uppercase mb-1">Crear Anticipos de Eventos</h3>
                        <x-message></x-message>

                        <div class=" mb-1">
                            <div class="card-body">

                                <p class="card-text">
                                    Cliente: {{$evento->clientes->nombre}}<br>
                                    Direccion: {{$evento->clientes->direccion}} <br>
                                    @if ($evento->clientes->tipo_cliente == 1)
                                            Tipo de Cliente: Natural
                                        @elseif ($evento->clientes->tipo_cliente == 0)
                                            Tipo de Cliente: Jurídico
                                        @else
                                            Tipo de Cliente Desconocido
                                        @endif
                                </p>

                            </div>
                        </div>

                        <form action="{{route('anticipos.anticipoEventos')}}" method="post">
                            @csrf

                            <input type="hidden" name="clientes_id" value="{{ $evento->clientes->id }}">

                            <div class="mb-3">
                                        <label for="forma_pagos_id" class="form-label">Seleccione la forma de pago:</label>
                                        <select class="form-select" id="fpago" name="forma_pagos_id" required>
                                            <option value="" disabled selected>Seleccione una forma de pago</option>
                                            @foreach ($Fpagos as $fpago)
                                                <option value="{{ $fpago->id }}">{{ $fpago->forma }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                            <div class="mb-3">
                                <x-input-date name="fecha_aplicacion" label="Fecha de aplicación del anticipo" :min="date('Y-m-d')" required="required" />
                            </div>
                            <div class="mb-3">
                                <label for="concepto" class="form-label">Concepto del anticipo:
                                </label>
                                <textarea class="form-control h-100" id="concepto" name="concepto"
                                    placeholder="Escriba aqui el concepto del anticipo"
                                    rows="3" style="resize: vertical;">Anticipo del evento #{{ $evento->id }} que inicia {{ $evento->fecha }} y finaliza {{ $evento->fecha_fin }}</textarea>

                            </div>
                            <div class="mb-3">
                                <x-input-number name="monto" label="Monto del anticipo:" :min="1" required="required" />
                            </div>

                            @if (isset($evento->id))
                                <input type="hidden" name="tipo_reservacion" value="{{ \Crypt::encryptString(4) }}">
                                <input type="hidden" name="reservacion_id" value="{{ \Crypt::encryptString($evento->id) }}">
                            @endif

                            <div class="mb-3">
                                <button class="btn btn-primary" type="submit">Guardar</button>
                                <button class="btn btn-second btn-sm" role="button" onclick="window.location='{{ route('eventos.detalle', [
                                                'id' => $evento->cid,
                                            ]) }}'" style="background:#D9D9D9;">
                                            <span class="mdi mdi-arrow-left-thick"></span> VOLVER</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
        <script>
        var app = new Vue({
            el: '#anticipoEvento',
            data: {
                evento: @json($evento),
                Fpagos: @json($Fpagos)

            },
            methods: {
            },
        });
    </script>
@endsection
