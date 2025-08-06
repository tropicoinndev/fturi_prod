<form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}" method="post">
    @csrf

    @if (isset($p) && $p->id > 0)
        <input type="hidden" name="id" value="{{$p->id}}">
    @endif
    
    <div>
        <div class="mb-2">
            <x-input-text name="pais" label="Países:" val="{{ $p->pais ?? ''}}" required maxlength="50"/>
        </div>

        <div class="mb-2">
            <x-input-text name="nacionalidad" label="Nacionalidad:"  val="{{  $p->nacionalidad ?? ''}}" required maxlength="50"/>
        </div>

        <div class="mb-2">
            <x-input-text name="codigo_mh" label="Código MH:"  val="{{  $p->codigo_mh ?? ''}}" required maxlength="4"/>
        </div>
        
        <div class="input-group">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </div>
</form>
