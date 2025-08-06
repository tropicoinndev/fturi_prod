<div {{ $attributes->merge(['class' => 'mb-2']) }}>
    <label for="{{ $name }}">{{ $label }}</label>
    <input type="file" name="{{ $name }}" id="{{ $name }}" class="form-control" {{ isset($required) && $required ? 'required' : '' }}>

    @if(isset($val) && !empty($val))
        <div class="mt-2">
            <label for="{{ $name }}" class="form-label">Logo actual:</label>
            <img src="{{ asset('lgo/' .$val) }}" alt="Logo actual" class="form-comtrol" style="max-width: 200px;">
        </div>
    @endif

    @error($name)
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>