@if((isset($class) && $class != "") &&
    (isset($ruta) && $ruta != "") &&
    (isset($icono) && $icono != "") &&
    (isset($label) && $label != ""))

    <a class="{{ $class }}" href="{{ route("$ruta") }}">
        <span class="{{ $icono }}"></span> {{ $label }}
    </a>
@else
    <p>El atributo del componente: btn-rutaj viene vacio.</p>
@endif
