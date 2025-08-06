@if((isset($rutaShow)          && $rutaShow          != "") &&
    (isset($rutaEdit)          && $rutaEdit          != "") &&
    (isset($rutaCambiarEstado) && $rutaCambiarEstado != "") &&
    (isset($rutaDestroy)       && $rutaDestroy       != "") &&
    (isset($id)                && $id                != "") &&
    (isset($estado)))

    <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="mdi mdi-cog"></span>
        </button>
        <ul class="dropdown-menu">
            <li>
                <a class="dropdown-item" href="{{ route("$rutaShow",["id"=>\Crypt::encryptString($id)]) }}"><span class="mdi mdi-eye"></span> Ver mas</a>
            </li>
            <li>
                <a class="dropdown-item" href="{{ route("$rutaEdit",["id"=>\Crypt::encryptString($id)]) }}"><span class="mdi mdi-pencil"></span> Editar</a>
            </li>
            <li>
                @if($estado)
                    <a class="dropdown-item" href="{{ route("$rutaCambiarEstado",["id"=>\Crypt::encryptString($id)]) }}"><span class="mdi mdi-toggle-switch text-success"></span> Desactivar</a>
                @else
                    <a class="dropdown-item" href="{{ route("$rutaCambiarEstado",["id"=>\Crypt::encryptString($id)]) }}"><span class="mdi mdi-toggle-switch-off text-danger"></span> Activar</a>
                @endif
            </li>
            <h1 class="dropdown-divider"></h1>
            <li>
                <form action="{{ route("$rutaDestroy",["id"=>\Crypt::encryptString($id)]) }}" method="POST">
                    @csrf @method("DELETE")
                    <button type="submit" class="dropdown-item">
                        <span class="mdi mdi-delete text-danger"></span> Eliminar
                    </button>
                </form>
            </li>
        </ul>
    </div>
@else
    <p>Los atributos del componente: btn-accionesj vienen vacios.</p>
@endif
