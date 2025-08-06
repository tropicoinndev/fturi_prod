@if (isset($table) && isset($data))
    <form action="{{ isset($p) && $p->id > 0 ? route($table . '.update') : route($table . '.store') }}" method="post"
        id="appServicios">
        @csrf

        @if (isset($p) && $p->id > 0)
            <input type="hidden" name="id" value="{{ $p->id }}">
        @endif

        <div class="mb-3">
            <label for="rubrosSelect" class="form-label">Rubros:</label>
            <select class="form-select" name="rubros_id" id="rubrosSelect" v-model="rubroSelected" @change="setRubro"
                required>
                <option value="" disabled>seleccione el rubro</option>
                <option v-for="r in rubros" :value="r.id">@{{ r.rubro }}</option>
            </select>
        </div>

        <div class="mb-3">
            <x-input-text name="servicio" label="Servicio:" val="{{ $p->servicio ?? '' }}" required maxlength="150" />
        </div>

        <div class="mb-3">
            <x-input-number name="precio_unitario" label="Precio unitario:" placeholder="0.00" min="0"
                max="9999999999" val="{{ $p->precio_unitario ?? '' }}" required maxDigitos="10" />
        </div>

        <div class="form-check form-check-inline">
            <input class="ml-1" type="checkbox" id="checkIva" name="iva" value="1"
                :class="{ 'd-none': getIva }" :checked="getIva">
            <span class="mdi mdi-check" :class="{ 'visually-hidden': !getIva }"></span>
            <label class="form-check-label" for="checkIva">IVA</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="ml-1" type="checkbox" id="checkCesc" name="cesc" value="1"
                :class="{ 'd-none': getCesc }" :checked="getCesc" :disabled="!getCesc">
            <span class="mdi mdi-check" :class="{ 'visually-hidden': !getCesc }"></span>
            <label class="form-check-label" for="checkCesc">CESC</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="ml-1" type="checkbox" id="checkAdvalorem" name="advalorem" value="1"
                :class="{ 'd-none': getAdvalorem }" :checked="getAdvalorem" :disabled="!getAdvalorem">
            <span class="mdi mdi-check" :class="{ 'visually-hidden': !getAdvalorem }"></span>
            <label class="form-check-label" for="">Advalorem</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="ml-1" type="checkbox" id="checkPropina" name="propina" value="1"
                :class="{ 'd-none': getPropina }" :checked="getPropina" :disabled="!getPropina">
            <span class="mdi mdi-check" :class="{ 'visually-hidden': !getPropina }"></span>
            <label class="form-check-label" for="checkPropina">Propina</label>
        </div>

        <div class="form-check form-check-inline mb-1">
            <input class="form-check-input" type="checkbox" id="descuento" name="descuento" value="1"
                @click="funcEstadoDescuento" v-model="estadoDescuento">
            <label class="form-check-label" for="descuento">Permite descuento</label>
        </div>

        <div :class="{ 'd-none': !getAdvalorem }">
            <x-input-number name="sugerido" label="Precio sugerido:" placeholder="0.00" min="0" max="9999999999"
                val="{{ $p->sugerido ?? '' }}" />
        </div>
        <div class="input-group mt-3">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </form>
    <script type="module">
        var app = window.appVue({
            el: '#appServicios',
            data() {
                return {
                    rubros: @json($data['rubros']),
                    rubroSelected: {{ $p->rubros_id ?? 0 }},

                    servicio: [],

                    estadoDescuento: false,
                };

                //Registro encontrado.

            },
            methods: {
                setRubro() {
                    this.servicio = this.rubros.filter(t => t.id == this.rubroSelected);
                    //console.log(this.servicio);
                },
                funcEstadoDescuento() {
                    this.estadoDescuento = !this.estadoDescuento;
                    //console.log('Estado descuento: ',this.estadoDescuento);
                },
            },
            mounted() {
                // Llamar a setRuro al cargar la página para inicializar los checkboxes
                this.setRubro();
            },
            computed: {
                getAdvalorem() {
                    if (this.servicio.length == 1) {
                        var token = parseInt(this.servicio[0].token);

                        //console.log(token);

                        if (token == 12005) {
                            return true;
                        }
                    }
                    return false;
                },
                getIva() {
                    if (this.servicio.length == 1) {
                        var token = parseInt(this.servicio[0].token);

                        if (token == 12001 || token == 12002 || token == 12003 || token == 12004 || token ==
                            12005) {
                            return true;
                        }
                    }
                    return false;
                },
                getPropina() {
                    if (this.servicio.length == 1) {
                        var token = parseInt(this.servicio[0].token);

                        if (token == 12001 || token == 12004 || token == 12005) {
                            return true;
                        }
                    }
                    return false;
                },
                getCesc() {
                    if (this.servicio.length == 1) {
                        var token = parseInt(this.servicio[0].token);

                        if (token == 12002) {
                            return true;
                        }
                    }
                    return false;
                },

            }
        });
        app.mount("#appServicios");
    </script>
@else
    Este formulario requiere lo atributos :table y :data
@endif
