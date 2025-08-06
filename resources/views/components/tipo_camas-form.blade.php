@if (isset($table) && isset($data))
    <form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}"
        method="post">

        @csrf
        @if (isset($p) && $p->id > 0)
            <input type="hidden" name="id" value="{{$p->id}}">
        @endif

        <div class="mb-3">
            <x-input-text
                name="tipo_cama"
                label="Tipo de Cama:"
                val="{{ $p->tipo_cama ?? '' }}"
            />
        </div>

        <div class="mb-3">
            <x-input-number
                name="largo"
                label="Largo Mts:"
                placeholder="0.00"
                min="0"
                val="{{ $p->largo ?? '' }}"
            />
        </div>

        <div class="mb-3">
            <x-input-number
                name="ancho"
                label="Ancho Mts:"
                placeholder="0.00"
                min="0"
                val="{{ $p->ancho ?? '' }}"
            />
        </div>

        <div class="input-group">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </form>
@else
    Este formulario requiere lo atributos :table y :data
@endif
