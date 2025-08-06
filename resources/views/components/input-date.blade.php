@isset($label)
    <x-input-label :for="$name" :value="$label" class="sr-only" />
    @endif
    @if (isset($name))
        <input id="{{ $name }}" name="{{ $name }}" type="date" class="form-control mt-1 block"
            value="{{ old($name) ?? ($val ?? '') }}" min="{{ $min ?? '' }}" {{ $required ?? '' }} />

        @error($name)
            <small class="text-danger"><span class="mdi mdi-alert"></span> {{ $message }}</small>
        @enderror
    @else
        Es requerido el atributo :name
    @endif
