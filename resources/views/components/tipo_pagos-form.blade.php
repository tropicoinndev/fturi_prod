<form action="{{ isset($p) && $p->id > 0 ? route($table . '.update') : route($table . '.store') }}" method="post" id="appTipoPagos">
    @csrf

    @if (isset($p) && $p->id > 0)
        <input type="hidden" name="id" value="{{ $p->id }}">
    @endif

    <div class="mb-3">
        <x-input-text name="tipo_pago" label="Agregar nuevo tipo de pago:" val="{{ old('tipo_pago') ?? $p->tipo_pago ?? ''}}" required maxlength="50"/>
    </div>

    <div id="appTipoPagos">
        <div class="mb-3">
            <label for="tipoPago" class="form-label">Tipo pago definido:</label>
            <select class="form-select" name="token" id="token" v-model="token" required>
                <option selected disabled value="">--Seleccione--</option>
                <option v-for="option in arrayTipoPagos" :value="option.value">
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
        el: '#appTipoPagos',
        data: {
            arrayTipoPagos: @json($data['tipoPagoTokens']) || [],
            token: '',
        },
        methods: {

        },
    });
</script>
