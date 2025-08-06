@if (isset($table) && isset($data))
    <form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}"
        method="post">

        @csrf
        @if (isset($p) && $p->id > 0)
            <input type="hidden" name="id" value="{{$p->id}}">
        @endif

        <div class="mb-3">
            <x-input-text
                name="titular"
                label="Titular:"
                val="{{ $p->titular ?? '' }}"
            />
        </div>

        

        <div class="mb-3">
            <x-input-number
                name="monto"
                label="Monto:"
                placeholder="0.000"
                min="1"
                max="99999"
                val="{{ $p->monto ?? '' }}"
            />
        </div>

        <div class="mb-3">
            <x-input-select
                name="tipo_cortesias_id"
                label="Tipo cortesia:"
                :data="$data['tipo_cortesia']"
                table="tipo_cortesia"
                showName="tipo"
                val="{{ $p->tipo_cortesias_id ?? '' }}"
            />
        </div>

        <div class="input-group">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </form>
@else
    Este formulario requiere lo atributos :table y :data
@endif
