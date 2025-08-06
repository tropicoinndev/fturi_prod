@if (isset($table) && isset($data))
    <form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}"
        method="post">

        @csrf
        @if (isset($p) && $p->id > 0)
            <input type="hidden" name="id" value="{{$p->id}}">
        @endif

        <div class="mb-3">
            <x-input-select
                name="opcion_turnos_id"
                label="Opcion turno:"
                :data="$data['opcionTurnos']"
                table="opcion_turnos"
                showName="turno"
                val="{{ $p->opcion_turnos_id ?? '' }}"
            />
        </div>

        <div class="mb-3">
            <x-input-select
                name="cajas_id"
                label="Caja:"
                :data="$data['cajas']"
                table="cajas"
                showName="caja"
                val="{{ $p->cajas_id ?? '' }}"
            />
        </div>

        <div class="input-group">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </form>
@else
    Este formulario requiere lo atributos :table y :data
@endif
