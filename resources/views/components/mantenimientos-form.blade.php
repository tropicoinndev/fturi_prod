@if (isset($table) && $habitaciones)
<div id="mantenimientos">
    <form action="{{ route('mantenimientos.store') }}" method="post">

        @csrf
        @if (isset($p) && $p->id > 0)
        <input type="hidden" name="id" value="{{ $p->id }}">
        @endif
        <div>
            <input type="hidden" name="habitaciones_id" value="{{ $habitaciones }}">
            <div class="mb-3">
                <div class="mb-3">
                    <label for="fecha" class="form-label">Fecha: </label>
                    <input type="date" class="form-control" id="fecha" name="fecha" v-model="fecha">
                </div>
                <div class="mb-3">
                    <label for="asignacion" class="form-label">Asignacion: </label>
                    <input type="date" class="form-control" id="asignacion" name="asignacion" v-model="asignacion">
                </div>
                <div class="mb-3">
                    <label for="finalizacion" class="form-label">Finalizacion: </label>0
                    <input type="date" class="form-control" id="finalizacion" name="finalizacion"
                        v-model="finalizacion">
                </div>

                <div class="mb-3">
                    <label for="estado" class="form-label">Estado: </label>
                    <input type="text" class="form-control" placeholder="asigne el estado del mantenimiento" id="estado"
                        name="estado" v-model="estado">
                </div>
                <div class="mb-3">
                    <x-input-select name="asignado_users_id" label="Usuario asignado:" :data="$data['users']"
                        table="users" showName="name" val="{{ $p[0]->users_id ?? '' }}"
                        v-model="selectedAsignadoUserId" />
                </div>
                <div class="mb-3">
                    <x-input-select name="supervisor_users_id" label="Usuario supervisor:" :data="$data['users']"
                        table="users" showName="name" val="{{ $p[0]->users_id ?? '' }}"
                        v-model="selectedSupervisorUserId" />
                </div>
                <div class="mb-3">
                    <label for="confirmacion_asignacion" class="form-label">Confirmacion de asignacion: </label>
                    <input type="date" class="form-control" id="confirmacion_asignacion" name="confirmacion_asignacion"
                        v-model="confirmacion_asignacion">
                </div>

                <div class="mb-3">
                    <x-input-select name="tipo_mantenimientos_id" label="Tipo de mantenimientos:"
                        :data="$data['tipo_mantenimientos']" table="tipo_mantenimientos" showName="mantenimiento"
                        val="{{ $p[0]->tipo_mantenimientos_id ?? '' }}" />
                </div>
                <div class="mb-3">
                    <x-input-select name="habitaciones_id" label="Numero de habitacion:" :data="$data['habitaciones']"
                        table="habitaciones" showName="numero_habitacion" val="{{ $p[0]->habitaciones_id ?? '' }}" />
                </div>
            </div>
            <div class="input-group">
                <button class="btn btn-primary" type="submit">Guardar</button>
            </div>
        </div>
    </form>
</div>
@else
Este formulario requiere lo atributos :table y :habitaciones
@endif
<script>
document.addEventListener('DOMContentLoaded', function() {
    var mantenimientos = new Vue({
        el: '#mantenimientos',
        data: {
            mantenimientos: @json($p),
            tipoFiltrado: 0,
            txtBusqueda: '',
            fecha: null,
            finalizacion: null,
            asignacion: null,
            confirmacion_asignacion: null,
            estado: null,
            selectedAsignadoUserId: null,
            selectedSupervisorUserId: null,
        },
        methods: {

        },
        mounted() {

        },
        computed: {

        }
    });
});
</script>