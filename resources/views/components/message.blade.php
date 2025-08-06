@if (session('message'))
    <div class="alert alert-{{ session('type') ?? 'info' }} alert-dismissible fade show" role="alert">
        <strong>{{ session('message') }}</strong>
        @if (session('redirect'))
            <a class="btn btn-light" href="{{ session('redirect') }}" target="_blank" role="button"><span
                    class="mdi mdi-printer"></span> Imprimir</a>
        @endif
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        Esta accionó errores:
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
