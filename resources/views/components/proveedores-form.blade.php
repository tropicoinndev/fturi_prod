@if (isset($table) && isset($data))
    <form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}" method="post">
        @csrf

        @if (isset($p) && $p->id > 0)
            <input type="hidden" name="id" value="{{$p->id}}">
        @endif

        <div class="mb-3">
            <x-input-text
                name="proveedor"
                label="Proveedor:"
                val="{{ $p->proveedor ?? '' }}"
                required maxlength="100"/>
        </div>

        <div class="mb-3">
            <x-input-text
                name="nrc"
                label="NRC: 9 dígitos sin guiones ni espacios"
                placeholder="00000000"
                val="{{ $p->nrc ?? '' }}"
                maxlength="8"/>
        </div>

        <div class="mb-3">
            <x-input-text
                name="nit"
                label="NIT: 14 dígitos sin guiones ni espacios"
                placeholder="00000000000000"
                val="{{ $p->nit ?? '' }}"
                maxlength="14"/>
        </div>

        <div class="mb-3">
            <x-input-text
                name="dui"
                label="DUI: 9 dígitos sin guiones ni espacios"
                placeholder="00000000-0"
                val="{{ $p->dui ?? '' }}"
                maxlength="9"/>
        </div>

        <div class="mb-3">
            <x-input-text-area name="direccion" label="Dirección:" rows="2" val="{{ $p->direccion ?? '' }}" required maxlength="255"/>
        </div>

        <div class="mb-3">
            <x-input-select
                name="municipios_id"
                label="Municipios:"
                :data="$data['municipios']"
                table="municipios"
                showName="municipio"
                val="{{$p->municipios_id ?? ''}}"
            />
        </div>

        <div class="mb-3">
            <x-input-text
                name="contactos"
                label="Contactos:"
                val="{{ $p->contactos ?? '' }}"
                required maxlength="50"/>
        </div>

        <div class="mb-3">
            <x-input-text
                name="informacion"
                label="Información:"
                val="{{ $p->informacion ?? '' }}"
                required maxlength="50"/>
        </div>

        <div class="input-group">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>

    </form>
@else
    Este formulario requiere lo atributos :table y :data
@endif
