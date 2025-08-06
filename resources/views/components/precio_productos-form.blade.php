@if (isset($table) && isset($data))
    <form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}"
        method="post">

        @csrf
        @if (isset($p) && $p->id > 0)
            <input type="hidden" name="id" value="{{$p->id}}">
        @endif
            
        <div class="mb-3">
            <x-input-select
                name="precios_id"
                label="Precios:"
                :data="$data['precios']"
                table="precios"
                showName="detalle"
                val="{{ $p->precios_id ?? '' }}"
            />
        </div>
            <div class="mb-3">
            <x-input-select
                name="productos_id"
                label="Productos:"
                :data="$data['productos']"
                table="productos"
                showName="nombre"
                val="{{ $p->productos_id ?? '' }}"
            />
        </div>
             <div class="mb-3">
            <x-input-number
                name="descargo"
                label="Descargo: 1-99999"
                placeholder="0000"
                min="1"
                max="99999"
                val="{{ $p->descargo ?? '' }}"
            />
        </div>
        <div class="form-check form-check-inline mb-1">
            <input class="ml-1" type="checkbox" id="checkProduccion" name="produccion" value="1" >
            
            <label class="form-check-label" for="checkIva">Produccion</label>
        </div>

        <div class="input-group">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </form>
@else
    Este formulario requiere lo atributos :table y :giros
@endif