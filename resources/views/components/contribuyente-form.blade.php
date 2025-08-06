@if (isset($table) && $cliente)
    <form action="{{ route('detalle_contribuyentes.store') }}" method="post">
        @csrf

        @if(isset($p) && $p->id > 0)
            <input type="hidden" name="id" value="{{ $p->id }}">

            @php
                $val = $p->municipios ? $p->municipios->toJson() : null;
            @endphp
        @endif

        <div>
            <input type="hidden" name="clientes_id" value="{{ $cliente }}">

            <div class="mb-3">
                <x-input-text-area name="juridico" label="Nombre Jurídico:" val="{{  $p->juridico ?? '' }}" required maxlength="255"/>
            </div>


            <div class="mb-3" >

                <x-input-text
                    name="nrc"
                    label="NRC: Se aceptan entre 1 a 8 dígitos sin guiones ni espacios"
                    placeholder="000000000"
                    val="{{ $p->nrc ?? '' }}"
                    maxlength="8"/>
            </div>

            <div class="input-group">
                <button class="btn btn-primary" type="submit">Guardar</button>
            </div>
        </div>
    </form>
@else
    Este formulario requiere lo atributos :table y :descuentos
@endif
