@isset ($label)
    <x-input-label for="name" :value="$label" class="sr-only"/>
@endif

@if (isset($name))
    <input
        type="time"
        class="form-control mt-1 block"
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ old($name) ?? $val ?? '' }}"
    >

@error($name)
    <small class="text-danger"><span class="mdi mdi-alert"></span> {{ $message }}</small>
@enderror

@else
    Es requerido el atributo :name
@endif
