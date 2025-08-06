@if (isset($table) && isset($data))
    <form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}"
        method="post">

        @csrf
        @if (isset($p) && $p->id > 0)
            <input type="hidden" name="id" value="{{$p->id}}">
        @endif

        <div class="mb-3">
            <x-input-text
                name="observacion"
                label="Observacion:"
                val="{{ $p->observacion ?? '' }}"
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
            <x-input-number
                name="origen"
                label="Origen:"
                placeholder="0"
                min="1"
                max="99999"
                val="{{ $p->origen ?? '' }}"
            />
        </div>
        <div class="mb-3">
            <x-input-number
                name="origen_id"
                label="Origen id:"
                placeholder="0"
                min="1"
                max="99999"
                val="{{ $p->origen_id ?? '' }}"
            />
        </div>

        <div class="input-group">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </form>
@else
    Este formulario requiere lo atributos :table y :data
@endif
