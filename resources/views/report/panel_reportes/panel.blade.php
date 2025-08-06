@extends('layouts.panel_reportes')

@section('css-panel_reportes')
    <style>
        .column,
        .dia {
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

        body {
            background: #E1F5FE;
        }

        .bold-text {
            font-weight: bold;
        }
    </style>
@endsection
@section('panel_reportes')
    <div id="appReporteTurnos" class="container">

        <div class="card-body p-2 ">
            <div class="row mb-4">
                <div class="col-12 text-uppercase h3">
                    Reporte de turnos
                </div>
            </div>
            <form action="{{ route('cajas.panel_turnos') }}" method="post">
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
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="inicio" class="form-label">Del</label>
                            <input type="date" name="inicio" id="inicio" class="form-control"
                                value="{{ $inicio ?? '' }}" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="fin" class="form-label">Al</label>
                            <input type="date" name="fin" id="fin" class="form-control"
                                value="{{ $fin ?? '' }}" />
                        </div>
                    </div>
                    <div class="col-md-6 d-flex align-items-center justify-content-start">
                        <div class="mb-3">
                            <button class="btn btn-light me-2" type="submit" value="1" name="opcion">
                                <span class="mdi mdi-magnify h5"></span> Buscar
                            </button>
                            <!--button class="btn btn-light" type="submit" value="2" name="opcion">
                                        <span class="mdi mdi-file-pdf-box h5"></span> PDF
                                    </button-->
                        </div>
                    </div>
                </div>
            </form>

            <div class="row">
                @foreach ($cajas as $caja)
                    @php
                        // Filtramos los turnos de la caja actual
                        $turnosCaja = $turnos->where('cajas_id', $caja->cajas->id);
                    @endphp

                    @if ($turnosCaja->isNotEmpty() && ($cajaId === null || $cajaId == $caja->cajas->id))
                        <div class="col-12">
                            <table class="table table-striped table-inverse">
                                <thead class="thead-inverse">
                                    <tr colspan="12">{{ $caja->cajas->caja }}</tr>
                                    <tr>
                                        <th>Turno</th>
                                        <th>Apertura</th>
                                        <th>Cierre</th>
                                        <th>Ver reporte</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($turnosCaja as $turno)
                                        <tr>
                                            <td>{{ $turno->opcion->turno }}</td>
                                            <td>{{ $turno->apertura }} · {{ $turno->uapertura->name }}</td>
                                            <td>{{ $turno->cierre ?? 'Aún sigue abierto' }}
                                                {{ $turno->ucierre->name ?? '' }}</td>
                                            <td>
                                                @if (!$turno->estado)
                                                    <a class="btn btn-outline-secondary"
                                                        href="{{ route('cajas.cierre_print', ['id' => \Crypt::encryptString($turno->id)]) }}"
                                                        role="button" target="_blank">PDF</a>
                                                @else
                                                    <p class="btn btn-outline-secondary">Abierto</p>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                @endforeach
            </div>
            @if ($turnos->isEmpty())
                <p class="text-uppercase">No se han aperturado turnos en ninguna caja.</p>
            @endif
        </div>
    </div>
    </div>
@endsection
@section('script')
    <script>
        const panel_turno = new Vue({
            el: '#appReporteTurnos',
            data: {
                inicio: "{{ $inicio ?? '' }}",
                fin: "{{ $fin ?? '' }}",
                turnos: @json($turno_selected ?? []),
            },
            mounted() {
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
