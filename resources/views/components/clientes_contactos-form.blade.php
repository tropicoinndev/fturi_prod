@if (isset($table) && isset($data))
    <form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}"
        method="post">

        @csrf
        @if (isset($p) && $p->id > 0)
            <input type="hidden" name="id" value="{{$p->id}}">
        @endif

        <div class="mb-3">
            <x-input-text
                name="valor"
                label="Valor:"
                val="{{ $p->valor ?? '' }}"
            />
        </div>

        <div class="mb-3">
            <x-input-select
                name="contactos_id"
                label="Contactos:"
                :data="$data['contactos']"
                table="contactos"
                showName="contacto"
                val="{{ $p->contactos_id ?? '' }}"
            />
        </div>

        <div class="mb-3">
            <x-input-select
                name="clientes_id"
                label="Clientes:"
                :data="$data['clientes']"
                table="clientes"
                showName="nombre"
                val="{{ $p->clientes_id ?? '' }}"
            />
        </div>

        <div class="mb-3">
            <x-input-text
                name="observaciones"
                label="Observaciones:"
                val="{{ $p->observaciones ?? '' }}"
            />
        </div>

        <div class="input-group">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </form>
@else
    Este formulario requiere lo atributos :table y :giros
@endif
