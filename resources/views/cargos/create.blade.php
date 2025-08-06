@extends('layouts.form')

@section('form')
    <form action="{{ isset($p) && $p->id > 0 ? route($table . '.update') : route($table . '.store') }}" method="post">
        @csrf

        @if (isset($p) && $p->id > 0)
            <input type="hidden" name="id" value="{{ Crypt::encryptString($p->id) }}">
        @endif

        <div class="mb-3">
            <x-input-text name="cargo" label="Cargo:" val="{{ $p->cargo ?? '' }}" required maxlength="50"/>
        </div>

        <div class="mb-3">
            <x-input-number name="precio" label="Precio:" min="0" max="99999" val="{{ $p->precio ?? '' }}" required maxDigitos="10"/>
        </div>

        <div class="mb-3">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" value="1" id="iva" name="iva"
                    {{ isset($p) && $p->iva ? 'checked' : '' }}>
                <label class="form-check-label" for="iva">
                    IVA
                </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" value="1" id="cesc" name="cesc"
                    {{ isset($p) && $p->cesc ? 'checked' : '' }}>
                <label class="form-check-label" for="cesc">
                    CESC
                </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" value="1" id="propina" name="propina"
                    {{ isset($p) && $p->propina ? 'checked' : '' }}>
                <label class="form-check-label" for="propina">
                    Propina
                </label>
            </div>
        </div>

        <div class="mb-3">
            <button class="btn btn-primary" type="submit">Guardar</button>
            @if (Route::has($table . '.index'))
                <a class="btn btn-light" href="{{ route($table . '.index') }}">Volver</a>
            @endif
        </div>
    </form>
@endsection
