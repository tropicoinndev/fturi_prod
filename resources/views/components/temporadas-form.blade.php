<form action="{{ isset($p) && $p->id > 0 ? route($table . '.update') : route($table . '.store') }}" method="post">
    @csrf

    @if (isset($p) && $p->id > 0)
        <input type="hidden" name="id" value="{{ $p->id }}">
    @endif

    <div>
        <div class="mb-3">
            <x-input-text name="temporada" label="Temporada:" val="{{ $p->temporada ?? '' }}" />
        </div>

        <div class="mb-3">
            <x-input-date name="fecha_inicio" label="Fecha Inicia:" val="{{ $p->fecha_inicio ?? '' }}" />
        </div>
        
        <div class="mb-3">
            <x-input-date name="fecha_finalizacion" label="Fecha Finaliza:" val="{{ $p->fecha_finalizacion ?? '' }}" />
        </div>

        <div class="input-group">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </div>
</form>
