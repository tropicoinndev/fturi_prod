<form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}" method="post">
    @csrf

    @if (isset($p) && $p->id > 0)
        <input type="hidden" name="id" value="{{$p->id}}">
    @endif

    <div class="mb-3">
        <x-input-text name="descuento" label="Descuento:" val="{{ $p->descuento ?? '' }}" required maxlength="50"/>
    </div>

    <div class="mb-3">
        <x-input-number
            name="porcentaje"
            label="Porcentaje: 1-100"
            placeholder="0"
            min="1"
            max="100"
            val="{{ $p->porcentaje ?? '' }}"
            required
            maxDigitos="3"
        />
    </div>

    <div class="mb-3">
        <x-input-number
            name="decimales"
            label="Decimales: 1-4"
            placeholder="0.00"
            min="0"
            max="100"
            val="{{ $p->decimales ?? '' }}"
            required
            maxDigitos="4"
        />
    </div>

    <div class="input-group">
        <button class="btn btn-primary" type="submit">Guardar</button>
    </div>
</form>
