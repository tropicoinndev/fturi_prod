@extends('layouts.panel_reportes')

@section('css-panel_reportes')
    <style>
        /*
            NOTA: ---LOS ESTILOS COMENTADOS NO SE USAN EN ESTE REPORTE, ESTABAN POR DEFECTO---

        .column, .dia {
            position: relative;
            width: 14.28%;
        }

        .dia {

            max-height: 100px;
            height: 100px;
            cursor: pointer;
        }

        .dia-content {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        .ocupado {
            background: #455A64;
            color: #fafafa;
        }

        .dia .tooltip {
            visibility: hidden;
            width: 180px;
            background-color: #2979FF;
            color: #fff;
            text-align: center;
            padding: 8px;
            border-radius: 5px;
            position: absolute;
            z-index: 1;
            bottom: 110%;
            left: 50%;
            margin-left: -75px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .ocupado:hover .tooltip {
            visibility: visible;
            opacity: 1;
        }

        .ocupado:hover {
            background: #009688;
            color: #fafafa;
        }

        .flecha {
            position: absolute;
            width: 0px;
            height: 0px;
            margin-left: 20px;
            border-top: 15px solid #2979FF;
            border-right: 15px solid transparent;
            border-bottom: 15px solid transparent;
            border-left: 15px solid transparent;
        }

        .head {
            background: #CFD8DC;
            padding: 10px;
        }

        .panelCalendar {
            min-height: 85vh;
        }

        .bold-text {
            font-weight: bold;
        }*/

        body {
            background: #E1F5FE;
        }
    </style>
@endsection
@section('panel_reportes')
    <div id="appReporteVentasCajas" class="container">
        <div class="card-body p-2 ">
            <div class="row mb-4">
                <div class="col-12 text-uppercase h3">
                    {{$title}}
                </div>
            </div>
            <form action="{{ $search }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-4">
                        <div class="mb-2">

                            <label for="" class="form-label">
                                Cajas
                            </label>
                            <select class="form-select" name="cajas_id">
                                <option selected value="0">Todas las cajas disponibles</option>
                                @foreach ($cajas as $c)
                                    <option value="{{ $c->cajas->id }}"
                                        {{ isset($cajaId) && $cajaId == $c->cajas->id ? 'selected' : '' }}>
                                        {{ $c->cajas->caja }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="mb-2">
                            <label for="" class="form-label">Seleccione los turnos</label><br>
                            <div class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
                                <input type="checkbox" class="btn-check" id="btncheck1" autocomplete="off"
                                    :checked="turnos.length == 0 || turnos == null" @click="turnos = []">
                                <label class="btn btn-outline-primary" for="btncheck1">TODOS</label>

                                @foreach ($opcion as $t)
                                    <input class="btn-check" name="turnos[]" type="checkbox" value="{{ $t->id }}"
                                        autocomplete="off" id="turnos_{{ $t->id }}" v-model="turnos">
                                    <label class="btn btn-outline-primary" for="turnos_{{ $t->id }}">
                                        {{ $t->turno }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                    </div>

                </div>

                <div class="row align-items-end mb-2">
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="inicio" class="form-label">Del:</label>
                            <input type="date" name="inicio" id="inicio" class="form-control"
                                value="{{ $inicio ?? '' }}" />
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="fin" class="form-label">Al:</label>
                            <input type="date" name="fin" id="fin" class="form-control"
                                value="{{ $fin ?? '' }}" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="comandas_id" class="form-label">Nº de Comanda: @if(isset($comandasId) && $comandasId) (total encontradas: {{ $data->count() }}) @endif</label>
                            <input type="number" class="form-control" id="comandas_id" name="comandas_id" value="{{ $comandasId ?? '' }}" placeholder="Buscar por número de comanda">
                        </div>
                    </div>
                    <div class="col-md-2">
                        {{-- <label for="" class="form-label">Rechazadas:</label> --}}
                        <div style="margin-bottom: 20px;" class="form-check fs-5">
                            <input class="form-check-input" type="checkbox" value="1" id="cancelado" name="cancelado" @if(isset($cancelado) && $cancelado) checked @endif>
                            <label class="form-check-label" for="cancelado">Cancelada <small>{{ (isset($cancelado) && $cancelado) ? '('.$data->count().')' : '' }}</small></label>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-center justify-content-start">
                        <div class="mb-3">
                            <button class="btn btn-light me-2" type="submit" value="1" name="opcion">
                                <span class="mdi mdi-magnify h5"></span> Buscar
                            </button>
                            <button class="btn btn-light" type="submit" value="2" name="opcion">
                                <span class="mdi mdi-file-pdf-box h5"></span> PDF
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            {{--Diseño original--}}
            {{-- <div class="row">
                <div class="col-12 table-responsive">
                    <table class="table table-striped table-inverse">
                        <thead class="thead-inverse">
                            <tr>
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
                                    <th class="text-uppercase">
                                        {{ $d->dprecio->detalle }} @if($d->observaciones) · <i><small>{{ $d->observaciones }}</small></i> @endif
                                    </th>
                                    <th>{{ $d->cantidad }}</th>
                                    <th class="text-uppercase">{{ $d->comandawtcaja->cajas->caja }}</th>
                                    <th class="text-uppercase">{{ $d->user_solicita->user ?? '----'}}</th>
                                    <th class="text-uppercase">{{ $d->user_acepta->user ?? '---' }}</th>
                                    <th class="text-uppercase">{{ $d->user_asignado->user ?? '---' }}</th>
                                    <th><small>{{ $d->solicitud }}</small></th>
                                    <th title="{{ $d->aceptacion }}">
                                        {{ $d->aceptaciontime }}
                                        @isset($d->aceptacion)
                                            <small>[{{ $d->aceptacion }}]</small>
                                        @endisset
                                    </th>
                                    <th>{{ $d->esperatime  }}</th>
                                    <th>{{ $d->incrementotime }}</th>
                                    <th>
                                        @isset($d->aceptacion)
                                            {{ \Carbon::parse($d->entregado)->diffForHumans(\Carbon::parse($d->aceptacion)) }}
                                        @endisset
                                    </th>
                                    <th>{{ $d->status }}</th>
                                </tr>
                            @empty
                            <tr>
                                <td colspan="12"><p class="text-uppercase"> No hay pedidos aún.</p></td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div> --}}

            {{--Diseño nuevo--}}
            <div style="font-size: 10pt; letter-spacing: 0.6px;" class="row align-items-center text-uppercase">
                <div class="col-12">

                    @forelse($data as $d)
                        <div class="card mb-3 rounded-3">
                            <div style="margin-bottom: -15px;" class="card-header bg-transparent border-0">
                                <div class="row">
                                    <div class="col-11">
                                        <span class="card-title fs-5">{{ $d->dprecio->detalle }} @if($d->observaciones) · <small><i class="text-muted">{{ $d->observaciones }}</i></small> @endif</span>
                                    </div>
                                    <div class="col-1 text-end">
                                        <span class="fs-5" title="Número de la comanda"><b>#{{ $d->comandas_id ?? '' }}</b></span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body bg-transparent">
                                <div class="row align-items-center">
                                    <div class="col-1 text-center">
                                        <b class="fs-4" title="Cantidad del producto">{{ $d->cantidad }}</b>
                                    </div>
                                    <div class="col-11">
                                        <!--Información sobre la comanda-->
                                        {{-- <div class="row">
                                            <div class="col-12">
                                                <span class="mdi mdi-pound me-2"></span> <b>Comanda:</b> <span style="font-size: 8.5pt;" class="badge text-bg-secondary">#{{ $d->comandas_id ?? '' }}</span>
                                            </div>
                                        </div> --}}

                                        <!--Información sobre usuarios-->
                                        <div class="row">
                                            <div class="col-5">
                                                <span class="mdi mdi-account-outline me-2"></span> <b>Solicitante:</b> {{ $d->user_solicita->user ?? '' }}
                                            </div>
                                            @if($d->user_acepta)
                                                <div class="col-4">
                                                    <b>Responde:</b> {{ $d->user_acepta->user ?? '' }}
                                                </div>
                                            @endif
                                            @if($d->user_asignado)
                                                <div class="col-3">
                                                    <b>Producido:</b> {{ $d->user_asignado->user ?? '' }}
                                                </div>
                                            @endif
                                        </div>

                                        <!--Información sobre tiempos-->
                                        <div class="row">
                                            <div class="col-5">
                                                <span class="mdi mdi-clock-time-four-outline me-2"></span> <b>T. Solicitado:</b> {{ $d->solicitud }}
                                            </div>
                                            @if($d->esperatime)
                                                <div class="col-2">
                                                    <b>Tiempo:</b> {{ $d->esperatime }}
                                                </div>
                                            @endif
                                            @if($d->esperatime || $d->incrementotime)
                                                <div class="col-2">
                                                    <b>Extra:</b> {{ $d->incrementotime }}
                                                </div>
                                            @endif
                                            @if($d->aceptacion)
                                                <div class="col-3">
                                                    <b>Total:</b>
                                                    @isset($d->aceptacion)
                                                        {{ \Carbon::parse($d->entregado)->diffForHumans(\Carbon::parse($d->aceptacion)) }}
                                                    @endisset
                                                </div>
                                            @endif
                                        </div>
                                        <div class="row">
                                            <div class="col-5">
                                                <span class="mdi mdi-clock-time-four-outline me-2"></span> <b>RS. Solicitud:</b> {{ $d->aceptaciontime }}
                                                @isset($d->aceptacion)
                                                    <small>[{{ $d->aceptacion }}]</small>
                                                @endisset
                                            </div>
                                            @if($d->status)
                                                <div class="col-7">
                                                    <b>Estado:</b> {{ $d->status }}
                                                </div>
                                            @endif
                                        </div>

                                        <!--Información de caja-->
                                        <div class="row">
                                            <div class="col-12">
                                                <span class="mdi mdi-cash-register me-2"></span> <b>Caja:</b> --{{ $d->comandawtcaja->cajas->caja }}--
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        No hay pedidos aún.
                    @endforelse
                </div>
            </div>

        </div>
    </div>
@endsection
@section('script')
    <script>
        const panel_turno = new Vue({
            el: '#appReporteVentasCajas',
            data: {
                inicio: "{{  $inicio ?? (new \DateTime())->format('Y-m-d') }}",
                fin: "{{ $fin ?? (new \DateTime())->format('Y-m-d') }}",
                turnos: @json($turno_selected ?? []),
            },
            mounted() {
                console.log('Vue.js está montado correctamente');
                //validacion maximos y minimos de personas
                document.addEventListener("DOMContentLoaded", function() {

                    //validacion fechas
                    const fecha = document.getElementById("inicio");
                    const final = document.getElementById("fin");

                    fecha.addEventListener("change", function() {
                        const fechaInicio = new Date(fecha.value);
                        const fechaFin = new Date(final.value);

                        if (fechaInicio > fechaFin) {
                            alert(
                                "La fecha de inicio no puede ser mayor a la fecha de finalización."
                            );
                            fecha.value = final
                                .value;
                        }
                    });

                    final.addEventListener("change", function() {
                        const fechaInicio = new Date(fecha.value);
                        const fechaFin = new Date(final.value);

                        if (fechaFin < fechaInicio) {
                            alert(
                                "La fecha de finalización no puede ser menor que la fecha de inicio del evento."
                            );
                            final.value = fecha
                                .value;
                        }
                    });

                });

            },

        });
    </script>
@endsection
