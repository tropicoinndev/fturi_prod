@if (isset($id) && isset($route) && isset($showname))
    <style scoped>
        .lista_{{ $id }} {
            position: absolute;
            width: 100%;
            z-index: 10;
            background: #fff;
        }

        .lista_{{ $id }} .list-group .list-group-item {
            border: 0;
            border-radius: 0;
            cursor: pointer;
        }

        .lista_{{ $id }} .list-group .list-group-item:hover {
            background: #0097A7;
            color: #fff;
        }
    </style>

    <div id="{{ $id }}">
        <input type="hidden" name="{{ $id }}" v-model="selected.id ?? null">
        <div class="form-group" v-if="selected.length == 0">
            <label for="">{{ $label ?? 'Buscar' }}</label>
            <input type="text" class="form-control" placeholder="Buscar..." v-model="txtBusqueda" @keyup="getData()"
                {{ isset($required) && $required ? 'required' : '' }}>
            <div class="lista_{{ $id }} shadow-lg">
                <ul class="list-group" v-if="list.length > 0" v-for="(p, index) in list" v-bind:key="index">
                    <li class="list-group-item" @click="setSelected(p)">@{{ p[show] }}</li>
                </ul>
                <ul class="list-group" v-if="list.length == 0 && txtBusqueda.length > 3">
                    <li class="list-group-item">
                        No se han encontrado resultados, revisé lo que busca si esta bien escrito.
                    </li>
                </ul>
            </div>
        </div>
        <div v-if="selected.id">
            <h5>
                <span class="badge bg-secondary text-uppercase">
                    @{{ show }} selecionado @{{ selected[show] }}
                    <span class="mdi mdi-close" @click="selected = []"></span>
                </span>
            </h5>
        </div>
    </div>

    <script type="application/javascript">
    var searchApp = new Vue({
        el: "#{{ $id }}"
        , data: {
            txtBusqueda: ""
            , show: '{{ $showname }}'
            , list: []
            , @if($val != '')
            selected: @json($val)
            @else
            selected: []
            @endif
        }
        , methods: {
            getData() {
                if (this.txtBusqueda != null && this.txtBusqueda.length > 3)
                    axios.post('{{ $route }}', {
                        '{{ $param ?? "txtBq" }}': (this.txtBusqueda).toUpperCase()
                    , }).then((rs) => {
                        if (rs.data.list)
                            this.list = rs.data.list;
                        else this.list = [];

                    });
                else this.list = [];
            }
            , setSelected(p) {
                this.selected = p;
                this.list = [];
                this.txtBusqueda = "";
            }
        }
    , });

</script>
@else
    <div class="alert alert-primary" role="alert">
        <h4 class="alert-heading">Component Search</h4>
        Son requeridos los campos :id, :showname, y :route.
        Campos no requeridos :label.
    </div>
@endif
