
    <form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}"
        method="post">

        @csrf
        @if (isset($p) && $p->id > 0)
            <input type="hidden" name="id" value="{{$p->id}}">
        @endif

        <div class="mb-3">
            <x-input-text
                name="forma_habitacion"
                label="Forma de habitacion:"
                val="{{ $p->forma_habitacion ?? '' }}"
            />
        </div>

        <div class="mb-3">
            <label for="max_personas" class="form-label">Nº Max. de personas:</label>
            <input type="text" class="form-control" id="max_personas" name="max_personas" value="{{ $p->max_personas ?? '' }}">
        </div>

        <div class="input-group">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </form>

