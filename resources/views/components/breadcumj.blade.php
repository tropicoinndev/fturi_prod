@if((isset($menuPrincipal) && $menuPrincipal != '') &&
    (isset($subMenu) && $subMenu != ''))

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="#">{{ $menuPrincipal }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $subMenu }}</li>
        </ol>
    </nav>
@else
    <span>Los atributos del componente: breadcum vienen vacios.</span>
@endif
