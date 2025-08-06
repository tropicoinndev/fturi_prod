@if (isset($table) && isset($data))
    <form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}"
        method="post" id="appRequisiciones">

        @csrf
        @if (isset($p) && $p->id > 0)
            <input type="hidden" name="id" value="{{$p->id}}">
        @endif

        <div class="mb-3">
            <label for="bodega_entrada_id" class="form-label">Bodega de entrada:</label>
            <select class="form-select" name="bodega_entrada_id" id="bodega_entrada_id">
                <option selected disabled>--Seleccione---</option>
                @foreach ($data['bodegaUsers'] as $item)
                    <option value="{{ $item->relacionBodegas->id ?? '' }}">{{ $item->relacionBodegas->bodega ?? '' }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="bodega_salida_id" class="form-label">Bodega de salida:</label>
            <select class="form-select" name="bodega_salida_id" id="bodega_salida_id">
                <option selected disabled>--Seleccione---</option>
                @foreach ($data['bodegaUsers'] as $item)
                    <option value="{{ $item->relacionBodegas->id ?? '' }}">{{ $item->relacionBodegas->bodega ?? '' }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <x-input-text-area name="solicitud" label="Describa la solicitud:" rows="2" val="{{ $p->solicitud ?? '' }}"/>
        </div>

        <div class="input-group mt-3">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </form>

<script>
    var app = new Vue({
        el: '#appRequisiciones',
        data:{
            
        },
        methods:{
            
        },
        computed:{
            
        }
    })
</script>
@else
    Este formulario requiere lo atributos :table y :data
@endif
