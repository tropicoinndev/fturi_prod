@extends('layouts.mantenimientos')
@section('css-mantenimiento')
    <style>
        [v-cloak] {
            display: none;
        }
    </style>
@endsection

@section('panel_mantenimiento')
    <div id="appPanelMantenimiento" v-cloak>
        <div class="row mb-4">
            <div class="col-12 text-uppercase h3">
                Reporte de mantenimientos
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12">
                <form class="form-inline" method="post" action="{{ route('mantenimientos.mantenimientos_reporte_search') }}">

                    @csrf
                    <div class="row">
                        <div class="col-4">
                            <label for="">Del</label>
                            <input type="date" class="form-control" name="fecha_inicio" v-model="fecha_inicio"
                                :max="fecha_fin">
                        </div>
                        <div class="col-4">
                            <label for="">Al</label>
                            <input type="date" class="form-control" name="fecha_fin" v-model="fecha_fin"
                                :min="fecha_inicio">
                        </div>
                        <div class="col-4 align-self-end">

                            <button class="btn btn-outline-primary" value="{{ Crypt::encryptString(1) }}" name="accion"
                                type="submit" >Buscar</button>
                            <button class="btn btn-outline-success" value="{{ Crypt::encryptString(2) }}" name="accion"
                                type="submit" :disabled="isValid()">Generar reporte</button>

                        </div>

                    </div>



                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-12 table-responsive" v-if="mantenimientos.length > 0">
                <table class="table table-striped table-inverse">
                    <!-- Encabezados de la tabla -->
                    <thead class="thead-inverse">
                        <tr>
                            <th>#</th>
                            <th>Mantenimiento</th>
                            <th>Habitacion</th>
                            <th>Solicitado por</th>
                            <th>Solicitud</th>
                            <th>Asignado</th>
                            <th>Bitacora mant.</th>
                            <th>Supervisor</th>
                            <th>Estado</th>
                            <th>Iniciado</th>
                            <th>Finalizado</th>
                            <th>tiempoTardado</th>
                        </tr>
                    </thead>
                    <!-- Cuerpo de la tabla -->
                    <tbody>
                        <!-- Filas de la tabla -->
                        @foreach ($mantenimientos as $t)
                            <tr>
                                <td scope="row">{{ $loop->iteration }}</td>
                                <td scope="row">{{ $t->tipo_mantenimientos->mantenimiento }}</td>
                                <td scope="row">{{ $t->habitaciones->numero_habitacion }}</td>
                                <td scope="row">{{ $t->creador ? $t->creador->name : 'N/A' }}</td>
                                <td scope="row">{{ $t->observacion}}</td>
                                <td scope="row">{{ $t->asignado ? $t->asignado->name : 'No asignado' }}</td>
                                <td scope="row">{{ $t->bitacora_asignado }}</td>
                                <td scope="row">{{ $t->supervisor ? $t->supervisor->name : 'No supervisado' }}</td>
                                <td scope="row">{{ $t->estado }}</td>
                                <td scope="row">{{ $t->inicio }}</td>
                                <td scope="row">{{ $t->finalizacion }}</td>
                                <td scope="row">{{ $t->transcurrido }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Mostrar mensaje si no hay mantenimientos -->
            <div class="col-12" v-else>
                <p>No hay mantenimientos en el rango de fechas seleccionado.</p>
            </div>

        </div>

    </div>
    <script>
        var app = new Vue({
            el: '#appPanelMantenimiento',
            data: {
             fecha_inicio: "{{ $fecha_inicio ?? '' }}",
            fecha_fin: "{{ $fecha_fin ?? '' }}",
            mantenimientos: @json($mantenimientos)


            },
            methods: {
                isValid: function() {
                    let fi = this.getDate(this.fecha_inicio)
                    let ff = this.getDate(this.fecha_fin);
                    if (fi[0] && ff[0] && fi[1].getTime() <= ff[1].getTime() && this.mantenimientos.length > 0)
                        return false;
                    else return true;
                },
                getDate: function(fecha) {
                    const date = new Date(fecha);
                    return [!isNaN(date.getTime()) && date.toISOString().slice(0, 10) === fecha, date];
                },



            },
            computed: {

            }
        });
    </script>
@endsection
