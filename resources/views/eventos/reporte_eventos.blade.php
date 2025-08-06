@extends('layouts.section_reporte_eventos')

@section('panel_reporte_eventos')
<div id="reporteEventos" class="container">
    <div class="card-body p-2">

                <div class="row mb-3">
                    <div class="col-12 text-uppercase h3">
                        Reporte de eventos
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-12">
                        <form class="form-inline" method="post" action="{{ route('eventos.eventos_reporte_search') }}">
                            @csrf
                            <div class="row">
                                <div class="col-4">
                                    <label for="fecha_inicio" class="form-label">Del</label>
                                    <input type="date" id="fecha_inicio" class="form-control" name="fecha_inicio" id="fecha_inicio" v-model="fecha_inicio" :max="fecha_fin">
                                </div>
                                <div class="col-4">
                                    <label for="fecha_fin" class="form-label">Al</label>
                                    <input type="date" id="fecha_fin" class="form-control" name="fecha_fin" id="fecha_fin" v-model="fecha_fin" :min="fecha_inicio">
                                </div>
                                <div class="col-4 align-self-end">
                                    <button class="btn btn-outline-primary" value="{{ Crypt::encryptString(1) }}" name="accion" type="submit">Buscar</button>
                                    @can('eventos.precios')
                                    <button class="btn btn-outline-success" value="{{ Crypt::encryptString(2) }}" name="accion" type="submit" :disabled="isValid()">Reporte eventos</button>
                                    @endcan
                                    @can('eventos.produccion')
                                    <button class="btn btn-outline-success" value="{{ Crypt::encryptString(3) }}" name="accion" type="submit" :disabled="isValid()">Reporte produccion</button>
                                    @endcan
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 table-responsive" v-if="eventos.length > 0">
                        <table class="table table-striped table-bordered">
                            <thead class="thead-light">

                                <tr>
                                    <th>ID</th>
                                    <th>Fecha</th>
                                    <th>Horario</th>
                                    <th>Cliente | Titular</th>
                                    <th>Sonido</th>
                                    <th>Tipo Montaje</th>
                                    <th>Salones</th>
                                    <th>Encargado</th>
                                </tr>

                            </thead>
                            <tbody>
                                @foreach ($eventos as $e)
                                <tr>

                                    <td>{{ $e->id }}</td>
                                    <td>{{ $e->formateada }}</td>
                                    <td>{{ \Carbon\Carbon::parse($e->inicio)->format('h:i A') }}-{{\Carbon\Carbon::parse($e->finalizacion)->format('h:i A')}}</td>
                                    <td>{{ $e->clientes->nombre ?? $e->titular }}</td>
                                    <td>{{ $e->sonidos->sonido ?? 'Sin sonido' }}</td>
                                    <td>{{ $e->montajes->montaje ?? 'Sin montaje' }}</td>
                                    <td>{{ implode(', ', $e->salones->pluck('salones.salon')->toArray()) }}</td>
                                    <td>{{ $e->encargado ?? 'pendiente asignar encargado' }}</td>



                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-12" v-else>
                        <p class="text-uppercase text-mute">No hay eventos en el rango de fechas seleccionado.</p>
                    </div>
                </div>

        </div>
</div>
@endsection
@section('script')
<script>
    const reporte = new Vue({
        el: '#reporteEventos',
        data: {
            fecha_inicio: "{{ $fecha_inicio ?? '' }}",
            fecha_fin: "{{ $fecha_fin ?? '' }}",
            eventos: @json($eventos)
        },
        mounted() {
            this.setFechaListeners();
        },
        methods: {
            isValid() {
                let fi = this.getDate(this.fecha_inicio);
                let ff = this.getDate(this.fecha_fin);
                return !(fi[0] && ff[0] && fi[1].getTime() <= ff[1].getTime() && this.eventos.length > 0);
            },
            getDate(fecha) {
                const date = new Date(fecha);
                return [!isNaN(date.getTime()) && date.toISOString().slice(0, 10) === fecha, date];
            },
            setFechaListeners() {
                const fecha = document.getElementById("fecha_inicio");
                const final = document.getElementById("fecha_fin");

                fecha.addEventListener("change", this.validarFechas);
                final.addEventListener("change", this.validarFechas);
            },
            validarFechas() {
                const fecha = document.getElementById("inicio");
                const final = document.getElementById("fin");

                const fechaInicio = new Date(fecha.value);
                const fechaFin = new Date(final.value);

                if (fechaInicio > fechaFin) {
                    alert("La fecha de inicio no puede ser mayor a la fecha de finalización.");
                    fecha.value = final.value;
                } else if (fechaFin < fechaInicio) {
                    alert("La fecha de finalización no puede ser menor que la fecha de inicio del evento.");
                    final.value = fecha.value;
                }
            },
        }
    });
</script>
@endsection
