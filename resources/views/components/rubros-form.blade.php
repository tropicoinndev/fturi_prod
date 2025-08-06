<form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}" method="post">
    @csrf
    
    @if(isset($p) && $p->id > 0 )
        <input type="hidden" name="id" value="{{$p->id}}">
    @endif

    <div class="mb-3">
        <x-input-text name="rubro" label="Rubro:" val="{{ $p->rubro ?? '' }}" required maxlength="50"/>
    </div>

    <div class="mb-3">
        <label for="token" class="form-label">Token:</label>
        <select class="form-select" aria-label="Default select example" id="token" name="token" required>
            <option selected disabled value="{{ $p->token ?? '' }}">{{ ($p->token ?? '') ? 'seleccionar' : 'seleccionar' }}</option>
            <option value="12001" {{ isset($p) && $p->token == '12001' ? 'selected' : '' }}>Alimentos</option>
            <option value="12002" {{ isset($p) && $p->token == '12002' ? 'selected' : '' }}>Hotel</option>
            <option value="12003" {{ isset($p) && $p->token == '12003' ? 'selected' : '' }}>Servicios</option>
            <option value="12004" {{ isset($p) && $p->token == '12004' ? 'selected' : '' }}>Bebidas</option>
            <option value="12005" {{ isset($p) && $p->token == '12005' ? 'selected' : '' }}>Bebidas Alcohólicas</option>
        </select>
        {{-- <small class="text-danger"><span class="mdi mdi-alert"></span> {{ $message }}</small> --}}
    </div>

    <div class="input-group">
        <button class="btn btn-primary" type="submit">Guardar</button>
    </div>
</form>
