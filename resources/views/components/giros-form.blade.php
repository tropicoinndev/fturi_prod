<form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}" method="post">
    @csrf

    @if (isset($p) && $p->id > 0)
        <input type="hidden" name="id" value="{{$p->id}}">
    @endif

    <div class="mb-3">
        <x-input-text name="giro" label="Giro:" val="{{ $p->giro ?? '' }}" required maxlength="150"/>
    </div>

    <div class="mb-3">
        <x-input-number
            name="codigo"
            label="Código: 1-99999"
            placeholder="00000"
            min="1"
            max="99999"
            val="{{ $p->codigo ?? '' }}"
            required
            maxDigitos="6"/>
    </div>

    <div class="mb-3">
        <x-input-text-area name="descripcion" label="Descripción:" rows="3" val="{{ $p->descripcion ?? '' }}" required maxlength="150"/>
    </div>

    <div class="input-group">
        <button class="btn btn-primary" type="submit">Guardar</button>
    </div>
</form>
