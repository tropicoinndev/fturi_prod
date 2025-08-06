@if (isset($table))
    <form action="{{ isset($p) && $p->id > 0 ? route($table . '.update') : route($table . '.store') }}" method="post">
        @csrf

        @if (isset($p) && $p->id > 0)
            <input type="hidden" name="id" value="{{ $p->id }}">
        @endif

        <div class="mb-3">
            <x-input-text name="name" label="Nombre:" val="{{ old('name') ?? ($p->name ?? '') }}" required
                maxlength="50" />
        </div>

        <div class="mb-3">
            <x-input-text name="email" label="Correo: ejemplo@ejemplo.com"
                val="{{ old('email') ?? ($p->email ?? '@fturi.com') }}" placeholder="ejemplo@ejemplo.com" required
                maxlength="50" pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$" />
        </div>

        <div class="input-group">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </form>
@else
    Este formulario requiere lo atributos :table y :data
@endif
