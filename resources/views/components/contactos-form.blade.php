<form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}" method="post">
    @csrf

    @if (isset($p) && $p->id > 0)
        <input type="hidden" name="id" value="{{$p->id}}">
    @endif

    <div class="mb-3">
        <x-input-text name="contacto" label="Contacto:" val="{{ $p->contacto ?? '' }}" required maxlength="50"/>
    </div>

    <div class="mb-3">
        <x-input-text name="regex" label="Expresión regular:" val="{{ $p->regex ?? '' }}" required maxlength="50"/>
    </div>

    <div class="mb-3">
        <x-input-text name="info" label="Información sobre la validación:" val="{{ $p->info ?? '' }}" required maxlength="200"/>
    </div>

    <div class="input-group">
        <button class="btn btn-primary" type="submit">Guardar</button>
    </div>
</form>
