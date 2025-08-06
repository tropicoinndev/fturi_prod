@if (isset($table) && isset($data))
    <form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}" method="post">
        @csrf

        @if (isset($p) && $p->id > 0)
            <input type="hidden" name="id" value="{{$p->id}}">
        @endif

        <div class="mb-3">
            <x-input-text name="identificacion" label="Identificación:" val="{{ $p->identificacion ?? '' }}" required maxlength="50"/>
        </div>

        <div class="mb-3">
            <x-input-text name="regex" label="Expresión regular:" val="{{ $p->regex ?? '' }}" required maxlength="50"/>
        </div>

        <div class="mb-3">
            <x-input-text name="info" label="Información sobre la validación:" val="{{ $p->info ?? '' }}" required maxlength="200"/>
        </div>

        <div class="mb-3">
            <x-input-text name="codigo" label="Código MH:" placeholder="0000" val="{{ $p->codigo ?? '' }}" required maxlength="4"/>
        </div>

        <div class="mb-3">
            <label for="">Identificación requerida para:</label><br>

            <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                <input type="radio" class="btn-check" name="tipo_cliente" id="natural" autocomplete="off" value="1" {{
                    isset($p->tipo_cliente) && $p->tipo_cliente?'checked':'' }} required>
                <label class="btn btn-outline-primary" for="natural">Natural</label>

                <input type="radio" class="btn-check" name="tipo_cliente" id="juridico" autocomplete="off" value="0" {{
                    isset($p->tipo_cliente) && !$p->tipo_cliente?'checked':'' }} required>
                <label class="btn btn-outline-primary" for="juridico">Jurídico</label>
            </div>
        </div>

        <div class="input-group">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </form>
@else
    Este formulario requiere lo atributos :table y :giros
@endif
