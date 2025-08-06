
    <form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}"
        method="post">

        @csrf
        @if (isset($p) && $p->id > 0)
            <input type="hidden" name="id" value="{{$p->id}}">
        @endif

        <div class="mb-3">
            <x-input-text
                name="tipo_habitacion"
                label="Tipo de habitacion:"
                val="{{ $p->tipo_habitacion ?? '' }}"
            />
        </div>

        <div class="mb-3">
            <x-input-text
                name="codigo"
                label="Codigo:"
                val="{{ $p->codigo ?? '' }}"
            />
        </div>

        <div class="input-group">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </form>

