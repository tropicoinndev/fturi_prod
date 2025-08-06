@extends('layouts.cajas')

@section('panel_caja')
    <div id="appPanelCaja">
        <div class="row mb-2">
            <div class="col-12 text-uppercase h3">
                {{ $title }}
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12">
                <form class="form-inline" method="post" action="{{ $search }}">
                    @csrf
                    <div class="row">

                        <div class="col-4">
                            <label for="">Del</label>
                            <input type="date" class="form-control" name="inicio" v-model="fecha_inicio">
                        </div>
                        <div class="col-4">
                            <label for="">Al</label>
                            <input type="date" class="form-control" name="fin" v-model="fecha_fin">
                        </div>
                        <div class="col-4 align-self-end">

                            <button class="btn btn-outline-primary" value="{{ Crypt::encryptString(1) }}" name="accion"
                                type="submit" :disabled="isValid()">Buscar</button>
                            <button class="btn btn-outline-success" value="{{ Crypt::encryptString(2) }}" name="accion"
                                type="submit" :disabled="isValid()">Generar reporte</button>

                        </div>

                    </div>



                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-12 table-responsive">

                <table class="table table-striped table-inverse">
                    <thead class="thead-inverse">
                        <tr>
                            <th>CMD</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Caja</th>
                            <th>Solicitante</th>
                            <th>Responde</th>
                            <th>Producido</th>
                            <th>T.Solicitado</th>
                            <th>RS. Solicitud</th>
                            <th>Tiempo</th>
                            <th>Extra</th>
                            <th>Total</th>
                            <th>Estado</th>

                        </tr>
                    </thead>
                    <tbody>

                        @forelse ($data as $d)
                            <tr>
                                <th class="text-uppercase">{{ $d->comandas_id }}</th>
                                <th class="text-uppercase">{{ $d->dprecio->detalle }}</th>
                                <th>{{ $d->cantidad }}</th>
                                <th class="text-uppercase">{{ $d->comandawtcaja?->cajas->caja }}</th>
                                <th class="text-uppercase">{{ $d->user_solicita?->user }}</th>
                                <th class="text-uppercase">{{ $d->user_acepta?->user ?? '---' }}</th>
                                <th class="text-uppercase">{{ $d->user_asignado?->user ?? '---' }}</th>
                                <th><small>{{ $d->solicitud }}</small></th>
                                <th title="{{ $d->aceptacion }}">
                                    {{ $d->aceptaciontime }}
                                    @isset($d->aceptacion)
                                        <small>[{{ $d->aceptacion }}]</small>
                                    @endisset
                                </th>
                                <th>
                                    {{ $d->esperatime }}
                                </th>
                                <th>{{ $d->incrementotime }}</th>
                                <th>
                                    @isset($d->aceptacion)
                                        {{ \Carbon::parse($d->entregado)->diffForHumans(\Carbon::parse($d->aceptacion)) }}
                                    @endisset
                                </th>
                                <th>
                                    {{ $d->status }}
                                </th>

                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <script setup>
        var app = new Vue({
            el: '#appPanelCaja',
            data: {
                fecha_inicio: "{{ $inicio ?? '' }}",
                fecha_fin: "{{ $fin ?? '' }}",

            },
            methods: {
                isValid: function() {
                    let fi = this.getDate(this.fecha_inicio)
                    let ff = this.getDate(this.fecha_fin);
                    if (fi[0] && ff[0] && fi[1].getTime() <= ff[1].getTime())
                        return false;
                    else return true;
                },
                getDate: function(fecha) {
                    const date = new Date(fecha);
                    return [!isNaN(date.getTime()) && date.toISOString().slice(0, 10) === fecha, date];
                }
            },
            computed: {

            }
        });
    </script>
@endsection
