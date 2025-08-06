@if (isset($table) && isset($data))
    <form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}" method="post" id="appPrecios">
        @csrf

        @if (isset($p) && $p->id > 0)
            <input type="hidden" name="id" value="{{$p->id}}">
        @endif

        <div class="mb-3">
            <x-input-select
                name="categorias_precios_id"
                label="Categorías precios:"
                :data="$data['categorias_precios']"
                table="categorias_precios"
                showName="categoria"
                val="{{ $p->categorias_precios_id ?? '' }}"
                required
            />
        </div>

        <div class="mb-3">
            <x-input-text
                name="detalle"
                label="Detalles:"
                val="{{ $p->detalle ?? '' }}"
                required maxlength="50"
            />
        </div>

        <div id="precio" class="mb-3">
            {{-- <x-input-text
                name="precio"
                label="Precios:"
                val="{{ $p->precio ?? '' }}"
            /> --}}
            <x-input-number
                name="precio"
                label="Precio:"
                placeholder="0.00"
                min="0"
                max="99999"
                val="{{ $p->precio ?? '' }}"
                required
                maxDigitos="10"
            />
        </div>
    
        <div class="form-check form-check-inline mb-1">
            <input class="ml-1" type="checkbox" id="checkIva" name="iva" value="1" {{ isset($p) && $p->iva ? 'checked' : '' }}>
            <label class="form-check-label" for="checkIva">IVA</label>
        </div>

        <div class="form-check form-check-inline mb-1">
            <input class="ml-1" type="checkbox" id="checkAdvalorem" name="advalorem" value="1" {{ isset($p) && $p->advalorem ? 'checked' : '' }}>
            <label class="form-check-label" for="">Advalorem</label>
        </div>

        <div class="form-check form-check-inline mb-1">
            <input class="ml-1" type="checkbox" id="checkPropina" name="propina" value="1" {{ isset($p) && $p->propina ? 'checked' : '' }}>
            <label class="form-check-label" for="checkPropina">Propina</label>
        </div>

        <div class="form-check form-check-inline mb-1">
            <input class="ml-1" type="checkbox" id="checkDescuento" name="descuento" value="1" {{ isset($p) && $p->descuento ? 'checked' : '' }}>
            <label class="form-check-label" for="descuento">Descuento</label>
        </div>
        <div class="form-check form-check-inline mb-1" id="constanteCheck">
            <input class="ml-1" type="checkbox" id="checkConstante" name="constante" value="1" {{ isset($p) && $p->constante ? 'checked' : '' }}>
            <label class="form-check-label" for="checkConstante">Constante</label>
        </div>
    
        <div id="sugeridoI" style="display: none;" >
            <x-input-number
                name="sugerido"
                label="Precio sugerido:"
                placeholder="0.00"
                min="0"
                max="9999999999"
                val="{{ $p->sugerido ?? '' }}"
            />
        </div>

        <div class="mb-3" id="fecha_inicio" >
            <x-input-date name="fecha_inicio" label="Fecha inicia:" val="{{ $p->fecha_inicio ?? '' }}"/>
        </div>

        <div class="mb-3" id="fecha_final" >
            <x-input-date name="fecha_final" label="Fecha finaliza:" val="{{ $p->fecha_final ?? '' }}"/>
        </div>

        <br>
        <div class="input-group">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </form>
@else
    Este formulario requiere los atributos :table y :data
@endif

<script> //se refactorizo es mas eficiente
    document.addEventListener('DOMContentLoaded', function() {
        // Obtener los elementos
        const advalorem = document.getElementById('checkAdvalorem');
        const iva = document.getElementById('checkIva');
        const propina = document.getElementById('checkPropina');
        const descuento = document.getElementById('checkDescuento');
        const fechaI = document.getElementById('fecha_inicio');
        const fechaF = document.getElementById('fecha_final');
        const constante = document.getElementById('checkConstante');
        const sugeridoI = document.getElementById('sugeridoI');

        // Al seleccionar advalorem, habilita el input precio sugerido
        advalorem.addEventListener('click', function() {
            sugeridoI.style.display = advalorem.checked ? 'block' : 'none';
        });  //si trae advalorem mostrara el input de precio sugerido
              if (advalorem.checked) {
                sugeridoI.style.display = 'block';
            }
        // Función para ocultar los elementos y el input de precio sugerido
        const ocultarCheckboxes = () => {
            [ fechaI, fechaF].forEach(element => {
                element.style.display = 'none';
            });
        };

        // Función para mostrar los elementos y el input de precio sugerido
        const mostrarCheckboxes = () => {
            [advalorem, iva, propina, descuento, fechaI, fechaF, sugeridoI].forEach(element => {
                element.style.display = 'inline';
            });
            sugeridoI.style.display = 'block';
        };

        // Al cargar la página, verificar si constante está marcado
        if (constante.checked) {
            ocultarCheckboxes();
        }

        // Al hacer clic en constante, mostrar u ocultar los elementos e input de precio sugerido
        constante.addEventListener('click', () => {
             constante.checked ? ocultarCheckboxes() : mostrarCheckboxes();
        }); 
        
                        // Validación del precio sugerido 
                const errorMensaje = document.createElement('div');
                errorMensaje.style.color = 'red';

                
                const form = document.getElementById('appPrecios');
                form.addEventListener('submit', function(event) {
                    const precio = parseFloat(document.getElementById('precio').querySelector('input').value);
                    const precioSugerido = parseFloat(document.getElementById('sugeridoI').querySelector('input').value);

                    if (precioSugerido >= precio) {
                    event.preventDefault();

                    errorMensaje.textContent = 'El precio sugerido debe ser menor al precio.';
                    sugeridoI.appendChild(errorMensaje);

                    sugeridoI.querySelector('input').style.borderColor = 'red';
                    } else {
                    // Si no hay error, eliminar el mensaje de error
                    if (errorMensaje.parentNode === sugeridoI) {
                        sugeridoI.removeChild(errorMensaje);
                        sugeridoI.querySelector('input').style.borderColor = 'green'
                    }
                    }
                });

    });
</script>
