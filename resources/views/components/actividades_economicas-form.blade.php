<form action="{{ isset($p) && $p->id > 0 ? route($table . '.update') : route($table . '.store') }}" method="post">
    @csrf

    @if (isset($p) && $p->id > 0)
        <input type="hidden" name="id" value="{{ $p->id }}">
    @endif

    <div>
        <div class="mb-2">
            <x-input-text name="codigo" label="Código: 00" val="{{ $p->codigo ?? '' }}" placeholder="00" required />
        </div>

        <div class="mb-2">
            <x-input-text name="actividad" label="Actividad:" val="{{ $p->actividad ?? '' }}" required maxlength="50" />
        </div>

        <div class="input-group">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </div>
</form>
