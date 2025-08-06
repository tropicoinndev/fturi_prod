<form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}" method="post">

    @csrf
    @if (isset($p) && $p->id > 0)
        <input type="hidden" name="id" value="{{$p->id}}">
    @endif

    <div class="mb-3">
        <x-input-text name="turno" label="Turno:" val="{{ $p->turno ?? '' }}"/>
    </div>

    <div class="mb-3">
        <x-input-time name="apertura" label="Apertura:" val="{{ $p->apertura ?? '' }}"/>
    </div>

    <div class="mb-3">
        <x-input-time name="cierre" label="Cierre:" val="{{ $p->cierre ?? '' }}"/>
    </div>

    <div class="input-group">
        <button class="btn btn-primary" type="submit">Guardar</button>
    </div>
    
</form>
