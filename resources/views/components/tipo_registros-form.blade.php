<form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}" method="post">

    @csrf
    @if (isset($p) && $p->id > 0)
        <input type="hidden" name="id" value="{{$p->id}}">
    @endif

    <div class="mb-3">
        <x-input-text name="tipo" label="Tipo de registro:" val="{{ $p->tipo ?? '' }}"/>
    </div>
    
    <div class="mb-3">
        <x-input-number
            name="token"
            label="Token: 1-9999"
            placeholder="0000"
            min="1"
            max="9999"
            val="{{ $p->token ?? '' }}"
        />
    </div>

    <div class="input-group">
        <button class="btn btn-primary" type="submit">Guardar</button>
    </div>
</form>
