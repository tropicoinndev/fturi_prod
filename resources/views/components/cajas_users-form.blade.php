@if (isset($table) && isset($data))
    <form action="{{ isset($p) && $p->id > 0 ? route($table.'.update') : route($table.'.store') }}"
        method="post">

        @csrf
        @if (isset($p) && $p->id > 0)
            <input type="hidden" name="id" value="{{$p->id}}">
        @endif
  
        <!-- <div class="mb-3">
            <x-input-number
                name="pin"
                label="Pin: 1-99999"
                placeholder="0"
                min="1"
                max="99999"
                val="{{ $p->pin ?? '' }}"
            />
        </div> -->

        <div class="mb-3">
            <x-input-select
                name="users_id"
                label="Usuarios:"
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
                val="{{ $p->cajas_id ?? '' }}"
            />
        </div>

        <div class="input-group">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </form>
@else
    Este formulario requiere lo atributos :table y :giros
@endif
