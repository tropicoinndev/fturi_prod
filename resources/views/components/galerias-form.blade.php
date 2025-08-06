<form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}" method="post" enctype="multipart/form-data">

    @csrf
    @if (isset($p) && $p->id > 0)
        <input type="hidden" name="id" value="{{$p->id}}">
    @endif
    
    <div>
        <x-input-file name="foto" label="Fotos:" val="{{ $p->foto ?? ''}}"/>

        <div class="mb-2">
            <x-input-text-area name="descripcion" label="Descripcion:"  val="{{  $p->descripcion ?? ''}}"/>
        </div>
        <div class="mb-3">
            <x-input-select
                name="categoria_fotos_id"
                label="Categoria fotos:"
                :data="$data['categoria_fotos']"
                table="categoria_fotos"
                showName="categoria"
                val="{{ $p->categoria_fotos_id ?? '' }}"
            />
        </div>
        <div class="input-group">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </div>
</form>
