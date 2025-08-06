@isset($label)
    <x-input-label :for="$name" :value="$label" class="sr-only" />
    @isset($table)
        @can($table . '.create')
            @if (Route::has($table . '.create'))
                <a href="{{ route($table . '.create') }}" target="_blank">Agregar</a>
            @endif
        @endcan
        @endif
    @endisset

    @if (isset($name) && isset($data) && isset($showName))
        <select class="form-select" name="{{ $name }}" id="{{ $name }}"
            {{ isset($required) && $required ? 'required' : '' }}>
            <option value=""value="" {{ !isset($val) || $val === '' ? 'selected' : '' }}  disabled>---Seleccione una opcion---</option>

            @forelse ($data as $i)
                <option value="{{ $i['id'] }}" {{ isset($val) && $val == $i['id'] ? 'selected' : '' }}
                    @if (isset($attr)) {{ $attr }}='{{ $i[$attrName ?? $attr] }}' @endif>
                    {{ $i[$showName] }}
                </option>
            @empty
                <option selected>Agregue un(a) {{ $label }}</option>
            @endforelse
        </select>

        @error($name)
            <small class="text-danger"><span class="mdi mdi-alert"></span> {{ $message }}</small>
        @enderror
    @else
        Es requerido el atributo :name
    @endif
