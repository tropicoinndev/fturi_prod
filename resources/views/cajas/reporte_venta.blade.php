@extends('layouts.cajas')

@section('panel_caja')
    <div id="appPanelCaja">
        <div class="row mb-2">
            <div class="col-12 text-uppercase h3">
                Generar reporte de ventas
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12">
                <form class="form-inline" method="post" action="{{ route('cajas.venta_reporte_search') }}">
                    @csrf
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="">Seleccione los turnos</label><br>
                            <div class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
                                <input type="checkbox" class="btn-check" id="btncheck1" autocomplete="off"
                                    :checked="turnos.length == 0 || turnos == null" @click="turnos = []">
                                <label class="btn btn-outline-primary" for="btncheck1">TODOS</label>

                                @foreach ($turnos as $t)
                                    <input class="btn-check" name="turnos[]" type="checkbox"
                                        value="{{ $t->opcion_turnos->id }}" autocomplete="off"
                                        id="turnos_{{ $t->id }}" v-model="turnos">
                                    <label class="btn btn-outline-primary" for="turnos_{{ $t->id }}">
                                        {{ $t->opcion_turnos->turno }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-4">
                            <label for="">Del</label>
                            <input type="date" class="form-control" name="fecha_inicio" v-model="fecha_inicio">
                        </div>
                        <div class="col-4">
                            <label for="">Al</label>
                            <input type="date" class="form-control" name="fecha_fin" v-model="fecha_fin">
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
                            <th>Caja</th>
                            <th>Turno</th>
                            <th>Apertura</th>
                            <th>Cierre</th>
                            <th>Ver reporte</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list as $t)
                            <tr>
                                <td scope="row">{{ $t->cajas->caja }}</td>
                                <td scope="row">{{ $t->opcion->turno }}</td>
                                <td>{{ $t->apertura }} · {{ $t->uapertura->name }}</td>
                                <td>{{ $t->cierre ?? 'Aun sigue abierto' }} {{ $t->ucierre->name ?? '' }}</td>
                                <td>
                                    <a class="btn btn-outline-secondary"
                                        href="{{ route('cajas.venta_print', ['id' => \Crypt::encryptString($t->id)]) }}"
                                        role="button" target="_blank">
                                        PDF
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <script setup>
        var app = new Vue({
            el: '#appPanelCaja',
            data: {
                fecha_inicio: "{{ $fecha_inicio ?? '' }}",
                fecha_fin: "{{ $fecha_fin ?? '' }}",
                turnos: @json($turno_selected ?? []),
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
