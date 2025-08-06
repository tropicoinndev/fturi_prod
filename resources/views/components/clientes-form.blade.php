@if (isset($table) && isset($data))
    <form action="{{ isset($p) && $p->id > 0 ? route($table . '.update') : route($table . '.store') }}" method="post">
        @csrf

        @if (isset($p) && $p->id > 0)
            <input type="hidden" name="id" value="{{ $p->id }}">
        @endif

        <div>
            <div class="form-group mb-3">
                <label class="w-100 mb-1">Tipo de cliente:</label>
                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" class="btn-check" name="tipo_cliente" id="juridico" value="0"
                        {{ old('tipo_cliente') && old('tipo_cliente') == 0
                            ? 'checked'
                            : (isset($p) && $p->tipo_cliente == 0
                                ? 'checked'
                                : '') }}
                        autocomplete="off" required>
                    <label class="btn btn-outline-primary" for="juridico">Contribuyente</label>

                    <input type="radio" class="btn-check" name="tipo_cliente" id="natural" value="1"
                        {{ old('tipo_cliente') && old('tipo_cliente') == 1
                            ? 'checked'
                            : (isset($p) && $p->tipo_cliente == 1
                                ? 'checked'
                                : '') }}
                        autocomplete="off">
                    <label class="btn btn-outline-primary" for="natural">Cliente</label>
                </div>
            </div>
            <div class="mb-2">
                <x-input-text name="nombre" label="Nombre:" val="{{ $p->nombre ?? '' }}" required maxlength="250" />
            </div>
            <div class="mb-2">
                <x-input-text name="email" label="Email:" val="{{ $p->email ?? '' }}" maxlength="50" />
            </div>
            <div class="mb-3">
                <x-input-text-area name="direccion" label="Dirección:" val="{{ $p->direccion ?? '' }}" required />
            </div>
            <div class="mb-3">
                <x-input-text-area name="observaciones" label="Observaciones: (Opcional)" val="{{ $p->observaciones ?? '' }}"/>
            </div>
            <div class="mb-3">
                <x-input-select name="categoria" label="Categoría del cliente:" :data="$data['categorias']" table="cliente"
                    showName="categoria" val="{{ $p->categoria ?? '' }}" />
            </div>

            <div class="mb-3">
                <x-search label="Buscar actividad economica:" showname="actividad_economica" :val="isset($p) ? $p->actividades : ''"
                    :route="route('clientes.get_actividades')" id="actividades_economicas_id" />
            </div>
            <div class="mb-3">
                <x-search label="Buscar ciudad o municipio: (Locales [El Salvador])" showname="ciudad" :val="isset($p) ? $p->municipios : ''"
                    :route="route('municipios.apiByCiudad')" id="municipios_id" />


            </div>

            <div class="mb-3" id="pais-container">
                <x-search label="Buscar país: (Solo Extranjeros)" showname="pais" :val="isset($p) ? $p->extranjero : ''" :route="route('municipios.apiByPais')"
                    id="extranjeros_id" />
            </div>

            <div class="input-group">
                <button class="btn btn-primary" type="submit">Guardar</button>
            </div>
        </div>
    </form>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pais = document.getElementById('pais-container');
            const clienteJuridico = document.getElementById('juridico');
            const clienteNatural = document.getElementById('natural');
            const extranjerosNode = document.getElementById('extranjeros_id'); // Nodo que quieres ocultar
            const targetNode = document.getElementById('municipios_id');
            const targeExtranje = document.getElementById('extranjeros_id');

            // Función para ocultar/mostrar el contenedor del país según el tipo de cliente
            function selectedJuridico() {
                if (clienteJuridico.checked) {
                    pais.style.display = 'none'; // Ocultar si es Contribuyente
                } else {
                    pais.style.display = 'block'; // Mostrar si es Cliente
                }
            }

            // Añadir eventos para verificar cambios en el tipo de cliente
            clienteJuridico.addEventListener('change', selectedJuridico);
            clienteNatural.addEventListener('change', selectedJuridico);

            // Inicializar visibilidad al cargar la página
            selectedJuridico();

            // Configuración del MutationObserver
            const config = {
                attributes: true,
                childList: true,
                subtree: true
            };

            // Función para manejar los cambios de atributos o hijos en el nodo municipios_id
            const callback = function(mutationsList, observer) {
                for (let mutation of mutationsList) {
                    if (mutation.type === 'childList' || mutation.type === 'attributes') {
                        const tipoClienteValue = clienteJuridico.checked ? 0 : 1;
                        if (tipoClienteValue === 1) {
                            extranjerosNode.style.display = 'none';
                        } else {
                            extranjerosNode.style.display = 'block';
                        }
                    }
                }
            };

            // Crear el observador
            const observer = new MutationObserver(callback);
            if (targetNode) {
                observer.observe(targetNode, config);
            }


        });
    </script>
@else
    Este formulario requiere lo atributos :table y :descuentos
@endif
