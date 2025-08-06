@extends('layouts.hab')

@section('content-hab')
    <style>
        .message {
            position: fixed;
            top: 10%;
            right: 1%;
            width: 20%;
            z-index: 100;
        }
    </style>

    <div id="appReservacionesCreate" class="container">
        <div class="row justify-content-center">
            <div class="card">
                <div class="card-body">
                    <x-message></x-message>
                    <h4 class="card-title text-uppercase mb-4">
                        Confirmar reservacion #{{ $hab->reserva[0]->reservaciones_id }} en Habitacion
                        {{ $hab->numero_habitacion }}
                    </h4>
                    <div class="card-text row">
                        <div class="col-2 mb-2">Cliente:</div>
                        <div class="col-10 mb-2">
                            @if ($hab->reserva[0]->relacionReservaciones->clientes_id > 0)
                                {{ $hab->reserva[0]->relacionReservaciones->relacionClientes->nombre }}
                            @else
                                {{ $hab->reserva[0]->relacionReservaciones->titular }}
                            @endif
                        </div>
                        <div class="col-2 mb-2">Estadía:</div>
                        <div class="col-10 mb-2">
                            {{ \Carbon::parse($hab->reserva[0]->fecha_ingreso)->format('d-m-Y') }} al
                            {{ \Carbon::parse($hab->reserva[0]->fecha_salida)->format('d-m-Y') }} ·
                            {{ \Carbon::parse($hab->reserva[0]->fecha_ingreso)->diffInDays($hab->reserva[0]->fecha_salida) }}
                            dia(s)
                        </div>
                        <div class="col-2 mb-2">Forma de pago:</div>
                        <div class="col-10 mb-2">
                            {{ $hab->reserva[0]?->reservacion?->forma_pago?->forma ?? 'NO SE AGREGO UNA FORMA DE PAGO' }}
                        </div>
                        <div class="col-2 mb-2">Observaciones:</div>
                        <div class="col-10 mb-2">
                            {{ $hab->reserva[0]->descripcion ?? '---' }}
                        </div>
                        <div class="col-2 mb-2">Cantidad de huespedes:</div>
                        <div class="col-10 mb-2">
                            Se espera(n) {{ $hab->reserva[0]->cantidad_personas }} persona(s) ·
                            {{ count($hab->reserva[0]->huespedes) }} / {{ $hab->reserva[0]->cantidad_personas }}
                        </div>
                        <div class="col-2 mb-2">Huespedes registrados:</div>
                        <div class="col-10 mb-2">
                            <ul>
                                @forelse ($hab->reserva[0]->huespedes as $h)
                                    <li>{{ $h->huesped->nombre }}</li>
                                @empty
                                    <li>Ningun huesped registrado</li>
                                @endforelse

                            </ul>
                        </div>
                        <div class="col-2 mb-2">Tarifa por día:</div>
                        <div class="col-10 mb-2">
                            ${{ number_format($hab->reserva[0]->relacionTarifas->precio, 2) }} ·
                            {{ $hab->reserva[0]->relacionTarifas->tarifa }}
                        </div>
                        <div class="col-2 mb-2">Total estadia:
                            [{{ \Carbon::parse($hab->reserva[0]->fecha_ingreso)->diffInDays($hab->reserva[0]->fecha_salida) }}
                            dia(s)]</div>


                        <div class="col-10 mb-2">
                            ${{ number_format($hab->reserva[0]->relacionTarifas->precio * intval(\Carbon::parse($hab->reserva[0]->fecha_ingreso)->diffInDays($hab->reserva[0]->fecha_salida)), 2) }}
                        </div>

                        <div class="col-12 mt-4">
                            <form action="{{ route('recepciones.create_reserva') }}" method="post"
                                id="reservacionesCreateForm">
                                @csrf
                                @if ($hab->reserva[0]->cid == null || $hab->reserva[0]->id == null)
                                    <div class="alert alert-danger" role="alert">
                                        No se encontro una reservacion
                                    </div>
                                @else
                                    <input type="hidden" name="detalle_reservaciones_id"
                                        value="{{ $hab->reserva[0]->cid }}">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" value="1" id="flexCheckChecked"
                                            required autocomplete="none">
                                        <label class="form-check-label" for="flexCheckChecked">
                                            Confirmar reservación
                                        </label>
                                    </div>
                                    <button class="btn btn-primary" type="submit" id="btnIngreso">Ingreso</button>
                                    <div id="labelProcess" style="display: none;">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Procesando...</span>
                                        </div>
                                        <span>Procesando...</span>
                                    </div>
                                @endif
                            </form>

                            <script>
                                //---Evitar duplicar registros al hacer click rápidamente y muchas veces en el botón de guardar---
                                //Siempre esperar que cargue todo el DOM
                                document.addEventListener('DOMContentLoaded',function(){
                                    const form = document.querySelector('#form-reserva');
                                    const btnGuardar = document.querySelector('#btn-guardar');

                                    form.addEventListener('submit',function(e){
                                        btnGuardar.disabled = true;
                                        btnGuardar.innerText = 'Guardando...';
                                    });
                                });
                                //-----
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('reservacionesCreateForm').addEventListener('submit', function(e) {

            let btn = document.getElementById('btnIngreso');
            let process = document.getElementById('labelProcess');
            btn.disabled = true
            btn.style.display = 'none';
            process.style.display = 'block';
            console.log('Bloqueado');

        });
    </script>
@endsection
