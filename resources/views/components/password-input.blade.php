<div {{ $attributes->merge(['class' => 'form-group']) }}>
    <label for="{{ $id }}">Contraseña</label>
    <input type="password" id="{{ $id }}" name="{{ $name }}" class="form-control {{ $class }}" placeholder="{{ $placeholder }}" {{ $attributes }}>
</div>
