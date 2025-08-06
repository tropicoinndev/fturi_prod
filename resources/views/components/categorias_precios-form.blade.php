<form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}" method="post">
    @csrf

    @if(isset($p) && $p->id > 0)
        <input type="hidden" name="id" value="{{$p->id}}">
    @endif

    <div class="mb-3">
        <x-input-text name="categoria" label="Categoría:" val="{{ $p->categoria ?? '' }}" required maxlength="50"/>
    </div>

    <div class="mb-3">
        <label for="token" class="form-label">Token:</label>
        <select class="form-select" aria-label="Default select example" id="token" name="token" required>
            <option selected value="{{ $p->token ?? '' }}">{{ ($p->token ?? '') ? 'seleccionar' : 'seleccionar' }}</option>
            <option value="1101" {{ isset($p) && $p->token == '1101' ? 'selected' : '' }}>Productos bajo inventario</option>
            <option value="1102" {{ isset($p) && $p->token == '1102' ? 'selected' : '' }}>Bebidas preparadas</option>
            <option value="1103" {{ isset($p) && $p->token == '1103' ? 'selected' : '' }}>Platos</option>
            <option value="1104" {{ isset($p) && $p->token == '1104' ? 'selected' : '' }}>Combos-promocion</option>
            <option value="1105" {{ isset($p) && $p->token == '1105' ? 'selected' : '' }}>Productos sin existencia</option>
        </select>
        {{-- <small class="text-danger"><span class="mdi mdi-alert"></span> {{ $message }}</small> --}}
    </div>

    <div class="mb-3">
        <x-input-text name="descripcion" label="Descripción:" val="{{ $p->descripcion ?? '' }}" required maxlength="100"/>
    </div>

    <div class="mb-3">
        <x-input-select
            name="rubros_id"
            label="Rubros:"
            :data="$data['rubros']"
            table="rubros"
            showName="rubro"
            val="{{ $p->rubros_id ?? '' }}"
            required
        />
    </div>

    <div class="input-group">
        <button class="btn btn-primary" type="submit">Guardar</button>
    </div>
</form>
