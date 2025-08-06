@isset($label)
    <x-input-label :for="$name" :value="$label" class="sr-only" />

    @endif

    @if (isset($name))
        <textarea id="{{ $name }}" name="{{ $name }}" type="text" class="form-control mt-1 block"
            placeholder="Escriba aqui..." rows="{{ $rows ?? 3 }}" {{ $required ?? '' }}>{{ old($name) ?? ($val ?? '') }}</textarea>

        @error($name)
            <small class="text-danger"><span class="mdi mdi-alert"></span> {{ $message }}</small>
        @enderror
    @else
        Es requerido el atributo :name
    @endif
