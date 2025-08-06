@if (isset($table) && isset($data))
    <form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}"
        method="post">

        @csrf
        @if (isset($p) && $p->id > 0)
            <input type="hidden" name="id" value="{{$p->id}}">
        @endif

        {{-- <div class="mb-3">
            <x-input-number
                name="numero_orden"
                label="Numero de orden: 1-9999"
                placeholder="0000"
                min="1"
                max="9999"
                val="{{ $p->numero_orden ?? '' }}"
            />
        </div>

        <div class="mb-3">
            <x-input-date
                name="fecha"
                label="Fecha:"
                val="{{ $p->fecha ?? '' }}"
            />
        </div>

        <div class="mb-3">
            <x-input-text
                name="titular"
                label="Titular:"
                val="{{ $p->titular ?? '' }}"
            />
        </div>
        
        <div class="mb-3">
            <x-input-text-area name="descripcion" label="Descripcion:" rows="2" val="{{ $p->descripcion ?? '' }}"/>
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
            <x-input-select
                name="cajas_id"
                label="Cajas:"
                :data="$data['cajas']"
                table="cajas"
                showName="caja"
                val="{{ $p->cajas_id ?? '' }}"
            />
        </div> --}}

        <div class="input-group">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </form>
@else
    Este formulario requiere lo atributos :table y :data
@endif
