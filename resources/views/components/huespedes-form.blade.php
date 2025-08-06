@if (isset($identificaciones) || isset($table))
    <form action="{{ isset($p) && $p->id > 0 ? route($table . '.update') : route($table . '.store') }}" method="post">
        @csrf

        @if (isset($p) && $p->id > 0)
            <input type="hidden" name="id" value="{{ $p->id }}">
        @endif

        {{--App Vue unicamente para los campos del nombre, identificación y número de identificación--}}
        <div id="appCreateHuesped">
            {{--Alerta que se mostrará cuando se encuentra una persona relacionada a ilicitos--}}
            <div v-if="arrayPersonasAlertas.length > 0" class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <strong>*** ESTÁ INTENTANDO AGREGAR UNA PERSONA RELACIONADA A ILICITOS ***</strong>
    
                <div v-for="pa in arrayPersonasAlertas" class="mt-2">
                    <p class="mb-0">Nombre: @{{ pa.nombres }} @{{ pa.apellidos }}</p>
                    <p class="mb-0">Alias: @{{ pa.alias ?? '---' }}</p>
                    <p class="mb-0">Nº de identificación: @{{ pa.numero_identificacion ?? '---' }}</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            {{--Input: es persona buscada: bandera para saber si coincide con el nombre o número de identificación y así enviar el correo--}}
            <input v-model="esPersonaBuscada" type="hidden" class="form-control" name="esPersonaBuscada">

            {{--Input: personaAlertaId: se envía el id de la persona alerta encontrada--}}
            <input v-model="personaAlertaId" type="hidden" name="personaAlertaId">

            {{--Input: nombre--}}
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre completo:</label>
                {{-- <x-input-text name="nombre" label="Nombre completo:" val="{{ $p->nombre ?? '' }}" :required="true" /> --}}
                <input type="text" class="form-control" value="{{ $p->nombre ?? '' }}" id="nombre" name="nombre" placeholder="Nombre completo" autocomplete="off" required>
            </div>

            {{--Input: tipo identificaciones--}}
            <div class="mb-3">
                <x-input-select name="identificaciones_id" label="Seleccione tipo de identificación:" :data="$identificaciones" showName="identificacion" table="identificaciones" val="{{ $p->identificaciones_id ?? '' }}" />
            </div>

            {{--Input: identificacion--}}
            <div class="mb-3">
                {{-- <x-input-text name="identificacion" label="Número de documento" val="{{ $p->identificacion ?? '' }}" /> --}}
                <label for="identificacion" class="form-label">Identificación:</label>
                <input v-model="txtIdentificacion" @change="apiSearchPersonasAlertasByIdentificacion" type="text" class="form-control" value="{{ $p->identificacion ?? '' }}" id="identificacion" name="identificacion" autocomplete="off">
                <div id="textHelp" class="form-text"></div>
            </div>
        </div>
        
        <div class="mb-3">
            <x-input-date name="nacimiento" label="Fecha de nacimiento" val="{{ $p->nacimiento ?? '' }}"
                :required="true" />
        </div>
        <div class="mb-3">
            <x-input-text name="telefono" label="Numero de telefono:" val="{{ $p->telefono ?? '' }}" />
        </div>
        {{--<div class="mb-3">
            <x-input-select name="identificaciones_id" label="Seleccione tipo de identificacion:" :data="$identificaciones"
                showName="identificacion" table="identificaciones" val="{{ $p->identificaciones_id ?? '' }}" />
        </div>
         <div class="mb-3">
            <x-input-text name="identificacion" label="Identificacion" val="{{ $p->identificacion ?? '' }}" />
        </div> --}}
        <div class="mb-3">
            <x-search label="Buscar ciudad o municipio:" showname="ciudad" :val="isset($p) ? $p->municipios : ''" :route="route('municipios.apiByCiudad')"
                id="municipios_id" :required="false" />
        </div>

        <div class="mb-3">
            <x-search label="Buscar país:" showname="pais" :val="isset($p) ? $p->paises : ''" :route="route('municipios.apiByPais')" id="paises_id"
                :required="false" />
        </div>

        <div class="input-group">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </form>
@else
    Los atributos :identificaciones y :table son requeridos
@endif


<script type="module">
    //console.log(window.appVue);

    var app = window.appVue({
        data() {
            return {
                txtNombre: @json($p->nombre ?? ''),
                txtIdentificacion: @json($p->identificacion ?? ''),

                identificaciones: @json($identificaciones),
                selectedIdentificacion: null,
                numeroDocumento: '',

                arrayPersonasAlertas: [],
                esPersonaBuscada: false,
                personaAlertaId: null,//Almacenará el id del primer registro encontrado
            }
        },
        mounted() {
            //console.log('Huéspedes mounted.');

            const identificacionSelect = document.querySelector('select[name="identificaciones_id"]');
            const documento = document.querySelector('input[name="identificacion"]');
            const validationMessage = document.getElementById('textHelp');

            identificacionSelect.addEventListener('change', () => {
                this.updateIdentificacion();
                documento.placeholder = this.placeholder;
                documento.pattern = this.regexPattern;
            });

            documento.addEventListener('input', () => {
                this.updateIdentificacion();
                this.numeroDocumento = documento.value;
                validationMessage.textContent = this.validationMessage;
                validationMessage.className = `form-text ${this.isDocumentoValid ? 'text-success' : 'text-danger'}`;
            });

            // Initialize
            this.updateIdentificacion();
            documento.placeholder = this.placeholder;
            documento.pattern = this.regexPattern;
        },
        methods: {
            apiSearchPersonasAlertasByNombre(){
                if(this.txtNombre != '' && this.txtNombre.length > 3){
                    axios.get(`/personas/alertas/nombre/api/${ (this.txtNombre).toUpperCase() }`)
                        .then((r) => {
                            if(!r.data.error){
                                this.arrayPersonasAlertas = r.data.list;
                                this.esPersonaBuscada = true;
                            }
                            console.log('API Nombres: ',r);
                        }).catch((e) => {
                            console.log('Error JS: ',e);
                        });
                }
                this.arrayPersonasAlertas = [];
                this.esPersonaBuscada = false;
            },
            apiSearchPersonasAlertasByIdentificacion(){
                if(this.txtIdentificacion != '' && this.txtIdentificacion.length > 3){
                    axios.get(`/personas/alertas/identificacion/api/${ (this.txtIdentificacion).toUpperCase() }`)
                        .then((r) => {
                            if(!r.data.error){
                                this.arrayPersonasAlertas = r.data.list;
                                this.personaAlertaId = this.arrayPersonasAlertas[0]['cid'];//Tomar el id encriptado del primer registro
                                this.esPersonaBuscada = true;
                            }
                            console.log('API Identificaciones: ',r);
                        }).catch((e) => {
                            console.log('Error JS: ',e);
                        });
                }
                this.arrayPersonasAlertas = [];
                this.esPersonaBuscada = false;
            },
            updateIdentificacion(){
                const selectElement = document.querySelector('select[name="identificaciones_id"]');
                const selectedId = selectElement.value;
                this.selectedIdentificacion = this.identificaciones.find(id => id.id == selectedId);
            },
        },
        computed: {
            placeholder() {
                return this.selectedIdentificacion ? this.selectedIdentificacion.info : 'Ingrese su número de documento';
            },
            regexPattern() {
                return this.selectedIdentificacion ? this.selectedIdentificacion.regex : '';
            },
            isDocumentoValid() {
                if(this.selectedIdentificacion){
                    const pattern = new RegExp(this.regexPattern);
                    return pattern.test(this.numeroDocumento);
                }
            },
            validationMessage() {
                return this.isDocumentoValid
                    ? `Número de documento válido: ${this.selectedIdentificacion?.info || ''}`
                    : `Número de documento no válido: ${this.selectedIdentificacion?.info || ''}`;
            }
        },
        watch: {
            selectedIdentificacion(newIdentificacion) {
                this.numeroDocumento = '';
            },
            numeroDocumento() {
                this.$nextTick(() => {
                    const documentoInput = document.querySelector('input[name="identificacion"]');
                    if (this.isDocumentoValid) {
                        documentoInput.classList.add('is-valid');
                        documentoInput.classList.remove('is-invalid');
                    } else {
                        documentoInput.classList.add('is-invalid');
                        documentoInput.classList.remove('is-valid');
                    }
                });
            }
        },
    }).mount('#appCreateHuesped');
</script>
