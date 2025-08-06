<div id="appPersonaNaturalCreate">
    <form action="{{ route('personas_naturales.store') }}" method="POST">
        @csrf
        @if (isset($cliente) && $cliente > 0)
            <input type="hidden" name="clientes_id" value="{{ $cliente }}">
        @endif
        <div class="mb-3">
            <label for="" class="form-label">Buscar datos en clientes: @{{ lblNombreCompleto }}</label>

            <div class="input-group">
                <input v-model="txtSearchCliente" @keyup="apiSearchCliente" type="text" class="form-control"
                    placeholder="Buscar cliente por Nombre, DUI o NIT" aria-describedby="backSpaceCliente">

                <button v-if="Object.keys(clienteSelected).length > 0" @click="clearClienteSelected"
                    class="btn btn-outline-secondary" type="button" id="backSpaceCliente">
                    <span class="mdi mdi-backspace-outline"></span>
                </button>
            </div>

            {{-- Desplegable / Listado de clientes --}}
            <div v-if="Object.keys(clienteSelected).length <= 0">{{-- El desplegable se ocultará cuando se seleccione un un registro --}}
                <ul v-if="arrayClientes.length > 0"
                    style="position: absolute; width: 94%; max-height: 120px; overflow-y: auto;"
                    class="list-group shadow mt-2">
                    <li v-for="cliente in arrayClientes" @click="setCliente(cliente)" style="cursor: pointer;"
                        class="list-group-item text-uppercase">
                        @{{ cliente.nombre }} ·
                        <span v-for="identi in cliente.identificaciones"
                            class="badge text-bg-secondary">@{{ identi.identificaciones.identificacion }}: @{{ identi.numero }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="mb-3">
            <label for="apellidos" class="form-label">Apellidos:</label>
            <input v-model="clienteSelected.apellidos" type="text" class="form-control" id="apellidos"
                name="apellidos" placeholder="Apellidos">
        </div>

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre:</label>
            <input v-model="clienteSelected.nombre" type="text" class="form-control" id="nombre" name="nombre"
                placeholder="Nombre">
        </div>

        <div class="mb-3">
            <label for="profesion" class="form-label">Profesión u oficio:</label>
            <input type="text" class="form-control" id="profesion" name="profesion"
                placeholder="Profesión">
        </div>

        <div v-if="clienteSelected.paises_id == null || clienteSelected.departamentos_id > 0" class="mb-3">{{--El campo del departamento se ocultará cuando se haya seleccionado un país--}}
            <label for="departamentos_id" class="form-label">Departamento: @{{ clienteSelected.departamentos_id }} (Locales [El
                Salvador])</label>
            <input type="hidden" name="departamentos_id" :value="clienteSelected.departamentos_id">

            <div class="input-group">
                <input v-model="txtSearchDepartamento" @keyup="apiSearchDepartamento" type="text"
                    id="departamentos_id" class="form-control" placeholder="Buscar departamento"
                    aria-describedby="backSpaceDepartamento">

                <button v-if="clienteSelected.departamentos_id != null" @click="clearDepartamentoSelected"
                    class="btn btn-outline-secondary" type="button" id="backSpaceDepartamento">
                    <span class="mdi mdi-backspace-outline"></span>
                </button>
            </div>

            {{-- Desplegable / Listado de departamentos --}}
            <div v-if="clienteSelected.departamentos_id == null">{{-- El desplegable se ocultará cuando se seleccione un registro --}}
                <ul v-if="arrayDepartamentos.length > 0"
                    style="position: absolute; width: 94%; max-height: 120px; overflow-y: auto;"
                    class="list-group shadow mt-2">
                    <li v-for="depa in arrayDepartamentos" @click="setDepartamento(depa)" style="cursor: pointer;"
                        class="list-group-item">@{{ depa.departamento }}</li>
                </ul>
            </div>
        </div>

        <div v-if="clienteSelected.departamentos_id == null || clienteSelected.paises_id > 0" class="mb-3">{{--El campo del país se ocultará cuando se haya seleccionado un departamento--}}
            <label for="paises_id" class="form-label">País: @{{ clienteSelected.paises_id }} (Solo Extranjeros)</label>
            <input type="hidden" name="paises_id" :value="clienteSelected.paises_id">

            <div class="input-group">
                <input v-model="txtSearchPais" @keyup="apiSearchPais" type="text" id="paises_id" class="form-control"
                    placeholder="Buscar país" aria-describedby="backSpacePais">

                <button v-if="clienteSelected.paises_id != null" @click="clearPaisSelected"
                    class="btn btn-outline-secondary" type="button" id="backSpacePais">
                    <span class="mdi mdi-backspace-outline"></span>
                </button>
            </div>

            {{-- Desplegable / Listado de paises --}}
            <div v-if="clienteSelected.paises_id == null">{{-- El desplegable se ocultará cuando se seleccione un registro --}}
                <ul v-if="arrayPaises.length > 0"
                    style="position: absolute; width: 94%; max-height: 120px; overflow-y: auto; z-index: 100;"
                    class="list-group shadow mt-2">
                    <li v-for="pais in arrayPaises" @click="setPais(pais)" style="cursor: pointer;"
                        class="list-group-item">
                        @{{ pais.codigo_mh }} · @{{ pais.pais }}</li>
                </ul>
            </div>
        </div>

        <div class="mb-3">
            <label for="identificaciones_id">Identificación:</label>
            <select v-show="arrayIdentificaciones.length > 0" class="form-select" id="identificaciones_id"
                name="identificaciones_id" aria-label="Default select example">
                <option value="0" selected disabled>--Seleccione---</option>
                <option v-for="iden in arrayIdentificaciones" :value="iden.id">@{{ iden.identificacion }}</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="identificacion" class="form-label">Nº de identificación:</label>

            <p v-for="identi in clienteSelected.identificaciones" class="ms-3">@{{ identi.identificaciones.identificacion }} :
                @{{ identi.numero }}</p>
            <input type="text" class="form-control" id="identificacion" name="identificacion"
                placeholder="identificacion" :value="clienteSelected.numIdentificacion">
        </div>

        <div class="mb-3">
            <label for="nacimiento" class="form-label">Lugar de nacimiento:</label>
            <input type="text" class="form-control" id="nacimiento" name="nacimiento"
                placeholder="Lugar de nacimiento">
        </div>

        <div class="mb-3">
            <label for="fecha_nacimiento">Fecha de nacimiento:</label>
            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento">
        </div>

        <div class="mb-3">
            <label for="estado_civil">Estado civil:</label>
            <select class="form-select" id="estado_civil" name="estado_civil" aria-label="Default select example">
                <option value="0" selected disabled>--Seleccione---</option>
                <option value="Soltero/a">Soltero/a</option>
                <option value="Casado/a">Casado/a</option>
                <option value="Acompañado/a">Acompañado/a</option>
                <option value="Viudo/a">Viudo/a</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="domicilio" class="form-label">Domicilio:</label>
            <textarea class="form-control" id="domicilio" name="domicilio" cols="25" rows="2"
                placeholder="Domicilio"></textarea>
        </div>

        <div class="mb-3">
            <label for="observaciones" class="form-label">Observaciones: (Opcional)</label>
            <textarea class="form-control" id="observaciones" name="observaciones" cols="25" rows="2"
                placeholder="observaciones"></textarea>
        </div>

        <div class="mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="persona_riesgo"
                    name="persona_riesgo">
                <label class="form-check-label" for="persona_riesgo">¿Es persona de riesgo?</label>
            </div>
        </div>
        <div class="mb-4 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>

    </form>
</div>
<script type="module">
    var app = window.appVue({
        data() {
            return {
                //Variables de clientes
                lblNombreCompleto: '',
                txtSearchCliente: '',
                arrayClientes: [],
                clienteSelected: {},
                //Variables de paises
                txtSearchPais: '',
                arrayPaises: [],
                //Variables de departamentos
                txtSearchDepartamento: '',
                arrayDepartamentos: [],
                //Variables de identificaciones
                arrayIdentificaciones: [],
            }
        },
        mounted() {
            //console.log('Persona Natural Mounted.');
            this.apiGetIdentificaciones();
        },
        methods: {
            apiGetIdentificaciones() {
                axios.get("{{ route('identificaciones.apiGetIdentificaciones') }}")
                    .then((r) => {
                        this.arrayIdentificaciones = r.data.identificaciones;
                        //console.log('Identificaciones: ',this.arrayIdentificaciones);
                    })
                    .catch((e) => {
                        console.log('Error JS: ', e);
                    });
            },
            apiSearchCliente() {
                if (this.txtSearchCliente.length > 2) {
                    axios.post("{{ route('clientes.getPersonasNaturales') }}", {
                            busqueda: (this.txtSearchCliente).toUpperCase(),
                        })
                        .then((r) => {
                            //Traemos todos los clientes, pero luego filtramos solo los que sean naturales
                            this.arrayClientes = r.data.clientes.filter(cli => cli.tipo_cliente === true);
                            console.log('Array clientes: ', this.arrayClientes);
                        })
                        .catch((e) => {
                            console.log('Error JS: ', e);
                        });
                } else {
                    //Vaciar el objeto y el array cuando no hayan datos en la caja de búsqueda (oculta el listado desplegable)
                    this.clienteSelected = {};
                    this.arrayClientes = [];
                }
            },
            apiSearchPais() {
                if (this.txtSearchPais.length <= 2)
                    return this
                        .arrayPaises = []; //Vaciar el array cuando no hayan datos en la caja de búsqueda (oculta el listado desplegable)

                axios.post("{{ route('paises.apiGetPaises') }}", {
                        busqueda: (this.txtSearchPais).toUpperCase(),
                    })
                    .then((r) => {
                        this.arrayPaises = r.data.paises;
                        //console.log('Paises: ',this.arrayPaises);
                    })
                    .catch((e) => {
                        console.log('Error JS: ', e);
                    });
            },
            apiSearchDepartamento() {
                if (this.txtSearchDepartamento.length <= 2)
                    return this
                        .arrayDepartamentos = []; //Vaciar el array cuando no hayan datos en la caja de búsqueda (oculta el listado desplegable)

                axios.post("{{ route('departamentos.apiGetDepartamentos') }}", {
                        busqueda: (this.txtSearchDepartamento).toUpperCase(),
                    })
                    .then((r) => {
                        this.arrayDepartamentos = r.data.departamentos;
                    })
                    .catch((e) => {
                        console.log('Error JS: ', e);
                    });
            },

            setCliente(cliente) {
                //---CLIENTES---
                //this.clienteSelected = cliente;//Asignar al objeto el cliente seleccionado

                this.lblNombreCompleto = cliente.nombre;
                this.txtSearchCliente = cliente.nombre; //Colocar el nombre del cliente en la caja de búsqueda

                //Separar el nombre completo en partes
                const nombreCompleto = cliente.nombre;
                const partesNombre = nombreCompleto.split(' ');

                //Asignar los primeros dos como nombres
                this.clienteSelected.nombre = partesNombre.slice(0, 2).join(' ');

                //Asignar los siguientes dos como apellidos
                this.clienteSelected.apellidos = partesNombre.slice(2, 4).join(' ');

                //Eliminar cualquier carácter no alfabético de los apellidos
                this.clienteSelected.apellidos = this.clienteSelected.apellidos.replace(
                    /[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
                //---

                //---PAISES---
                this.clienteSelected.paises_id = cliente.extranjeros_id;
                this.clienteSelected.pais = cliente.extranjero?.pais || 'Sin país';
                this.txtSearchPais = this.clienteSelected.pais;
                //---

                //---DEPARTAMENTOS---
                this.clienteSelected.departamentos_id = cliente.municipios_departamentos?.departamentos_id ||
                    'Sin id de departamento|municipio';
                this.clienteSelected.departamento = cliente.municipios_departamentos?.municipio ||
                    'Sin departamento|municipio';
                this.txtSearchDepartamento = this.clienteSelected.departamento;
                //---

                //---IDENTIFICACIONES---
                this.clienteSelected.identificaciones = cliente.identificaciones;
                this.clienteSelected.numIdentificacion = cliente.identificaciones[0]?.numero || [];
                //---

                console.log('Cliente seleccionado: ', this.clienteSelected);
            },
            setPais(pais) {
                this.txtSearchPais = pais.pais; //Colocar el nombre del país en la caja de búsqueda
                this.clienteSelected.paises_id = pais.id;

                console.log('País seleccionado: ', pais.pais);
            },
            setDepartamento(depa) {
                this.txtSearchDepartamento = depa
                    .departamento; //Colocar el nombre del país en la caja de búsqueda
                this.clienteSelected.departamentos_id = depa.id;

                console.log('Departamento seleccionado: ', depa.departamento);
            },

            clearClienteSelected() {
                this.lblNombreCompleto = '';
                this.txtSearchCliente = '';
                this.clienteSelected = {};
                this.arrayClientes = [];

                //Si vacía el cliente, también se deben vaciar el país y el departamento
                this.clearPaisSelected();
                this.clearDepartamentoSelected();
            },
            clearPaisSelected() {
                this.txtSearchPais = '';
                this.clienteSelected.paises_id = null;
                this.arrayPaises = [];
            },
            clearDepartamentoSelected() {
                this.txtSearchDepartamento = '';
                this.clienteSelected.departamentos_id = null;
                this.arrayDepartamentos = [];
            },
        },
    }).mount('#appPersonaNaturalCreate');
</script>
