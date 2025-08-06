<form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}" method="post">
    @csrf

    @if(isset($p) && $p->id > 0 )
        <input type="hidden" name="id" value="{{$p->id}}">
    @endif

    <div class="mb-3">
        <x-input-text name="categoria" label="Categoría:" val="{{ $p->categoria ?? '' }}" required maxlength="50"/>
    </div>

    <div class="mb-3">
        <label for="token" class="form-label">Token:</label>
        <select class="form-select" aria-label="Default select example" id="token" name="token" required>
            <option selected disabled value="{{ $p->token ?? '' }}">{{ ($p->token ?? '') ? 'seleccionar' : 'seleccionar' }}</option>
            <option value="1201"{{ isset($p) && $p->token == '1201' ? 'selected' : '' }}>Venta</option>
            <option value="1202"{{ isset($p) && $p->token == '1202' ? 'selected' : '' }}>Producción</option>
            <option value="1203" {{ isset($p) && $p->token == '1203' ? 'selected' : '' }}>Venta-producción</option>
        </select>
        {{-- <small class="text-danger"><span class="mdi mdi-alert"></span> {{ $message }}</small> --}}
    </div>

    <div class="input-group">
        <button class="btn btn-primary" type="submit">Guardar</button>
    </div>
</form>
