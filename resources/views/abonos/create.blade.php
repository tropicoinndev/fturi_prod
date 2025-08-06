@extends('layouts.cajas')

@section('panel_caja')
    <div id="appAbonos">
        <div class="row">
            <div class="col-12">
                <h4 class="text-uppercase">Nuevo ingreso a caja</h4>
            </div>
            <div class="col-12 mb-3">
                <small>Busque un cliente para generar el ingreso a caja.</small>
            </div>
        </div>
        <form action="{{ route('abonos.store') }}" method="post">
            @csrf
            <input type="hidden" name="clientes_id" :value="cliente ? cliente.id : null">
            <div class="mb-3">
                <label for="forma_pagos">Formas de pagos</label>
                <select class="form-select" id="forma_pagos" name="forma_pagos" aria-label="Default select example"
                    v-model="forma_pagos">
                    <option selected value="">Seleccione una forma de pago</option>
                    @foreach ($forma_pagos as $f)
                        <option value="{{ Crypt::encryptString($f->id) }}">{{ $f->forma }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <clientes url="{{ route('clientes.api_search_list') }}" @cliente="setCliente"></clientes>
            </div>

            <div class="mt-3">
                <button class="btn btn-primary" type="submit"
                    :disabled="cliente == null || forma_pagos == '' || forma_pagos == null">Guardar</button>
                @if (Route::has('abonos.index'))
                    <a class="btn btn-light mx-2" href="{{ route('abonos.index') }}">Volver</a>
                @endif
            </div>
        </form>
    </div>
@endsection
@section('script-caja')
    <script type="module">
        var abonos = appVue({
            data() {
                return {
                    cliente: null,
                    forma_pagos: "",
                }
            },
            methods: {
                setCliente: function(c) {
                    this.cliente = c;
                }
            },
        });
        abonos.component('clientes', component.clientes);
        abonos.mount("#appAbonos");
    </script>
@endsection
