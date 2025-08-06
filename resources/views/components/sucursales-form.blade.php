@if (isset($table) && isset($data))
    <div id="appSucursales" class="container">
        <form action="{{ isset($p) && $p->id > 0 ? route($table . '.update') : route($table . '.store') }}" method="post" enctype="multipart/form-data">

            @csrf
            @if (isset($p) && $p->id > 0)
                <input type="hidden" name="id" value="{{ $p->id }}">
            @endif

            <div class="mb-3">
                <x-input-file name="logo" label="Logo de sucursales:" val="{{ $p->logo ?? '' }}"/>
            </div>

            <div class="mb-3">
                <x-input-text name="sucursal" label="Sucursal:" val="{{ $p->sucursal ?? '' }}" required maxlength="50"/>
            </div>

            <div class="mb-3">
                <x-input-text name="direccion" label="Dirección:" placeholder="Eje: Plaza Merliot, Boulevar 5to..."
                    val="{{ $p->direccion ?? '' }}" required maxlength="200"/>
            </div>

            <div class="mb-3">
                <x-input-text name="telefono" label="Teléfono:" placeholder="00000000" val="{{ $p->telefono ?? '' }}"
                    required maxlength="15"/>
            </div>

            <div class="mb-3">
                <x-input-text name="correo" label="Correo:" placeholder="algo@algo.algo"
                    val="{{ $p->correo ?? '' }}" />
            </div>

            <div class="mb-3">
                <x-input-text name="nit" label="NIT:" placeholder="0000-000000-000-0" val="{{ $p->nit ?? '' }}" />
            </div>

            <div class="mb-3">
                <x-input-text name="nrc" label="NRC:" placeholder="0000000-0" val="{{ $p->nrc ?? '' }}" />
            </div>

            <div class="mb-3">
                <x-input-text name="giro" label="Giro:" val="{{ $p->giro ?? '' }}" />
            </div>
            <div class="mb-3">
                <x-input-text name="codigo_establecimiento" label="Codigo de establecimiento:" placeholder="TROP-I"
                    val="{{ $p->codigo_establecimiento ?? '' }}" />
            </div>
            <div class="mb-3">
                <label for="ciudad">Buscar ciudad o municipio:</label>
                <input type="text" id="municipios_id" v-model="txtBusqueda" @input="getData" class="form-control"
                    placeholder="Buscar...">

                <div class="" v-if="list.length > 0">
                    <ul class="list-group list-group-numbered">
                        <li class="list-group-item " v-for="item in list" :key="item.id"
                            @click="setSelected(item)" :class="{ 'active': selected && selected.id == item.id }">
                            @{{ item.ciudad }}
                        </li>
                    </ul>
                </div>
                <input type="hidden" name="municipios_id" :value="selected ? selected.id : ''">
            </div>



            <div class="mb-3">
                <label for="matriz" class="form-label">Matriz</label>
                <select class="form-select" aria-label="Default select example" id="matriz" name="matriz">
                    <option selected disabled>--Seleccione--</option>

                    <option value="1" :selected="matriz == 1">Si</option>
                    <option value="0" :selected="matriz == 0">No</option>
                </select>
                {{-- <small class="text-danger"><span class="mdi mdi-alert"></span> {{ $message }}</small> --}}
            </div>

            <div class="input-group">
                <button class="btn btn-primary" type="submit">Guardar</button>
            </div>
        </form>
    </div>
    <script>
        var app = new Vue({
            el: '#appSucursales',
            data: {
                matriz: {{ isset($p) ? ($p->matriz == 1 ? '1' : '0') : '0' }},
                txtBusqueda: '{{ isset($p) && $p->municipios ? $p->municipios->municipio : '' }}',
                list: [],
                selected: null,

            },
            methods: {
                getData() {
                    if (this.txtBusqueda.length > 4) {
                        axios.post('{{ route('municipios.apiByCiudad') }}', {
                            txtBq: this.txtBusqueda.toUpperCase()
                        }).then(response => {
                            this.list = response.data.list;
                        }).catch(error => {
                            console.error(error);
                        });
                    } else {
                        this.list = [];
                    }
                },
                setSelected(item) {
                    this.selected = item;
                    this.txtBusqueda = item.ciudad;
                    this.list = [];
                }
            },
        });
    </script>
@else
    Este formulario requiere lo atributos :table y :giros
@endif
