<form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}" method="post">
    @csrf

    @if (isset($p) && $p->id > 0)
        <input type="hidden" name="id" value="{{$p->cid}}">
    @endif
    
    <div>
        <div class="mb-3">
            <x-input-text name="periodo" label="Periodo:" val="{{ $p->periodo ?? ''}}" required maxlength="255"/>
        </div>

        <div class="mb-3">
            <x-input-number
                required
                name="dias"
                label="Nº de días:"
                placeholder="Ingrese el número de días"
                min="1"
                max="9999"
                step="1"
                val="{{ $p->dias ?? '' }}"
            />
        </div>
        
        <div class="input-group">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </div>
</form>
