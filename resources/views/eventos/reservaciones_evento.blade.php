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


        .card {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            color: #37474F;
            background-color: #ffffff;
            padding: 2rem;
        }

        .btn-primary {
            background-color: #009688;
            border-color: #009688;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #00796B;
            border-color: #00796B;
        }

        .btn-outline-success {
            border-color: #00796B;
            color: #00796B;
        }

        .btn-outline-success:hover {
            background-color: #00796B;
            color: #fff;
        }

        .form-label {
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: #37474F;
        }

        .form-control {
            border-radius: 4px;
        }

        .btn-group {
            display: flex;
            flex-wrap: wrap;
        }

        .btn-check:checked + .btn-outline-success {
            background-color: #00796B;
            color: #fff;
        }

        .text-danger {
            color: #f44336;
        }

        .text-muted {
            color: #9e9e9e;
        }

        .text-success {
            color: #4caf50;
        }

        .mb-3 {
            margin-bottom: 1rem;
        }
    </style>
@endsection
@section('content')
      <div class="container" id="reservacionEvento">
        <div class="row justify-content-center panel-body">
            <div class="col-md-10 ">

                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-uppercase mb-4">Crear reservaciones del Evento # {{$evento->id}}</h3>

                        <!-- Mensajes de alerta -->
                        <x-message></x-message>

                        <p class="card-text">
                            <b>Cliente: {{$evento->clientes->nombre ?? $evento->titular}}</b>


                        </p>

                        <form action="{{ route('reservaciones.reserva_evento') }}" method="post">
                            @csrf

                            <input type="hidden" name="eventos_id" value="{{$evento->cid }}">
                            <input type="hidden" name="clientes_id" value="{{\Crypt::encryptString($evento->clientes->id) }}">

                            <div class="mb-3">
                                <label class="form-label">Medio de reserva:</label><br>
                                <div class="  btn-group mt-2" role="group" aria-label="Basic radio toggle button group">
                                    @foreach ($tipo_reservaciones as $tr)
                                        <input type="radio" class="btn-check" name="tipo_reservaciones_id"
                                            value="{{ \Crypt::encryptString($tr->id) }}" id="btnradio-{{ $tr->id }}"
                                            autocomplete="off" required>
                                        <label class="btn btn-outline-success"
                                            for="btnradio-{{ $tr->id }}">{{ $tr->tipo_reservacion }}</label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="mb-3">

                            <button type="submit" class="btn btn-primary" >
                                Siguiente <span class="mdi mdi-arrow-right-thick"></span>
                            </button>
                                <button class="btn btn-outline-secondary btn-sm" role="button" @click="goBack">
                                    <span class="mdi mdi-arrow-left-thick"></span> Volver
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
        <script>
        const reservacionEvento = new Vue({
            el: '#reservacionEvento',
            data: {
                evento: @json($evento),
                titular:'',
                contacto:'',


            },
            methods: {
                // Add your methods here
            },
        });
    </script>
@endsection
