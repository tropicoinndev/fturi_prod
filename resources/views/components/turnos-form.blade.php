@if (isset($table) && isset($data))
<form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}" method="post">

    @csrf
    @if (isset($p) && $p->id > 0)
        <input type="hidden" name="id" value="{{$p->id}}">
    @endif
    <div>
    <div class="mb-3">
            <x-input-date
                name="fecha"
                label="Fecha:"
                val="{{ $p->fecha ?? '' }}"
            />
        </div>
           <div class="mb-3">
        <x-input-time name="apertura" label="Apertura:" val="{{ $p->apertura ?? '' }}"/>
    </div>

    <div class="mb-3">
        <x-input-time name="cierre" label="Cierre:" val="{{ $p->cierre ?? '' }}"/>
    </div>
      <div class="mb-3">
            <x-input-select
                name="users_id"
                label="Usuario que apertura:"
                :data="$data['users']"
                table="users"
                showName="name"
                val="{{ $p->users_id ?? '' }}"
            />
        </div>
          <div class="mb-3">
            <x-input-select
                name="users_id"
                label="Usuario que cierra:"
                :data="$data['users']"
                table="users"
                showName="name"
                val="{{ $p->users_id ?? '' }}"
            />
        </div>

    <div class="mb-3">
            <x-input-select
                name="cajas_id"
                label="Cajas:"
                :data="$data['cajas']"
                table="cajas"
                showName="caja"
                val="{{$p->cajas_id ?? ''}}"
                 
            />
        </div>
        
           <div class="mb-3">
            <x-input-select
                name="opcion_turnos_id"
                label="Opcion de turnos:"
                :data="$data['opcion_turnos']"
                table="opcion_turnos"
                showName="turno"
                val="{{ $p->opcion_turnos_id ?? '' }}"
            />
        </div>
      
    

    <div class="input-group">
        <button class="btn btn-primary" type="submit">Guardar</button>
    </div>
    </div>
</form>
@else
    Este formulario requiere lo atributos :table y :descuentos
@endif