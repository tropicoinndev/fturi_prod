<!--Componente original-->
{{--
@isset($label)
    <x-input-label for="name" :value="$label" class="sr-only"/>
@endif

@if(isset($name))
    <x-text-input id="{{ $name }}" name="{{ $name }}" type="text" class="form-control mt-1 block"
        placeholder="{{ $placeholder ?? 'Escriba aqui...' }}" value="{{ old($name) ?? $val ?? '' }}" :required="isset($required) && $required"/>

    @error($name)
        <small class="text-danger"><span class="mdi mdi-alert"></span> {{ $message }}</small>
    @enderror
@else
    Es requerido el atributo :name
@endif
--}}



<!--Componente nuevo con validaciones-->
@isset($label)
    <x-input-label for="name" :value="$label" class="sr-only"/>
@endisset

@if(isset($name))
    @if(isset($pattern))
        <x-text-input id="{{ $name }}" name="{{ $name }}" type="text" class="form-control mt-1 block"
            placeholder="{{ $placeholder ?? 'Escriba aqui...' }}" value="{{ old($name) ?? $val ?? '' }}" :required="isset($required) && $required" 
            maxlength="{{ $maxlength ?? '50' }}" pattern="{{ $pattern ?? '' }}"/>
    @else
        <x-text-input id="{{ $name }}" name="{{ $name }}" type="text" class="form-control mt-1 block"
            placeholder="{{ $placeholder ?? 'Escriba aqui...' }}" value="{{ old($name) ?? $val ?? '' }}" :required="isset($required) && $required" 
            maxlength="{{ $maxlength ?? '50' }}"/>
    @endif

    @error($name)
        <small class="text-danger"><span class="mdi mdi-alert"></span> {{ $message }}</small>
    @enderror
@else
    Es requerido el atributo :name
@endif
