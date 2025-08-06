@if (isset($table) && isset($data))
    <form action="{{ isset($p) && $p->id > 0 ? route($table . '.update') : route($table . '.store') }}" method="post">
        @csrf

        @if (isset($p) && $p->id > 0)
            <input type="hidden" name="id" value="{{ $p->id }}">
        @endif

        <div class="mb-3">
            <x-input-text name="bodega" label="Bodega:" val="{{ old('bodega') ?? $p->bodega ?? ''}}" required maxlength="50"/>

            <input type="hidden" class="form-control" name="tipo" v-model="tipo">
        </div>

        <div class="mb-3">
            <label for="color_fondo" class="form-label">Color fondo:</label>
            <input type="color" class="form-control" id="color_fondo" name="color_fondo" value="{{ $p->color_fondo ?? '' }}">
        </div>

        <div class="mb-3">
            <label for="color_texto" class="form-label">Color texto:</label>
            <input type="color" class="form-control" id="color_texto" name="color_texto" value="{{ $p->color_texto ?? '' }}">
        </div>
        
        <div class="mb-3">
            <x-input-select name="tipo" label="Tipo de bodegas:" :data="$data['tipos']" table="bodega" showName="tipo" val="{{ $p->tipo ?? '' }}"/>
        </div>

        <div class="input-group">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </form>
@else
    Este formulario requiere lo atributos :table y :data
@endif


