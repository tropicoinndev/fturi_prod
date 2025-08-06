@if (isset($identificaciones) || isset($table))
    <form action="{{ isset($p) && $p->id > 0 ? route($table . '.update') : route($table . '.store') }}" method="post" id="form-anticipo">
        @csrf
        @if (isset($p) && $p->id > 0)
            <input type="hidden" name="id" value="{{ $p->id }}">
        @endif
        <div class="mb-3">
            <x-input-select name="forma_pagos_id" :data="$forma" showName="forma" label="Seleccione una forma de pago"
                val="{{ $p->forma_pagos_id ?? '' }}" required='required' />
        </div>
        <div class="mb-3">
            <x-input-date name="fecha_aplicacion" label="Fecha de aplicacion del anticipo"
                val="{{ $p->fecha_aplicacion ?? '' }}" :min="date('Y-m-d')" required="required" />
        </div>
        <div class="mb-3">
            <x-input-text-area name="concepto" label="Concepto del anticipo"
                val="{{ old('concepto') ?? ($concepto ?? ($p->concepto ?? '')) }}" required="required" />
        </div>

        <div class="mb-3">
            <x-input-number name="monto" label="Monto del anticipo:" val="{{ $p->monto ?? '' }}" :min="1"
                required="required" />
        </div>
        @if (isset($cliente))
            <input type="hidden" name="clientes_id" value="{{ $cliente }}">
        @else
            <div class="mb-3">
                <x-search label="Buscar cliente:" showname="cliente" :val="isset($p) ? $p->clientes : ''" :route="route('clientes.api_search_list')"
                    param="busqueda" id="clientes_id" :required="true" />
            </div>
        @endif
        @if (isset($tipoReservacion) && isset($reservacionId))
            <input type="hidden" name="tipo_reservacion" value="{{ \Crypt::encryptString($tipoReservacion) }}">
            <input type="hidden" name="reservacion_id" value="{{ \Crypt::encryptString($reservacionId) }}">
        @endif
        <div class="input-group">
            <button class="btn btn-primary" type="submit" id="btn-guardar">Guardar</button>
        </div>
    </form>

    <script>
        document.getElementById('form-anticipo').addEventListener('submit',function(e){
            //Deshabilitar el botón de envío al hacer clic
            document.getElementById('btn-guardar').disabled = true;

            //Cambiar el texto del botón para mostrar que se está procesando
            document.getElementById('btn-guardar').innerText = 'Guardando...';
        });
    </script>

@else
    Los atributos :identificaciones y :table son requeridos
@endif
