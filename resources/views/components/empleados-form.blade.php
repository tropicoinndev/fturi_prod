@if (isset($identificaciones) || isset($table))
<form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}" method="post" id="Empleado">
    @csrf
    @if (isset($p) && $p->id > 0 )
    <input type="hidden" name="id" value="{{$p->id}}">
    @endif
    <div class="mb-3">
        <x-input-text name="nombre_completo" label="Nombre completo:" val="{{ $p->nombre_completo ?? '' }}" :required="true" />
    </div>
    <div class="mb-3">
        <x-input-select name="identificaciones_id" label="Seleccione tipo de identificación:" :data="$data['identificaciones']" showName="identificacion" table="identificaciones" val="{{ $p->identificaciones_id ?? '' }}" />
    </div>
    <div class="mb-3">
        <x-input-text name="numero_documento" label="Número de documento" val="{{ $p->numero_documento ?? '' }}" />
        <div id="textHelp" class="form-text"></div>
    </div>
    <div class="mb-3">
        <x-input-select name="users_id" label="Seleccione el usuario:" :data="$data['usuarios']" showName="name" table="users" val="{{ $p->users_id ?? '' }}" />
    </div>
    <div class="input-group">
        <button class="btn btn-primary" type="submit">Guardar</button>
    </div>
</form>
<script>
    new Vue({
        el: '#Empleado',
        data() {
            return {
                identificaciones: @json($data['identificaciones']),
                selectedIdentificacion: null,
                numeroDocumento: ''
            };
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
                    const documentoInput = document.querySelector('input[name="numero_documento"]');
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
        methods: {
            updateIdentificacion() {
                const selectElement = document.querySelector('select[name="identificaciones_id"]');
                const selectedId = selectElement.value;
                this.selectedIdentificacion = this.identificaciones.find(id => id.id == selectedId);
            }
        },
        mounted() {
            const identificacionSelect = document.querySelector('select[name="identificaciones_id"]');
            const documento = document.querySelector('input[name="numero_documento"]');
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
        }
    });
</script>
@else
<p>Los atributos :identificaciones :usuarios y :table son requeridos</p>
@endif
