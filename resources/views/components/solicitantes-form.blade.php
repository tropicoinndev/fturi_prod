<form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}" method="post">
    @csrf

    @if(isset($p) && $p->id > 0)
        <input type="hidden" name="id" value="{{ $p->cid }}"><!--Id encriptado-->
    @endif

    <div>
        <div class="mb-2">
            <x-input-text name="nombre_completo" label="Nombre completo:" val="{{ $p->nombre_completo ?? '' }}" required maxlength="100" pattern="^[a-zA-Z ]{3,100}$"/>
        </div>

        <div class="mb-3">
            <x-input-select name="identificaciones_id" label="Identificación:"
                :data="$data['identificaciones']" table="identificaciones" showName="identificacion"
                val="{{ $p->identificaciones_id ?? '' }}" />
        </div>

        <div class="mb-3">
            <x-input-text name="numero_documento" label="Nº documento:" val="{{ $p->numero_documento ?? '' }}" required maxlength="20" pattern="^[0-9]{3,20}$"/>
        </div>

        <div class="mb-3">
            <x-input-text name="telefono" label="Teléfono: 8 dígitos sin guiones ni espacios" val="{{ $p->telefono ?? '' }}" placeholder="00000000" required maxlength="8" pattern="^[0-9]{8}$"/>
        </div>

        <div class="mb-3">
            <x-input-text name="correo" label="Correo: ejemplo@ejemplo.com" val="{{ $p->correo ?? '' }}" placeholder="ejemplo@ejemplo.com" required maxlength="100" pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$"/>
        </div>

        <div class="input-group">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </div>
</form>
