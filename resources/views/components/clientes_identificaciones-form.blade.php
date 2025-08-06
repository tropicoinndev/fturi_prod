@if (isset($table) && isset($data))
    <form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}"
        method="post">

        @csrf
        @if (isset($p) && $p->id > 0)
            <input type="hidden" name="id" value="{{$p->id}}">
        @endif

        <div class="mb-3">
            <x-input-number
                name="numero"
                label="Numero:"
                placeholder="0"
                min="1"
                max="9999"
                val="{{ $p->numero ?? '' }}"
            />
        </div>

        <div class="mb-3">
            <x-input-select
                name="identificaciones_id"
                label="Identificacion:"
                :data="$data['identificaciones']"
                table="identificaciones"
                showName="identificacion"
                val="{{ $p->identificaciones_id ?? '' }}"
            />
        </div>

        <div class="mb-3">
            <x-input-select
                name="clientes_id"
                label="Cliente:"
                :data="$data['clientes']"
                table="clientes"
                showName="nombre"
                val="{{ $p->clientes_id ?? '' }}"
            />
        </div>

        <div class="input-group">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </form>
@else
    Este formulario requiere lo atributos :table y :data
@endif
