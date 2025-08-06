<form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}" method="post">

    @csrf
    @if (isset($p) && $p->id > 0)
        <input type="hidden" name="id" value="{{$p->id}}">
    @endif
    
    <div>
        <div class="mb-2">
            <x-input-text name="montaje" label="Montajes:" val="{{ $p->montaje ?? ''}}" />
        </div>

        <div class="mb-2">
            <x-input-text-area name="descripcion" label="Descripcion:"  val="{{  $p->descripcion ?? ''}}"/>
        </div>
        
        <div class="input-group">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </div>
</form>
