@if (isset($table) && isset($data))
    <form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}" method="post">
        @csrf

        @if (isset($p) && $p->id > 0)
            <input type="hidden" name="id" value="{{$p->id}}">
        @endif
        
        <div class="mb-2">
            <x-input-text name="municipio" label="Municipio:" val="{{ $p->municipio ?? ''}}" required maxlength="50"/>
        </div>

        <div class="mb-2">
            <x-input-text name="codigo_postal" label="Código Postal: 00000" val="{{  $p->codigo_postal ?? ''}}" placeholder="00000" required maxlength="5"/>
        </div>

        <div class="mb-2">
            <x-input-text name="codigo_mh" label="Código Ministerio de Hacienda: 00" val="{{  $p->codigo_mh ?? ''}}" placeholder="00" required maxlength="2"/>
        </div>
    
        <div class="mb-3">
            <x-input-select
                name="departamentos_id"
                label="Departamentos:"
                :data="$data['departamentos']"
                table="departamentos"
                showName="departamento"
                val="{{$p->departamentos_id ?? ''}}"
            />
        </div>
        
        <div class="input-group">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </form>
@else
    Este formulario requiere lo atributos :table y :data
@endif
