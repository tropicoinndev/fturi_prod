@if (isset($data) || isset($table))
<form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}" method="post" id="appTipoComprobantes">
    @csrf
    @if (isset($p) && $p->id > 0)
        <input type="hidden" name="id" value="{{$p->id}}">
    @endif

    <div class="mb-3">
        <input type="hidden" class="form-control" name="tipo" v-model="tipo">
    </div>

    <div class="mb-3">
        <x-input-number
            name="codigo"
            label="Codigo: 1-9999"
            placeholder="0000"
            min="1"
            max="9999"
            val="{{ $p->codigo ?? '' }}"
        />
    </div>

    <div class="mb-3">
        <label for="tipoComprobantes" class="form-label">Tipo comprobantes:</label>
        <select class="form-select" name="tipoComprobantes" id="tipoComprobantes" v-model="tipoComprobanteSelected" @change="setTipoComprobante">
            <option disabled selected>--Seleccione--</option>
            <option v-for="tipoComprobante in arrayTipoComprobantes" :value="tipoComprobante.id" :key="tipoComprobante.id">
                @{{ tipoComprobante.tipo }}
            </option>
        </select>
    </div>

    <div class="mb-3">
        <input type="hidden" class="form-control" name="token" v-model="token">
    </div>

    <div class="input-group">
        <button class="btn btn-primary" type="submit">Guardar</button>
    </div>
</form>
<script type="module">
 const app = window.appVue({
    el: '#appTipoComprobantes',
    data() {
        return {
            arrayTipoComprobantes: @json($data['tipoComprobanteTokens']) || [],
            tipoComprobanteSelected: '{{isset($p->tipo) ?? 0}}',
            tipoComprobante: [],
             tipo: '{{ old( $p->tipo ?? '') }}',
            token: '{{ old( $p->token ?? '') }}',
        };
    },
    mounted() {
        this.setTipoComprobante();
    },
    methods: {
        setTipoComprobante() {
            const tipoComprobante = this.arrayTipoComprobantes.find(t => t.id == this.tipoComprobanteSelected) || {};
            this.tipo = tipoComprobante.tipo || null;
            this.token = tipoComprobante.token || null;
        }
    }
});
app.mount("#appTipoComprobantes");
</script>
@else
<p>Los atributos :data y :table son requeridos</p>
@endif




