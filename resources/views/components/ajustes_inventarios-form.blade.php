@if (isset($lotes) || isset($table))
    <form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}" method="post">
        @csrf
        
        @if (isset($p) && $p->id > 0 )
            <input type="hidden" name="id" value="{{$p->id}}">
        @endif

        
        {{-- <div class="mb-3">
            <x-input-select name="lotes_id" label="Lotes:" :lotes="$lotes" showName="cantidad" table="lotes" val="{{ $p->lotes_id ?? '' }}" />
        </div> --}}

        <div class="input-group">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </form>
@else
    Los atributos :identificaciones y :table son requeridos
@endif
