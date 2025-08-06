<form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}" method="post">
    <div id="appAnulaciones">
        @csrf

        @if(isset($p) && $p->id > 0)
            <input type="hidden" name="id" value="{{$p->id}}">
        @endif

        <div class="mb-3">
            {{-- <x-input-text name="codigo" label="Código:" val="{{ $p->codigo ?? '' }}"/> --}}
            <div class="mb-3">
                <label for="codigo" class="form-label">Código:</label>
                {{-- <select class="form-select" name="codigo" id="codigo">
                    <option selected disabled value="{{ $p->codigo ?? '' }}">{{ ($p->codigo ?? '') ? 'seleccionar' : 'seleccionar' }}</option>
                    <option value="1"{{ isset($p) && $p->codigo == '1' ? 'selected' : '' }}>Error en la información del DTE a invalidar</option>
                    <option value="2"{{ isset($p) && $p->codigo == '2' ? 'selected' : '' }}>Rescindir de la operación realizada</option>
                    <option value="3" {{ isset($p) && $p->codigo == '3' ? 'selected' : '' }}>Otro</option>
                </select> --}}
                <select class="form-select" name="codigo" id="codigo" v-model="selCodigo">
                    <option value="1">Error en la información del DTE a invalidar</option>
                    <option value="2">Rescindir de la operación realizada</option>
                    <option value="3">Otro</option>
                </select>
            </div>
        </div>

        <div v-show="selCodigo == 3" class="mb-3">
            {{-- <x-input-text name="anulacion" label="Anulación:" val="{{ $p->anulacion ?? '' }}"/> --}}
            <label for="anulacion">Anulación:</label>
            <input type="text" class="form-control" id="anulacion" name="anulacion" placeholder="Escriba aqui..." v-model="txtAnulacion">
        </div>

        <div class="input-group">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </div>
</form>

<script type="module">
    let app = window.appVue({
        data(){
            return {
                selCodigo: @json($p->codigo ?? 1),
                txtAnulacion: @json($p->anulacion ?? ''),
            }
        },
        mounted(){
            console.log('Anulaciones Mounted.');
        },
        methods: {

        },
        computed: {
            //Code...
        },
        watch: {
            //Code...
        },
    });
    app.mount('#appAnulaciones');
</script>
