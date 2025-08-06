<form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}" method="post" id="appFormaPagos">

    @csrf
    @if (isset($p) && $p->id > 0)
        <input type="hidden" name="id" value="{{$p->id}}">
    @endif

    <div class="mb-3">
        <x-input-text name="forma" label="Forma de pago:" val="{{ $p->forma ?? '' }}"/>
    </div>

        <div id="appFormaPagos">
        <div class="mb-3">
            <label for="formaPago" class="form-label">Tipo pago definido:</label>
            <select class="form-select" name="token" id="token" v-model="token">
                <option selected disabled value="">--Seleccione--</option>
                <option v-for="option in forma_pagos" :value="option.token">
                    @{{ option.forma }}
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
        el: '#appFormaPagos',
        data: {
        forma_pagos:@json($data['formaPagoTokens']) || [],
        forma_pago: '',
        token: ''
        },
    })
</script>
