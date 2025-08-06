@if (isset($table) && isset($data))
    <form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}" method="post" id="appAdministrarHabitaciones">

        @csrf
        @if (isset($p) && $p->id > 0)
            <input type="hidden" name="id" value="{{$p->id}}">
        @endif


        <div class="mb-3">
            <x-input-select
                name="tipo_mantenimientos_id"
                label="Tipo de mantenimientos:"
                :data="$data['tipo_mantenimientos']"
                table="tipo_mantenimientos"
                showName="mantenimiento"
                val="{{ $p->tipo_mantenimientos_id ?? '' }}"
            />
        </div>

        <div class="mb-3">
            <x-input-select
                name="habitaciones_id"
                label="Habitaciones:"
                :data="$data['habitaciones']"
                table="habitaciones"
                showName="numero_habitacion"
                val="{{ $p->habitaciones_id ?? '' }}"
            />
        </div>

    

        


        <div class="mb-3">
            <x-input-text-area name="observacion" label="Observacion:" val="{{  $p->observacion ?? ''}}"/>
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