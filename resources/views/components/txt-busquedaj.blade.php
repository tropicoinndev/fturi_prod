@if(isset($ruta) && $ruta != "")

    <form action="{{ route("$ruta") }}" method="POST">
        @csrf
        <input type="text" class="form-control" placeholder="Buscar..." id="txtBusqueda" name="txtBusqueda" value="{{ $txtBusqueda ?? "" }}">
    </form>
@else
    <p>El atributo del componente: txt-busqueda vienen vacios.</p>
@endif
