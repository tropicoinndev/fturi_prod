<form action="{{ isset($id) && $id > 0 ? route($table.'.update') : route($table.'.store') }}" method="post">

    @csrf
    @if (isset($id) && $id > 0)

    <input type="hidden" name="id" value="$id">
    @endif
    <div class="mb-3">

        <x-input-text name="name" label="Permiso" />
    </div>
    <div class="input-group">
        <button class="btn btn-primary" type="submit">Guardar</button>
    </div>
</form>
