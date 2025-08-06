@isset($label)
    <x-input-label :for="$name" :value="$label" class="sr-only" />

    @endif

    @if (isset($name))
        <input id="{{ $name }}" name="{{ $name }}" type="number" step="any" class="form-control mt-1 block"
            placeholder="{{ $placeholder ?? '0.00' }}" min="{{ $min ?? 0 }}" max="{{ $max ?? '' }}"
            value="{{ old($name) ?? ($val ?? '') }}" {{ isset($required) && $required ? 'required' : '' }}/>

        @error($name)
            <small class="text-danger"><span class="mdi mdi-alert"></span> {{ $message }}</small>
        @enderror
    @else
        Es requerido el atributo :name
    @endif

    <!--Limitar cantidad de dígitos a ingresar-->
    <script>
        document.getElementById('{{ $name }}').addEventListener('input', function (event) {
            let value = event.target.value;

            if(value.length > '{{ $maxDigitos ?? 11 }}')
                event.target.value = value.slice(0, '{{ $maxDigitos ?? 11 }}');
        });
    </script>
