@if (isset($table) && isset($data))
    <form action="{{ isset($p) && $p->id > 0 ? route($table . '.update') : route($table . '.store') }}" method="post">

        @csrf
        @if (isset($p) && $p->id > 0)
            <input type="hidden" name="id" value="{{ $p->id }}">
        @endif
        <div class="mb-3">
            <x-input-text name="tarifa" label="Tarifa:" val="{{ $p->tarifa ?? '' }}" />
        </div>
        <div class="mb-3">
            <x-input-text name="precio" label="Precio:" val="{{ $p->precio ?? '' }}" />
        </div>
        <div class="mb-3">
            <x-input-number min="1" step="1" name="numero_dias" label="Numero de dias:"
                val="{{ $p->numero_dias ?? '1' }}" />
        </div>
        <div class="mb-3">
            <x-input-select name="temporadas_id" label="Temporada:" :data="$data['temporadas']" table="temporadas"
                showName="temporada" val="{{ $p->temporadas_id ?? '3' }}" />
        </div>
        <div class="input-group">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </form>
@else
    Este formulario requiere lo atributos :table y :tarifas
@endif
