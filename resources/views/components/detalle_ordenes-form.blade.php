@if (isset($table) && isset($data))
    <form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}"
        method="post">

        @csrf
        @if (isset($p) && $p->id > 0)
            <input type="hidden" name="id" value="{{$p->id}}">
        @endif

        <div class="mb-3">
            <x-input-number
                name="cantidad"
                label="Cantidad:"
                placeholder="0"
                min="1"
                max="9999"
                val="{{ $p->cantidad ?? '' }}"
            />
        </div>

        <div class="mb-3">
            <x-input-select
                name="servicios_id"
                label="Servicios:"
                :data="$data['servicios']"
                table="servicios"
                showName="servicio"
                val="{{ $p->servicios_id ?? '' }}"
            />
        </div>

        <div class="mb-3">
            <x-input-select
                name="ordenes_id"
                label="Ordenes:"
                :data="$data['ordenes']"
                table="ordenes"
                showName="numero_orden"
                val="{{ $p->ordenes_id ?? '' }}"
            />
        </div>

        <div class="input-group">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </form>
@else
    Este formulario requiere lo atributos :table y :giros
@endif
