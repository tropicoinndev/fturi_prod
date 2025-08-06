@if (isset($table) && isset($data))
    <form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}"
        method="post">

        @csrf
        @if (isset($p) && $p->id > 0)
            <input type="hidden" name="id" value="{{$p->id}}">
        @endif

        <div class="mb-3">
            <x-input-text
                name="caja"
                label="Caja:"
                val="{{ $p->caja ?? '' }}"
            />
        </div>

        {{-- <div class="mb-3">
            <x-input-number
                name="codigo"
                label="Codigo: 1-9999"
                placeholder="0000"
                min="1"
                max="9999"
                val="{{ $p->codigo ?? '' }}"
            />
        </div> --}}

       {{--  <div class="mb-3">
            <x-input-select
                name="sucursales_id"
                label="Sucursal:"
                :data="$data['sucursales']"
                table="sucursales"
                showName="sucursal"
                val="{{ $p->sucursales_id ?? '' }}"
            />
        </div> --}}

        <div class="input-group">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </form>
@else
    Este formulario requiere lo atributos :table y :data
@endif
