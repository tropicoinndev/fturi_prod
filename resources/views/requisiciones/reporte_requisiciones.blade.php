@extends('layouts.bodegas')

@section('panel_bodega')
<div id="appPanelRequisiciones">
    <div class="row mb-4">
        <div class="col-12 text-uppercase h3">
            Reporte de requisiciones
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12">
            <form class="form-inline" method="post" action="{{ route('requisiciones.requisiciones_reporte_search') }}">

                @csrf
                <div class="row">
                    <div class="col-2">
                        <label for="">Del</label>
                        <input type="date" class="form-control" name="fecha_inicio" v-model="fecha_inicio" :max="fecha_fin">
                    </div>
                    <div class="col-2">
                        <label for="">Al</label>
                        <input type="date" class="form-control" name="fecha_fin" v-model="fecha_fin" :min="fecha_inicio">
                    </div>
                    <div class="col-3">
                    <label for="">Filtrar por bodegas</label>
                    <!-- Agrega un campo para seleccionar la bodega -->
                    <select class="form-control" name="bodega_users_id" v-model="bodegalogueado" @change="actualizarBodegaLogueada">
                        <option slected disabled>Seleccionar Bodega</option>
                        @foreach ($bodega as $b)
                            <option value="{{ $b->relacionBodegas->id }}">{{ $b->relacionBodegas->bodega }}</option>
                        @endforeach
                    </select>
                </div>
                    <div class="col-3 align-self-end">

                        <button class="btn btn-outline-primary" value="{{ Crypt::encryptString(1) }}" name="accion" type="submit" :disabled="isValid()">Buscar por bodegas</button>
                        <button class="btn btn-outline-success" value="{{ Crypt::encryptString(2) }}" name="accion" type="submit":disabled="isValid()" {{ collect($reporte)->isEmpty() ? 'disabled' : '' }}>Generar reporte</button>

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
                        <th>Fecha</th>
                        <th>Solicitud</th>
                        <th>Bodega de salida</th>
                        <th>Bodega de entrada</th>
                        <th>Autorizada por</th>
                        <th>Creada por</th>
                        <th>Estado de la requisición</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reporte as $requisicion)
                        <tr>
                            <td scope="row">{{ $requisicion['requisicion']->fecha }}</td>
                            <td scope="row">{{ $requisicion['requisicion']->solicitud }}</td>
                            <td scope="row">{{ $requisicion['requisicion']->bodega_salida_id ? $requisicion['requisicion']->relacionBodegasSalida->bodega : 'N/A' }}</td>
                            <td scope="row">{{ $requisicion['requisicion']->bodega_entrada_id ? $requisicion['requisicion']->relacionBodegasEntrada->bodega : 'N/A' }}</td>
                            <td scope="row">{{ $requisicion['requisicion']->user_autorizacion_id ? $requisicion['requisicion']->relacionUserAutorizacion->name : 'No se autorizado' }}</td>
                            <td scope="row">{{ $requisicion['requisicion']->user_creacion_id ? $requisicion['requisicion']->relacionUsuarios->name : 'N/a'}}</td>
                            <td scope="row">{{ $requisicion['requisicion']->estado == 1 ? 'Activa' : ($requisicion['requisicion']->estado == 2 ? 'Completada' : ($requisicion['requisicion']->estado == 3 ? 'Autorizada' : 'Anulada')) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-uppercase">No hay requisiciones en la bodega seleccionada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    var app = new Vue({
        el: '#appPanelRequisiciones'
        , data: {
             bodegabyFiltro: "{{ $bodegabyFiltro ?? '' }}"
             ,bodega: "{{$bodega}}"
             ,bodegalogueado: "{{$bodegalogueado}}"
             ,fecha_fin: "{{$fecha_fin}}"
             ,fecha_inicio: "{{$fecha_inicio}}"

        }
        , mounted() {
                    const bodega = localStorage.getItem('bodegaLogueada');
                    if (bodega) {
                        this.bodegalogueado = bodega;
                    }
                }
        ,methods: {
            actualizarBodegaLogueada() {
            // Validar que bodegalogueado es un valor válido antes de almacenarlo en localStorage
            if (this.bodegalogueado) {
                localStorage.setItem('bodegaLogueada', this.bodegalogueado); // Almacenar en localStorage
            }
        },
            isValid: function() {
                let fi = this.getDate(this.fecha_inicio)
                let ff = this.getDate(this.fecha_fin);
                if (fi[0] && ff[0] && fi[1].getTime() <= ff[1].getTime())
                    return false;
                else return true;
            }
            , getDate: function(fecha) {
                const date = new Date(fecha);
                return [!isNaN(date.getTime()) && date.toISOString().slice(0, 10) === fecha, date];
            }
        }
        , computed: {

        }
    });

</script>
@endsection
