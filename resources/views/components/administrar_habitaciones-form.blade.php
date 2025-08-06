@if (isset($table) && isset($data))
    <form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}" method="post" id="appAdministrarHabitaciones">

        @csrf
        @if (isset($p) && $p->id > 0)
            <input type="hidden" name="id" value="{{$p->id}}">
        @endif

        <div class="mb-3">
            <x-input-text
                name="numero_habitacion"
                label="Nº de habitacion:"
                placeholder="0"
                val="{{ $p->numero_habitacion ?? '' }}"
            />
        </div>

        <div class="mb-3">
            <x-input-select
                name="tipo_habitaciones_id"
                label="Tipo de habitacion:"
                :data="$data['tipoHabitaciones']"
                table="tipo_habitaciones"
                showName="tipo_habitacion"
                val="{{ $p->tipo_habitaciones_id ?? '' }}"
            />
        </div>

        <div class="mb-3">
            <x-input-select
                name="forma_habitaciones_id"
                label="Forma de habitacion:"
                :data="$data['formaHabitaciones']"
                table="forma_habitaciones"
                showName="forma_habitacion"
                val="{{ $p->forma_habitaciones_id ?? '' }}"
            />
        </div>

        <div class="mb-3">
            <x-input-select
                name="estado_habitaciones_id"
                label="Estado de habitacion:"
                :data="$data['estadoHabitaciones']"
                table="estado_habitaciones"
                showName="estado_habitacion"
                val="{{ $p->estado_habitaciones_id ?? '' }}"
            />
        </div>

        <div class="mb-3">
            <x-input-select
                name="ubicacion_habitaciones_id"
                label="Ubicacion de habitacion:"
                :data="$data['ubicacionHabitaciones']"
                table="ubicacion_habitaciones"
                showName="ubicacion_habitacion"
                val="{{ $p->ubicacion_habitaciones_id ?? '' }}"
            />
        </div>
        <div class="mb-3">
            <x-input-select
                name="sucursales_id"
                label="Sucursal para la habitacion:"
                :data="$data['sucursales']"
                table="sucursales"
                showName="sucursal"
                val="{{ $p->sucursales_id ?? '' }}"
            />
        </div>

        <div class="mb-3">
            <x-input-text
                name="telefono"
                label="Telefono:"
                placeholder="0000-0000"
                val="{{ $p->telefono ?? '' }}"
            />
        </div>

        <div class="mb-3">
            <x-input-text
                name="extension"
                label="Extension:"
                placeholder="0000"
                val="{{ $p->extension ?? '' }}"
            />
        </div>

        <div class="mb-3">
            <x-input-text-area name="descripcion" label="Descripcion:" val="{{  $p->descripcion ?? ''}}"/>
        </div>

        <div class="input-group">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </form>
@else
    Este formulario requiere lo atributos :table y :data
@endif

<script>
    var app = new Vue({
        el: '#appAdministrarHabitaciones',
        data: {
            
        },
        mounted(){

        },
        computed: {

        },
        methods: {
            
        }
    })
</script>

