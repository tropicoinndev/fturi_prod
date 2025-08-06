@if (isset($table) && isset($data))
<form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}" method="post">

    @csrf
    @if (isset($p) && $p->id > 0)
    <input type="hidden" name="id" value="{{$p->id}}">
    @endif
    <div class="mb-3">
        <x-input-text name="mantenimiento" label="Mantenimiento:" val="{{ $p->mantenimiento ?? '' }}" />
    </div>
    <div class="mb-3">
        <x-input-time name="duracion_promedio" label="Duracion promedio:" val="{{ $p->duracion_promedio ?? '' }}" />
    </div>
    <div class="mb-3">
        <x-input-text name="id_grupo_telegram" label="Id de el grupo de telegram:" val="{{ $p->id_grupo_telegram ?? '' }}" />
    </div>
    <div class="mb-3">
        <x-input-select name="estado_habitacion_inicio_id" label="Estado habitaciones que inicia :" :data="$data['estado_habitaciones']"
            table="estado_habitaciones" showName="estado_habitacion" val="{{ $p->estado_habitacion_inicio_id ?? '' }}" />
    </div>
    <div class="mb-3">
        <x-input-select name="estado_habitacion_completado_id" label="Estado habitaciones cuando se complete:" :data="$data['estado_habitaciones']" table="estado_habitaciones"
            table="estado_habitaciones" showName="estado_habitacion" val="{{ $p->estado_habitacion_completado_id ?? '' }}" />
    </div>
    <div class="mb-3">
            <label for="notificacion" class="form-label">Notificacion</label>
            <select class="form-select" aria-label="Default select example" id="notificacion" name="notificacion">
                <option selected >--Seleccione--</option>

                <option value="1" {{isset($p) && $p->notificacion ==1 ? 'selected': ''}}>Si</option>
                <option value="0" {{ isset($p) && $p->notificacion == 0 ? 'selected' : '' }}>No</option>
            </select>

    </div>

    <div class="input-group">
        <button class="btn btn-primary" type="submit">Guardar</button>
    </div>
</form>
@else
Este formulario requiere lo atributos :table y :tipo_mantenimientos
@endif
