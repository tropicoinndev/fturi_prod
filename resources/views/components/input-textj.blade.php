@if((isset($label) && $label != "") &&
    (isset($id)    && $id    != "") &&
    (isset($name)  && $name  != "") &&
    (isset($value) && $value != ""))

    <div class="mb-3">
        <label for="{{ $name }}" class="form-label">{{ $label }}: <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error("$name") is-invalid @enderror" id="{{ $id }}" name="{{ $name }}" placeholder="{{ $placeHolder ?? "Escriba aqui..." }}" autocomplete="off" autofocus value="{{ old("$value",$data) }}">
        @error("$name")
            <span class="invalid-feedback" role="alert" style="font-size: 14px;"><strong>{{ $message }}</strong></span>
        @enderror
    </div>
@else
    <p>Los atributos del componente: input-textj vienen vacios.</p>
@endif
