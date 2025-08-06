@if (isset($table) && $producto)
    <form action="{{  route('detalle_productos.store') }}"
        method="post"  id="appDetalle_producto">

        @csrf
        @if (isset($p) && $p->id > 0)
        <input type="hidden" name="id" value="{{$p->id}}">
        @endif
           <input type="hidden" name="productos_id" value="{{ $producto }}">
         <div  class="mb-3">
            <x-input-number 
                name="medida_ml"
                label="Medida en ml:"
                placeholder="0.0"
                min="1"
                max="9999"
                val="{{ $p->medida_ml ?? '' }}"
                
            />
        </div>
             <div class="mb-3">
            <x-input-number
                name="onzas"
                label="Onzas:"
                placeholder="0.0"
                min="0"
                max="9999"
                 val="{{$p->onzas ?? ''}}"
                
            />
            </div>
   
  

        <div class="mb-3">
            <x-input-number 
                name="perdida_onzas"
                label="Perdida onzas: "
                placeholder="0.0"
                min="0"
                max="9999"
                val="{{ $p->perdida_onzas ?? '' }}"
            />
         </div>
       
   
        <div class="input-group">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    

</form>

@else
    Este formulario requiere lo atributos :table y :detalle_productos
@endif
    <script type="application/javascript">
        /**lo realize de varias formas y no lo hacia hasta que decidi hacerlo directamente al input */
        document.addEventListener('DOMContentLoaded', function () {
            const medidaMl = document.querySelector('input[name="medida_ml"]');
            const onzasI = document.querySelector('input[name="onzas"]');

            medidaMl.addEventListener('change', function () {
                const medida_ml = parseFloat(this.value);
                const onzas = medida_ml * 0.033814;

                if (!isNaN(onzas)) {
                    onzasI.value = onzas.toFixed(2);
                }
            });
        });
    </script>


