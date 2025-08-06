@if (isset($table) && isset($data))
    <form action="{{ isset($p) && $p->id > 0 ? route($table . '.update') : route($table . '.store') }}" method="post">
        @csrf

        @if (isset($p) && $p->id > 0)
            <input type="hidden" name="id" value="{{ $p->id }}">
        @endif

        <div class="mb-3">
            <x-input-text name="caja" label="Caja:" val="{{ $p->caja ?? '' }}" />
        </div>

        <div class="mb-3">
            <label for="color_fondo" class="form-label">Color fondo:</label>
            <input type="color" class="form-control" id="color_fondo" name="color_fondo"
                value="{{ $p->color_fondo ?? '' }}">
        </div>

        <div class="mb-3">
            <label for="color_texto" class="form-label">Color texto:</label>
            <input type="color" class="form-control" id="color_texto" name="color_texto"
                value="{{ $p->color_texto ?? '' }}">
        </div>

        <div class="mb-3">
            <x-input-text name="codigo_punto_venta" label="Codigo de punto de venta" placeholder="TRO-19900"
                min="1" max="9999" val="{{ $p->codigo_punto_venta ?? '' }}" />
        </div>

        <div class="mb-3">
            <x-input-select name="sucursales_id" label="Sucursal:" :data="$data['sucursales']" table="sucursales"
                showName="sucursal" val="{{ $p->sucursales_id ?? '' }}" />
        </div>

        <div class="mb-3">
            <x-input-text name="ip" label="IP de impresión:" val="{{ $p->ip ?? '' }}" />
        </div>

        <div class="input-group">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </form>
@else
    Este formulario requiere lo atributos :table y :data
@endif
