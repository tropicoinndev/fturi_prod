@if (isset($table) && isset($data))
<form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}" method="post">

    @csrf
    @if (isset($p) && $p->id > 0)
    <input type="hidden" name="id" value="{{$p->id}}">
    @endif

    <div class="mb-3">
        <x-input-text name="estado_habitacion" label="Estado de habitacion:" val="{{ $p->estado_habitacion ?? '' }}" />
    </div>
        <div id="appTokenEstados">
        <div class="mb-3">
            <label for="tipo_token" class="form-label">Tipo de token definido:</label>
            <select class="form-select" name="token" id="token" v-model="token">
                <option selected disabled value="">--Seleccione--</option>
                <option v-for="option in arrayTipoTokens" :value="option.value">
                    @{{ option.text }}
                </option>
            </select>
        </div>
    </div>
    <div class="input-group">
        <button class="btn btn-primary" type="submit">Guardar</button>
    </div>
</form>
<script>
    var app = new Vue({
        el: '#appTokenEstados',
        data: {
            arrayTipoTokens: @json($data['tokenEstados']) || [],
            tipo_pago: '',
            token: '',
            token_user: ''
        },
        methods: {

        },
    });
</script>
@else
    Este formulario requiere lo atributos :table y :data
@endif