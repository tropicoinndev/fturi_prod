@if (session('caja'))


@if (isset($table) && isset($data))
<form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}" method="post">

    @csrf
    @if (isset($p) && $p->id > 0)
    <input type="hidden" name="id" value="{{$p->id}}">
    @endif
    <div class="mb-3">
        <x-input-select name="tipo_comprobantes_id" label="Tipo de comprobante:" :data="$data['tipo_comprobantes']"
            table="tipo_comprobantes" showName="tipo" val="{{ $p->tipo_comprobantes_id ?? '' }}" />
    </div>

    <div class="mb-3">
        <x-input-number name="inicio" label="Inicio: 1-99999999999" placeholder="0000" min="1" max="99999999999"
            val="{{ $p->inicio ?? '' }}" />
    </div>

    <div class="mb-3">
        <x-input-number name="final" label="Final: 1-99999999999" placeholder="0000" min="1" max="99999999999"
            val="{{ $p->final ?? '' }}" />
    </div>




    <div class="input-group">
        <button class="btn btn-primary" type="submit">Guardar</button>
    </div>

</form>
@else
Este formulario requiere lo atributos :table y :data
@endif

@else
Antes de continuar con esta accion debe iniciar sesion en una caja.
@endif
