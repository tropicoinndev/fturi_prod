<form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}" method="post" id="appTipoServicios">

    @csrf
    @if (isset($p) && $p->id > 0)
        <input type="hidden" name="id" value="{{$p->id}}">
    @endif

    <div class="mb-3">
        {{-- <x-input-text name="tipo_servicio" label="Tipo de servicio:" val="{{ $p->tipo_servicio ?? '' }}"/> --}}
        <input type="hidden" class="form-control" name="tipo_servicio" v-model="tipo_servicio">
    </div>

    <div class="mb-3">
        <label for="tipoServicio" class="form-label">Tipo servicio:</label>
        <select class="form-select form-select" name="tipoServicio" id="tipoServicio" v-model="tipoServicioSelected" @change="setTipoServicio">
                <option selected disabled value="">--Seleccione--</option>
                <option v-for="tipoServicios in arrayTipoServicios" :value="tipoServicios.id">@{{ tipoServicios.tipo_servicio }}</option>
        </select>
    </div>
    
    <div class="mb-3">
        {{-- <x-input-number
            name="token"
            label="Token: 1-99999"
            placeholder="00000"
            min="1"
            max="99999"
            val="{{ $p->token ?? '' }}"
        /> --}}
        <input type="hidden" class="form-control" name="token" v-model="token">
    </div>

    <div class="input-group">
        <button class="btn btn-primary" type="submit">Guardar</button>
    </div>
</form>

<script>
    var app = new Vue({
        el: '#appTipoServicios',
        data: {
            arrayTipoServicios: @json($data['tipoServicioTokens']) || [],
            tipoServicioSelected: 0,
            tipoServicioSelected: {{ $p->tipoServicio->id ?? 0 }},
            tipoServicio: [],

            //Inputs a enviar a la BD.
            tipo_servicio: null,
            token: null
        },
        mounted(){

        },
        computed: {

        },
        methods: {
            setTipoServicio(){
                 this.tipoServicio = this.arrayTipoServicios.filter(t => t.id == this.tipoServicioSelected);
            console.log(`Tipo servicio: ${this.tipoServicio[0].tipo_servicio} - Token: ${this.tipoServicio[0].token}`);
            
            this.tipo_servicio = this.tipoServicio[0].tipo_servicio;
            this.token = this.tipoServicio[0].token;
            }
        }
    });
</script>
